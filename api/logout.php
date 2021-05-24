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
    $u = $_SESSION["user"];
    $cart = $_SESSION["cart"][$u];
  // Unset the session variables.
    session_unset();
    session_start();
    $_SESSION["cart"][$u] = $cart;
    $u = null;
    $cart = null;
echo json_encode(array('logout' =>1));
  