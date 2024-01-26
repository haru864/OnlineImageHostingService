<?php

use Database\DatabaseHelper;
use Validate\ValidationHelper;
use Render\interface\HTTPRenderer;
use Render\HTMLRenderer;
use Render\JSONRenderer;
use Settings\Settings;
use Logging\Logger;
use Logging\LogLevel;

return [
    'OnlineImageHostingService/uploader' => function (): HTTPRenderer {
        return new HTMLRenderer('uploader');
    },
    'OnlineImageHostingService/register' => function (): HTTPRenderer {
        ValidationHelper::image();
        $tmpFilePath = $_FILES['fileUpload']['tmp_name'];
        $imageData = file_get_contents($tmpFilePath);
        $uploadDate = date('Y-m-d H:i:s');
        $combinedData = $imageData . $uploadDate;
        $hash = hash('sha256', $combinedData);
        $base_url = Settings::env("BASE_URL");
        $view_url = "{$base_url}/view?hash={$hash}";
        $delete_url = "{$base_url}/delete?hash={$hash}";

        $logger = Logger::getInstance();
        // ob_start();
        // var_dump($_FILES);
        // $output = ob_get_clean();
        // $logger->log(LogLevel::DEBUG, $output);
        // $logger->log(LogLevel::DEBUG, $tmpFilePath);
        // $logger->log(LogLevel::DEBUG, $hash);
        // $logger->log(LogLevel::DEBUG, $imageData);
        // $logger->log(LogLevel::DEBUG, $uploadDate);
        // $logger->log(LogLevel::DEBUG, $view_url);
        // $logger->log(LogLevel::DEBUG, $delete_url);

        DatabaseHelper::insertImage($hash, $imageData, $_FILES['fileUpload']['type'], $uploadDate, $view_url, $delete_url);
        return new JSONRenderer(['view_url' => $view_url, 'delete_url' => $delete_url]);
    },
    'OnlineImageHostingService/view' => function (): HTTPRenderer {
        $hash = ValidationHelper::string($_GET['hash'] ?? null);
        DatabaseHelper::incrementViewCount($hash);
        $imageData = DatabaseHelper::selectImage($hash);
        $encoded_image = base64_encode($imageData);
        $extension = DatabaseHelper::selectExtension($hash);
        $view_count = DatabaseHelper::selectViewCount($hash);
        return new HTMLRenderer('viewer', ['encoded_image' => $encoded_image, 'extension' => $extension, 'view_count' => $view_count]);
    },
    'OnlineImageHostingService/delete' => function (): HTTPRenderer {
        $hash = ValidationHelper::string($_GET['hash'] ?? null);
        $imageData = DatabaseHelper::selectImage($hash);
        if (is_null($imageData)) {
            return new HTMLRenderer('deleted', ['delete_message' => '削除済みの画像です。']);
        }
        DatabaseHelper::deleteRow($hash);
        return new HTMLRenderer('deleted', ['delete_message' => '画像の削除に成功しました。']);
    }
];
