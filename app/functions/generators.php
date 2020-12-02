<?php

use App\App;


/**
 *
 * Returns an array for navigation,
 * with name and links
 *
 * @return string[]
 */
function nav(): array
{
    $nav = ['/index.php' => 'Home'];

    if (App::$session->getUser()) {
        return $nav + [
            '/admin/history.php' => 'History',
            '/admin/buy.php' => 'Buy Gems',
            '/logout.php' => 'Logout',
        ];
    } else {
        return $nav + [
            '/register.php' => 'Register',
            '/login.php' => 'Login',
        ];
    }
}