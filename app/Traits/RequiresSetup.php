<?php

namespace App\Traits;

use App\Helpers\SetupHelper;

trait RequiresSetup
{
    function __construct()
    {
        parent::__construct();
        SetupHelper::setup();
    }
}
