<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * This is a generic form test template that can be adapted to test any form in the application.
 * 
 * To use this template:
 * 1. Copy this file and rename it to match your form (e.g., RegistrationFormTest.php)
 * 2. Update the form URL, field names, and assertions to match your form
 * 3. Run the test with `php artisan dusk`
 */
test('generic form submission example', function () {
    $this->browse(function (Browser $browser) {
        // Replace '/form-url' with the actual URL of your form
        $browser->visit('/form-url')
            // Assert that the form page loaded correctly
            ->assertSee('Form Title')
            
            // Fill in text fields
            ->type('field_name1', 'Value 1')
            ->type('field_name2', 'Value 2')
            
            // Select from dropdown
            ->select('dropdown_field', 'option_value')
            
            // Check/uncheck checkboxes
            ->check('checkbox_field')
            ->uncheck('another_checkbox')
            
            // Radio buttons
            ->radio('radio_group', 'selected_value')
            
            // File uploads
            // ->attach('file_field', storage_path('app/public/test-file.pdf'))
            
            // Submit the form
            ->press('Submit Button Text')
            
            // Wait for response (adjust timeout as needed)
            ->waitForText('Success Message', 10)
            
            // Assert the expected outcome
            ->assertPathIs('/success-page')
            ->assertSee('Success Message');
    });
});

test('generic form validation example', function () {
    $this->browse(function (Browser $browser) {
        // Replace '/form-url' with the actual URL of your form
        $browser->visit('/form-url')
            // Assert that the form page loaded correctly
            ->assertSee('Form Title')
            
            // Submit the form without filling required fields
            ->press('Submit Button Text')
            
            // Assert validation errors
            ->assertSee('The field_name1 field is required')
            ->assertSee('The field_name2 field is required');
    });
});

/**
 * Additional Dusk browser methods you can use:
 * 
 * ->clickLink('Link Text')       - Click a link with the given text
 * ->value('field', 'New Value')  - Set value directly
 * ->clear('field')               - Clear a field
 * ->keys('field', ['a', 'b'])    - Send specific keystrokes
 * ->assertInputValue('field', 'value') - Assert input has value
 * ->assertChecked('checkbox')    - Assert checkbox is checked
 * ->assertNotChecked('checkbox') - Assert checkbox is not checked
 * ->assertSelected('select', 'value') - Assert option is selected
 * ->assertVisible('element')     - Assert element is visible
 * ->assertMissing('element')     - Assert element is not visible
 * ->screenshot('filename')       - Take a screenshot
 * ->mouseover('element')         - Hover over an element
 * ->dragAndDrop('source', 'target') - Drag and drop
 */