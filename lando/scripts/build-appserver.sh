#!/usr/bin/env bash
source "$(dirname "$0")/common.sh"

log "\e[36m--------- Installing additional resource into the appserver service ---------\e[39m"

log "Install YARN"
curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - || exit 1;
echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list || exit 1;
apt-get update && apt-get install -y yarn || exit 1;

YARN_VERSION=$(yarn --version);
log "Yarn version: $YARN_VERSION is installed"

log "\e[36m--------- Additional resources are installed to the appserver ---------\e[39m"
