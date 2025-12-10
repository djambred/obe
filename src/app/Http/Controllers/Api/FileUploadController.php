<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    /**
     * Generate pre-signed URL untuk direct upload ke MinIO
     * Best untuk production: tidak perlu CORS, signed URL langsung
     */
    public function getUploadUrl(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'folder' => 'required|string|in:universities,faculties,study-programs,lecturers,courses,rps',
            'mime_type' => 'nullable|string',
        ]);

        $folder = $request->folder;
        $extension = pathinfo($request->filename, PATHINFO_EXTENSION);
        $filename = Str::ulid() . '.' . $extension;
        $path = "{$folder}/logos/{$filename}";

        try {
            // Generate pre-signed PUT URL (valid 15 minutes)
            $url = Storage::disk('minio')->temporaryUploadUrl(
                $path,
                now()->addMinutes(15),
                [
                    'ContentType' => $request->mime_type ?? 'application/octet-stream',
                ]
            );

            return response()->json([
                'success' => true,
                'upload_url' => $url,
                'path' => $path,
                'public_url' => Storage::disk('minio')->url($path),
                'expires_in' => 900, // 15 minutes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate upload URL',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload file via backend (proxy method)
     * Best untuk local development & fallback
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'folder' => 'required|string|in:universities,faculties,study-programs,lecturers,courses,rps',
        ]);

        try {
            $file = $request->file('file');
            $folder = $request->folder;
            $subfolder = $this->getSubfolder($folder);

            // Store dengan nama unique
            $path = Storage::disk('minio')->putFile(
                "{$folder}/{$subfolder}",
                $file,
                'public'
            );

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::disk('minio')->url($path),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete file from MinIO
     */
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            if (Storage::disk('minio')->exists($request->path)) {
                Storage::disk('minio')->delete($request->path);

                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get temporary download URL
     */
    public function getDownloadUrl(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            if (!Storage::disk('minio')->exists($request->path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                ], 404);
            }

            $url = Storage::disk('minio')->temporaryUrl(
                $request->path,
                now()->addMinutes(60)
            );

            return response()->json([
                'success' => true,
                'url' => $url,
                'expires_in' => 3600, // 1 hour
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate download URL',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper: get subfolder based on type
     */
    private function getSubfolder($folder)
    {
        $map = [
            'universities' => 'logos',
            'faculties' => 'logos',
            'study-programs' => 'logos',
            'lecturers' => 'photos',
            'courses' => 'materials',
            'rps' => 'documents',
        ];

        return $map[$folder] ?? 'files';
    }
}
