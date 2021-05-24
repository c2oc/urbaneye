<?php
    require("dbConn.php");

    $db = db_connection();
    for ($i = 0; $i < 10000; $i++) {
        $sql = 'INSERT INTO Reviews (reviewEnvironment, reviewTaxes, reviewCOL, reviewSecurity, cityID) VALUES (?,?,?,?,?)';
        $res = $db->prepare($sql);
        $res->execute(array(rand(1, 99), rand(1, 99), rand(1, 99), rand(1, 99), rand(1000, 5000)));
        echo $i;
    }