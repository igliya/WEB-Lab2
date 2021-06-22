<?php

namespace App\Controller;

use App\Entity\AppUserEntity;
use App\Repository\AppUserRepository;
use App\Services\Router;
use App\Validator\AppUserValidator;
use Exception;

class AuthorizationController
{
    public function register(array $data)
    {
        $valid = AppUserValidator::validate($data);
        if ($valid) {
            $data['role'] = 'USER';
            $user = new AppUserEntity($data);
            $user->setPassword($data['password']);
            if (!$user->save()) {
                Router::errorPage(500);
            }
            unset($_SESSION['errors']);
            unset($_SESSION['user_data']);
            Router::redirect('/login');
        } else {
            $_SESSION['errors'] = AppUserValidator::getErrors();
            $_SESSION['user_data'] = $data;
            Router::redirect('/register');
        }
    }

    public function login(array $data)
    {
        $email = $data['email'];
        $password = $data['password'];
        try {
            $repos = new AppUserRepository();
            $user = $repos->getUser($email, $password);
            $_SESSION['user'] = [
                'id' => $user->userId,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ];
            unset($_SESSION['errors']);
            unset($_SESSION['user_data']);
            Router::redirect('/');
        } catch (Exception $e) {
            $_SESSION['errors'] = [
                'email' => 'Неправильная почта',
                'password' => 'Неправильный пароль'
            ];
            $_SESSION['user_data'] = $data;
            Router::redirect('/login');
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
        Router::redirect('/login');
    }
}
