<?php

    require("dbConn.php");

    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

    $db = db_connection();
    $sql = "SELECT userPropic, userMail userUsername, userPassword FROM Users WHERE userUsername = ?";
    $res = $db->prepare($sql);
    $res->execute(array($username));
    $userData = $res->fetch(PDO::FETCH_ASSOC);

    if(password_verify($password, $userData['userPassword'])){
        if (password_needs_rehash($userData['userPassword'], PASSWORD_DEFAULT, ["cost" => costCalculator()])){
           $newHash = password_hash($password, PASSWORD_DEFAULT, ["cost" => costCalculator()]);
           $sql = "UPDATE Users SET userPassword = ? WHERE userUsername = ?";
           $res = $db->prepare($sql);
           $res->execute(array($newHash, $username));
        }
        session_start();
        $_SESSION["userSession"] = $username;
        $_SESSION["userData"] = array($userData["userMail"], $userData["userUsername"], $userData["userPropic"]);
        echo json_encode(array('success' => $_SESSION["userSession"]));
    } else {
        echo json_encode(array('success' => false));
    }
    $userData = null;
    $db = null;