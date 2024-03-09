<?php

namespace Render;

use Render\Interface\HTTPRenderer;

class ImageRenderer implements HTTPRenderer
{
    private int $statusCode = 200;
    private string $mimeType;
    private string $imageFilepath;

    public function __construct(string $mimeType, string $imageFilepath)
    {
        $this->mimeType = $mimeType;
        $this->imageFilepath = $imageFilepath;
    }

    public function isStringContent(): bool
    {
        return false;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getFields(): array
    {
        return [
            'Content-Type' => $this->mimeType,
        ];
    }

    public function getContent(): string
    {
        return $this->imageFilepath;
    }
}
