<?php

namespace Exceptions;

use Exceptions\Interface\UserVisibleException;
use Exceptions\Trait\GenericUserVisibleException;

class FileUploadLimitExceededException extends UserVisibleException
{
    use GenericUserVisibleException;

    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
