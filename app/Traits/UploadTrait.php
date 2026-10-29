<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{
    public function uploadImage(?UploadedFile $file, string $folder = 'uploads', ?string $oldPath = null): ?string
    {
        if (!$file) {
            return $oldPath;
        }

        // Delete old file if present
        if ($oldPath) {
            $cleanOld = $oldPath;
            if (filter_var($cleanOld, FILTER_VALIDATE_URL)) {
                $parsed = parse_url($cleanOld, PHP_URL_PATH);
                if ($parsed && str_contains($parsed, '/storage/')) {
                    $cleanOld = substr($parsed, strpos($parsed, '/storage/') + 9);
                } else {
                    $cleanOld = ltrim((string) $parsed, '/');
                }
            }

            $cleanOld = ltrim($cleanOld, '/');
            if (str_starts_with($cleanOld, 'storage/')) {
                $cleanOld = substr($cleanOld, 8);
            }

            if (!str_contains($cleanOld, 'default.jpg')) {
                if (Storage::disk('public')->exists($cleanOld)) {
                    Storage::disk('public')->delete($cleanOld);
                } elseif (file_exists(public_path($cleanOld))) {
                    @unlink(public_path($cleanOld));
                }
            }
        }

        // Store file in public storage disk
        return $file->store($folder, 'public');
    }
}
