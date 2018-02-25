<?php
/**
 * Created by PhpStorm.
 * User: Carlos
 * Date: 2/24/2018
 * Time: 11:16 PM
 */

session_start();


$_SESSION = array();


if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header("Location:logIn.php");
exit;