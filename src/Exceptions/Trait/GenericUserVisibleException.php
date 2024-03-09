<?php

namespace Exceptions\Trait;

trait GenericUserVisibleException
{
    public function displayErrorMessage(): string
    {
        return "<div>" . $this->getMessage() . "<div>";
    }
}
