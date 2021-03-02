#!/bin/bash
source "$(dirname "$0")/common.sh"

log "\e[36m--------- Running Unit tests ---------\e[39m"

FILTER="$1"
log "Run phpunit tests with filter: $FILTER"

# Set PHP Unit bin path.
PHPUNITBIN="$(cd -P -- "$(dirname -- "$0")" && pwd -P)/../../vendor/bin/phpunit"
# Set PHP Unit settings xml path.
PHPUNITSETTINGS="$(cd -P -- "$(dirname -- "$0")" && pwd -P)/../../settings/phpunit.xml"

log "Running unit testsuite".
${PHPUNITBIN} -c ${PHPUNITSETTINGS} --testsuite unit --no-coverage --log-junit=/app/output/report-unit.xml $FILTER || exit;
log "Running kernel testsuite".
${PHPUNITBIN} -c ${PHPUNITSETTINGS} --testsuite kernel --no-coverage --log-junit=/app/output/report-kernel.xml $FILTER || exit;
log "Running functional testsuite".
${PHPUNITBIN} -c ${PHPUNITSETTINGS} --testsuite functional --no-coverage --log-junit=/app/output/report-functional.xml $FILTER || exit;
log "Running functional-javascript testsuite".
${PHPUNITBIN} -c ${PHPUNITSETTINGS} --testsuite functional-javascript --no-coverage --log-junit=/app/output/report-functional-javascript.xml $FILTER || exit;
log "Running existing-site testsuite".
${PHPUNITBIN} -c ${PHPUNITSETTINGS} --testsuite existing-site --no-coverage --log-junit=/app/output/report-existing-site.xml $FILTER || exit;
log "Running existing-site-javascript testsuite".
${PHPUNITBIN} -c ${PHPUNITSETTINGS} --testsuite existing-site-javascript --no-coverage --log-junit=/app/output/report-existing-site-javascript.xml $FILTER || exit;

log "\e[36m--------- Unit test runs are finished ---------\e[39m"
