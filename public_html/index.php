<?php

require '../bootloader.php';

use App\App;

$nav = nav();

if (App::$session->getUser()) {
    $h3 = "Sveiki sugrize {$_SESSION['username']}";
    $play_value = 'play';
} else {
    $h3 = 'Jus neprisijunges';
    $play_value = 'no';
}

if (isset($_POST['id']) && $_POST['id'] == 'no') {
    header("Location: /login.php");
    exit();
}

if (isset($_POST['id']) && $_POST['id'] == 'play') {

    $row = App::$session->getUser();

    if ($row['gems'] >= 100) {

        $wild = 'https://cdn.imgbin.com/12/13/3/imgbin-slot-machine-online-casino-casino-game-progressive-jackpot-symbol-Tywnp5DcTTz1zJC1Jfz5TqyRM.jpg';
        $cherry = 'https://cdn3.iconfinder.com/data/icons/casino/256/Cherries-512.png';
        $star = 'https://cdn1.iconfinder.com/data/icons/macster/70/.svg-17-512.png';
        $bell = 'https://cdn3.iconfinder.com/data/icons/casino/256/Bell-512.png';
        $shell = 'https://cdn0.iconfinder.com/data/icons/summer-314/100/Summer_999-26-512.png';
        $seven = 'https://cdn3.iconfinder.com/data/icons/casino-flat-icons/512/casino_slot_poker_777-512.png';
        $bar = 'https://cdn1.iconfinder.com/data/icons/leto-travel-vacation/64/__alcohol_bar_coctail-512.png';
        $queen = 'https://cdn1.iconfinder.com/data/icons/photo-stickers-hats/128/hat_10-512.png';
        $king = 'https://cdn1.iconfinder.com/data/icons/unigrid-phantom-holidays/60/015_017_crown_corona_cesar_tsar_king_leader_gold_silver_jewelery_diamond_adamant-512.png';
        $jack = 'https://cdn4.iconfinder.com/data/icons/playing-card-thinline/32/ico-jack-of-hearts-512.png';

        $reel = [$wild, $star, $bell, $shell, $seven, $cherry, $bar, $king, $queen, $jack];

        $count = count($reel) - 1;
        $spinner = [$reel[rand(0, $count)], $reel[rand(0, $count)], $reel[rand(0, $count)]];

        $user_key = is_logged_user();
        $row['gems'] -= 100;

        App::$db->insertRow('history', [
            'user' => $row['username'],
            'date' => date('Y-m-d', time()),
            'action' => 'play',
            'sum' => 100
        ]);
        App::$db->updateRow('users', $user_key, $row);

        if ($spinner[0] == $spinner[1] || $spinner[0] == $spinner[2] || $spinner[1] == $spinner[2]) {
            $row = App::$session->getUser();
            $row['gems'] += 500;
            App::$db->updateRow('users', $user_key, $row);

            App::$db->insertRow('history', [
                'user' => $row['username'],
                'date' => date('Y-m-d', time()),
                'action' => 'won',
                'sum' => 500
            ]);

            $message = 'success 500';
        } elseif ($spinner[0] == $spinner[1] && $spinner[0] == $spinner[2]) {
            $row = App::$session->getUser();
            $row['gems'] += 1000;
            App::$db->updateRow('users', $user_key, $row);

            App::$db->insertRow('history', [
                'user' => $row['username'],
                'date' => date('Y-m-d', time()),
                'action' => 'won',
                'sum' => 1000
            ]);

            $message = 'success 1000';
        } else {
            $message = 'lose 100';
        }
    } else {
        $question = 'https://cdn2.iconfinder.com/data/icons/lined-slot-machine/48/a-11-512.png';
        $spinner = [$question, $question, $question];
        $message = 'not enough gems, go buy';
    }

} else {
    $question = 'https://cdn2.iconfinder.com/data/icons/lined-slot-machine/48/a-11-512.png';
    $spinner = [$question, $question, $question];
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/media/style.css">
    <title>Play</title>
</head>
<body>
<main>

    <?php require ROOT . '/app/templates/nav.tpl.php'; ?>

    <article class="wrapper">
        <h1 class="header header--main">Welcome to Game of Games</h1>
        <h3 class="header"><?php print $h3; ?></h3>
        <section class="grid-container">

            <?php foreach ($spinner as $spin) : ?>

                <div class="grid-item">
                    <img class="product-img" src="<?php print $spin; ?>" alt="">
                </div>

            <?php endforeach; ?>

        </section>

        <form method="POST">
            <input type="hidden" name="id" value="<?php print $play_value; ?>">
            <button type="submit">Play</button>
        </form>

        <?php if (isset($_POST['id']) && $_POST['id'] == 'play'): ?>

            <p><?php print $message; ?></p>

        <?php endif; ?>

    </article>
</main>
</body>
</html>