<?php
    require("dbconn.php");
    $db = dbconn();
    $isReview = $_POST["isReview"];
    $alreadyGenerated = $_POST["alreadyGenerated"];
    $toGenerate = $_POST["toGenerate"];

    if ($isReview) {
        $sql = "
                SELECT RE.reviewCreation, CI.cityID, CI.countryID, RE.userID, U.userName, U.userPropic, CI.cityName, CO.countryName, (AVG(reviewTaxes)+AVG(reviewEnvironment)+AVG(reviewCOL)+AVG(reviewSecurity))/4 as overallScore
                FROM cities as CI
                JOIN countries CO ON CI.countryID = CO.countryID
                JOIN reviews RE ON CI.cityID = RE.cityID
                JOIN users U on RE.userID = U.userID
                GROUP BY RE.reviewCreation
                ORDER BY RE.reviewCreation DESC
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
            SELECT CI.cityID, CI.cityImage, CI.cityName, CO.countryName, AVG(reviewTaxes) as overallTaxes, AVG(reviewEnvironment) as overallEnvironment, AVG(reviewCOL) AS overallCOL, AVG(reviewSecurity) AS overallSecurity, (AVG(reviewTaxes)+AVG(reviewEnvironment)+AVG(reviewCOL)+AVG(reviewSecurity))/4 as overallScore
            FROM cities as CI
            JOIN countries CO ON CI.countryID = CO.countryID
            LEFT JOIN reviews RE ON CI.cityID = RE.cityID
            GROUP BY CI.cityID
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
