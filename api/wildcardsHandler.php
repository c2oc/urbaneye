<?php
    require("dbConn.php");
    $db = db_connection();
    $isReview = $_POST["isReview"];
    $alreadyGenerated = $_POST["alreadyGenerated"];
    $toGenerate = $_POST["toGenerate"];

    if ($isReview) {
        $sql = "
                SELECT reviewCreated_at, reviewOverallEvaluation, userUsername, userPropic, cityName, countryName, 
                       (AVG(reviewTaxes)+AVG(reviewEnvironment)+AVG(reviewCOL)+AVG(reviewSecurity))/4 as overallScore
                FROM Cities
                JOIN Countries ON cityCountryID = countryID
                JOIN Reviews ON cityID = reviewCityID
                JOIN Users on reviewUserID = userID
                GROUP BY reviewCreated_at
                ORDER BY reviewCreated_at DESC
                LIMIT 4
                ";
        $reviewsDump = array();
        foreach ($db->query($sql) as $review){
            $reviewsDump[] = [
                $review["reviewCreated_at"], $review["userUsername"], $review["userPropic"],
                $review["countryName"], $review["cityName"],
                $review["overallScore"], $review["reviewOverallEvaluation"]
            ];
        }
        echo json_encode(array('reviews' => $reviewsDump));
        $reviewsDump = null;
    } else {
        $sql = '
            SELECT cityID, cityName, cityImage, countryName, AVG(reviewTaxes) as taxes, 
                   AVG(reviewEnvironment) as environment, AVG(reviewCOL) AS col, 
                   AVG(reviewSecurity) AS security, 
                   (AVG(reviewTaxes)+AVG(reviewEnvironment)+AVG(reviewCOL)+AVG(reviewSecurity))/4 as overallScore
            FROM Cities
            JOIN Countries ON cityCountryID = countryID
            LEFT JOIN Reviews ON cityID = reviewCityID
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
            $citiesDump[] = [
                $city["cityName"], $city["countryName"], [isNull($city["taxes"]),
                isNull($city["environment"]), isNull($city["security"]),
                isNull($city["col"]), isNull($city["overallScore"])], $city["cityImage"], $city["cityID"]
            ];
        }
        echo json_encode(array('cities' => $citiesDump));
        $citiesDump = null;
    }
    $db = null;
