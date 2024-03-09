<?php

namespace Services;

class ImageService
{
    public function getUploadPageName(): string
    {
        return 'uploader';
    }

    public function getViewPageName(): string
    {
        return 'viewer';
    }
}
