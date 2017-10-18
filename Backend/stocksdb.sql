-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: localhost    Database: stocksdb
-- ------------------------------------------------------
-- Server version	5.7.19-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `orderHistory`
--

DROP TABLE IF EXISTS `orderHistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderHistory` (
  `symbol` char(1) NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchasePrice` int(11) NOT NULL,
  `totalPrice` int(11) NOT NULL,
  `orderNumber` int(11) NOT NULL AUTO_INCREMENT,
  `ID` int(11) DEFAULT NULL,
  `stockName` char(1) NOT NULL,
  PRIMARY KEY (`orderNumber`),
  KEY `ID` (`ID`),
  CONSTRAINT `orderHistory_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `userInfo` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderHistory`
--

LOCK TABLES `orderHistory` WRITE;
/*!40000 ALTER TABLE `orderHistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `orderHistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stockInfo`
--

DROP TABLE IF EXISTS `stockInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stockInfo` (
  `symbol` char(20) NOT NULL,
  `marketPrice` int(10) NOT NULL,
  `open` int(10) NOT NULL,
  `close` int(20) NOT NULL,
  `high` int(20) NOT NULL,
  `low` int(20) NOT NULL,
  `volume` int(10) NOT NULL,
  `stockName` char(20) DEFAULT NULL,
  `timeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stockInfo`
--

LOCK TABLES `stockInfo` WRITE;
/*!40000 ALTER TABLE `stockInfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `stockInfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userInfo`
--

DROP TABLE IF EXISTS `userInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userInfo` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `balance` int(10) NOT NULL,
  `password` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userInfo`
--

LOCK TABLES `userInfo` WRITE;
/*!40000 ALTER TABLE `userInfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `userInfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userStocks`
--

DROP TABLE IF EXISTS `userStocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userStocks` (
  `ID` int(11) NOT NULL,
  `symbol` char(20) NOT NULL,
  `purchasePrice` int(10) NOT NULL,
  `totalValue` int(20) NOT NULL,
  `stockName` char(20) NOT NULL,
  `quantity` int(5) NOT NULL,
  KEY `ID` (`ID`),
  KEY `symbol` (`symbol`),
  CONSTRAINT `userStocks_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `userInfo` (`ID`),
  CONSTRAINT `userStocks_ibfk_2` FOREIGN KEY (`symbol`) REFERENCES `stockInfo` (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userStocks`
--

LOCK TABLES `userStocks` WRITE;
/*!40000 ALTER TABLE `userStocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `userStocks` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-10-18 19:16:04
