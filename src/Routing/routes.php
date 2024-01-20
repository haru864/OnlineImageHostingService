<?php

use Database\DatabaseHelper;
use Validate\ValidationHelper;
use Render\interface\HTTPRenderer;
use Render\HTMLRenderer;
use Render\JSONRenderer;

return [
    'OnlineImageHostingService/uploader' => function (): HTTPRenderer {
        return new HTMLRenderer('uploader', []);
    },
    // 'TextSnippetSharingService/register' => function (): HTTPRenderer {
    //     $snippet = ValidationHelper::string($_POST['snippet'] ?? null);
    //     $language = ValidationHelper::string($_POST['language'] ?? null);
    //     $term_minute = $_POST['term_minute'] ? intval($_POST['term_minute']) : null;
    //     $hash_value = hash('sha256', $snippet);
    //     DatabaseHelper::insertSnippet($hash_value, $snippet, $language, $term_minute);
    //     $url = "http://localhost:8000/TextSnippetSharingService/display?hash={$hash_value}";
    //     return new JSONRenderer(['url' => $url]);
    // },
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
