#!/bin/bash

CURRENT_DIR=$(pwd)
PORT=8123
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