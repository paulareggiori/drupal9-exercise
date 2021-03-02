#!/bin/bash
source "$(dirname "$0")/common.sh"

if [ "$#" -ne 1 ]; then
  log "Xdebug has been turned off, to enable it use the following syntax: 'lando xdebug <mode>'."
  echo xdebug.mode = off > /usr/local/etc/php/conf.d/zzz-lando-xdebug.ini
  pkill -o -USR2 php-fpm
  /etc/init.d/apache2 reload

else
  mode="$1"
  echo xdebug.mode = "$mode" > /usr/local/etc/php/conf.d/zzz-lando-xdebug.ini
  pkill -o -USR2 php-fpm
  log "Xdebug is loaded in $mode mode."
  /etc/init.d/apache2 reload
fi
