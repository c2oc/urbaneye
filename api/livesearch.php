<?php
    require("dbconn.php");
    $searchData = $_POST["searchData"];
    $db = dbconn();
    $sql = '
        SELECT cityName, cityID, "city" as type FROM cities WHERE cityName LIKE CONCAT(?,"%")
        UNION ALL 
        SELECT countryName, countryID, "country" as type FROM countries WHERE countryName LIKE CONCAT(?,"%")
        LIMIT 5
    ';
      $res = $db->prepare($sql);
      $res->bindParam(1, $searchData);
      $res->bindParam(2, $searchData);
      $res->execute();
      $results = $res->fetchAll(PDO::FETCH_ASSOC);
      $resultsDump = array();
      foreach ($results as $result) {
          $resultsDump[] = [$result["cityName"], $result["cityID"], $result["type"]];
      }
      $db = null;
      echo json_encode($resultsDump);
      $resultsDump = null;