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
        $imageFileDir = Settings::env('IMAGE_FILE_LOCATION');
        $imageFilePath = $imageFileDir . DIRECTORY_SEPARATOR . $hash;
        if (!rename($uploadedTmpImagePath, $imageFilePath)) {
            throw new InternalServerException("Failed to save uploaded image file.");
        }
    }
}
