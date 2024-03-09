<?php

namespace Http;

use Exceptions\InvalidRequestURIException;

class HttpRequest
{
    private string $method;
    private string $uri;
    private array $pathArray = [];
    private array $queryStringArray = [];
    private array $textParamArray = [];

    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $pathString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathTrimed = ltrim($pathString, '/');
        $this->pathArray = explode('/', $pathTrimed);
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'GET') {
            $queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
            parse_str($queryString, $this->queryStringArray);
        } elseif ($this->method == 'POST') {
            foreach ($_POST as $key => $value) {
                $this->textParamArray[$key] = $value;
            }
        }
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getURI(): string
    {
        return $this->uri;
    }

    public function getTextParam(string $paramName): string
    {
        return $this->textParamArray[$paramName];
    }

    public function getNumOfUrlDirLayers(): int
    {
        return count($this->pathArray);
    }

    public function getTopDir(): string
    {
        return $this->pathArray[0];
    }

    public function getSubDir(): string
    {
        return $this->pathArray[1];
    }

    public function getThirdDir(): string
    {
        return $this->pathArray[2];
    }
}
