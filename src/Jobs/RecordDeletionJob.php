<?php

use Settings\Settings;
use Database\DatabaseHelper;
use Logging\Logger;
use Logging\LogLevel;

$logger = Logger::getInstance();

try {
    $logger->log(LogLevel::INFO, "Starts a record deletion job.");
    $imageStorageDays = Settings::env('IMAGE_STORAGE_DAYS');
    $numOfDeletedImages = DatabaseHelper::deleteNotAccessedImages($imageStorageDays);
    $logger->log(LogLevel::INFO, "Number of records deleted: {$numOfDeletedImages}");
    $logger->log(LogLevel::INFO, "Terminates a record deletion job.");
} catch (Throwable $e) {
    $logger->log(LogLevel::ERROR, $e->getMessage());
    $logger->log(LogLevel::INFO, "Terminates a record deletion job with an error.");
}
