<?php

use App\Repository\CommentRepository;
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
    <title>Детальная страница тикета</title>
    <link rel="icon" type="image/png" href="<?php echo View::includeIcon() ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href=<?php echo View::includeStyle(); ?>>
</head>
<body class="bg-color">

<?php
View::getPartByName('navbar');
$ticketRepo = new TicketRepository();
$commentRepo = new CommentRepository();
$detailTicket = $ticketRepo->findOneBy(['ticket_id' => $_SESSION['detail_ticket']]);
try {
    $comments = $commentRepo->findBy(['ticket_id' => $_SESSION['detail_ticket']], ['comment_date']);
} catch (Exception $exception) {
    $comments = [];
}
?>

<div class="row main__row d-flex justify-content-center">
    <main class="col-md-10 ml-sm-auto col-lg-10 px-4 shadow bg-white rounded" role="main">
        <div class="topic-title text-left">
            <?php echo $detailTicket->theme ?>
            <?php if ($detailTicket->isClosed) : ?>
                <span class="text-success"><?php echo $detailTicket->status ?></span>
            <?php else : ?>
                <span class="text-danger"><?php echo $detailTicket->status ?></span>
            <?php endif; ?>
        </div>

        <div class="d-flex flex-row">
            <div class="topic-text rounded shadow">
                <div>
                    <span class="topic-author"><?php echo $detailTicket->getUserName() ?></span>
                    <span class="topic-date"><?php echo $detailTicket->ticketDate ?></span>
                </div>
                <span class="topic-text-block"><?php echo $detailTicket->content ?></span>
            </div>
        </div>

        <div class="text-center">
            <?php if ($detailTicket->file != null) : ?>
                <a class="ticket-file mt-3 ml-3" download="<?php echo $detailTicket->file ?>"
                   href=<?php echo $detailTicket->getFileUrl(); ?>>Прикреплённый файл</a>
            <?php endif ?>
            <?php if (isset($detailTicket->supportId)) : ?>
                <div class="ticket-support text-left mt-3 ml-2">Назначенный
                    сотрудник: <?php echo $detailTicket->getSupportName() ?></div>
            <?php endif ?>
            <?php if ($_SESSION['user']['role'] == "EDITOR") : ?>
                <div class="ticket-support text-left mt-3 ml-2">
                    <a href="/ticket-edit/<?php echo $_SESSION['detail_ticket'] ?>" class="btn btn-primary text-white">
                        Редактировать
                    </a>
                </div>
            <?php endif ?>
        </div>

        <hr class="topic-divider">
        <div class="d-flex flex-row">
            <div class="comment-wrapper">
                <form name="comment" method="post" action="/ticket-comment/<?php echo $_SESSION['detail_ticket'] ?>">
                    <div class="new-comment-details">
                        <div class="input-box">
                            <span class="details">Сообщение</span>
                            <textarea id="text" name="text" required></textarea>
                        </div>
                    </div>
                    <div class="button">
                        <input type="submit" value="Добавить сообщение">
                    </div>
                    <form/>
            </div>
        </div>
        <!-- Topic comments -->
        <?php foreach ($comments as $comment) : ?>
            <?php if ($comment->userId == $_SESSION['user']['id']) : ?>
                <div class="d-flex flex-row-reverse">
                    <div class="topic-text-comment rounded shadow">
                        <div>
                            <span class="topic-author">
                                <?php echo $comment->getUserName() ?>
                            </span>
                            <span class="topic-date"><?php echo $comment->commentDate ?></span>
                        </div>
                        <span class="topic-text-block"><?php echo $comment->text ?></span>
                    </div>
                </div>
            <?php else : ?>
                <div class="d-flex flex-row">
                    <div class="topic-text-comment rounded shadow">
                        <div>
                        <span class="topic-author">
                            <?php echo $comment->getUserName() ?>
                        </span>
                            <span class="topic-date"><?php echo $comment->commentDate ?></span>
                        </div>
                        <span class="topic-text-block"><?php echo $comment->text ?></span>
                    </div>
                </div>
            <?php endif ?>
        <?php endforeach; ?>
    </main>
</div>
</body>