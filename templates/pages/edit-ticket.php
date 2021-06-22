<?php

use App\Repository\AppUserRepository;
use App\Services\View;
use Dotenv\Dotenv;

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Редактирование выбранного тикета</title>
    <link rel="icon" type="image/png" href="<?php echo View::includeIcon() ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href=<?php echo View::includeStyle(); ?>>
</head>
<body class="bg-color">

<?php
View::getPartByName('navbar');
$userRepo = new AppUserRepository();
try {
    $editors = $userRepo->findBy(['role' => 'EDITOR']);
} catch (Exception $exception) {
    $editors = [];
}
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

$statuses = explode(';', $_ENV['STATUS_ARRAY']);
?>


<div class="row main__row d-flex justify-content-center">
    <main class="col-md-10 ml-sm-auto col-lg-10 px-4 shadow bg-white rounded" role="main">
        <div class="topic-form-container">
            <div class="title text-center">Редактирование тикета</div>
            <div class="content">
                <form method="post" action="/ticket-update">
                    <div class="new-topic-details">
                        <div class="input-box">
                            <span class="details">Ответственный сотрудник</span>
                            <select name="support_id" class="form-select">
                                <?php foreach ($editors as $editor) : ?>
                                    <option value="<?php echo $editor->userId ?>"><?php echo $editor->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="input-box">
                            <span class="details">Статус</span>
                            <select name="status" class="form-select" aria-label="Default select example">
                                <?php foreach ($statuses as $status) : ?>
                                    <option value="<?php echo $status ?>"><?php echo $status ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="is_closed">
                            <label class="checkbox-label" for="is-closed">Закрыть тикет</label>
                        </div>
                    </div>
                    <div class="button">
                        <input type="submit" value="Сохранить">
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
</body>