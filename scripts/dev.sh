#!/bin/bash
# Simple script for concurrently running php artisan serve and yarn dev
# This is useful for development, but should not be used in production.

PORT=8123
open http://localhost:$PORT
concurrently  "php artisan config:cache" "php artisan serve --port=$PORT" "yarn dev"