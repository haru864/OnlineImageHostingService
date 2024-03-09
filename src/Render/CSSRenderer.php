<?php

namespace Render;

use Render\Interface\HTTPRenderer;

class CSSRenderer implements HTTPRenderer
{
    private int $statusCode = 200;
    private string $cssFileBasename;

    public function __construct(string $cssFileBasename)
    {
        $this->cssFileBasename = $cssFileBasename;
    }

    public function isStringContent(): bool
    {
        return true;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getFields(): array
    {
        return [
            'Content-Type' => 'text/css',
        ];
    }

    public function getContent(): string
    {
        $cssFileBasename = $this->getScriptFilePath($this->cssFileBasename);
        if (!file_exists($cssFileBasename)) {
            throw new \Exception("CSS file '{$cssFileBasename}' does not exist.");
        }
        return file_get_contents($cssFileBasename);
    }

    private function getScriptFilePath(string $cssFileBasename): string
    {
        return sprintf("%s/%s/Views/css/%s.css", __DIR__, '..', $cssFileBasename);
    }
}
