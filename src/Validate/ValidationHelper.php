<?php

namespace Validate;

use Settings\Settings;
use Database\DatabaseHelper;
use Exceptions\FileSizeLimitExceededException;
use Exceptions\FileUploadLimitExceededException;
use Exceptions\InternalServerException;
use Exceptions\InvalidMimeTypeException;
use Exceptions\InvalidHashException;

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
        // php.iniで定義されたアップロード可能な最大ファイルサイズを上回る場合もこのエラーになる
        if ($_FILES['fileUpload']['error'] != UPLOAD_ERR_OK) {
            throw new InternalServerException("Upload Error: error occured when uploading image.");
        }

        // php.iniで定義されたアップロード可能な最大ファイルサイズを下回る必要がある
        $uploadLimitMaxBytes = Settings::env('UPLOAD_LIMIT_MAX_BYTES');
        if ($_FILES['fileUpload']['size'] > $uploadLimitMaxBytes) {
            throw new FileSizeLimitExceededException("File Size Over: file size must be under {$uploadLimitMaxBytes} bytes.");
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['fileUpload']['type'];
        if (!in_array($fileType, $allowedTypes)) {
            throw new InvalidMimeTypeException("Invalid File Type: jpeg, png, gif are allowed. Given file type was '{$fileType}'");
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['fileUpload']['tmp_name']);
        if (!in_array($mime, $allowedTypes)) {
            throw new InvalidMimeTypeException("Invalid Mime Type: jpeg, png, gif are allowed. Given MIME-TYPE was '{$fileType}'");
        }

        $imageData = getimagesize($_FILES['fileUpload']['tmp_name']);
        if ($imageData === false) {
            throw new InternalServerException("Upload Error: server error occured when uploading image.");
        }
    }

    public static function client(string $clientIpAddress): void
    {
        $uploadLimitTermMinutes = Settings::env('UPLOAD_LIMIT_TERM_MINUTES');
        $uploadLimitNumOfFiles = Settings::env('UPLOAD_LIMIT_NUM_OF_FILES');
        $uploadLimitTotalBytes = Settings::env('UPLOAD_LIMIT_TOTAL_BYTES');

        $rows = DatabaseHelper::getImageHashesForClientByTime($clientIpAddress, $uploadLimitTermMinutes);
        if (is_null($rows)) {
            return;
        }

        $numOfFiles = 0;
        $totalBytes = 0;
        foreach ($rows as $row) {
            $imageFilePath = Settings::env('IMAGE_FILE_LOCATION') . DIRECTORY_SEPARATOR . $row[0];
            $numOfFiles++;
            $totalBytes += filesize($imageFilePath);
        }

        if ($numOfFiles >= $uploadLimitNumOfFiles) {
            throw new FileUploadLimitExceededException("The maximum number of files that can be uploaded has been reached. ({$uploadLimitNumOfFiles} files per {$uploadLimitTermMinutes} minutes)");
        }
        if ($totalBytes >= $uploadLimitTotalBytes) {
            throw new FileSizeLimitExceededException("The total uploadable file size limit has been reached. ({$uploadLimitTotalBytes} bytes per {$uploadLimitTermMinutes} minutes)");
        }
    }

    public static function hash(string $hash): void
    {
        $viewCount = DatabaseHelper::selectViewCount($hash);
        $imageFilePath = Settings::env('IMAGE_FILE_LOCATION') . DIRECTORY_SEPARATOR . $hash;
        if (is_null($viewCount) || !file_exists($imageFilePath)) {
            throw new InvalidHashException('Invalid hash value. No corresponding image.');
        }
    }
}
