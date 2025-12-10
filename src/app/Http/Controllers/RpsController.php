<?php

namespace App\Http\Controllers;

use App\Models\Rps;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RpsController extends Controller
{
    /**
     * Preview RPS as PDF (inline)
     */
    public function previewPdf(Rps $rps)
    {
        return $this->generatePdf($rps, true);
    }

    /**
     * Download RPS as PDF
     */
    public function downloadPdf(Rps $rps)
    {
        return $this->generatePdf($rps, false);
    }

    /**
     * Generate PDF (shared method for preview and download)
     */
    private function generatePdf(Rps $rps, bool $inline = false)
    {
        $rps->load(['faculty', 'studyProgram', 'course', 'lecturer', 'coordinator', 'headOfProgram', 'curriculum']);

        // Get university data
        $university = \App\Models\University::where('is_active', true)->first();

        // Handle logo from MinIO - resize and convert to base64 for PDF
        $logoBase64 = null;
        if ($university && $university->logo) {
            try {
                $disk = Storage::disk('minio');
                if ($disk->exists($university->logo)) {
                    // Get file content from MinIO
                    $logoContent = $disk->get($university->logo);

                    // Get extension
                    $extension = pathinfo($university->logo, PATHINFO_EXTENSION);

                    // Create image resource from string
                    $image = @imagecreatefromstring($logoContent);

                    if ($image !== false) {
                        // Get original dimensions
                        $originalWidth = imagesx($image);
                        $originalHeight = imagesy($image);

                        // Calculate new dimensions (max 100px width/height for header)
                        $maxSize = 100;
                        $ratio = min($maxSize / $originalWidth, $maxSize / $originalHeight);
                        $newWidth = (int)($originalWidth * $ratio);
                        $newHeight = (int)($originalHeight * $ratio);

                        // Create new image with smaller size
                        $resized = imagecreatetruecolor($newWidth, $newHeight);

                        // Preserve transparency for PNG
                        if (strtolower($extension) === 'png') {
                            imagealphablending($resized, false);
                            imagesavealpha($resized, true);
                            $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                            imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
                        }

                        // Resize image
                        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

                        // Convert to base64
                        ob_start();
                        if (strtolower($extension) === 'png') {
                            imagepng($resized, null, 9);
                            $mimeType = 'image/png';
                        } else {
                            imagejpeg($resized, null, 85);
                            $mimeType = 'image/jpeg';
                        }
                        $resizedContent = ob_get_clean();

                        $logoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($resizedContent);

                        // Free memory
                        imagedestroy($image);
                        imagedestroy($resized);
                    }
                }
            } catch (\Exception $e) {
                // If logo fails, continue without it
                Log::warning('Failed to load university logo: ' . $e->getMessage());
            }
        }

        // Generate QR Code for RPS - link to public verification page
        $qrCodePath = null;
        try {
            $rpsUrl = route('rps.verify', $rps->id);
            $qrCode = QrCode::format('png')
                ->size(150)
                ->margin(0)
                ->generate($rpsUrl);

            $tempPath = storage_path('app/temp');
            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0755, true);
            }

            $qrCodeFile = $tempPath . '/rps_qrcode_' . $rps->id . '.png';
            file_put_contents($qrCodeFile, $qrCode);
            $qrCodePath = $qrCodeFile;
        } catch (\Exception $e) {
            Log::warning('Failed to generate QR code: ' . $e->getMessage());
        }

        // Prepare data for template
        $data = [
            'rps' => $rps,
            'university' => $university,
            'logoBase64' => $logoBase64,
            'qrCodePath' => $qrCodePath,
        ];

        $pdf = Pdf::loadView('pdf.rps', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10);

        // Sanitize filename - replace / and \ with -
        $sanitizedYear = str_replace(['/', '\\'], '-', $rps->academic_year);
        $sanitizedCode = str_replace(['/', '\\'], '-', $rps->course->code);
        $filename = 'RPS_' . $sanitizedCode . '_' . $sanitizedYear . '_' . $rps->semester . '.pdf';

        if ($inline) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * Public RPS Verification Page
     */
    public function verifyRps(Rps $rps)
    {
        $rps->load(['faculty', 'studyProgram', 'course', 'lecturer', 'coordinator', 'headOfProgram', 'curriculum']);

        // Get university data
        $university = \App\Models\University::where('is_active', true)->first();

        // Handle logo from MinIO
        $logoUrl = null;
        if ($university && $university->logo) {
            try {
                $disk = Storage::disk('minio');
                if ($disk->exists($university->logo)) {
                    // Generate URL for MinIO
                    $logoUrl = $disk->url($university->logo);
                }
            } catch (\Exception $e) {
                Log::warning('Failed to load university logo: ' . $e->getMessage());
            }
        }

        return view('rps.verify', compact('rps', 'university', 'logoUrl'));
    }
}
