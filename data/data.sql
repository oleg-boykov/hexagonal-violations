-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: speedypaper
-- ------------------------------------------------------
-- Server version	5.7.22

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
-- Table structure for table `support_rules`
--

DROP TABLE IF EXISTS `support_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `critical` smallint(6) NOT NULL,
  `severity` smallint(6) NOT NULL,
  `fine_percent` smallint(6) NOT NULL,
  `days` smallint(6) NOT NULL DEFAULT '30',
  `wh` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1951 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_rules`
--

LOCK TABLES `support_rules` WRITE;
/*!40000 ALTER TABLE `support_rules` DISABLE KEYS */;
INSERT INTO `support_rules` VALUES (1,'Wrong information provided to the customer',2,0,100,30,NULL),(2,'No notes to myself were left, describing unresolved issues',2,0,100,30,NULL),(3,'Rudeness',1,0,200,30,NULL),(4,'Late or absent on the shift without a reasonable excuse',2,0,200,30,NULL),(5,'More than one chat was missed during the shift',2,0,100,30,NULL),(6,'Information was not forwarded to the writer',2,0,100,30,NULL),(7,'No Support Agents online in Chat',2,0,200,30,NULL),(8,'Message is not answered for more than 15 minutes',2,0,1,30,NULL),(9,'Revision message that contradicts initial instructions was approved to the writer',2,0,100,30,NULL),(10,'Personal/confidential information was not deleted',2,0,1,30,NULL),(11,'Unnecessary submission of a clarification message',2,0,100,30,NULL),(12,'A writer was not informed why he was reassigned',2,0,1,30,NULL),(13,'Price/deadline was not adjusted for a new writer',2,0,2,30,NULL),(14,'Email was left without reply',2,0,100,30,NULL),(15,'No actions to prevent order cancellation',1,0,100,30,NULL),(16,'Reason for cancellation was not clarified with the client',1,0,100,30,NULL),(17,'Reason for cancellation did not match real situation',2,0,100,30,NULL),(18,'Credit was not offered; standard template was not followed',1,0,100,30,NULL),(19,'The writer was not asked to stop working',2,0,2,30,NULL),(20,'The payment was not saved as credit per client’s request',2,0,100,30,NULL),(21,'Refund request was not submitted when it was promised',2,0,100,30,NULL),(22,'Wfp/inquiry order not processed at all',2,0,100,30,NULL),(23,'Wfp/inquiry order not processed on time',2,0,1,30,NULL),(24,'Client was not informed about unsuccessful payment attempt',2,0,2,30,NULL),(25,'Client was not provided with guidelines on using the site',1,0,100,30,NULL),(26,'Client was not offered a discount he is eligible for (price appeared to be too high for him)',2,0,2,30,NULL),(27,'Wrong dispute submission (policy contradiction)',2,0,2,30,NULL),(28,'No actions to avoid a dispute were undertaken',2,0,100,30,NULL),(29,'Inappropriate dispute submission (no explanation)',2,0,2,30,NULL),(30,'The dispute caused by a Support agent',1,0,0,30,NULL),(31,'Wrong solution offered on Approved order (policy)',2,0,100,30,NULL),(32,'Support’s offer contradicts the CEM’s offer',2,0,100,30,NULL),(33,'The order was not submitted to CEM if the client asks for it',2,0,1,30,NULL),(34,'Lack of assistance',2,0,1,30,NULL),(155,'Not following the verification procedure',2,0,100,30,NULL),(156,'Wrong/plagiarized file was delivered',2,0,100,30,NULL),(617,'Unreasonable writer’s price increase',2,0,2,30,NULL),(735,'Reminder set for wrong time/Unnecessary reminder was set',2,0,1,30,NULL),(750,'Order was not published for WM',2,0,1,30,NULL),(765,'Order published with wrong details',2,0,100,30,NULL),(791,'Unreasonably low writer’s price',2,0,100,30,NULL),(1011,'Credit was wrongly applied',2,0,1,30,NULL),(1145,'WFP/Inquiry wrongly processed',2,0,1,30,NULL),(1417,'Wrong grammar/spelling in a message to the customer',2,0,1,30,NULL),(1504,'Unreasonable fine to writer',2,0,1,30,NULL),(1613,'(CEM) Client\'s email was not processed within 3 days',2,0,1,30,NULL),(1614,'(CEM) Reviews were left after the CEM shift',2,0,1,30,NULL),(1615,'(CEM) Inappropriate refund offer',2,0,1,30,NULL),(1946,'Support Quality Check Failure',2,0,2,30,NULL),(1947,'Contradiction to Manager’s Offer',2,0,100,30,NULL),(1948,' Unjustified bonus request ',2,0,100,30,NULL),(1949,'Corporate values violation',2,0,200,90,8),(1950,'Supervisor’s failure',0,0,0,30,NULL);
/*!40000 ALTER TABLE `support_rules` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-11-02  7:47:53
