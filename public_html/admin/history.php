<?php

use App\App;

require '../../bootloader.php';

if (!App::$session->getUser()) {
    header("Location: /login.php");
    exit();
}

$nav = nav();
$rows = App::$db->getRowsWhere('history', ['user' => $_SESSION['username']]);
foreach ($rows as &$row) {
    unset($row['user']);
}
$table = [
    'headers' => [
        'Date',
        'Action',
        'Gems amount'
    ],
    'rows' => $rows
]

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/media/style.css">
    <title>History</title>
</head>
<body>
<main>

    <?php require ROOT . '/app/templates/nav.tpl.php'; ?>

    <article class="wrapper">
        <h1 class="header header--main">History</h1>
        <section class="grid-container">

            <?php require ROOT . '/core/templates/table.tpl.php'; ?>

        </section>
    </article>
</main>
</body>
</html>
