<?php
namespace App\Services\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
class Requestervice
{
    /**
     * Creates a request for the given folder name.
     *
     * @param string $folderName The name of the folder.
     */
    public function createRequest($model)
    {
        // Generate 'StoreRequest' request
        Artisan::call('make:request', ['name' => 'Admin/' . $model . '/StoreRequest']);

        // Generate 'UpdateRequest' request
        Artisan::call('make:request', ['name' => 'Admin/' . $model . '/UpdateRequest']);

        // Copy 'store_copy.php' to 'StoreRequest.php' and replace 'Copy' with folderName
        File::copy(
            'app/Console/CommandCopys/store_copy.php',
            base_path('app/Http/Requests/Admin/' . $model . '/StoreRequest.php')
        );
        file_put_contents(
            'app/Http/Requests/Admin/' . $model . '/StoreRequest.php',
            preg_replace("/Copy/", $model, file_get_contents('app/Http/Requests/Admin/' . $model . '/StoreRequest.php'))
        );

        // Copy 'update_copy.php' to 'UpdateRequest.php' and replace 'Copy' with folderName
        File::copy(
            'app/Console/CommandCopys/update_copy.php',
            base_path('app/Http/Requests/Admin/' . $model . '/UpdateRequest.php')
        );
        file_put_contents(
            'app/Http/Requests/Admin/' . $model . '/UpdateRequest.php',
            preg_replace("/Copy/", $model, file_get_contents('app/Http/Requests/Admin/' . $model . '/UpdateRequest.php'))
        );
    }
}