<?php
    require("dbConn.php");
    session_start();
    $pageRequested = $_POST["pageRequested"];
    if ($pageRequested == "user-drop") {
        echo json_encode(array('email' => $_SESSION["userData"][0], 'username' => $_SESSION["userData"][1], 'propic' => $_SESSION["userData"][2]));
    } else if ($pageRequested == "favourites") {
        $alreadyGenerated = $_POST["alreadyGenerated"];
        $toGenerate = $_POST["toGenerate"];
        $db = db_connection();
        $sql = "
        SELECT cityID, cityImage, cityName, countryName, AVG(reviewTaxes) as overallTaxes, AVG(reviewEnvironment) as overallEnvironment, AVG(reviewCOL) AS overallCOL, AVG(reviewSecurity) AS overallSecurity, (AVG(reviewTaxes)+AVG(reviewEnvironment)+AVG(reviewCOL)+AVG(reviewSecurity))/4 as overallScore
        FROM Countries
        JOIN Cities ON countryID = cityCountryID
        LEFT JOIN Reviews ON reviewCityID = cityID
        JOIN Favourites ON favouriteCityID = cityID
        WHERE favouriteUserID = (SELECT userID FROM Users WHERE userUsername = ?)
        GROUP BY cityID
        ORDER BY favouriteCreated_at DESC
        LIMIT ?, ?
    ";
        $res = $db->prepare($sql);
        echo $_SESSION["userSession"];
        $res->bindParam(1, $_SESSION['userSession']);
        $res->bindParam(2, $alreadyGenerated, PDO::PARAM_INT);
        $res->bindParam(3, $toGenerate, PDO::PARAM_INT);
        $res->execute();
        $res = $res->fetchAll(PDO::FETCH_ASSOC);
        $favourites = array();
        foreach ($res as $r) {
            $favourites[] = [$r["cityName"], $r["countryName"], [isNull($r["overallTaxes"]), isNull($r["overallEnvironment"]), isNull($r["overallSecurity"]), isNull($r["overallCOL"]), isNull($r["overallScore"])], $r["cityImage"], $r["cityID"]];
        }
        echo json_encode(array('favourites' => $favourites));
    }
    $db = null;