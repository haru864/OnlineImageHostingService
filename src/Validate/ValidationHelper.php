<?php

namespace Validate;

class ValidationHelper
{
    public static function integer($value, float $min = -INF, float $max = INF): int
    {
        if (!is_int($value)) {
            throw new \InvalidArgumentException("The provided value is not a integer.");
        }
        $value = filter_var($value, FILTER_VALIDATE_INT, ["min_range" => (int) $min, "max_range" => (int) $max]);
        if ($value === false) throw new \InvalidArgumentException("The provided integer is too small/large.");
        return $value;
    }

    public static function string($value): string
    {
        if (is_null($value) || !is_string($value) || $value === "") {
            throw new \InvalidArgumentException("The provided value is not a valid string.");
        }
        return $value;
    }
}
