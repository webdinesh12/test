<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestMessage extends Model
{
    protected $casts = [
        'content' => 'encrypted',
    ];
}
