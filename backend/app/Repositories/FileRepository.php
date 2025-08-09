<?php

// app/Repositories/FileRepository.php

namespace App\Repositories;

use App\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Util\File;  // Assuming a File model that stores file metadata

class FileRepository implements FileRepositoryInterface
{
    // Store file in the specified disk (local, public, private, S3)
    public function storeFile(UploadedFile $file, string $directory, Model $model, ?string $disk = 'local', string $type = null): mixed
    {
        $path = Storage::disk($disk)->putFile($directory, $file);

        // Create a record in the database with file metadata
        return $model->files()->create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'type' => $type,  // optional file type
        ]);
    }

    // Download file (generalized for both public and private files)
    public function downloadFile(int|string $fileId): mixed
    {
        $file = File::find($fileId);
        if (!$file) {
            throw new \Exception('File not found');
        }

        // Handle public and private file download differently
        if ($this->isFilePublic($file)) {
            return Storage::disk('public')->download($file->path, $file->name);
        } else {
            return $this->generateTemporaryUrl($file);
        }
    }

    // Delete file (generalized for any disk)

    /**
     * @throws \Exception
     */
    public function deleteFile(int|string $fileId): bool
    {
        $file = File::find($fileId);
        if (!$file) {
            throw new \Exception('File not found');
        }

        // Delete the file from the appropriate disk
        Storage::disk($this->getDiskFromPath($file->path))->delete($file->path);

        // Delete the record from the database
        return $file->delete();
    }

    // Check if file is public based on path or disk
    protected function isFilePublic($file): bool
    {
        return str_contains($file->path, 'public');
    }

    // Generate temporary URL for private files
    protected function generateTemporaryUrl($file): string
    {
        $disk = 'private';  // Assuming 'private' disk for local or cloud storage
        $url = Storage::disk($disk)->temporaryUrl(
            $file->path,
            now()->addMinutes(5),  // URL expires in 5 minutes
            ['ResponseContentDisposition' => 'attachment; filename="' . $file->name . '"']
        );

        return redirect()->away($url);
    }

    // Get disk dynamically based on file path (could be enhanced if needed)
    protected function getDiskFromPath($path): string
    {
        // Check if the file is stored in public or private disk
        if (str_contains($path, 'public')) {
            return 'public';
        }

        return 'private';  // Default to private storage
    }
}

