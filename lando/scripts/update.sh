#!/bin/bash
source "$(dirname "$0")/common.sh"

log "\e[36m--------- Running drush updates ---------\e[39m"

DRUSH="$(cd -P -- "$(dirname -- "$0")" && pwd -P)/../../vendor/bin/drush"

# Perform cim, cr, etc. etc.
log "Rebuilding cache"
${DRUSH} cr || exit 1;
log "Running pending database updates."
${DRUSH} updb -y || exit 1;
# ${DRUSH} csex excluded -y || exit 1; #causing error: The following split is not available: config_split.config_split.excluded
log "Import configuration."
${DRUSH} cim -y || exit 1;
log "Rebuild cache."
${DRUSH} cr || exit 1;
# ${DRUSH} cim -y || exit 1; Note: redundant?
log "Running locale update."
${DRUSH} locale-update -y || exit 1;
log "Rebuild cache."
${DRUSH} cr || exit 1;
log "Clearing search index."
${DRUSH} search-api:clear -y || exit 1;
log "Running search indexing."
${DRUSH} search-api:index -y || exit 1;

log "\e[36m--------- Drush updates are finished ---------\e[39m"
