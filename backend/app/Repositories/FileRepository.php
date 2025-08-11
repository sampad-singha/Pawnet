<?php

// app/Repositories/FileRepository.php

namespace App\Repositories;

use App\Repositories\Interfaces\FileRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Util\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileRepository implements FileRepositoryInterface
{
    public function storeFile(UploadedFile $file, string $directory, Model $model, string $type = null): mixed
    {
        // Use the default disk (no need for disk parameter)
        $disk = config('filesystems.default');

        // Store the file on the default disk
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
    /**
     * @throws Exception
     */
    public function downloadFile(File $file): mixed
    {
        // Handle file download using the default disk
        return $this->generateTemporaryUrl($file);
    }

    // Delete file (generalized for any disk)
    /**
     * @throws Exception
     */
    public function deleteFile(int|string $fileId): bool
    {
        $file = File::find($fileId);
        if (!$file) {
            throw new Exception('File not found');
        }

        // Delete the file from the appropriate disk
        $disk = config('filesystems.default');
        Storage::disk($disk)->delete($file->path);

        // Delete the record from the database
        return $file->delete();
    }

    // Generate temporary URL for private files (works for all disks)
    protected function generateTemporaryUrl($file): string
    {
        $disk = config('filesystems.default');
        $url = Storage::disk($disk)->temporaryUrl(
            $file->path,
            now()->addMinutes(5),  // URL expires in 5 minutes
            ['ResponseContentDisposition' => 'attachment; filename="' . $file->name . '"']
        );

        return redirect()->away($url);
    }

    //Methods for handling large files
    public function streamFile(File $file): StreamedResponse
    {
        // Use the default disk or specify a disk if needed
        $disk = config('filesystems.default');

        // Make sure the file exists
        if (!Storage::disk($disk)->exists($file->path)) {
            abort(404);
        }

        $response = new StreamedResponse(function() use ($disk, $file) {
            $stream = Storage::disk($disk)->readStream($file->path);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        });

        // Set the headers for the download
        $response->headers->set('Content-Type', $file->mime_type);
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $file->name . '"');
        $response->headers->set('Content-Length', Storage::disk($disk)->size($file->path));

        return $response;
    }
}

