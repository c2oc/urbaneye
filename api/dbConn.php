<?php
include("utilities.php");
function db_connection(): PDO
{
    $dbHost = "";
    $dbName = "";
    $dbUser = "";
    $dbPassword = "";
    try {
        return new PDO("mysql:host=".$dbHost.";dbname=".$dbName."", $dbUser, $dbPassword,
            array(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            ));
    } catch(PDOException $e){
        echo 'Error: ' . $e->getMessage() . "<br>";
        die();
    }
}
