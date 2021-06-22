<?php

namespace App\Services;

class Router
{
    private static array $routeList = [];

    public static function page(string $uri, string $pageName)
    {
        self::$routeList[] = [
            "uri" => $uri,
            "page" => $pageName
        ];
    }

    public static function action(
        string $uri,
        string $controller,
        string $classMethod,
        string $method,
        bool $formData = false,
        bool $files = false
    ) {
        self::$routeList[] = [
            "uri" => $uri,
            "class" => $controller,
            "class_method" => $classMethod,
            "method" => $method,
            "form_data" => $formData,
            "files" => $files
        ];
    }

    public static function enable()
    {
        $query = rtrim($_GET['q'], '/');
        $queryArray = self::convertQuery($query);
        foreach (self::$routeList as $route) {
            if ($route['uri'] == $queryArray['query']) {
                self::redirectMethod($route);
            } elseif (
                str_starts_with($route['uri'], $queryArray['query']) &&
                $queryArray['query'] != '/' && isset($queryArray['param'])
            ) {
                self::redirectMethod($route, $queryArray['param']);
            }
        }
        self::errorPage(404);
    }

    private static function convertQuery(string $srcQuery): array
    {
        $navigationArray = array();
        if (empty($srcQuery)) {
            $query = '/';
        } else {
            $params = explode('/', ltrim($srcQuery, '/'));
            if ($params[0] == 'auth' || $params[1] == 'create') {
                $query = '/' . $params[0] . '/' . $params[1];
            } else {
                $query = '/' . $params[0];
                $param = $params[1] ?? "";
            }
        }
        $navigationArray['query'] = $query;
        $navigationArray['param'] = $param;
        return $navigationArray;
    }

    private static function redirectMethod(array $route, string $param = "")
    {
        $routeMethod = $route['method'];
        if ($routeMethod == "POST" && $_SERVER['REQUEST_METHOD'] == "POST") {
            self::redirectToPostMethod($route);
        } elseif ($routeMethod == "GET" && $_SERVER['REQUEST_METHOD'] == "GET") {
            self::redirectToGetMethod($route, $param);
        } else {
            self::redirectToPage($route);
        }
    }

    private static function redirectToPostMethod(array $route)
    {
        $action = new $route['class']();
        $method = $route['class_method'];
        if ($route['form_data'] && $route['files']) {
            $action->$method($_POST, $_FILES);
        } elseif ($route['form_data'] && !$route['files']) {
            $action->$method($_POST);
        } else {
            $action->$method();
        }
        die();
    }

    private static function redirectToGetMethod(array $route, string $param = "")
    {
        $action = new $route['class']();
        $method = $route['class_method'];
        if (!isset($param)) {
            $action->$method();
        } else {
            $action->$method($param);
        }
        die();
    }

    private static function redirectToPage(array $route)
    {
        $filePath = "templates/pages/" . $route['page'] . ".php";
        self::openFile($filePath);
    }

    private static function openFile(string $path)
    {
        if (file_exists($path)) {
            require_once $path;
            die();
        }
    }

    public static function errorPage(int $errorCode)
    {
        require "templates/errors/" . $errorCode . ".php";
    }

    public static function redirect(string $uri)
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $uri);
    }
}
