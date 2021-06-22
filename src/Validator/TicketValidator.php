<?php

namespace App\Validator;

class TicketValidator extends BaseValidator
{
    public static function validate(array $data): bool
    {
        self::$errors = [];
        self::checkContainsFields($data);
        if (count(self::$errors) != 0) {
            return false;
        }
        self::validateTheme($data['theme']);
        self::validateContent($data['content']);
        if (count(self::$errors) != 0) {
            return false;
        }
        return true;
    }

    protected static function checkContainsFields(array $data): void
    {
        if (!isset($data['theme'])) {
            self::$errors['theme'] = "Данное поле обязательно!";
        }
        if (!isset($data['content'])) {
            self::$errors['content'] = "Данное поле обязательно!";
        }
    }

    private static function validateTheme(string $theme)
    {
        if (empty($theme)) {
            self::$errors['theme'] = "Укажите название тикета";
            return;
        }
        if (strlen($theme) >= 100) {
            self::$errors['theme'] = "Максимальный размер названия 100 символов!";
        }
    }

    private static function validateContent(string $content)
    {
        if (empty($content)) {
            self::$errors['content'] = "Укажите описание тикета";
            return;
        }
        if (strlen($content) >= 512) {
            self::$errors['content'] = "Максимальный размер названия 512 символов!";
        }
    }

    public static function getErrors(): array
    {
        return self::$errors;
    }
}
