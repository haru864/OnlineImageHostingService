<?php

namespace Controllers;

use Controllers\Interface\ControllerInterface;
use Exceptions\InvalidUrlException;
use Exceptions\InvalidRequestMethodException;
use Services\ImageService;
use Http\HttpRequest;
use Render\Interface\HTTPRenderer;
use Render\HTMLRenderer;
use Render\JSONRenderer;
use Validate\ValidationHelper;
use Settings\Settings;
use Database\DatabaseHelper;
use Render\RedirectRenderer;

class ImageController implements ControllerInterface
{
    private ImageService $imageService;
    private HttpRequest $httpRequest;

    public function __construct(ImageService $imageService, HttpRequest $httpRequest)
    {
        $this->imageService = $imageService;
        $this->httpRequest = $httpRequest;
    }

    public function assignProcess(): HTTPRenderer
    {
        $urlTorDir = $this->httpRequest->getTopDir();
        $numOfUrlDirLayers = $this->httpRequest->getNumOfUrlDirLayers();
        if ($urlTorDir === '') {
            $uploadUrl = Settings::env('BASE_URL') . '/upload';
            return new RedirectRenderer($uploadUrl, []);
        } else if ($urlTorDir === 'upload') {
            return $this->processUpload();
        } else if (in_array($urlTorDir, ['jpeg', 'png', 'gif']) && $numOfUrlDirLayers >= 2) {
            return $this->processImage();
        } else {
            throw new InvalidUrlException("Given URL is Invalid.");
        }
    }

    private function processUpload(): HTTPRenderer
    {
        $requestMethod = $this->httpRequest->getMethod();
        if ($requestMethod === 'GET') {
            return $this->getUploadPage();
        } else if ($requestMethod === 'POST') {
            return $this->publishURL();
        } else {
            throw new InvalidRequestMethodException("Supported Method: GET, POST");
        }
    }

    private function processImage(): HTTPRenderer
    {
        $numOfUrlDirLayers = $this->httpRequest->getNumOfUrlDirLayers();
        $isDeletion = ($numOfUrlDirLayers === 3 && $this->httpRequest->getThirdDir() === 'delete');
        if ($isDeletion) {
            return $this->deleteImage();
        } else {
            return $this->getViewPage();
        }
    }

    private function getUploadPage(): HTMLRenderer
    {
        return new HTMLRenderer(200, $this->imageService->getUploadPageName(), []);
    }

    private function publishURL(): JSONRenderer
    {
        ValidationHelper::image();
        ValidationHelper::client($_SERVER['REMOTE_ADDR']);
        $hash = $this->imageService->registerImage();
        $this->imageService->moveUploadedFile($hash);
        $mediaType = $_FILES['fileUpload']['type'];
        $subTypeName = explode('/', $mediaType)[1];
        $baseUrl = Settings::env("BASE_URL");
        $viewUrl = "{$baseUrl}/{$subTypeName}/{$hash}";
        $deleteUrl = "{$viewUrl}/delete";
        return new JSONRenderer(['viewUrl' => $viewUrl, 'deleteUrl' => $deleteUrl]);
    }

    private function getViewPage(): HTMLRenderer
    {
        $hash = $this->httpRequest->getSubDir();
        $imageFileBasename = $this->imageService->getImageFileBasename($hash);
        $this->imageService->updateImageView($hash);
        $viewCount = $this->imageService->getViewCount($hash);
        return new HTMLRenderer(200, 'viewer', [
            'view_count' => $viewCount, 'image_file_basename' => $imageFileBasename
        ]);
    }

    private function deleteImage(): HTMLRenderer
    {
        $hash = $this->httpRequest->getSubDir();
        $imageData = DatabaseHelper::selectImage($hash);
        if (is_null($imageData)) {
            return new HTMLRenderer(200, 'deleted', ['delete_message' => '削除済みの画像です。']);
        }
        DatabaseHelper::deleteRow($hash);
        return new HTMLRenderer(200, 'deleted', ['delete_message' => '画像の削除に成功しました。']);
    }
}
