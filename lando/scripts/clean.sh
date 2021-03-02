#!/usr/bin/env bash
source "$(dirname "$0")/common.sh"

log "\e[36m--------- Cleaning downloaded and built resources ---------\e[39m"

log "Go to the project root."
cd "$(cd -P -- "$(dirname -- "$0")" && pwd -P)/../.." || exit 1;

log "Delete vendor"
rm -rf vendor || exit 1;
log "Vendor is deleted"

log "Delete Drupal core"
rm -rf htdocs/core || exit 1;
log "Drupal core is deleted"

log "Delete libraries"
rm -rf htdocs/libraries || exit 1;
log "Libraries are deleted"

log "Delete contrib modules"
rm -rf htdocs/modules/contrib || exit 1;
log "Contrib modules are deleted"

log "Delete contrib profiles"
rm -rf htdocs/profiles/contrib || exit 1;
log "Contrib profiles are deleted"

log "Delete contrib themes"
rm -rf htdocs/themes/contrib || exit 1;
log "Contrib themes are deleted"

log "\e[36m--------- Finished cleaning downloaded and built resources ---------\e[39m"
