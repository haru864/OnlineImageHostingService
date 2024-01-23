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
        $logger = Logger::getInstance();
        ob_start();
        var_dump($_FILES);
        $output = ob_get_clean();
        $logger->log(LogLevel::DEBUG, $output);

        ValidationHelper::image();
        $tmpFilePath = $_FILES['fileUpload']['tmp_name'];
        $imageData = file_get_contents($tmpFilePath);
        $uploadDate = date('Y-m-d H:i:s');
        $combinedData = $imageData . $uploadDate;
        $hash = hash('sha256', $combinedData);
        $base_url = Settings::env("BASE_URL");
        $view_url = "{$base_url}/view?hash={$hash}";
        $delete_url = "{$base_url}/delete?hash={$hash}";

        $logger->log(LogLevel::DEBUG, $tmpFilePath);
        $logger->log(LogLevel::DEBUG, $hash);
        // $logger->log(LogLevel::DEBUG, $imageData);
        $logger->log(LogLevel::DEBUG, $uploadDate);
        $logger->log(LogLevel::DEBUG, $view_url);
        $logger->log(LogLevel::DEBUG, $delete_url);

        DatabaseHelper::insertImage($hash, $imageData, $_FILES['fileUpload']['type'], $uploadDate, $view_url, $delete_url);
        return new JSONRenderer(['view_url' => $view_url, 'delete_url' => $delete_url]);
    },
    // 'TextSnippetSharingService/display' => function (): HTTPRenderer {
    //     $hash_value = ValidationHelper::string($_GET['hash'] ?? null);
    //     $result = DatabaseHelper::getSnippetAndLanguageByHashValue($hash_value);
    //     if (!$result) {
    //         return new HTMLRenderer('expired', []);
    //     }
    //     $snippet = $result[0];
    //     $language = $result[1];
    //     return new HTMLRenderer('snippet', ['snippet' => $snippet, 'language' => $language]);
    // }
];
