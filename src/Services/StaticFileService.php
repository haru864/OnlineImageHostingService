<?php

namespace Services;

use Render\CSSRenderer;
use Render\ImageRenderer;
use Render\JavaScriptRenderer;
use Settings\Settings;

class StaticFileService
{
    public function __construct()
    {
    }

    public function getJavaScript(string $fileName): JavaScriptRenderer
    {
        return new JavaScriptRenderer($fileName);
    }

    public function getCSS(string $fileName): CSSRenderer
    {
        return new CSSRenderer($fileName);
    }

    public function getImage(string $imageFileBasename): ImageRenderer
    {
        $hash = explode(".", $imageFileBasename)[0];
        $imageFilePath = Settings::env('IMAGE_FILE_LOCATION') . DIRECTORY_SEPARATOR . $hash;
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($imageFilePath);
        return new ImageRenderer($mimeType, $imageFilePath);
    }
}
