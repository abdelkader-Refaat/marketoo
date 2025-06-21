# Running Laravel Dusk Tests

This guide explains how to run the Laravel Dusk browser tests in this project.

## Using the Helper Script

We've created a helper script to make running Dusk tests easier. The script:

1. Checks if a development server is running and starts one if needed
2. Ensures ChromeDriver matches your Chrome version
3. Runs the tests
4. Cleans up by stopping the development server if it was started by the script

### Running All Tests

```bash
./run-dusk-tests.sh
```

### Running Specific Tests

```bash
./run-dusk-tests.sh LoginTest
```

Or for a specific test method:

```bash
./run-dusk-tests.sh "LoginTest::login page loads correctly"
```

## Manual Testing Steps

If you prefer to run tests manually:

1. Start a development server:
   ```bash
   php artisan serve
   ```

2. In a separate terminal, ensure ChromeDriver matches your Chrome version:
   ```bash
   php artisan dusk:chrome-driver --detect
   ```

3. Run the tests:
   ```bash
   php artisan dusk
   ```

## Understanding ChromeDriver

ChromeDriver runs on port 9515 by default and is used by Laravel Dusk to control Chrome for testing. It's not meant to be accessed directly in a browser.

If you visit `http://localhost:9515/` in your browser and see an "unknown command" error, this is normal behavior. ChromeDriver expects specific JSON commands via HTTP requests, not direct browser visits.

For more details about ChromeDriver and the "unknown command" error, see the [README-CHROMEDRIVER.md](README-CHROMEDRIVER.md) file.

## Troubleshooting

If you encounter issues:

1. Make sure Chrome is installed
2. Check that your `.env.dusk.local` file has the correct settings:
   ```
   APP_URL=http://localhost:8000
   DUSK_DRIVER_URL=http://localhost:9515
   ```
3. Try running with the `--verbose` flag for more detailed output:
   ```bash
   php artisan dusk --verbose
   ```
4. If tests fail with timeout errors, you may need to increase the wait time in your tests or check your internet connection

For more detailed troubleshooting, see the [README-DUSK-SETUP.md](README-DUSK-SETUP.md) file.