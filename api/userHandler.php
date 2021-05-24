<?php
    require("dbconn.php");
    $db = dbconn();
    session_start();
    $pageRequested = $_POST["pageRequested"];
    $username = $_SESSION["userSession"];
    if ($pageRequested == "user-drop") {
        if (!isset($_SESSION["userData"])) {
            $sql = "
                SELECT userMail, userName, userPropic FROM users WHERE userName = ?
            ";
            $res = $db->prepare($sql);
            $res->execute(array($username));
            $userData = $res->fetch(PDO::FETCH_ASSOC);
            $_SESSION["userData"] = array($userData["userMail"], $userData["userName"], $userData["userPropic"]);
            echo json_encode(array('email' => $userData["userMail"], 'username' => $userData["userName"], 'propic' => $userData["userPropic"]));
        } else {
            echo json_encode(array('email' => $_SESSION["userData"][0], 'username' => $_SESSION["userData"][1], 'propic' => $_SESSION["userData"][2]));
        }
    }