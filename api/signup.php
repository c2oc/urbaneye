<?php

    require("dbConn.php");

    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $package = array(false,false);
	
    $db = db_connection ();
    $usernameQuery = "SELECT userUsername FROM Users WHERE userUsername = ?";
    $res = $db->prepare($usernameQuery);
    $res->execute(array($username));
    if ($res->rowCount() >= 1){
        $package[0] = true;
    }
    $emailQuery = "SELECT userMail FROM Users WHERE userMail = ?";
    $res = $db->prepare($emailQuery);
    $res->execute(array($email));
    if ($res->rowCount() >= 1){
       $package[1] = true;
    }
    if (!$package[0] && !$package[1]){
        $password = password_hash($password, PASSWORD_DEFAULT, ["cost" => costCalculator()]);
        $sql = 'INSERT INTO Users (userUsername, userMail, userPassword) VALUES (?,?,?)';
        $res = $db->prepare($sql);
        $res->execute(array($username, $email, $password));
        echo json_encode(array('registered' => true, 'e_isTaken' => $package[0], 'u_isTaken' => $package[1]));
    } else {
    	echo json_encode(array('registered' => false, 'u_isTaken' => $package[0], 'e_isTaken' => $package[1]));
    }
    $db = null;
