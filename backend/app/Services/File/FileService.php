<?php

namespace App\Services\File;

use App\Services\File\Interfaces\FileServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileService implements FileServiceInterface
{

    // Implement methods defined in FileServiceInterface here
    // For example, methods for uploading, downloading, deleting files, etc.

    // Example method
    public function storeFilesPublic(UploadedFile $file, string $directory, Model $model, string $type = null)
    {
        $path = Storage::disk('public')->putFile($directory, $file);

        // 2. Create the file record in the database using the polymorphic relationship
        return $model->files()->create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'type' => $type, // Use the provided file type
        ]);
    }

    public function storeFilesPrivate(UploadedFile $file, string $directory, Model $model, string $type = null)
    {
        $path = Storage::putFile($directory, $file);

        // 2. Create the file record in the database using the polymorphic relationship
        return $model->files()->create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'type' => $type, // Use the provided file type
        ]);
    }

    public function downloadFile($fileId)
    {
        // Logic to handle file download
    }

    public function deleteFile($fileId)
    {
        // Logic to handle file deletion
    }
}
