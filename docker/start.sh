#!/bin/sh

# automated install
php /var/www/html/install/cli/docker_installer.php

# Start server - depending on the image, one of these will work
echo "Starting server"
$@