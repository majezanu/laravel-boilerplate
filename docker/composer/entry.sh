set -e

# disable xdebug during composer actions, supposedly this makes it faster.
sudo mv $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini{,.off}

# to be automatically used by docker-compose when running up
# custom use is by `docker-compose run --rm composer update --lock`

# make sure vendor is writable all the way
sudo chown -R cpdev:cpdev vendor /home/cpdev/.composer

# useful for when a package just got uninstalled, etc.
rm bootstrap/cache/* || true

# default command is "install"
COMPOSER_MEMORY_LIMIT=-1 composer "$@"

# returning to previous state, just in case
sudo mv $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini{.off,}
