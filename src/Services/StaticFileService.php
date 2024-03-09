<?php

namespace Services;

use Render\CSSRenderer;
use Render\JavaScriptRenderer;
use Render\Interface\HTTPRenderer;

class StaticFileService
{
    public function __construct()
    {
    }

    public function getJavaScript(string $fileName): HTTPRenderer
    {
        return new JavaScriptRenderer($fileName);
    }

    public function getCSS(string $fileName): HTTPRenderer
    {
        return new CSSRenderer($fileName);
    }
}
