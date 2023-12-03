#!/bin/bash

yarn build
open http://localhost:8000
php artisan serve --port=8000 --no-interaction --tries=0 --quiet &