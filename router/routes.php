<?php

use App\Controller\AuthorizationController;
use App\Controller\CommentController;
use App\Controller\HomeController;
use App\Controller\TicketController;
use App\Services\Router;

// Все доступные страницы для перехода
Router::page("/login", "login");
Router::page("/register", "register");
Router::page("/home", "home");
Router::page('/create-ticket', "add_ticket");
Router::page('/php-info', 'php-info');
Router::page('/detail-ticket', 'detail_ticket');
Router::page('/edit-ticket', 'edit-ticket');

// Все доступные методы для обработки
Router::action('/', HomeController::class, "loadHomePage", "GET");
Router::action("/auth/register", AuthorizationController::class, "register", "POST", true);
Router::action("/auth/login", AuthorizationController::class, "login", "POST", true);
Router::action("/auth/logout", AuthorizationController::class, "logout", "POST", true);
Router::action('/ticket/create', TicketController::class, 'create', "POST", true, true);
Router::action('/ticket-update', TicketController::class, 'update', 'POST', true);
Router::action('/ticket/{id}', TicketController::class, 'open', "GET");
Router::action('/ticket-edit/{id}', TicketController::class, 'edit', "GET");
Router::action('/ticket-comment/{id}', CommentController::class, 'create', 'POST', true);

// Подключаем все вышеперечисленные методы и страницы
Router::enable();
