<?php

spl_autoload_extensions(".php");
spl_autoload_register(function ($class) {
    $class = str_replace("\\", "/", $class);
    $file = __DIR__ . '/../' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use Settings\Settings;
use Database\DatabaseHelper;
use Logging\Logger;

$logger = Logger::getInstance();

try {
    $logger->logInfo("[Batch] Starts a record deletion job.");
    $imageStorageDays = Settings::env('IMAGE_STORAGE_DAYS');
    $rows = DatabaseHelper::deleteNotAccessedImages($imageStorageDays);
    $numOfDeletedImages = 0;
    foreach ($rows as $row) {
        $hash = $row[0];
        $imageFilePath = Settings::env('IMAGE_FILE_LOCATION') . DIRECTORY_SEPARATOR . $hash;
        unlink($imageFilePath);
        $numOfDeletedImages++;
        $logger->logInfo("[Batch] delete '{$imageFilePath}'");
    }
    $logger->logInfo("[Batch] Number of records deleted: {$numOfDeletedImages}");
    $logger->logInfo("[Batch] Terminates a record deletion job.");
} catch (Throwable $e) {
    $logger->logError($e);
    $logger->logInfo("[Batch] Terminates a record deletion job with an error. ({$numOfDeletedImages} deleted)");
}
