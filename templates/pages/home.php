<?php

use App\Repository\TicketRepository;
use App\Services\View;

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главная страница</title>
    <link rel="icon" type="image/png" href="<?php echo View::includeIcon() ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href=<?php echo View::includeStyle(); ?>>
</head>
<body class="bg-color">

<?php
View::getPartByName('navbar');
$ticketRepos = new TicketRepository();
$ticketStats = $ticketRepos->getDetailInformation();
try {
    if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'USER') {
        $tickets = $ticketRepos->findBy(
            ['user_id' => $_SESSION['user']['id'], 'support_id' => $_SESSION['user']['id']],
            ['ticket_date', 'is_closed'],
            false
        );
    } elseif (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'EDITOR') {
        $tickets = $ticketRepos->findBy(
            [],
            ['ticket_date', 'is_closed'],
            false
        );
    }
} catch (Exception $exception) {
    $tickets = [];
}
?>

<div class="row main__row d-flex justify-content-center">
    <?php if (!isset($_SESSION['user'])) : ?>
        <div class="text-center">
            <h1>
                <p>Приветствуем на данном сайте!</p>
                <p>На данный момент статистика сайта следующая:</p>
                <p>Всего тикетов: <?php echo $ticketStats['total_ticket_cnt'] ?></p>
                <p>Открытых тикетов: <?php echo $ticketStats['open_ticket_cnt'] ?></p>
                <p>Закрытых тикетов: <?php echo $ticketStats['closed_ticket_cnt'] ?></p>
                <div>
                    Для работы с сайтом необходимо пройти
                    <span>
                    <a href="/login"> авторизацию</a>
                </span>
                </div>
            </h1>
        </div>
    <?php else : ?>
        <main class="col-md-10 ml-sm-auto col-lg-10 px-4 shadow bg-white rounded" role="main">
            <!-- Home start -->
            <div class="home">
                <div class="wrapper">
                    <div class="home__info">
                        <h1 class="section__link section__title text-center">
                            Список тикетов: <?php echo $_SESSION['user']['name'] ?>
                        </h1>
                    </div>
                </div>
                <table class="table table-hover text-center">
                    <thead class="topic-table__header">
                    <tr>
                        <th scope="col">Тема</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Автор</th>
                        <th scope="col">Назначенный сотрудник</th>
                        <th scope="col">Дата создания</th>
                    </tr>
                    </thead>
                    <tbody class="topic-table__content">
                    <?php foreach ($tickets as $ticket) : ?>
                        <tr>
                            <td>
                                <a href=<?php echo $ticket->getTicketUrl() ?>>
                                    <?php echo $ticket->theme ?>
                                </a>
                            </td>
                            <?php if ($ticket->isClosed) : ?>
                                <td class="text-success"><?php echo $ticket->status ?></td>
                            <?php else : ?>
                                <td class="text-danger"><?php echo $ticket->status ?></td>
                            <?php endif; ?>
                            <td><?php echo $ticket->getUserName() ?></td>
                            <td><?php echo $ticket->getSupportName() ?></td>
                            <td><?php echo $ticket->ticketDate ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Home end -->
        </main>
    <?php endif; ?>
</div>

</body>
</html>