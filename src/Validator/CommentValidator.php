<?php

namespace App\Validator;

class CommentValidator extends BaseValidator
{

    public static function validate(array $data): bool
    {
        self::$errors = [];
        self::checkContainsFields($data);
        if (count(self::$errors) != 0) {
            return false;
        }
        self::validateText($data['text']);
        if (count(self::$errors) != 0) {
            return false;
        }
        return true;
    }

    protected static function checkContainsFields(array $data): void
    {
        if (!isset($data['text'])) {
            self::$errors['text'] = "Данное поле обязательно!";
        }
    }

    private static function validateText($text)
    {
        if (empty($text)) {
            self::$errors['text'] = "Укажите текст сообщения!";
        }
    }

    public static function getErrors(): array
    {
        return self::$errors;
    }
}
