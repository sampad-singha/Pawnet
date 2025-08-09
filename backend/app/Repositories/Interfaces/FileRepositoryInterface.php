<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

interface FileRepositoryInterface
{
    /**
     * Store a file in the specified directory and associate it with a model.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param Model $model
     * @param string|null $disk
     * @param string|null $type
     * @return mixed
     */
    public function storeFile(UploadedFile $file, string $directory, Model $model, string $type = null, ?string $disk = 'local'): mixed;

    /**
     * Download a file by its ID.
     *
     * @param int|string $fileId
     * @return mixed
     */
    public function downloadFile(int|string $fileId): mixed;

    /**
     * Delete a file by its ID.
     *
     * @param int|string $fileId
     * @return bool
     */
    public function deleteFile(int|string $fileId): bool;
}
