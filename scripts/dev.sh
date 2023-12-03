#!/bin/bash

open http://localhost:8000
concurrently  "php artisan config:cache" "php artisan serve" "yarn dev"