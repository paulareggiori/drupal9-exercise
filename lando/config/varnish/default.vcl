# modules
vcl 4.0;
import std;

include "hit-miss.vcl";

## Backend
backend default {
  .host = "apache";
  .port = "80";
  .connect_timeout = 300s;
  .first_byte_timeout = 120s;
  .between_bytes_timeout = 60s;
}

## ACLs
acl purge {
  "localhost";
  "127.0.0.1";
}

## vcl_recv
sub vcl_recv {

  # Set X-Forwarded-For header for logging and HTTP Auth
  if (req.restarts == 0) {
    if (!req.http.X-Forwarded-For) {
      set req.http.X-Forwarded-For = client.ip;
    }
  }

  ## Handle SSL offloading
  if (client.ip == "apache:80") {
    set client.identity = req.http.X-Real-IP;
  } else {
    set client.identity = client.ip;
  }

  # Check the incoming request type is "PURGE", not "GET" or "POST"
  if (req.method == "PURGE") {
    # Check if the ip coresponds with the acl purge
    if (!client.ip ~ purge) {
      # Return error code 405 (Forbidden) when not
      return (synth(405, "Not allowed."));
    }
    return (hash);
  }

  # Only deal with "normal" types
  if (req.method != "GET" &&
          req.method != "HEAD" &&
          req.method != "PUT" &&
          req.method != "POST" &&
          req.method != "TRACE" &&
          req.method != "OPTIONS" &&
          req.method != "PATCH" &&
          req.method != "DELETE") {
      /* Non-RFC2616 or CONNECT which is weird. */
      return (pipe);
  }

  # Needed for ogone post via pipe:
  if (req.method == "POST") {
    return (pipe);
  }

  # Only cache GET or HEAD requests. This makes sure the POST requests are always passed.
  if (req.method != "GET" && req.method != "HEAD" && req.method != "PURGE") {
      return (pass);
  }

  # Get rid of progress.js query params
  if (req.url ~ "^/misc/progress\.js\?[0-9]+$") {
    set req.url = "/misc/progress.js";
  }

  # Pipe these paths directly to Apache for streaming.
  if (req.url ~ "^/admin/content/backup_migrate/export") {
    return (pipe);
  }

  if (req.url ~ "^/system/files") {
    return (pipe);
  }

  if (req.url ~ "^/system/encrypted-files") {
    return (pipe);
  }

  # Do not allow outside access to cron.php or install.php. Depending on your access to the server, you might want to comment-out this block of code for development.
  if (req.url ~ "^/(install)\.php$")
    {
      set req.url = "/error-404";
    }

  # Do not cache these paths.
  if (req.url ~ "^/status\.php$" ||
    req.url ~ "^/update\.php$" ||
    req.url ~ "^/authorize\.php$" ||
    req.url ~ "^/xmlrpc\.php$" ||
    req.url ~ "^/install\.php" ||
    req.url ~ "^/cron\.php" ||
    req.url ~ "^/admin" ||
    req.url ~ "^/admin/.*$" ||
    req.url ~ "^/user" ||
    req.url ~ "^/user/.*$" ||
    req.url ~ "^/users/.*$" ||
    req.url ~ "^/info/.*$" ||
    req.url ~ "^/flag/.*$" ||
    req.url ~ "^/printpdf/.*$" ||
    req.url ~ "^/simplesaml/.*$" ||
    req.url ~ "^.*/ajax/.*$" ||
    req.url ~ "^.*/ahah/.*$") {
    return (pass);
  }

  # Always cache the following file types for all users.
  if (req.url ~ "(?i)\.(png|gif|jpeg|jpg|ico|swf|css|js|html|htm)(\?[a-z0-9]+)?$") {
    unset req.http.Cookie;
  }

  # Remove all cookies that Drupal doesn't need to know about. ANY remaining
  # cookie will cause the request to pass-through to Apache. For the most part
  # we always set the NO_CACHE cookie after any POST request, disabling the
  # Varnish cache temporarily. The session cookie allows all authenticated users
  # to pass through as long as they're logged in.
  # See: http://drupal.stackexchange.com/questions/53467/varnish-problem-user-logged-out-for-a-hit-page
  #
  # 1. Append a semi-colon to the front of the cookie string.
  # 2. Remove all spaces that appear after semi-colons.
  # 3. Match the cookies we want to keep, adding the space we removed
  # previously, back. (\1) is first matching group in the regsuball.
  # 4. Remove all other cookies, identifying them by the fact that they have
  # no space after the preceding semi-colon.
  # 5. Remove all spaces and semi-colons from the beginning and end of the cookie string.
  if (req.http.Cookie) {
    set req.http.Cookie = ";" + req.http.Cookie;
    set req.http.Cookie = regsuball(req.http.Cookie, "; +", ";");
    set req.http.Cookie = regsuball(req.http.Cookie, ";(S{1,2}ESS[a-z0-9]+|NO_CACHE)=", "; \1=");
###    set req.http.Cookie = regsuball(req.http.Cookie, ";[^ ][^;]*", "");
    set req.http.Cookie = regsuball(req.http.Cookie, "^[; ]+|[; ]+$", "");
    if (req.http.Cookie == "") {
      # If there are no remaining cookies, remove the cookie header. If there
      # aren't any cookie headers, Varnish's default behavior will be to cache
      # the page.
      unset req.http.Cookie;
    }
    else {
      # If there is any cookies left (a session or NO_CACHE cookie), do not
      # cache the page. Pass it on to Apache directly.
      return (pass);
    }
  }

  # Remove the "cookie-agreed" cookie
  ### set req.http.Cookie = regsuball(req.http.Cookie, "cookie-agreed=[^;]+(; )?", "");
  # Remove the "has_js" cookie
  set req.http.Cookie = regsuball(req.http.Cookie, "has_js=[^;]+(; )?", "");
  # Remove cookies that start with a underscore
  ###set req.http.Cookie = regsuball(req.http.Cookie, "(_[a-z]+)=[^;]+(; )?", "");
  # Remove the "rocmn-favorites" cookie
  set req.http.Cookie = regsuball(req.http.Cookie, "rocmn-favorites=[^;]+(; )?", "");
  # Remove the "zld26801000000002015float" cookie
  ###set req.http.Cookie = regsuball(req.http.Cookie, "zld26801000000002015float=[^;]+(; )?", "");
  # Remove the "Drupal.toolbar.collapsed" cookie
  ###set req.http.Cookie = regsuball(req.http.Cookie, "Drupal.toolbar.collapsed=[^;]+(; )?", "");
  # Remove any Google Analytics based cookies
  set req.http.Cookie = regsuball(req.http.Cookie, "__utm.=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "_ga=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "_gat=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "utmctr=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "utmcmd.=[^;]+(; )?", "");
  set req.http.Cookie = regsuball(req.http.Cookie, "utmccn.=[^;]+(; )?", "");
  # Remove the Quant Capital cookies (added by some plugin, all __qca)
  ###set req.http.Cookie = regsuball(req.http.Cookie, "__qc.=[^;]+(; )?", "");
  # Remove piwik cookies
  ###set req.http.Cookie = regsuball(req.http.Cookie, "(^|;\s*)(_pk_(ses|id|ref|cvar)[\.a-z0-9]*)=[^;]*", "");
  # Remove DoubleClick offensive cookies
  ###set req.http.Cookie = regsuball(req.http.Cookie, "__gads=[^;]+(; )?", "");
  # Remove the AddThis cookies
  ###set req.http.Cookie = regsuball(req.http.Cookie, "__atuv.=[^;]+(; )?", "");

  # Are there cookies left with only spaces or that are empty?
  if (req.http.cookie ~ "^ *$") {
    unset req.http.cookie;
  }

  # Cache static content unique to the theme (so no user uploaded images)
  if (req.url ~ "^/themes/" && req.url ~ ".(css|js|png|gif|jp(e)?g)") {
    unset req.http.cookie;
  }

  # Exclude URL's containing /admin/ from caching.
  if (req.url == "^/admin/") {
    return (pass);
  }
}

