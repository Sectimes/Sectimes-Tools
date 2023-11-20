#!/bin/bash

# Start the Laravel web server
php artisan serve --host=0.0.0.0 --port=8000 &

# Start the queue worker
php artisan queue:work --timeout=600 # timeout after 10 mins, adjusts this if you need.
