#!/bin/bash
# Simple script to start the app in the background and open the app in the browser.
# This is useful if you simply want to start up the application in the background without setting up supervisor.
# Run with flag --quit to kill the background process.

CURRENT_DIR=$(pwd)
PORT=8124
SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
cd $SCRIPT_DIR
cd ..

case $1 in
    --quit)
        kill $(lsof -t -i:$PORT)
        exit 1
        ;;
esac

yarn build
open http://localhost:$PORT
php artisan serve --port=$PORT --no-interaction --tries=0 --quiet &

cd $CURRENT_DIR