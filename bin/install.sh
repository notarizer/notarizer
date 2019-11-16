#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

# Output running commands and fail on non-zero
set -e
set -x

# Move out of the bin/ directory
cd $DIR/..

echo "Installing the application from default upstream"

php artisan down

git stash

git pull --ff-only

git stash apply || true

composer install --optimize-autoloader

php artisan config:cache || true

php artisan route:cache || true

php artisan queue:restart

# Use yarn if yarn is being used
if [ -f yarn.lock ]; then
	yarn
else
	npm install
fi

# Output to null because it clearing the console is annoying
npm run production > /dev/null

php artisan up

echo "NOTE: Database migrations have not been run!"
