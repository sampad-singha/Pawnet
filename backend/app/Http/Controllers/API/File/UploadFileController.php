<?php

namespace App\Http\Controllers\API\File;

use App\Http\Controllers\Controller;
use App\Services\File\FileService;
use App\Services\File\Interfaces\FileServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UploadFileController extends Controller
{
    // Declare typed property for dependency injection
    private FileServiceInterface $fileService;

    // Constructor to initialize the fileService via dependency injection
    public function __construct(FileServiceInterface $fileService)
    {
        $this->fileService = $fileService;  // Initialize the $fileService property
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if ($request->file('chunkfile')) {
            $folderName = $this->fileService->simpleUpload($request->file('chunkfile'));
            if ($folderName)  return response()->json(['folderPath' => $folderName], ResponseAlias::HTTP_OK);
            return response()->json('error', ResponseAlias::HTTP_NOT_ACCEPTABLE);
        } elseif ($request->method() == 'POST') {
            $folderName = $this->fileService->startChunkProcess();
            if ($folderName) return response()->json($folderName, ResponseAlias::HTTP_OK);
            return response()->json('error', ResponseAlias::HTTP_NOT_ACCEPTABLE);
        } else {
            $result = $this->fileService->processChunkUploads($request);
            if (!$result) return response()->json('error', ResponseAlias::HTTP_NOT_ACCEPTABLE);
        }

        return response()->json('success', ResponseAlias::HTTP_OK);
    }
}
