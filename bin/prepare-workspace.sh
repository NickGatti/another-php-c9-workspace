#!/usr/bin/env bash

# Store script dir
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Check the current ubuntu version
# lsb_release -a


# Install php7 from ppa
source $DIR/scripts/php7.sh


# Install composer
source $DIR/scripts/composer.sh
echo $RESULT;
