<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;

test('user can submit contact form', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/contact')
            ->assertSee('Contact Us')
            ->type('name', 'Test User')
            ->type('email', 'test@example.com')
            ->type('subject', 'Test Subject')
            ->type('message', 'This is a test message from Dusk browser testing.')
            ->press('Send Message')
            ->waitForText('Thank you for your message')
            ->assertSee('Thank you for your message');
    });
});

test('contact form validation works', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/contact')
            ->assertSee('Contact Us')
            ->press('Send Message')
            ->assertSee('The name field is required')
            ->assertSee('The email field is required')
            ->assertSee('The message field is required');
    });
});
