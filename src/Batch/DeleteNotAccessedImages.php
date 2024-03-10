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
    $logger->logInfo("Starts a record deletion job.");
    $imageStorageDays = Settings::env('IMAGE_STORAGE_DAYS');
    $numOfDeletedImages = DatabaseHelper::deleteNotAccessedImages($imageStorageDays);
    $logger->logInfo("Number of records deleted: {$numOfDeletedImages}");
    $logger->logInfo("Terminates a record deletion job.");
} catch (Throwable $e) {
    $logger->logError($e);
    $logger->logInfo("Terminates a record deletion job with an error.");
}
