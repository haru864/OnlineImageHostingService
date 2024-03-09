<?php

namespace Controllers;

use Controllers\Interface\ControllerInterface;
use Http\HttpRequest;
use Render\Interface\HTTPRenderer;
use Validate\ValidationHelper;
use Services\StaticFileService;

class StaticFileController implements ControllerInterface
{
    private StaticFileService $staticFileService;
    private HttpRequest $httpRequest;

    public function __construct(StaticFileService $staticFileService, HttpRequest $httpRequest)
    {
        $this->staticFileService = $staticFileService;
        $this->httpRequest = $httpRequest;
    }

    public function assignProcess(): HTTPRenderer
    {
        ValidationHelper::validateGetStaticFileRequest();
        $urlTopDir = $this->httpRequest->getTopDir();
        $urlSubDir = $this->httpRequest->getSubDir();
        if ($urlTopDir === "css") {
            return $this->staticFileService->getCSS($urlSubDir);
        } else if ($urlTopDir === "js") {
            return $this->staticFileService->getJavaScript($urlSubDir);
        }
    }
}
