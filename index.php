<?php
const MAIN = true;
require __DIR__.'/config.php';

auth()->check_redirect();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css">
</head>
<body class="container">
    <?php require __DIR__.'/nav.php' ?>
    <h2>Admin Panel</h2>
    <article>
        <p>
            Welcome, <?= auth()->user()->username ?>
            <a href="/actions/logout.php">Logout</a>
        </p>
    </article>
</body>
</html>