## vcl_hit
sub vcl_hit {
  if (req.method == "PURGE") {
    #
    # This is now handled in vcl_recv.
    #
    # purge;
    return (synth(200, "Purged"));
  }
}

## vcl_miss
sub vcl_miss {
  if (req.method == "PURGE") {
    #
    # This is now handled in vcl_recv.
    #
    # purge;
    return (synth(404, "Not in cache"));
  }
}

## vcl_pass
sub vcl_pass {
  if (req.method == "PURGE") {
    return (synth(502, "PURGE on a passed object"));
  }
}

## vcl_backend_response
sub vcl_backend_response {
  # Don't allow static files to set cookies.
  # (?i) denotes case insensitive in PCRE (perl compatible regular expressions).
  # This list of extensions appears twice, once here and again in vcl_recv so
  # make sure you edit both and keep them equal.
  # (bereq.url ~ "(?i)\.(pdf|asc|dat|txt|doc|xls|ppt|tgz|csv|png|gif|jpeg|jpg|ico|swf|css|js)(\?.*)?$") {
  if (bereq.url ~ "(?i)\.(png|gif|jpeg|jpg|ico|swf|css|js|html|htm)(\?[a-z0-9]+)?$") {
    unset beresp.http.set-cookie;
  }

  # For static content related to the theme, strip all backend cookies
  if (bereq.url ~ "^/themes/" && bereq.url ~ "\.(css|js|png|gif|jp(e?)g)") {
    unset beresp.http.cookie;
  }

  # don't cache 404 to long and unset cache-control the client needs to get them each time.
  if (beresp.status == 404) {
    set beresp.ttl = 30s;
    unset beresp.http.cache-control;
  }
}

## vcl_deliver
sub vcl_deliver {

  # Remove some headers: PHP version
  unset resp.http.X-Powered-By;

  # Remove some headers: Apache version & OS
  unset resp.http.Server;
  unset resp.http.X-Drupal-Cache;
  unset resp.http.X-Varnish;
  unset resp.http.Via;
  unset resp.http.Link;
}
