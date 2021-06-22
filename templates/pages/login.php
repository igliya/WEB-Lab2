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
    <title>Авторизация</title>
    <link rel="icon" type="image/png" href="<?php echo View::includeIcon() ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href=<?php echo View::includeStyle(); ?>>
</head>
<body class="auth">

<?php View::getPartByName('navbar'); ?>

<div class="container">
    <div class="title">Вход</div>
    <div class="content">
        <form action="/auth/login" method="post" accept-charset="utf-8">
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
                                   placeholder="example@mail.ru" required>
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
                                   placeholder="******" required>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="button">
                <input type="submit" class="button" value="Войти"></input>
            </div>
        </form>
    </div>
</div>

</body>
</html>