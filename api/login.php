<?php

    require("dbconn.php");
    require("utilities.php");

    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

    $db = dbconn();
    $sql = "SELECT userID, userName, userPassword FROM users WHERE userName = '$username'";
    $res = $db->prepare($sql);
    $res->execute();
    $userData = $res->fetch(PDO::FETCH_ASSOC);

    if(password_verify($password, $userData[userPassword])){
        if (password_needs_rehash($userData[userPassword], PASSWORD_DEFAULT, ["cost" => costCalculator()])){
           $newHash = password_hash($password, PASSWORD_DEFAULT, ["cost" => costCalculator()]);
           $sql = "UPDATE users SET userPassword = ' $newHash ' WHERE userID = '$userData[userID]'";
           $res = $db->prepare($sql);
           $res->execute();
        }
        $userData = null;
        session_start();
        $_SESSION["userSession"] = $username;
        echo json_encode(array('success' => $_SESSION["userSession"]));
    } else {
        echo json_encode(array('success' => false));
    }
    $db = null;