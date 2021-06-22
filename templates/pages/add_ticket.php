<?php

use App\Services\View;

View::checkIfNotLogin('/login');
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Создание нового тикета</title>
    <link rel="icon" type="image/png" href="<?php echo View::includeIcon() ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
            integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href=<?php echo View::includeStyle(); ?>>
</head>
<body class="bg-color">

<?php View::getPartByName('navbar'); ?>

<div class="row main__row d-flex justify-content-center">
    <main class="col-md-10 ml-sm-auto col-lg-10 px-4 shadow bg-white rounded" role="main">
        <?php if (isset($_SESSION['errors']['file'])) : ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    var errorMsg = "<?php print($_SESSION['errors']['file']); ?>";
                    console.log(errorMsg);
                    alert(errorMsg);
                });
            </script>
        <?php endif; ?>
        <div class="topic-form-container">
            <div class="title text-center">Создание нового тикета</div>
            <div class="content">
                <form action="ticket/create" method="post" enctype="multipart/form-data">
                    <div class="new-topic-details">
                        <div class="input-box">
                            <span class="details">Тема</span>
                            <?php if (isset($_SESSION['user_data']['theme'])) : ?>
                                <input type="text" id="theme" name="theme" required
                                       value=<?php echo $_SESSION['user_data']['theme']; ?>>
                            <?php else : ?>
                                <input type="text" id="theme" name="theme" required>
                            <?php endif; ?>
                        </div>
                        <div class="input-box">
                            <span class="details">Описание проблемы</span>
                            <?php if (isset($_SESSION['user_data']['content'])) : ?>
                                <textarea class="form-control" name="content" id="content"
                                          required><?php echo $_SESSION['user_data']['content']; ?>
                                    </textarea>
                            <?php else : ?>
                                <textarea class="form-control" name="content" id="content" required></textarea>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label label-details">Файл</label>
                            <input class="form-control" type="file" id="files" name="ticket_file[]">
                        </div>
                    </div>
                    <div class="button">
                        <input type="submit" value="Создать тикет">
                    </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>