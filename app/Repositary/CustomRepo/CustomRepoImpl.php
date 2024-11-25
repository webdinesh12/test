<?php

namespace App\Repositary\CustomRepo;

use App\Repositary\CustomRepo\CustomRepo;
use Illuminate\Support\Facades\Log;

class CustomRepoImpl implements CustomRepo{
    public function logCustomRepo(){
        Log::info('This is from custom repo impl.');
    }
}