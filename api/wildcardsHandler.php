<?php
    require("dbConn.php");
    $db = db_connection();
    $isReview = $_POST["isReview"];
    $alreadyGenerated = $_POST["alreadyGenerated"];
    $toGenerate = $_POST["toGenerate"];

    if ($isReview) {
        $sql = "
                SELECT reviewCreation, cityID, cityCountryID, reviewUserID, userUsername, userPropic, cityName, countryName, (AVG(reviewTaxes)+AVG(reviewEnvironment)+AVG(reviewCOL)+AVG(reviewSecurity))/4 as overallScore
                FROM Cities
                JOIN Countries ON cityCountryID = countryID
                JOIN Reviews ON cityID = reviewCityID
                JOIN Users on reviewUserID = userID
                GROUP BY reviewCreated_at
                ORDER BY reviewCreated_at DESC
                LIMIT 5
                ";
        $reviewsDump = array();
        foreach ($db->query($sql) as $review){
            $reviewsDump[] = [$review["reviewCreation"], $review["userName"], $review["userPropic"], $review["countryName"], $review["cityName"], $review["overallScore"]];
        }
        echo json_encode(array('reviews' => $reviewsDump));
        $reviewsDump = null;
    } else {
        $sql = '
            SELECT cityID, cityImage, countryName, countryName, AVG(reviewTaxes) as overallTaxes, AVG(reviewEnvironment) as overallEnvironment, AVG(reviewCOL) AS overallCOL, AVG(reviewSecurity) AS overallSecurity, (AVG(reviewTaxes)+AVG(reviewEnvironment)+AVG(reviewCOL)+AVG(reviewSecurity))/4 as overallScore
            FROM Cities
            JOIN Countries ON cityCountryID = countryID
            LEFT JOIN reviews ON cityID = reviewCityID
            GROUP BY cityID
            ORDER BY overallScore DESC
            LIMIT ?, ?
            ';
        $res = $db->prepare($sql);
        $res->bindParam(1, $alreadyGenerated, PDO::PARAM_INT);
        $res->bindParam(2, $toGenerate, PDO::PARAM_INT);
        $res->execute();
        $cities = $res->fetchAll(PDO::FETCH_ASSOC);
        $citiesDump = array();
        foreach ($cities as $city) {
            $citiesDump[] = [$city["cityName"], $city["countryName"], [isNull($city["overallTaxes"]), isNull($city["overallEnvironment"]), isNull($city["overallSecurity"]), isNull($city["overallCOL"]), isNull($city["overallScore"])], $city["cityImage"], $city["cityID"]];
        }
        echo json_encode(array('cities' => $citiesDump));
        $citiesDump = null;
    }
    $db = null;
