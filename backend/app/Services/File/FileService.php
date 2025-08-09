<?php

namespace App\Services\File;

use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Services\File\Interfaces\FileServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class FileService implements FileServiceInterface
{

    protected FileRepositoryInterface $fileRepository;

    public function __construct(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    // Store file (using repository method)
    public function storeFile(UploadedFile $file, string $directory, Model $model, string $type = null, ?string $disk = 'local'): mixed
    {
        return $this->fileRepository->storeFile($file, $directory, $model, $type, $disk);
    }

    // Download file (using repository method)
    public function downloadFile(int|string $fileId): mixed
    {
        return $this->fileRepository->downloadFile($fileId);
    }

    // Delete file (using repository method)
    public function deleteFile(int|string $fileId): bool
    {
        return $this->fileRepository->deleteFile($fileId);
    }
}
