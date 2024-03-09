<?php

namespace Exceptions;

use Exceptions\Interface\UserVisibleException;
use Exceptions\Trait\GenericUserVisibleException;

class InvalidUrlException extends UserVisibleException
{
    use GenericUserVisibleException;

    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
