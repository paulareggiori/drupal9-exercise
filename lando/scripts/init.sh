#!/bin/bash
source "$(dirname "$0")/common.sh"

log "\e[36m--------- Running Open Online Backend project initialisation ---------\e[39m"

log "Running cleanup"
bash "$(dirname "$0")/clean.sh" || exit 1

# Get settings path.
SETTINGS_FOLDER_PATH="$(cd -P -- "$(dirname -- "$0")" && pwd -P)/../settings"

log "Go to the project root."
cd "$(cd -P -- "$(dirname -- "$0")" && pwd -P)/../.." || exit 1;

# Create the settings here, otherwise it will be created by a script during composer install but the owner will be root
# which will cause permission problems.
log "Create settings folder in project root."
mkdir -p settings || exit 1;

log "Create private files folder in project root."
mkdir -p private || exit 1;

# Copy the sites.php (will be provisioned below).
log "Copying sites.php into the project."
cp "$SETTINGS_FOLDER_PATH"/sites.php htdocs/sites/sites.php || exit 1;

# Copy the PHPUnit settings.
log "Copying phpunit.xml.dist..."
cp "$SETTINGS_FOLDER_PATH"/phpunit.xml.dist settings/phpunit.xml || exit 1;

# Copy the Docker services.local.yml.
log "Copying services.local.yml..."
cp "$SETTINGS_FOLDER_PATH"/services.local.yml settings/services.local.yml || exit 1;

# Loop through the drush site.yml files in the site repository.
FILES=drush/sites/*
for f in $FILES
do
  SITE_FILENAME="${f##*/}"
  SITE="${SITE_FILENAME%%.*}"

  mkdir -p settings/${SITE}
  # Copy the main Docker settings.
  echo "Copying settings.lando.php for ${SITE} ..."
  cp "$SETTINGS_FOLDER_PATH"/settings.lando.php settings/${SITE}/settings.lando.php
  # Add the database settings.
  echo "\$databases['default']['default'] = [" >> settings/${SITE}/settings.lando.php
  echo "  'database' => 'drupal9'," >> settings/${SITE}/settings.lando.php
  echo "  'username' => 'drupal9'," >> settings/${SITE}/settings.lando.php
  echo "  'password' => 'drupal9'," >> settings/${SITE}/settings.lando.php
  echo "  'prefix' => ''," >> settings/${SITE}/settings.lando.php
  echo "  'host' => 'database'," >> settings/${SITE}/settings.lando.php
  echo "  'port' => '3306'," >> settings/${SITE}/settings.lando.php
  echo "  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql'," >> settings/${SITE}/settings.lando.php
  echo "  'driver' => 'mysql'," >> settings/${SITE}/settings.lando.php
  echo "];" >> settings/${SITE}/settings.lando.php
  echo "Adding ${SITE} to sites.php ..."
  echo "\$sites['${SITE}.localhost'] = '${SITE}';" >> htdocs/sites/sites.php

done || exit 1;

log "Creating symlink for .lando.base.yml in project root."
ln -sf "$SETTINGS_FOLDER_PATH"/../.lando.base.yml . || exit 1;

log "\e[36m--------- Open Online Backend project initialisation is finished ---------\e[39m"
