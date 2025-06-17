#!/bin/bash

# Script to run Dusk tests for the login page

# Ensure we're in the project root
cd "$(dirname "$0")"

# Display header
echo "====================================="
echo "Running Dusk Tests for the Login Page"
echo "====================================="

# Check if ChromeDriver is installed
echo "Checking ChromeDriver..."
php artisan dusk:chrome-driver --detect

# Generate key if needed
if grep -q "APP_KEY=" .env.dusk.local && ! grep -q "APP_KEY=base64:" .env.dusk.local; then
    echo "Generating application key for Dusk environment..."
    php artisan key:generate --env=dusk
fi

# Run the login tests
echo "Running login tests..."
php artisan dusk --filter LoginTest

# Display completion message
echo "====================================="
echo "Tests completed! Check the results above."
echo "If there were failures, check the screenshots in tests/Browser/screenshots/"
echo "====================================="