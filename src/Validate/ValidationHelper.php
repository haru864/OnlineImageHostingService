<?php

namespace Validate;

class ValidationHelper
{
    public static function integer($value, float $min = -INF, float $max = INF): int
    {
        if (!is_int($value)) {
            throw new \InvalidArgumentException("The provided value is not a integer.");
        }
        $value = filter_var($value, FILTER_VALIDATE_INT, ["min_range" => (int) $min, "max_range" => (int) $max]);
        if ($value === false) throw new \InvalidArgumentException("The provided integer is too small/large.");
        return $value;
    }

    public static function string($value): string
    {
        if (is_null($value) || !is_string($value) || $value === "") {
            throw new \InvalidArgumentException("The provided value is not a valid string.");
        }
        return $value;
    }

    public static function image(): void
    {
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($_FILES['fileUpload']['size'] > $maxSize) {
            throw new \InvalidArgumentException("File Size Over: file size must be under 5MB.");
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['fileUpload']['type'];
        if (!in_array($fileType, $allowedTypes)) {
            throw new \InvalidArgumentException("Invalid File Type: jpeg, png, gif are allowed." . PHP_EOL . "Given file type was {$fileType}");
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['fileUpload']['tmp_name']);
        if (!in_array($mime, $allowedTypes)) {
            throw new \InvalidArgumentException("Invalid Mime Type: jpeg, png, gif are allowed.");
        }

        if ($_FILES['fileUpload']['error'] != UPLOAD_ERR_OK) {
            throw new \InvalidArgumentException("Upload Error: error occured when uploading iamge.");
        }

        $imageData = getimagesize($_FILES['fileUpload']['tmp_name']);
        if ($imageData === false) {
            throw new \InvalidArgumentException("Upload Error: server error occured when uploading iamge.");
        }
    }
}
