# Laravel Dusk Browser Testing Guide

This guide provides instructions on how to run and create browser tests using Laravel Dusk.

## Prerequisites

- PHP 8.4 or higher
- Composer
- Chrome browser installed
- ChromeDriver matching your Chrome version

## Setup

Laravel Dusk is already installed and configured in this project. The following steps have been completed:

1. Dusk package is installed via Composer
2. DuskTestCase.php is configured
3. .env.dusk.local environment file is created
4. ChromeDriver is installed for the current Chrome version

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
php artisan dusk --filter "LoginTest::user can login with email"
```

## Creating New Tests

### Generate a Test

```bash
php artisan dusk:make NewFormTest
```

Or copy and modify the GenericFormTest.php template.

### Test Structure

Dusk tests use the Pest testing framework. A typical test looks like:

```php
test('user can submit form', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/form')
            ->type('field_name', 'value')
            ->press('Submit')
            ->assertPathIs('/success');
    });
});
```

## Common Browser Actions

- `visit('/url')` - Navigate to a page
- `type('field', 'value')` - Type text into a field
- `select('dropdown', 'option')` - Select from a dropdown
- `check('checkbox')` - Check a checkbox
- `radio('group', 'value')` - Select a radio button
- `press('Button Text')` - Click a button
- `clickLink('Link Text')` - Click a link
- `attach('file_field', '/path/to/file')` - Upload a file

## Assertions

- `assertSee('text')` - Assert page contains text
- `assertDontSee('text')` - Assert page doesn't contain text
- `assertPathIs('/path')` - Assert current URL path
- `assertInputValue('field', 'value')` - Assert input has value
- `assertChecked('checkbox')` - Assert checkbox is checked
- `assertSelected('select', 'value')` - Assert option is selected

## Waiting

- `waitFor('.selector')` - Wait for element to be visible
- `waitForText('text')` - Wait for text to be visible
- `waitForLocation('/path')` - Wait for URL to change
- `pause(1000)` - Pause for 1 second

## Troubleshooting

### ChromeDriver Issues

If you encounter ChromeDriver version mismatch errors, update the ChromeDriver:

```bash
php artisan dusk:chrome-driver --detect
```

### Screenshots and Console Logs

Dusk automatically takes screenshots and console logs on failures. Check:

- `tests/Browser/screenshots/` - For screenshots of failures
- `tests/Browser/console/` - For browser console logs

### Headless Mode

By default, tests run in headless mode. To see the browser during tests, modify the `driver()` method in `DuskTestCase.php` to disable headless mode.

## Additional Resources

- [Laravel Dusk Documentation](https://laravel.com/docs/dusk)
- [Pest PHP Documentation](https://pestphp.com/docs)