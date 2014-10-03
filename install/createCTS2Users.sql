-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 06, 2014 at 11:11 AM
-- Server version: 5.5.34
-- PHP Version: 5.3.10-1ubuntu3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pwcmbrdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cts2users`
--

CREATE TABLE IF NOT EXISTS `cts2users` (
  `SeqNo` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` varchar(50) CHARACTER SET utf8 NOT NULL,
  `Password` varchar(15) CHARACTER SET utf8 NOT NULL,
  `Role` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Notes` varchar(150) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`SeqNo`),
  UNIQUE KEY `UserID` (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
