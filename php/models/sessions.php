<?php
    /**
     * File: sessions.php
     * Author: Lucas Pallud
     * Date: 10.05.2017
     * Description: manage the login, logout and roles for the web site.
     * Version: 1.0
     */


    //if the session is not start yet
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    /**
     * put a user in the session
     * @param table $user a table with all the informations of the user except the password
     */
    function sessionSetUser($user) {
        $_SESSION['user'] = $user;
    }

    /**
     * logout the user (this function is gave by PHP)
     */
    function sessionForgetUser() {
        // Unset all of the session variables.
        $_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

// Finally, destroy the session.
        session_destroy();
    }

    /**
     * Define the role of the user
     * @return string the role (admin or anonymous)
     */
    function getRole() {
        if (!isset($_SESSION['user'])) {
            return "anonymous";
        } else {
            return 'admin';
        }
    }

    /**
     * Define if the user is admin
     * @return bool TRUE|FALSE if the user is admin
     */
    function isAdmin() {
        return (isset($_SESSION['user']));
    }