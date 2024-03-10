<?php

use Controllers\ImageController;
use Controllers\StaticFileController;
use Http\HttpRequest;
use Services\ImageService;
use Services\StaticFileService;
use Settings\Settings;

$httpRequest = new HttpRequest();
$imageService = new ImageService();
$staticFileService = new StaticFileService();
$imageController = new ImageController($imageService, $httpRequest);
$staticFileController = new StaticFileController($staticFileService, $httpRequest);

$URL_DIR_PATTERN_HOME = '/^\/$/';
$URL_DIR_PATTERN_UPLOAD = Settings::env("URL_DIR_PATTERN_UPLOAD");
$URL_DIR_PATTERN_DISPLAY = Settings::env("URL_DIR_PATTERN_DISPLAY");
$URL_DIR_PATTERN_DELETE = Settings::env("URL_DIR_PATTERN_DELETE");
$URL_DIR_PATTERN_STATIC_FILE = Settings::env("URL_DIR_PATTERN_STATIC_FILE");

return [
    $URL_DIR_PATTERN_HOME => $imageController,
    $URL_DIR_PATTERN_UPLOAD => $imageController,
    $URL_DIR_PATTERN_DISPLAY => $imageController,
    $URL_DIR_PATTERN_DELETE => $imageController,
    $URL_DIR_PATTERN_STATIC_FILE => $staticFileController
];
