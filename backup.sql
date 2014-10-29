-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: edu
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.14.04.1

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
-- Table structure for table `AuthAssignment`
--

DROP TABLE IF EXISTS `AuthAssignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AuthAssignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  KEY `userid` (`userid`),
  KEY `itemname` (`itemname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AuthAssignment`
--

LOCK TABLES `AuthAssignment` WRITE;
/*!40000 ALTER TABLE `AuthAssignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `AuthAssignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AuthItem`
--

DROP TABLE IF EXISTS `AuthItem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AuthItem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  `module` varchar(64) NOT NULL,
  PRIMARY KEY (`name`),
  KEY `type` (`type`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AuthItem`
--

LOCK TABLES `AuthItem` WRITE;
/*!40000 ALTER TABLE `AuthItem` DISABLE KEYS */;
/*!40000 ALTER TABLE `AuthItem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AuthItemChild`
--

DROP TABLE IF EXISTS `AuthItemChild`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AuthItemChild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `parent` (`parent`),
  KEY `child` (`child`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AuthItemChild`
--

LOCK TABLES `AuthItemChild` WRITE;
/*!40000 ALTER TABLE `AuthItemChild` DISABLE KEYS */;
/*!40000 ALTER TABLE `AuthItemChild` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `infomessage2user`
--

DROP TABLE IF EXISTS `infomessage2user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `infomessage2user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `read` tinyint(2) unsigned NOT NULL,
  `show_once` tinyint(2) unsigned NOT NULL,
  `message_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `infomessage2user`
--

LOCK TABLES `infomessage2user` WRITE;
/*!40000 ALTER TABLE `infomessage2user` DISABLE KEYS */;
/*!40000 ALTER TABLE `infomessage2user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `infomessages`
--

DROP TABLE IF EXISTS `infomessages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `infomessages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `text` text NOT NULL,
  `info_type` tinyint(4) NOT NULL,
  `type` int(4) unsigned NOT NULL,
  `operation` int(4) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `author` bigint(20) NOT NULL,
  `buttons` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`,`author`),
  KEY `type` (`type`,`operation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `infomessages`
--

LOCK TABLES `infomessages` WRITE;
/*!40000 ALTER TABLE `infomessages` DISABLE KEYS */;
/*!40000 ALTER TABLE `infomessages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `institutions`
--

DROP TABLE IF EXISTS `institutions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `institutions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `logo` varchar(256) NOT NULL,
  `fullTitle` varchar(256) NOT NULL,
  `status` tinyint(4) unsigned NOT NULL,
  `type` int(4) unsigned NOT NULL,
  `priority` int(4) unsigned NOT NULL,
  `phones` text NOT NULL,
  `emails` text NOT NULL,
  `addresses` text NOT NULL,
  `announce` text NOT NULL,
  `text` text NOT NULL,
  `customText` text NOT NULL,
  `image` varchar(256) DEFAULT NULL,
  `latitude` varchar(256) DEFAULT NULL,
  `longitude` varchar(256) DEFAULT NULL,
  `zoom` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `status` (`status`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `institutions`
--

LOCK TABLES `institutions` WRITE;
/*!40000 ALTER TABLE `institutions` DISABLE KEYS */;
INSERT INTO `institutions` VALUES (1,'ЕКАТЕРИНБУРГСКИЙ ЭКОНОМИКО-ТЕХНОЛОГИЧЕСКИЙ КОЛЛЕДЖ','671f460a.gif','ГБОУ СПО «ЕКАТЕРИНБУРГСКИЙ ЭКОНОМИКО-ТЕХНОЛОГИЧЕСКИЙ КОЛЛЕДЖ»',50,0,20,'{\"1\":{\"phone\":\"\",\"text\":\"\"},\"2\":{\"phone\":\"+7 (234) 983-74-93\",\"text\":\"!!!!!!!!!!\"},\"3\":{\"phone\":\"+7 (293) 847-23-74\",\"text\":\"askjhdakdjfdhsks fff\"}}','{\"1\":{\"email\":\"\",\"text\":\"\"},\"2\":{\"email\":\"email@mail.ru\",\"text\":\"\"}}','{\"1\":{\"address\":\"\",\"text\":\"\"},\"2\":{\"address\":\"\\u0410\\u0434\\u0440\\u0435\\u0441 1\",\"text\":\"(\\u043e\\u0444\\u0438\\u0441)\",\"lat\":\"56.82141855779782\",\"lng\":\"60.59682501586912\",\"zoom\":\"12\"},\"3\":{\"address\":\"\\u0410\\u0414\\u0440\\u0435\\u0441 2\",\"text\":\"(\\u0441\\u043a\\u043b\\u0430\\u0434)\",\"lat\":\"56.82085376509415\",\"lng\":\"60.63287390502906\",\"zoom\":\"12\"}}','Екатеринбургский колледж транспортного строительства - многопрофильное государственное образовательное учреждение, имеющее интересную и славную историю 83-летнего существования. \r\nЕКТС имеет интересную и славную историю 83-летнего существования. За эти годы выпущено свыше 25 тысяч специалистов для различных отраслей экономики и народного хозяйства Уральского региона и всей страны. \r\nВысокая квалификация преподавателей, инновационные методики образования – то, что отличает ЕКТС от других колледжей и техникумов.','<ul>\r\n	<li><a href=\"http://www.ects.ru/page257.htm\" target=\"_blank\">Строительство и эксплуатация зданий и сооружений&nbsp;</a></li>\r\n	<li><a href=\"http://www.ects.ru/page262.htm\" target=\"_blank\">Строительство и эксплуатация городских путей сообщения&nbsp;</a></li>\r\n	<li><a href=\"http://www.ects.ru/page261.htm\" target=\"_blank\">Строительство железных дорог, путь и путевое хозяйство</a></li>\r\n	<li><a href=\"http://www.ects.ru/page254.htm\" target=\"_blank\">Автоматика и телемеханика на железнодорожном транспорте &nbsp;</a></li>\r\n	<li><a href=\"http://www.ects.ru/page258.htm\" target=\"_blank\">Производство неметаллических строительных изделий и конструкций&nbsp;</a></li>\r\n	<li><a href=\"http://www.ects.ru/page255.htm\" target=\"_blank\">Компьютерные системы и комплексы &nbsp;</a></li>\r\n	<li><a href=\"http://www.ects.ru/page256.htm\" target=\"_blank\">Программирование в компьютерных системах &nbsp;&nbsp;</a></li>\r\n	<li><a href=\"http://www.ects.ru/page253.htm\" target=\"_blank\">Техническое обслуживание и ремонт автомобильного транспорта &nbsp;</a></li>\r\n	<li><a href=\"http://www.ects.ru/page260.htm\" target=\"_blank\">Монтаж и эксплуатация оборудования и систем газоснабжения &nbsp;</a></li>\r\n	<li><a href=\"http://www.ects.ru/page252.htm\" target=\"_blank\">Эксплуатация транспортного электрооборудования и автоматики&nbsp;</a></li>\r\n	<li><a href=\"http://www.ects.ru/page259.htm\" target=\"_blank\">Водоснабжение и водоотведение&nbsp;</a></li>\r\n	<li><a href=\"http://www.ects.ru/page265.htm\" target=\"_blank\">Прикладная геодезия</a></li>\r\n</ul>\r\n\r\n<p>Занятия на подготовительных курсах проходят &nbsp;в будни и в выходные дни.&nbsp;</p>\r\n\r\n<p>sdjfhdfk</p>\r\n\r\n<p><img alt=\"\" src=\"/store/99/87/8e/e0/99878ee0.png\" style=\"height:70px; width:124px\" /></p>\r\n\r\n<h2>Список специальностей</h2>\r\n\r\n<p><em>Заголовок таблицы</em></p>\r\n\r\n<table width=\"100%\">\r\n	<tbody>\r\n		<tr>\r\n			<th>Заголовок</th>\r\n			<th>Заголовок</th>\r\n			<th>Заголовок</th>\r\n		</tr>\r\n		<tr>\r\n			<td>&nbsp;</td>\r\n			<td>&nbsp;</td>\r\n			<td>&nbsp;</td>\r\n		</tr>\r\n		<tr>\r\n			<td>&nbsp;</td>\r\n			<td>&nbsp;</td>\r\n			<td>&nbsp;</td>\r\n		</tr>\r\n		<tr>\r\n			<td>&nbsp;</td>\r\n			<td>&nbsp;</td>\r\n			<td>&nbsp;</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>и т.д.</p>\r\n\r\n<p>и т.д.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2>Расписание</h2>\r\n\r\n<p><em>Заголовок таблицы</em></p>\r\n\r\n<table width=\"100%\">\r\n	<tbody>\r\n		<tr>\r\n			<th>Заголовок</th>\r\n			<th>Заголовок</th>\r\n			<th>Заголовок</th>\r\n		</tr>\r\n		<tr>\r\n			<td>kjhk</td>\r\n			<td>kjh</td>\r\n			<td>kjhlkcjdlskjd afkj f</td>\r\n		</tr>\r\n		<tr>\r\n			<td>&nbsp;</td>\r\n			<td>&nbsp;</td>\r\n			<td>&nbsp;</td>\r\n		</tr>\r\n		<tr>\r\n			<td>&nbsp;</td>\r\n			<td>&nbsp;</td>\r\n			<td>&nbsp;</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>sdfsdkfsdkjfh</p>\r\n\r\n<p><img src=\"/store/67/1f/46/0a/671f460a_180.gif\" style=\"float:left\" /></p>\r\n\r\n<p><img src=\"/store/67/1f/46/0a/671f460a_180.gif\" style=\"float:left\" /></p>\r\n\r\n<p><img src=\"/store/67/1f/46/0a/671f460a_180.gif\" /></p>\r\n\r\n<p>sdfkjsdfh</p>\r\n\r\n<p>sdf</p>\r\n','{\"1\":{\"title\":\"\",\"text\":\"\"},\"2\":{\"title\":\"\\u0442\\u0438\\u0442\\u043b 2\",\"text\":\"\\u0442\\u0435\\u043a\\u0441\\u0442 2\"}}','bbc503a5.jpg',NULL,NULL,NULL);
/*!40000 ALTER TABLE `institutions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issues`
--

DROP TABLE IF EXISTS `issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `issues` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(5) unsigned NOT NULL,
  `title` varchar(256) NOT NULL,
  `body` text NOT NULL,
  `clientSource` varchar(256) NOT NULL,
  `clientSourceId` bigint(20) unsigned NOT NULL DEFAULT '0',
  `devSource` varchar(256) NOT NULL,
  `devSourceId` bigint(20) unsigned NOT NULL DEFAULT '0',
  `requesterId` bigint(20) unsigned NOT NULL,
  `assigneeId` bigint(20) unsigned NOT NULL,
  `deadlineDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `typeId` bigint(20) unsigned NOT NULL,
  `priority` bigint(20) unsigned NOT NULL,
  `priorityId` bigint(20) unsigned NOT NULL,
  `orgId` bigint(20) unsigned NOT NULL,
  `createDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `closedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `processDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `solvedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `milestoneId` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `clientSource` (`clientSource`(255)),
  KEY `clientSourceId` (`clientSourceId`),
  KEY `devSource` (`devSource`(255)),
  KEY `devSourceId` (`devSourceId`),
  KEY `typeId` (`typeId`),
  KEY `priority` (`priority`),
  KEY `deadlineDate` (`deadlineDate`),
  KEY `priorityId` (`priorityId`),
  KEY `orgId` (`orgId`),
  KEY `createDate` (`createDate`),
  KEY `closedDate` (`closedDate`),
  KEY `processDate` (`processDate`),
  KEY `solvedDate` (`solvedDate`),
  KEY `milestoneId` (`milestoneId`)
) ENGINE=MyISAM AUTO_INCREMENT=3971 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issues`
--

LOCK TABLES `issues` WRITE;
/*!40000 ALTER TABLE `issues` DISABLE KEYS */;
/*!40000 ALTER TABLE `issues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vtokens`
--

DROP TABLE IF EXISTS `vtokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vtokens` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `token` varchar(256) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `expire` varchar(256) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vtokens`
--

LOCK TABLES `vtokens` WRITE;
/*!40000 ALTER TABLE `vtokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `vtokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vuploadedfiles`
--

DROP TABLE IF EXISTS `vuploadedfiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vuploadedfiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vuploadedfiles`
--

LOCK TABLES `vuploadedfiles` WRITE;
/*!40000 ALTER TABLE `vuploadedfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `vuploadedfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vusers`
--

DROP TABLE IF EXISTS `vusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vusers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `service` varchar(256) NOT NULL,
  `serviceId` varchar(256) NOT NULL,
  `avatar` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `username` varchar(256) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `url` varchar(256) NOT NULL,
  `photo` varchar(256) NOT NULL,
  `updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birthday` varchar(255) NOT NULL,
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vusers`
--

LOCK TABLES `vusers` WRITE;
/*!40000 ALTER TABLE `vusers` DISABLE KEYS */;
/*!40000 ALTER TABLE `vusers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-26 13:59:01
