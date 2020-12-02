<?php

use App\App;

require '../../bootloader.php';

if (!App::$session->getUser()) {
    header("Location: /login.php");
    exit();
}

$nav = nav();

$form = [
    'attr' => [
        'class' => 'form'
    ],
    'fields' => [
        'money' => [
            'label' => 'Price in $',
            'type' => 'number',
            'validators' => [
                'validate_field_not_empty',
                'validate_field_range' => [
                    'min' => 5,
                    'max' => 1000
                ]
            ],
            'extra' => [
                'attr' => [
                    'placeholder' => '350$',
                    'class' => 'input-field'
                ]
            ]
        ]
    ],
    'buttons' => [
        'add' => [
            'title' => 'Add gems',
            'type' => 'submit',
            'extra' => [
                'attr' => [
                    'class' => 'btn'
                ]
            ]
        ],
        'clear' => [
            'title' => 'Clear',
            'type' => 'reset',
            'extra' => [
                'attr' => [
                    'class' => 'btn'
                ]
            ]
        ]
    ]
];


$clean_inputs = get_clean_input($form);

if ($clean_inputs) {
    $success = validate_form($form, $clean_inputs);

    if ($success) {
        $user_key = is_logged_user();
        $row = App::$db->getRowWhere('users', ['username' => $_SESSION['username']]);

        $gems_add = $clean_inputs['money'] * 100;
        $row['gems'] += $gems_add;

        App::$db->updateRow('users', $user_key, $row);

        App::$db->insertRow('history', [
            'user' => $row['username'],
            'date' => date('Y-m-d', time()),
            'action' => 'add',
            'sum' => $gems_add
        ]);

        $p = 'Sveikinu pridejus preke';
    } else {
        $p = 'Uzpildyki visus laukus';
    }
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
    <title>Buy</title>
</head>
<body>
<main>

    <?php require ROOT . '/app/templates/nav.tpl.php'; ?>

    <article class="wrapper">
        <h1 class="header header--main">Add Gems</h1>

        <?php require ROOT . '/core/templates/form.tpl.php'; ?>

        <?php if (isset ($p)): ?>
            <p><?php print $p; ?></p>
        <?php endif; ?>

    </article>
</main>
</body>
</html>

