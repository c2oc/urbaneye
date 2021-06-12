-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Generation Time: May 30, 2021 at 02:59 PM
-- Server version: 8.0.23
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
--

-- --------------------------------------------------------

--
-- Table structure for table `Cities`
--

CREATE TABLE IF NOT EXISTS `Cities` (
  `cityID` int NOT NULL AUTO_INCREMENT,
  `cityImage` varchar(120) NOT NULL DEFAULT 'default.jpg',
  `cityCountryID` int NOT NULL,
  `cityName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`cityID`),
  KEY `cityCountryID` (`cityCountryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Countries`
--

CREATE TABLE IF NOT EXISTS `Countries` (
  `countryID` int NOT NULL AUTO_INCREMENT,
  `countryName` varchar(100) NOT NULL,
  PRIMARY KEY (`countryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Favourites`
--

CREATE TABLE IF NOT EXISTS `Favourites` (
  `favouriteCityID` int NOT NULL,
  `favouriteUserID` bigint NOT NULL,
  `favouriteCreated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`favouriteCityID`,`favouriteUserID`),
  KEY `user_PK` (`favouriteUserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Reviews`
--

CREATE TABLE IF NOT EXISTS `Reviews` (
  `reviewID` bigint NOT NULL AUTO_INCREMENT,
  `reviewEnvironment` float NOT NULL,
  `reviewEnvironmentDescription` text NOT NULL,
  `reviewTaxes` float NOT NULL,
  `reviewTaxesDescription` text,
  `reviewCOL` float NOT NULL COMMENT 'COL = Cost of Life',
  `reviewCOLDescription` text,
  `reviewSecurity` float NOT NULL COMMENT 'Is the city safe?',
  `reviewSecurityDescription` text,
  `reviewOverallEvaluation` text NOT NULL,
  `reviewCreated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reviewUserID` bigint NOT NULL,
  `reviewCityID` int NOT NULL,
  PRIMARY KEY (`reviewID`),
  KEY `reviewUserID` (`reviewUserID`),
  KEY `reviewCityID` (`reviewCityID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `userID` bigint NOT NULL AUTO_INCREMENT,
  `userUsername` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userMail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userPassword` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `userPropic` varchar(120) NOT NULL DEFAULT 'default.jpg',
  `userDeleted_at` date DEFAULT NULL,
  `userCreated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Cities`
--
ALTER TABLE `Cities`
  ADD CONSTRAINT `cities_fk_1` FOREIGN KEY (`cityCountryID`) REFERENCES `Countries` (`countryID`);

--
-- Constraints for table `Favourites`
--
ALTER TABLE `Favourites`
  ADD CONSTRAINT `city_PK` FOREIGN KEY (`favouriteCityID`) REFERENCES `Cities` (`cityID`),
  ADD CONSTRAINT `user_PK` FOREIGN KEY (`favouriteUserID`) REFERENCES `Users` (`userID`);

--
-- Constraints for table `Reviews`
--
ALTER TABLE `Reviews`
  ADD CONSTRAINT `reviews_fk_1` FOREIGN KEY (`reviewUserID`) REFERENCES `Users` (`userID`),
  ADD CONSTRAINT `reviews_fk_2` FOREIGN KEY (`reviewCityID`) REFERENCES `Cities` (`cityID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
