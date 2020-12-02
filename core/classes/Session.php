<?php


namespace Core;


use App\App;

class Session
{
    private ?array $user = null;

    public function __construct()
    {
        session_start();
        $this->loginFromCookie();
    }

    public function loginFromCookie(): bool
    {
        if ($_SESSION) {
            $this->login($_SESSION['username'], $_SESSION['password']);
        }

        return false;
    }

    /**
     * Checks if there is such username and password in the database.
     *
     * If there is such user and password is the same as in database returns true
     * and sets $_SESSION and $user.
     * If username or password are not in the database, returns false.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login(string $username, string $password): bool
    {
        $user = App::$db->getRowWhere('users', [
            'username' => $username,
            'password' => $password
        ]);

        if ($user) {
            $_SESSION = [
                'username' => $username,
                'password' => $password
            ];

            $this->user = $user;

            return true;
        }

        $this->user = null;

        return false;
    }

    /**
     * Get $user variable.
     *
     * @return array|null
     */
    public function getUser(): ?array
    {
        return $this->user;
    }

    /**
     * Ends session.
     * Makes session data clean and destroys server side cookie
     * If it is written redirects to location
     *
     * @param string|null $redirect
     */
    public function logout(?string $redirect = null)
    {
        $_SESSION = [];
        session_destroy();

        if ($redirect) {
            header("Location: $redirect");
        }
    }
}