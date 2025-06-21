# Running Laravel Dusk Tests

This guide explains how to run Laravel Dusk browser tests in this project.

## Prerequisites

1. PHP 8.4 or higher
2. Composer
3. Chrome browser installed
4. ChromeDriver matching your Chrome version

## Setup Steps

We've already completed the following setup steps:

1. Added the `Tests` namespace to the `autoload-dev` section in `composer.json`:
   ```json
   "autoload-dev": {
       "psr-4": {
           "App\\": "app/",
           "Tests\\": "tests/"
       }
   }
   ```

2. Fixed the Pest configuration in `tests/Pest.php` to avoid duplicate test case definitions.

3. Configured the `.env.dusk.local` file with appropriate settings:
   ```
   APP_URL=http://localhost:8000
   DB_CONNECTION=sqlite
   DB_DATABASE=:memory:
   DUSK_DRIVER_URL=http://localhost:9515
   ```

## Running Tests

To run all Dusk tests:

```bash
php artisan dusk
```

To run a specific test file:

```bash
php artisan dusk --filter LoginTest
```

To run a specific test method:

```bash
php artisan dusk --filter "LoginTest::login page loads correctly"
```

## Troubleshooting

If you encounter issues:

1. **ChromeDriver version mismatch**:
   ```bash
   php artisan dusk:chrome-driver --detect
   ```

2. **Class not found errors**:
   Run `composer dump-autoload` to refresh the autoloader.

3. **Database errors**:
   For tests that require database operations, you may need to run migrations in the Dusk environment:
   ```bash
   php artisan migrate --env=dusk
   ```
   
   Alternatively, design your tests to not rely on database operations when possible.

4. **Connection errors**:
   Make sure your application is running at the URL specified in `.env.dusk.local`.
   You can start a development server with:
   ```bash
   php artisan serve
   ```

## Writing Tests

When writing Dusk tests with Pest:

1. Use the functional style with `test()` functions
2. Don't explicitly import the `Tests\DuskTestCase` class in your test files
3. Use the `$this->browse()` method to interact with the browser

Example:

```php
<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;

test('page loads correctly', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')
            ->assertSee('Laravel');
    });
});
```

## Additional Resources

- [Laravel Dusk Documentation](https://laravel.com/docs/dusk)
- [Pest PHP Documentation](https://pestphp.com/docs)