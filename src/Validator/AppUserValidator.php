<?php

namespace App\Validator;

use App\Repository\AppUserRepository;
use Exception;

class AppUserValidator extends BaseValidator
{
    public static function validate(array $data): bool
    {
        self::$errors = array();
        self::checkContainsFields($data);
        if (count(self::$errors) != 0) {
            return false;
        }
        self::checkExists($data['email']);
        if (count(self::$errors) != 0) {
            return false;
        }
        self::validateUserName($data['name']);
        self::validateEmail($data['email']);
        self::validatePassword($data['password'], $data['password_confirm']);
        $validateResult = self::$errors;
        return count($validateResult) == 0;
    }

    protected static function checkContainsFields(array $data): void
    {
        if (!isset($data['name'])) {
            self::$errors['name'] = "Данное поле обязательно!";
        }
        if (!isset($data['email'])) {
            self::$errors['email'] = "Данное поле обязательно!";
        }
        if (!isset($data['password'])) {
            self::$errors['password'] = "Данное поле обязательно!";
        }
        if (!isset($data['password_confirm'])) {
            self::$errors['password_confirm'] = "Данное поле обязательно!";
        }
    }

    private static function checkExists(string $email)
    {
        $repos = new AppUserRepository();
        try {
            $repos->findOneBy(['email' => $email]);
            self::$errors['email'] = "Данная почта уже используется!";
        } catch (Exception $exception) {
        }
    }

    private static function validateUserName(string $name)
    {
        if (!preg_match('/^[а-яё -]+$/ui', $name) || empty($name)) {
            self::$errors['name'] = "Введите корректное имя";
        }
    }

    private static function validateEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::$errors['email'] = "Введите корректную почту";
        }
    }

    private static function validatePassword(string $password, string $repeatPassword)
    {
        if ($password != $repeatPassword) {
            self::$errors['password_confirm'] = "Пароли не совпадают!";
            return;
        }
        if (preg_match('/[0-9]+/', $password) && !preg_match('/[A-zА-я]+/', $password)) {
            self::$errors['password'] = "Пароль должен состоять не только из цифр!";
        }
    }

    public static function getErrors(): array
    {
        return self::$errors;
    }
}
