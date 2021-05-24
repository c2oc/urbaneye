<?php
include("utilities.php");
function db_connection(): PDO
{
    $dbHost = "private-ubeye-db-1-do-user-8609113-0.b.db.ondigitalocean.com";
    $dbName = "ubeye-db";
    $dbUser = "www-data";
    $dbPassword = "al59n9pxoyzxfaxg";
    try {
        return new PDO("mysql:host=".$dbHost.";port=25060;dbname=".$dbName."", $dbUser, $dbPassword,
            array(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            ));
    } catch(PDOException $e){
        echo 'Error: ' . $e->getMessage() . "<br>";
        die();
    }
}