<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SetupHelper
{

    public static function setup()
    {
        // Check to see if database path exists and is set
        $path = $_SERVER['HOME'] . '/.timr';
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $file = $path . '/database.sqlite';
        if (!File::exists($file)) {
            $dbFile = fopen($file, "w") or die("Unable to create database file: " . $file);
            fwrite($dbFile, "");
            fclose($dbFile);

            Artisan::call('migrate');
        }

    }


}
