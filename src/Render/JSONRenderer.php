<?php

namespace Render;

use Render\Interface\HTTPRenderer;

class JSONRenderer implements HTTPRenderer
{
    private int $statusCode = 200;
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
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
            'Content-Type' => 'application/json; charset=UTF-8',
        ];
    }

    public function getContent(): string
    {
        return json_encode($this->data, JSON_THROW_ON_ERROR);
    }
}
