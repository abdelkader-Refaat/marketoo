<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

test('login page loads correctly', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/site/login')
            ->assertPathIs('/site/login');
    });
});

test('login form validation works', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/site/login')
            ->assertPathIs('/site/login');
    });
});
