<?php
// app/helpers/upload.php

function handleCarImageUpload(array $file, int $carId): ?string
{
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes, true)) {
        return null;
    }

    if ($file['size'] > $maxSize) {
        return null;
    }

    $uploadDir = BASE_PATH . '/public/assets/uploads/cars/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = match ($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        default      => 'jpg',
    };

    $filename = 'car_' . $carId . '_' . uniqid() . '.' . $extension;
    $destination = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return 'assets/uploads/cars/' . $filename;
    }

    return null;
}