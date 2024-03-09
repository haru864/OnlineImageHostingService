<?php

namespace Controllers;

use Controllers\Interface\ControllerInterface;
use Exceptions\InvalidUrlException;
use Exceptions\InvalidRequestMethodException;
use Services\ImageService;
use Http\HttpRequest;
use Render\interface\HTTPRenderer;
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
            return $this->registerImage();
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

    private function registerImage(): JSONRenderer
    {
        ValidationHelper::image();
        ValidationHelper::client($_SERVER['REMOTE_ADDR']);
        $tmpFilePath = $_FILES['fileUpload']['tmp_name'];
        $imageData = file_get_contents($tmpFilePath);
        $uploadDate = date('Y-m-d H:i:s');
        $combinedData = $imageData . $uploadDate;
        $hash = hash('sha256', $combinedData);
        $base_url = Settings::env("BASE_URL");
        $mediaType = $_FILES['fileUpload']['type'];
        $subTypeName = explode('/', $mediaType)[1];
        $view_url = "{$base_url}/{$subTypeName}/{$hash}";
        $delete_url = "{$base_url}/delete/{$hash}";
        $client_ip_address = $_SERVER['REMOTE_ADDR'];
        DatabaseHelper::insertImage($hash, $imageData, $mediaType, $uploadDate, $view_url, $delete_url, $client_ip_address);
        return new JSONRenderer(['view_url' => $view_url, 'delete_url' => $delete_url]);
    }

    private function getViewPage(): HTMLRenderer
    {
        $hash = $this->httpRequest->getSubDir();
        $imageData = DatabaseHelper::selectImage($hash);
        if (is_null($imageData)) {
            return new HTMLRenderer(200, 'deleted', ['delete_message' => '削除済みの画像です。']);
        }
        DatabaseHelper::deleteRow($hash);
        return new HTMLRenderer(200, 'deleted', ['delete_message' => '画像の削除に成功しました。']);
    }

    private function deleteImage(): HTMLRenderer
    {
        $hash = $this->httpRequest->getSubDir();
        DatabaseHelper::incrementViewCount($hash);
        DatabaseHelper::updateAccessedDate($hash, date('Y-m-d H:i:s'));
        $imageData = DatabaseHelper::selectImage($hash);
        $encoded_image = base64_encode($imageData);
        $mediaType = DatabaseHelper::selectMediaType($hash);
        $view_count = DatabaseHelper::selectViewCount($hash);
        return new HTMLRenderer(200, 'viewer', [
            'encoded_image' => $encoded_image, 'media_type' => $mediaType, 'view_count' => $view_count
        ]);
    }
}
