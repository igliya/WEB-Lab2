<?php

namespace App\Services;

class View
{
    public static function getPartByName(string $componentName)
    {
        require_once "templates/components/" . $componentName . ".php";
    }

    public static function includeStyle(): string
    {
        return 'http://' . $_SERVER['HTTP_HOST'] . '/assets/style/style.css';
    }

    public static function includeImage(string $name): string
    {
        return "http://" . $_SERVER['HTTP_HOST'] . '/assets/images/' . $name . '.png';
    }

    public static function includeIcon()
    {
        return "http://" . $_SERVER['HTTP_HOST'] . '/assets/icons/favicon.png';
    }

    public static function includeScript(string $name): string
    {
        return "http://" . $_SERVER['HTTP_HOST'] . '/assets/js/' . $name . '.js';
    }

    public static function checkIfLogin(string $redirectPage)
    {
        if (isset($_SESSION['user'])) {
            Router::redirect($redirectPage);
            die();
        }
    }

    public static function checkIfNotLogin(string $redirectPage)
    {
        if (!isset($_SESSION['user'])) {
            Router::redirect($redirectPage);
            die();
        }
    }
}
