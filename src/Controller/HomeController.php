<?php

namespace App\Controller;

use App\Services\Router;

class HomeController
{
    public function loadHomePage()
    {
        unset($_SESSION['errors']);
        unset($_SESSION['user_data']);
        Router::redirect('/home');
    }
}
