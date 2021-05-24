<?php
	session_start();
    /* 
    Urushibara Luka.

    A stunning example of feminine charm and grace.

    Lips delicate like cherry blossoms in bloom.

    The essence of Japanese beauty.

    The chief priest's son.

    That's right, "son"
    */
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();
    echo json_encode(array('logout' =>1));
  