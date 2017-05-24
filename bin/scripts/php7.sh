#!/usr/bin/env bash

# Installs php7 from ppa. Requires sudo.
apt-get install -y language-pack-en-base
LC_ALL=en_US.UTF-8
add-apt-repository ppa:ondrej/php

apt-get update

apt-get install -y --force-yes php7.0 php7.0-mysql

rm /etc/apache2/mods-enabled/php5*
cp /etc/apache2/mods-available/php7* /etc/apache2/mods-enabled/

apt-get update && apt-get install -y --force-yes php7.0-sqlite3

tar -xzf app.tar.gz

apt-get install php7.0-xdebug