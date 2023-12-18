#!/bin/bash
PORT=8123
open http://localhost:$PORT
concurrently  "php artisan config:cache" "php artisan serve --port=$PORT" "yarn dev"