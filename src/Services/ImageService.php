<?php

namespace Services;

use Validate\ValidationHelper;
use Database\DatabaseHelper;
use Settings\Settings;
use Exceptions\InternalServerException;

class ImageService
{
    public function getUploadPageName(): string
    {
        return 'uploader';
    }

    public function getViewPageName(): string
    {
        return 'viewer';
    }

    public function registerImage(): string
    {
        $tmpFilePath = $_FILES['fileUpload']['tmp_name'];
        $imageData = file_get_contents($tmpFilePath);
        $uploadDate = date('Y-m-d H:i:s');
        $combinedData = $imageData . $uploadDate;
        $hash = $this->generateUniqueHashWithLimit($combinedData);
        DatabaseHelper::insertImage($hash, $_SERVER['REMOTE_ADDR']);
        return $hash;
    }

    private function generateUniqueHashWithLimit(string $data, $limit = 100): string
    {
        $hash = hash('sha256', $data);
        $counter = 0;
        while ($counter < $limit) {
            $registeredSnippet = DatabaseHelper::selectViewCount($hash);
            if (is_null($registeredSnippet)) {
                return $hash;
            }
            $counter++;
            $hash = hash('sha256', $data . $counter);
        }
        throw new InternalServerException('Failed to generate unique hash value.');
    }

    public function moveUploadedFile(string $hash): void
    {
        $uploadedTmpImagePath = $_FILES['fileUpload']['tmp_name'];
        $imageFilePath = Settings::env('IMAGE_FILE_LOCATION') . DIRECTORY_SEPARATOR . $hash;
        if (!rename($uploadedTmpImagePath, $imageFilePath)) {
            throw new InternalServerException("Failed to save uploaded image file.");
        }
    }

    public function getImageFileBasename(string $hash): string
    {
        ValidationHelper::hash($hash);
        $imageFilePath = Settings::env('IMAGE_FILE_LOCATION') . DIRECTORY_SEPARATOR . $hash;
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($imageFilePath);
        $extension = explode("/", $mimeType)[1];
        return "{$hash}.{$extension}";
    }

    public function getViewCount(string $hash): int
    {
        return DatabaseHelper::selectViewCount($hash);
    }

    public function updateImageView(string $hash): void
    {
        // TODO トランザクション化して一貫性を担保したい
        DatabaseHelper::incrementViewCount($hash);
        DatabaseHelper::updateAccessedDate($hash);
    }
}
