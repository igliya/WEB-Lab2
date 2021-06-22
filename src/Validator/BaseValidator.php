<?php

namespace App\Validator;

abstract class BaseValidator
{
    protected static array $errors;

    abstract public static function validate(array $data): bool;

    abstract public static function getErrors(): array;

    abstract protected static function checkContainsFields(array $data): void;
}
