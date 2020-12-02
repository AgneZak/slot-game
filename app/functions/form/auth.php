<?php

use App\App;

/**
 * Function checks if user is logged in
 *
 * @return bool
 */
function is_logged_in(): bool
{
    if ($_SESSION) {
        return (bool) App::$db->getRowWhere('users', [
            'username' => $_SESSION['username'],
            'password' => $_SESSION['password']
        ]);
    }

    return false;
}

/**
 * Ends session.
 * Makes session data clean and destroys server side cookie
 * If it is written redirects to location
 *
 * @param string|null $redirect
 */
function logout(string $redirect = null): void
{
    session_unset();
    session_destroy();
    header("Location: /$redirect");
}

/**
 * Function returns which index the user is written in database
 *
 * @return false|int|string
 */
function is_logged_user()
{
    if ($_SESSION) {

        $data = App::$db->getData();

        foreach ($data['users'] ?? [] as $key => $entry) {
            if ($_SESSION['username'] === $entry['username'] &&
                $_SESSION['password'] === $entry['password']) {

                return $key;
            }
        }
    }

    return false;
}