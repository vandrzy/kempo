<?php

namespace App\Helpers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class FotoHelper
{
    /**
     * Simpan file foto dan pastikan tersedia baik di storage internal maupun public storage.
     *
     * @param \Illuminate\Http\UploadedFile|null $image
     * @param string|null $existingFoto
     * @return string|null
     */
    public static function simpanFoto($image, ?string $existingFoto = null): ?string
    {
        if ($image && $image->isValid()) {
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $storageDir = storage_path('app/public/foto_peserta');
            $publicDir = public_path('storage/foto_peserta');

            if (!file_exists($storageDir)) {
                @mkdir($storageDir, 0755, true);
            }
            if (!file_exists($publicDir)) {
                @mkdir($publicDir, 0755, true);
            }

            $storagePath = $storageDir . '/' . $filename;
            $publicPath = $publicDir . '/' . $filename;

            try {
                $manager = new ImageManager(new GdDriver());
                $manager->read($image->getRealPath())
                    ->scale(width: 1200)
                    ->save($storagePath, quality: 50);
            } catch (\Throwable $e) {
                @copy($image->getRealPath(), $storagePath);
            }

            // Jika public/storage bukan symlink/junction, salin ke public_path agar langsung bisa diakses browser
            $realStorageDir = realpath($storageDir);
            $realPublicDir = realpath($publicDir);
            if ($realStorageDir !== $realPublicDir && file_exists($storagePath)) {
                @copy($storagePath, $publicPath);
            }

            return 'storage/foto_peserta/' . $filename;
        }

        if (!empty($existingFoto)) {
            // Jika foto lama digunakan (misal dari fitur auto-fill), pastikan file fisik tersedia di public
            $cleanPath = ltrim(str_replace('storage/', '', $existingFoto), '/');
            $storageExisting = storage_path('app/public/' . $cleanPath);
            $publicExisting = public_path('storage/' . $cleanPath);

            if (file_exists($storageExisting) && !file_exists($publicExisting)) {
                if (!file_exists(dirname($publicExisting))) {
                    @mkdir(dirname($publicExisting), 0755, true);
                }
                @copy($storageExisting, $publicExisting);
            }

            return $existingFoto;
        }

        return null;
    }
}
