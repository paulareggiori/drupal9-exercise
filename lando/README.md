# INSTRUCTIONS

## INSTALL Lando
Install Lando for your OS: https://docs.lando.dev/basics/installation.html

## START A NEW PROJECT
Make sure you have the submodule available:
Run `git submodule update --init --remote`

Then run `bash lando/scripts/init.sh` in the project root folder where this submodule is initialised.

Then run `lando start` in the project root.

When this command completes it will show the urls for all available services.

The installation profile is unreliable at this point. So we recommend getting a clean DB. Contact a team member for this.

Import the database with `lando db-import my_db.sql`

## Working on this submodule
For better separation use the provided .lando.local.yml file.

## Lando tools
On top of Lando default commands this project defines a custom set of tools.
These tools were added to ease repetitive tasks on the project.

###`lando build-site`
This will build site resources. Basically it runs `composer install` and builds the administration theme.

###`lando drupal`
Runs project definded Drupal console commands in the *webroot*.

###`lando clean`
Deletes vendor, core, contributed folders. Useful for clean start.

###`lando phpunit <option>FILTER`
Runs all unit tests. If FILTER is provided it will add it as the --filter option to the phpunit command. Defaults to `.`

###`lando redis-cli`
Provides cli integration for the *redis* service

###`lando update`
Runs drush updates.

## REBUILD
To completely rebuild the project run
`lando destroy -y && bash lando/scripts/init.sh && lando start`

## XDEBUG (BROWSER)
Put this in .lando.local.yml in the root:

```
# To activate this feature, copy this file as .lando.local.yml.
# Further read on configuration file load order is available on:
# https://docs.lando.dev/config/lando.html#base-file
config:
  xdebug: true

services:
  appserver:
    xdebug: true
    overrides:
      environment:
        XDEBUG_MODE:
        PHP_IDE_CONFIG: "serverName=appserver"

events:
  post-start:
    - appserver: export PHP_IDE_CONFIG="serverName=appserver"
```

lando rebuild
lando start
lando xdebug debug

Enable listener and set a breakpoint in PHPStorm.

## XDEBUG (CLI)
[@TODO]

## TROUBLESHOOTING
[@TODO]
