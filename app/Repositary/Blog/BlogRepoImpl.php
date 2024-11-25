<?php

namespace App\Repositary\Blog;

use Illuminate\Support\Facades\Log;

Class BlogRepoImpl implements BlogRepo{
    public function writeLog(){
        Log::info("This is from Repositary.");
    }
}