<?php

use App\Services\View;

?>
<header class="header">
    <div class="wrapper">
        <div class="header__wrapper">
            <div class="header__logo">
                <a href="/home" class="header__logo-link">
                    <img src="<?php echo View::includeImage('logo') ?>" alt="Logo" class="header__logo-pic">
                </a>
            </div>
            <nav class="header__nav">
                <ul class="header__list">
                    <?php if (!isset($_SESSION['user'])) : ?>
                        <li class="header__item">
                            <a href="/login" class="header__link">Вход</a>
                        </li>
                        <li class="header__item">
                            <a href="/register" class="header__link">Регистрация</a>
                        </li>
                    <?php else : ?>
                        <li class="header__item">
                            <form action="/create-ticket" method="post" class="header__link">
                                <button class="header__link">
                                    Создать топик
                                </button>
                            </form>
                        </li>
                        <li class="header__item">
                            <div class="dropdown">
                                <button class="dropdown-button"><?php echo $_SESSION['user']['name'] ?></button>
                                <div class="dropdown-content">
                                    <form action="/auth/logout" method="post">
                                        <button type="submit" class="w-100 text-center">Выход</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    <?php endif ?>
                </ul>
            </nav>
        </div>
    </div>
</header>
<!-- Header end -->