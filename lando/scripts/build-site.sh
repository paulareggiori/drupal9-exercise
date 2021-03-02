#!/usr/bin/env bash
source "$(dirname "$0")/common.sh"

log "\e[36m--------- Installing open_online backend site---------\e[39m"

log "Go to the project root."
cd "$(cd -P -- "$(dirname -- "$0")" && pwd -P)/../.." || exit 1;

log "Installing drupal composer dependencies (with development dependencies)."
composer install --no-interaction --no-progress "$@" || exit 1;
log "Composer packages are installed"

log "Build admin theme"
log "Change to the theme dir."
cd "$(cd -P -- "$(dirname -- "$0")" && pwd -P)/../../htdocs/themes/contrib/toptasks_base_theme" || exit 1;

log "Install theme dependencies."
yarn install || exit 1;
yarn copy-twig-functions || exit 1;

log "Build theme resources."
yarn build-patternlab || exit 1;
yarn build || exit 1;

log "Admin theme is built"

log "\e[36m--------- open_online backend site is installed ---------\e[39m"
