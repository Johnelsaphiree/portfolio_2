-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 28, 2015 at 08:21 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `vsms`
--

-- --------------------------------------------------------

--
-- Table structure for table `keyword`
--

CREATE TABLE IF NOT EXISTS `keyword` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `keyword` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `keyword`
--

INSERT INTO `keyword` (`id`, `keyword`) VALUES
(1, 'koko,loop,scoop');

-- --------------------------------------------------------

--
-- Table structure for table `scredit`
--

CREATE TABLE IF NOT EXISTS `scredit` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `credit` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `scredit`
--

INSERT INTO `scredit` (`id`, `credit`) VALUES
(1, 5.9);

-- --------------------------------------------------------

--
-- Table structure for table `voice`
--

CREATE TABLE IF NOT EXISTS `voice` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `vreg` varchar(200) NOT NULL,
  `vlogin` varchar(200) NOT NULL,
  `vadmin` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `voice`
--

INSERT INTO `voice` (`id`, `vreg`, `vlogin`, `vadmin`) VALUES
(1, 'http://192.169.249.196/~wwwsupre/247directsms/register.html', 'http://192.169.249.196/~wwwsupre/robocall/', 'http://192.169.249.196/~wwwsupre/S2/');

-- --------------------------------------------------------

--
-- Table structure for table `xjob`
--

CREATE TABLE IF NOT EXISTS `xjob` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `start` bigint(20) NOT NULL,
  `message` longtext NOT NULL,
  `type` varchar(50) NOT NULL,
  `body` bigint(20) NOT NULL,
  `credit` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
