#!/bin/bash

# Check if the development server is running
if ! curl -s http://localhost:8000 > /dev/null; then
    echo "Starting Laravel development server..."
    php artisan serve &
    SERVER_PID=$!
    # Give the server a moment to start
    sleep 3
    echo "Development server started with PID: $SERVER_PID"
else
    echo "Development server is already running"
    SERVER_PID=""
fi

# Ensure ChromeDriver matches Chrome version
echo "Checking ChromeDriver version..."
php artisan dusk:chrome-driver --detect

# Run the tests
if [ "$1" == "" ]; then
    echo "Running all Dusk tests..."
    php artisan dusk
else
    echo "Running test: $1"
    php artisan dusk --filter "$1"
fi

# If we started the server, stop it
if [ "$SERVER_PID" != "" ]; then
    echo "Stopping development server..."
    kill $SERVER_PID
fi

echo "Testing completed!"