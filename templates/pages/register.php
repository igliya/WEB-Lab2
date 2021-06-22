<?php

use App\Services\View;

View::checkIfLogin('/');
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Регистрация</title>
    <link rel="icon" type="image/png" href="<?php echo View::includeIcon() ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href=<?php echo View::includeStyle(); ?>>
</head>
<body class="auth">

<?php
View::getPartByName('navbar');
?>

<div class="container">
    <div class="title">Регистрация</div>
    <div class="content">
        <form method="post" action="/auth/register">
            <div class="user-details">
                <div class="input-box">
                    <?php if (isset($_SESSION['errors']['email'])) : ?>
                        <div class="form-group">
                            <label class="text-danger" for="email">Электронная почта</label>
                            <input type="email" class="form-control is-invalid" name="email" id="email"
                                   placeholder="example@mail.ru"
                                   value="<?php echo $_SESSION['user_data']['email'] ?>" required>
                            <span class="text-danger"><?php echo $_SESSION['errors']['email'] ?></span>
                        </div>
                    <?php elseif (isset($_SESSION['user_data']['email'])) : ?>
                        <div class="form-group">
                            <label for="email">Электронная почта</label>
                            <input type="email" class="form-control" name="email" id="email"
                                   placeholder="example@mail.ru"
                                   value="<?php echo $_SESSION['user_data']['email'] ?>" required>
                        </div>
                    <?php else : ?>
                        <div class="form-group">
                            <label for="email">Электронная почта</label>
                            <input type="email" class="form-control" name="email" id="email"
                                   placeholder="example@mail.ru"
                                   required>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="input-box">
                    <?php if (isset($_SESSION['errors']['name'])) : ?>
                        <div class="form-group">
                            <label class="text-danger" for="name">Имя</label>
                            <input type="text" class="form-control is-invalid" name="name" id="last_name"
                                   placeholder="Иванов Иван Иванович"
                                   value="<?php echo $_SESSION['user_data']['name'] ?>" required>
                            <span class="text-danger"><?php echo $_SESSION['errors']['name'] ?></span>
                        </div>
                    <?php elseif (isset($_SESSION['user_data']['name'])) : ?>
                        <div class="form-group">
                            <label for="last_name">Имя</label>
                            <input type="text" class="form-control" name="name" id="last_name"
                                   placeholder="Иванов Иван Иванович"
                                   value="<?php echo $_SESSION['user_data']['name'] ?>" required>
                        </div>
                    <?php else : ?>
                        <div class="form-group">
                            <label for="last_name">Имя</label>
                            <input type="text" class="form-control" name="name" id="last_name"
                                   placeholder="Иванов Иван Иванович" required>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="input-box">
                    <?php if (isset($_SESSION['errors']['password'])) : ?>
                        <div class="form-group">
                            <label class="text-danger" for="password">Пароль</label>
                            <input type="password" class="form-control is-invalid" name="password" id="password"
                                   placeholder="******"
                                   value="<?php echo $_SESSION['user_data']['password'] ?>" required>
                            <span class="text-danger"><?php echo $_SESSION['errors']['password'] ?></span>
                        </div>
                    <?php elseif (isset($_SESSION['user_data']['password'])) : ?>
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input type="password" class="form-control" name="password" id="password"
                                   placeholder="******"
                                   value="<?php echo $_SESSION['user_data']['password'] ?>" required>
                        </div>
                    <?php else : ?>
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input type="password" class="form-control" name="password" id="password"
                                   placeholder="******"
                                   required>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="input-box">
                    <?php if (isset($_SESSION['errors']['password_confirm'])) : ?>
                        <div class="form-group">
                            <label class="text-danger" for="password_confirm">Повторите пароль</label>
                            <input type="password" class="form-control is-invalid" name="password_confirm"
                                   id="password_confirm"
                                   placeholder="******"
                                   value="<?php echo $_SESSION['user_data']['password_confirm'] ?>" required>
                            <span class="text-danger"><?php echo $_SESSION['errors']['password_confirm'] ?></span>
                        </div>
                    <?php elseif (isset($_SESSION['user_data']['password_confirm'])) : ?>
                        <div class="form-group">
                            <label for="password_confirm">Повторите пароль</label>
                            <input type="password" class="form-control" name="password_confirm" id="password_confirm"
                                   placeholder="******"
                                   value="<?php echo $_SESSION['user_data']['password_confirm'] ?>" required>
                        </div>
                    <?php else : ?>
                        <div class="form-group">
                            <label for="password_confirm">Повторите пароль</label>
                            <input type="password" class="form-control" name="password_confirm" id="password_confirm"
                                   placeholder="******"
                                   required>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="checkbox">
                    <input type="checkbox" id="registration_form_agreeTerms" name="personal_data" required>
                    <label class="checkbox-label required" for="registration_form_agreeTerms">Согласие на обработку
                        персональных данных</label>
                </div>
            </div>
            <div class="button">
                <input type="submit" value="Зарегистрироваться">
            </div>
            <form/>
    </div>
</div>