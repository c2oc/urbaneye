<?php
    include("utilities.php");
    function dbconn(): PDO
    {
        try {
            return new PDO("mysql:host=localhost;dbname=my_bigempty", 'root', '',
            array(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            ));
        } catch(PDOException $e){
            echo 'Error: ' . $e->getMessage() . "<br>";
            die();
        }
    }