<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="utf-8">
    <!-- <base href="/"> -->

    <title>VotUsh</title>
    <meta name="description" content="">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <script src="https://code.jquery.com/jquery-3.4.1.js"
            integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
            crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
          integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

    <link rel="stylesheet" href="/css/main.min.css">


</head>

<body>

<header>
    <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
            <a href="/" class="navbar-brand d-flex align-items-center">
                <strong>Шутов Руслан</strong>
            </a>
            <?php if($_SESSION['username'] == ADMIN_USERNAME) { ?>
                <a class="btn btn-sm btn-outline-secondary" href="admin.php?action=logout">Выйти из <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b></a>
            <?php } else{ ?>
                <a class="btn btn-sm btn-outline-secondary" href="admin.php">Войти</a>
            <?php } ?>
        </div>
    </div>
</header>