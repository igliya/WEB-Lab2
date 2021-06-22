<?php

namespace App\Validator;

class FileValidator extends BaseValidator
{
    private static array $types;

    public static function validate(array $data): bool
    {
        self::$errors = [];
        self::$types = require_once dirname(__DIR__, 1) . "/Helper/files_extension_constants.php";
        foreach ($data as $file) {
            self::validateType($file['tmp_name'], $file['extension']);
        }
        if (count(self::$errors) == 0) {
            return true;
        }
        return false;
    }

    private static function validateType(string $path, string $extension)
    {
        $fileType = mime_content_type($path);
        if (self::$types[$extension] != $fileType) {
            self::$errors['file'] = "Данный формат файла не поддерживается (" . $extension . ")";
        }
    }

    public static function getErrors(): array
    {
        return self::$errors;
    }

    protected static function checkContainsFields(array $data): void
    {
    }
}
