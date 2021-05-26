<?php
    require("dbConn.php");

    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $package = array(0,0);

    $db = db_connection ();
    $usernameQuery = "SELECT userUsername FROM Users WHERE userUsername = ?";
    $res = $db->prepare($usernameQuery);
    $res->execute(array($username));
    if ($res->rowCount() > 0) {
        $package[0] = 1;
    }
    $emailQuery = "SELECT userMail FROM Users WHERE userMail = ?";
    $res = $db->prepare($emailQuery);
    $res->execute(array($email));
    if ($res->rowCount() > 0) {
        $package[1] = 1;
    }
    if (!$package[0] && !$package[1]) {
        $password = password_hash($password, PASSWORD_DEFAULT, ["cost" => costCalculator()]);
        $sql = 'INSERT INTO Users (userUsername, userMail, userPassword) VALUES (?,?,?)';
        $res = $db->prepare($sql);
        $res->execute(array($username, $email, $password));
        if ($res->rowCount() == 1) {
            session_start();
            $_SESSION["userSession"] = $username;
            $_SESSION["userData"] = array($email, $username, "default.jpg");
            echo json_encode(array('registered' => 1, 'e_isTaken' => $package[0], 'u_isTaken' => $package[1]));
        } else {
            echo json_encode(array('registered' => 0, 'e_isTaken' => $package[0], 'u_isTaken' => $package[1]));
        }
    } else {
        echo json_encode(array('registered' => 0, 'u_isTaken' => $package[0], 'e_isTaken' => $package[1]));
    }
    $db = null;