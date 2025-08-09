<?php

namespace App\Services\File;

use App\Models\Util\File;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Services\File\Interfaces\FileServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class FileService implements FileServiceInterface
{

    protected FileRepositoryInterface $fileRepository;

    public function __construct(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    // Store file (using repository method, without the disk argument)
    public function storeFile(UploadedFile $file, string $directory, Model $model, string $type = null): mixed
    {
        // Now calling the repository without passing the disk argument, it will use the default disk
        return $this->fileRepository->storeFile($file, $directory, $model, $type);
    }

    // Download file (using repository method)
    /**
     * @throws Exception
     */
    public function downloadFile(int|string $fileId): mixed
    {
        // Fetch the file by ID
        $file = File::find($fileId);
        if (!$file) {
            throw new Exception('File not found');
        }

        // Now using the repository method to handle file download
        return $this->fileRepository->downloadFile($file);
    }

    // Delete file (using repository method)
    public function deleteFile(int|string $fileId): bool
    {
        // Delegating to the repository to delete the file
        return $this->fileRepository->deleteFile($fileId);
    }
}
