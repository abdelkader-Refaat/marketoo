# Running Dusk Tests for the Login Page

This guide provides specific instructions for running browser tests for the login page at https://marketoo.test/site/login.

## Prerequisites

Ensure you have completed the general Dusk setup as described in the main README.md file:

1. Chrome browser is installed
2. ChromeDriver matching your Chrome version is installed
3. The .env.dusk.local file is configured correctly
4. An application key has been generated for the Dusk environment

## Running the Login Tests

To run all Dusk tests:

```bash
php artisan dusk
```

To run only the login tests:

```bash
php artisan dusk --filter LoginTest
```

To run a specific login test method:

```bash
php artisan dusk --filter "LoginTest::user can login with email"
```

## Available Login Tests

The LoginTest.php file contains the following tests:

1. `user can login with email` - Tests successful login using email credentials
2. `user can login with phone` - Tests successful login using phone number credentials
3. `user cannot login with invalid credentials` - Tests validation for incorrect login information

## Troubleshooting

If you encounter issues running the tests:

1. **ChromeDriver Connection Issues**:
   - Ensure ChromeDriver is running: `php artisan dusk:chrome-driver --detect`
   - Check that DUSK_DRIVER_URL in .env.dusk.local is set to http://localhost:9515

2. **URL Issues**:
   - Verify that APP_URL in .env.dusk.local is set to https://marketoo.test
   - Ensure the site is accessible at https://marketoo.test/site/login

3. **Test Failures**:
   - Check screenshots in tests/Browser/screenshots/ for visual evidence of failures
   - Review console logs in tests/Browser/console/ for JavaScript errors

## Running Tests in Non-Headless Mode

To see the browser in action during tests (useful for debugging):

1. Open tests/DuskTestCase.php
2. Find the `hasHeadlessDisabled()` method or modify the ChromeOptions to remove headless mode
3. Run the tests again

## Example: Manual Test Run

Here's a step-by-step example of running the login tests:

1. Start your local development server if not already running
2. Open a terminal and navigate to your project root
3. Run: `php artisan dusk:chrome-driver --detect`
4. Run: `php artisan dusk --filter LoginTest`
5. Watch the tests execute or check the results in the terminal