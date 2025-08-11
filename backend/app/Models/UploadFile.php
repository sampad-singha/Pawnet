<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadFile extends Model
{
    protected $fillable = [
        'name',
        'path',
        'mime_type',
        'size',
        'type',
    ];

    protected $casts = [
        'size' => 'integer',
    ];
}
