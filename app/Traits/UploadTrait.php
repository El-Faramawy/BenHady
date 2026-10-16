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
            $cleanOld = str_starts_with($oldPath, 'storage/') ? substr($oldPath, 8) : $oldPath;
            if (Storage::disk('public')->exists($cleanOld)) {
                Storage::disk('public')->delete($cleanOld);
            } elseif (file_exists(public_path($oldPath)) && !str_contains($oldPath, 'default.jpg')) {
                @unlink(public_path($oldPath));
            }
        }

        // Store file in public storage disk
        return $file->store($folder, 'public');
    }
}
