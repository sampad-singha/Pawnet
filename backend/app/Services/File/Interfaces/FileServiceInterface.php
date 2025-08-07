<?php

namespace App\Services\File\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

interface FileServiceInterface
{
    public function storeFilesPublic(UploadedFile $file, string $directory, Model $model, string $type = null);
}
