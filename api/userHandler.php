<?php
    require("dbConn.php");
    $db = db_connection();
    session_start();
    $pageRequested = $_POST["pageRequested"];
    $username = $_SESSION["userSession"];
    if ($pageRequested == "user-drop") {
            echo json_encode(array('email' => $_SESSION["userData"][0], 'username' => $_SESSION["userData"][1], 'propic' => $_SESSION["userData"][2]));
        }