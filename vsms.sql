-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 05, 2015 at 06:39 AM
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
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL,
  `balance` double NOT NULL,
  `reserved` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_unique` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `role`, `balance`, `reserved`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 5000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `api`
--

CREATE TABLE IF NOT EXISTS `api` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slink` varchar(255) NOT NULL,
  `blink` varchar(255) NOT NULL,
  `xdefault` varchar(1) NOT NULL DEFAULT '0',
  `sresponse` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE IF NOT EXISTS `bank` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `acc_name` varchar(100) NOT NULL,
  `acc_no` varchar(100) NOT NULL,
  `logo` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`id`, `acc_name`, `acc_no`, `logo`, `name`) VALUES
(1, 'Mobicom Ent', '099876565444444', 1, 'Tomb Bank'),
(2, 'Mobicom Ent', '08877989944444', 2, 'Colonial bank'),
(3, 'Supremeweb Solutions', '0030003001', 3, 'Guaranty Trust Bank');

-- --------------------------------------------------------

--
-- Table structure for table `bank_images`
--

CREATE TABLE IF NOT EXISTS `bank_images` (
  `img_no` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`img_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `bank_images`
--

INSERT INTO `bank_images` (`img_no`) VALUES
(1),
(2),
(3);

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE IF NOT EXISTS `card` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `tell` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `user` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` longtext NOT NULL,
  `type` varchar(10) NOT NULL,
  `user` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `controller`
--

CREATE TABLE IF NOT EXISTS `controller` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `controller`
--

INSERT INTO `controller` (`id`, `username`, `password`) VALUES
(1, 'controller', '594c103f2c6e04c3d8ab059f031e0c1a');

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE IF NOT EXISTS `coupon` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `unit` double NOT NULL,
  `exp_date` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupon_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usage`
--

CREATE TABLE IF NOT EXISTS `coupon_usage` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `coupon_id` bigint(20) NOT NULL,
  `user` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cpages`
--

CREATE TABLE IF NOT EXISTS `cpages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `message` longtext NOT NULL,
  `type` varchar(50) NOT NULL,
  `sort` bigint(20) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `elink` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `draft`
--

CREATE TABLE IF NOT EXISTS `draft` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `senderid` varchar(20) NOT NULL,
  `destination` longtext NOT NULL,
  `message` longtext NOT NULL,
  `user` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `form`
--

CREATE TABLE IF NOT EXISTS `form` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `mno` bigint(20) NOT NULL,
  `sid` varchar(20) NOT NULL,
  `user` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_item`
--

CREATE TABLE IF NOT EXISTS `form_item` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fmessage` longtext NOT NULL,
  `fwhen` varchar(100) NOT NULL,
  `ftime` varchar(20) NOT NULL,
  `form` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_job`
--

CREATE TABLE IF NOT EXISTS `form_job` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `senderid` varchar(20) NOT NULL,
  `destination` longtext NOT NULL,
  `message` longtext NOT NULL,
  `entrydate` varchar(100) NOT NULL,
  `senddate` varchar(100) NOT NULL,
  `user` bigint(20) NOT NULL,
  `credit` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_recipient`
--

CREATE TABLE IF NOT EXISTS `form_recipient` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `tell` varchar(100) NOT NULL,
  `form` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `glic`
--

CREATE TABLE IF NOT EXISTS `glic` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lkey` longtext NOT NULL,
  `url` varchar(100) NOT NULL,
  `exp` bigint(20) NOT NULL,
  `yr` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE IF NOT EXISTS `info` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tell` bigint(20) NOT NULL,
  `sid` varchar(100) NOT NULL,
  `sname` varchar(100) NOT NULL,
  `surl` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `num_gen` bigint(20) NOT NULL DEFAULT '5',
  `style` varchar(100) NOT NULL DEFAULT 'bootstrap.css',
  `stat` varchar(20) NOT NULL DEFAULT 'setup',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`id`, `tell`, `sid`, `sname`, `surl`, `email`, `description`, `num_gen`, `style`, `stat`) VALUES
(1, 2347034534116, 'MobicomCMS', 'Acada SMS Portal', 'http://www.acadacity.com', 'info@mobicomcms.com', 'MobicomCMS', 100, '3.css', 'setup');

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE IF NOT EXISTS `job` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `senderid` varchar(20) NOT NULL,
  `destination` longtext NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `job1`
--

CREATE TABLE IF NOT EXISTS `job1` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `senderid` varchar(20) NOT NULL,
  `destination` longtext NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `job2`
--

CREATE TABLE IF NOT EXISTS `job2` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `senderid` varchar(20) NOT NULL,
  `destination` longtext NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `job3`
--

CREATE TABLE IF NOT EXISTS `job3` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `senderid` varchar(20) NOT NULL,
  `destination` longtext NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `job4`
--

CREATE TABLE IF NOT EXISTS `job4` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `senderid` varchar(20) NOT NULL,
  `destination` longtext NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `keyword`
--

CREATE TABLE IF NOT EXISTS `keyword` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `keyword` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `type`, `message`) VALUES
(1, 'welcome', 'Welcome Acada SMS, your SMS solution starts here.'),
(2, 'reseller', 'Welcome to Acada SMS, what a wise decision you took to become a Reseller with Limitless Resources.');

-- --------------------------------------------------------

--
-- Table structure for table `network`
--

CREATE TABLE IF NOT EXISTS `network` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ncode` bigint(20) NOT NULL,
  `ucost` double NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `network_unique` (`ncode`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `network`
--

INSERT INTO `network` (`id`, `ncode`, `ucost`, `name`) VALUES
(1, 234803, 1.5, 'MTN'),
(2, 234703, 1.5, 'MTN'),
(3, 234806, 1.5, 'MTN'),
(4, 234805, 1.8, 'Glo'),
(5, 234802, 1.8, 'Airtel'),
(6, 234809, 1.5, 'Etisalat'),
(7, 234706, 1.5, 'MTN'),
(8, 234813, 1.5, 'MTN'),
(9, 234816, 1.5, 'MTN'),
(10, 234810, 1.5, 'MTN'),
(11, 234814, 1.5, 'MTN'),
(12, 234903, 1.5, 'MTN'),
(13, 234906, 1.5, 'MTN'),
(15, 234807, 1.8, 'GLO'),
(16, 234705, 1.8, 'GLO'),
(17, 234815, 1.8, 'GLO'),
(18, 234811, 1.8, 'GLO'),
(19, 234905, 1.8, 'GLO'),
(21, 234808, 1.5, 'Airtel'),
(22, 234708, 1.5, 'Airtel'),
(23, 234812, 1.5, 'Airtel'),
(25, 234818, 1.5, 'Etisalat'),
(26, 234817, 1.5, 'Etisalat'),
(27, 234909, 1.5, 'Etisalat');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_job`
--

CREATE TABLE IF NOT EXISTS `newsletter_job` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `topic` varchar(100) NOT NULL,
  `start` bigint(20) NOT NULL,
  `hour` int(3) NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `message` longtext NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `message`, `type`) VALUES
(1, '<p style="text-align: justify;"><span class="example2"><img class=img-responsive class="img-responsive" style="float: left;" src="../images/uploads/1.jpg" alt="" width="228" height="221" />&lsquo;Your Site Name&rsquo;: We help you Boost your business with the power of SMS Messaging and most importantly Location Based Marketing (LBM)! &lsquo;Your Site Name&rsquo; is an industry leader in bulk SMS messaging and provides customers with a secure, simple, dependable, high capacity mobile marketing platform to over 700 networks worldwide.</span></p>\r\n<p style="text-align: justify;"><span class="example2">This easy to use web-based product offers bulk SMS delivery, address book merge functionality, history reporting, bulk imports of contacts and many advanced sending features like targeted sms marketing, coupon rewarding system, refer and earn program etc. SMS Text Messaging ensures you reach your target market by contacting them directly on their mobile phone.</span></p>\r\n<p style="text-align: justify;"><span class="example2">A large number of our populations carry a mobile phone and SMS Text Messaging can get your message through quickly and efficiently. This form of communication can be particularly effective when contacting members of a club or society, sales force, event attendees and more. The potential is unlimited.</span></p>\r\n<p><strong>Getting Started:</strong></p>\r\n<p>In order to use our facilities you will need to create an account. With <span class="example2">&lsquo;Your Site Name&rsquo;</span>, You can register and start sending now! You will get FREE test credits to test our services.</p>\r\n<p>Once your account has been activated, you can login with your username &amp; password. After you are logged in, you will see a welcome message- "Hi, your username". Then proceed to "SEND SMS" and reach out to your clients, members, friends etc.</p>\r\n<p>What We Offer</p>\r\n<ul>\r\n<li>Simple, reliable, high-speed bulk SMS service</li>\r\n<li>24x7 support and Call Centre assistance</li>\r\n<li>No hidden costs &ndash; you only pay per SMS</li>\r\n<li>Many advanced sending features such as: address book merge functionality; SMS scheduling; advanced number management; easily transfer existing database of mobile numbers.</li>\r\n</ul>', 'home'),
(2, '<p style="text-align: center;"><img class=img-responsive src="http://www.vsms.onecrib.com/images/uploads/2.jpg" alt="" width="281" height="179" /></p>\r\n<p style="text-align: justify;">&lsquo;Your site name&rsquo; is a distinguished player in innovative technologies with highly skilled professionals to provide cutting edge solutions to its clients such as Professional websites, software and mobile applications development for small, medium and large sized business organizations, Mobile marketing with state-of-the-art platform for bulk messaging application and Business Consultancy. We are currently serving numerous clients globally.</p>\r\n<h3 style="text-align: justify;">Our Services Include;</h3>\r\n<p><strong>Web Design</strong></p>\r\n<p style="text-align: justify;"><strong>Fully Functional and High Converting Web Designs...</strong> The years of designing web sites, consulting and helping others to design and promote their web sites has given&nbsp;&lsquo;Your site name&rsquo; experience and knowledge in web design principles and web site promotion that many claim to know but only a few can apply.</p>\r\n<h3>Software Development</h3>\r\n<p style="text-align: justify;"><strong>Custom ERP, CRM, Financial, Business Process Automation Software Development</strong><br />Our professional software development team, certified processes, Quality Assurance and testing, cutting-edge technologies such as Java, .Net, PHP and others allow our clients to timely return their investments into all their custom software development initiatives.</p>\r\n<h3>Mobile App. Dev.</h3>\r\n<p style="text-align: justify;">From android development through to iphone, Nokia, Windows, Blackberry development we deliver, no matter the nature of the apps and what your requirements are, we are keen to work on your project and promise to complete it to your satisfaction... Our Team of Experienced Mobile developers and analyst are on ground to bring uality delivery</p>\r\n<p style="text-align: justify;"><strong>And Many More!</strong></p>\r\n<p style="text-align: justify;"><strong>Just contact and you are sure of the best service ever.</strong></p>', 'services'),
(3, '<p style="text-align: justify;"><strong>&lsquo;Your website name&rsquo; is an ICT based privately owned Nigerian company duly registered by the Corporate Affairs Commission of Nigeria. </strong></p>\r\n<p style="text-align: justify;">The company is a leading provider of full Information, Communication and Technology (ICT) services dedicated to small, medium and large-scale businesses.</p>\r\n<p style="text-align: justify;"><strong>The company is a complete advertising and publisher&rsquo;s solution to all online ad campaign management requirements be it Google advertisement, Facebook advertisement, email marketing, bulk SMS or campaign management. Ensuring maximum return on your marketing and branding activities.</strong></p>\r\n<p style="text-align: justify;"><br /> We look at providing complete solutions as we take the time to fully understand not only your requirements, but also your business as this is key to providing complete solutions that fit your needs.<br /> <br /> The company is your trusted partner in ICT solutions, backed by our highly experienced professionals with diverse background.<br /> <strong>&nbsp;</strong><br /> <strong>The company is managed by a team of adept professionals with extensive knowledge and Ict infrastructural experience in both local and international consulting environments.</strong></p>\r\n<p>&nbsp;</p>', 'about'),
(4, '<p style="text-align: justify;">These Terms and Conditions have been substantively updated to take into consideration changes in legislation and regulations applicable to our business in Nigeria.</p>\r\n<h4 style="text-align: justify;">1. INTRODUCTION</h4>\r\n<p style="text-align: justify;">These terms and conditions govern the legal relationship between &lsquo;Your site name&rsquo; and its Users.</p>\r\n<p style="text-align: justify;"><strong>TAKE NOTE THAT THESE TERMS AND CONDITIONS CONTAIN PROVISIONS THAT DISCLAIM, LIMIT AND EXCLUDE THE LIABILITY OF &lsquo;YOUR SITE NAME&rsquo; TO YOU AND THAT INDEMNIFY &lsquo;YOUR SITE NAME&rsquo; AGAINST CLAIMS AND DAMAGES THAT IT MAY SUFFER AS A RESULT OF YOUR CONDUCT.</strong></p>\r\n<p style="text-align: justify;">Please read these terms and conditions carefully.</p>\r\n<h4 style="text-align: justify;">2. INTERPRETATION</h4>\r\n<p style="text-align: justify;">2.1 The words and phrases listed below shall bear the following meanings in these terms and conditions, unless the context clearly indicates otherwise:</p>\r\n<p style="text-align: justify;">&ldquo;&lsquo;Your site name&rsquo;&rdquo; means the legal entity with whom you contract for the provision of the Services; &ldquo;End user&rdquo; or &ldquo;recipient&rdquo; means any person, including both natural and juristic entities who receives or is intended to receive any message sent by a User using the Services; &ldquo;Network Operator&rdquo; means any party licensed to install, operate and maintain a cellular telephony network; &ldquo;Services&rdquo; shall mean and include all products and services offered or provided to Users by &lsquo;Your site name&rsquo;; &ldquo;SMS&rdquo; means a short message service provided by means of a text or data message to the cellular handset either on request of the handset User or via a pre-configured batch process; and &ldquo;User&rdquo; shall mean any natural or legal person who makes use of any of the Services or who uses or visits the Website.</p>\r\n<p style="text-align: justify;">2.2 Any reference in these standard terms to the singular includes the plural and vice versa, any reference to persons includes both natural and juristic persons and any reference to a gender includes the other gender.</p>\r\n<p style="text-align: justify;">2.3 Any clause headings inserted into these terms and conditions have been inserted for convenience only and shall not be taken into account in interpreting the terms and conditions.</p>\r\n<p style="text-align: justify;">2.4 Words and expressions defined in any other part of these terms and conditions shall, for the purposes of that part, bear the meaning assigned to such words and expressions in that part.</p>\r\n<p style="text-align: justify;">2.5 To the extent that any provision of this Agreement conflicts with any law, then to the limited extent of such conflict, such provision shall be severed from this Agreement without affecting the enforceability of the remainder of its terms.</p>\r\n<h4 style="text-align: justify;">3. AGREEMENT</h4>\r\n<p style="text-align: justify;">3.1 Persons using the Website or the Services for any reason whatsoever bind themselves and agree to these terms and conditions.</p>\r\n<p style="text-align: justify;">3.2 Should a User not agree to all the terms and conditions of this agreement or be unable to comply with these terms and conditions, the User should immediately cease using the Website and/or terminate the registration process.</p>\r\n<p style="text-align: justify;">3.3 You may not use the Website or the Services if you are not of a legal age to form a binding contract with &lsquo;Your site name&rsquo;.</p>\r\n<p style="text-align: justify;">3.4 Users agree that all terms and conditions herewith published shall be binding on the User and that should there be a contradiction between these general terms and conditions and any other product-specific or service-specific terms and conditions, the product-specific or service-specific terms and conditions shall prevail to the limited extent of such conflict.</p>\r\n<p style="text-align: justify;">3.5 &lsquo;Your site name&rsquo; reserves the right to refuse to accept and/or execute an order or request to do business or to render any Services without giving any reasons therefore. &lsquo;Your site name&rsquo; also reserves the right to cancel orders in whole or in part in &lsquo;Your site name&rsquo;&rsquo; sole and absolute discretion.</p>\r\n<h4 style="text-align: justify;">4. CHANGES AND AMENDMENTS</h4>\r\n<p style="text-align: justify;">4.1 &lsquo;Your site name&rsquo; expressly reserves the right, in its sole and absolute discretion, to alter and/or amend any criteria or information set out in these terms and conditions or any information on the Website without prior notice and to update prices and rates quoted on its Website from time to time.</p>\r\n<p style="text-align: justify;">4.2 Users undertake to check the Website frequently and to acquaint themselves with the changes and/or amendments in the information supplied on the Website and, in this regard, Users undertake to check, at a minimum, these terms and conditions for any alteration thereto, including in respect of the prices and nature of any Services, prior to the conclusion of each new credit purchase or service order in respect of the Services governed by these terms and conditions. Users should regularly verify which networks are covered by the Services. Changes may occur as to which networks are covered from time to time.</p>\r\n<h4 style="text-align: justify;">5. THE SERVICES</h4>\r\n<p style="text-align: justify;">5.1 &lsquo;Your site name&rsquo; shall make all reasonable endeavours to ensure uninterrupted and continued use of the Services, however the delivery of SMS messages is largely dependent on the effective functioning of Network Operators&rsquo; cellular networks, network coverage and the SMS recipient&rsquo;s mobile handset. &lsquo;Your site name&rsquo; does not and cannot guarantee the availability of any Service, the delivery of SMS messages or the compatibility between any message or content format and any particular mobile handsets or mobile operating systems.</p>\r\n<p style="text-align: justify;">5.2 Network Operators may modify, enhance, develop or discontinue components of their services at any time without prior notice, in which event &lsquo;Your site name&rsquo; shall be entitled to modify, enhance, develop or discontinue affected Services to Users without notice.</p>\r\n<p style="text-align: justify;">5.3 &lsquo;Your site name&rsquo; shall use its reasonable endeavours to provide the User with advance notice of any modification, suspension or termination of its Services and shall endeavour to minimise the duration of any suspension thereof in so far as this is reasonably practicable.</p>\r\n<p style="text-align: justify;">5.4 Messages shall be deemed to have been delivered when &lsquo;Your site name&rsquo; has sent the messages to the immediate destination that it is requested to send to, including, but not limited to, mobile telephone networks, SMTP or other servers.</p>\r\n<p style="text-align: justify;">5.5 &lsquo;Your site name&rsquo; shall have the right to withhold, terminate or suspend the provision of Services to the User at any time. Where this Agreement or the provision of any Service is terminated by &lsquo;Your site name&rsquo; for any reason other than breach by the User or discontinuance of a Service by a Network Operator, &lsquo;Your site name&rsquo; shall refund all monies already paid in advance by the User for any unused terminated Service.</p>\r\n<p style="text-align: justify;">5.6 Ordinary mobile terminated messaging services may be terminated by the User at any time.</p>\r\n<p style="text-align: justify;">&nbsp;</p>', 'terms'),
(5, '<p><img class=img-responsive class="img-responsive" src="../images/logo.jpg" alt="" width="212" height="190" /></p>\r\n<p>We @ ''Your site name'' apprecaite your interest in doing business with us.</p>\r\n<p><strong>You can contact us with the following information provided below.</strong></p>\r\n<p>&nbsp;</p>\r\n<p><strong>Phone No:</strong></p>\r\n<ul style="list-style-type: circle;">\r\n<li><strong>080</strong></li>\r\n<li><strong>070</strong></li>\r\n<li><strong>081</strong></li>\r\n</ul>\r\n<p><strong>Email: info@yoursitename.com</strong></p>\r\n<p><strong>Office: No. 15, xyz, Nigeria.</strong></p>\r\n<p>&nbsp;</p>\r\n<p>We''ll be glad to hear from you.</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>', 'contact'),
(6, '<p style="text-align: justify;">Internet user privacy is of paramount importance to ''Your site name''. Our goal is to maintain the trust of our users and others who visit our web sites. Below is information regarding ''Your site name''''s commitment to protect the privacy of users:<br /><br />1. A user''s phonebook, message content and MSISDN''s sent to belong exclusively to that user, and will not be disclosed to any third parties without the user''s written permission.<br /><br />2. Any recipient of any message has the right to know the identity of the sender, and this will be disclosed on request to the recipient.<br /><br />3. By using this web site the User consents to the following regarding user contact information: (user contact information refers only to that information supplied when a user registers, and excludes all other information such as phonebook content and message history.):<br /><br />&nbsp;&nbsp;&nbsp; 3.1 ''Your site name'' may use a users contact information to communicate with the user from time to time.<br />Users may indicate if they do not wish to receive such communications;<br /><br />&nbsp;&nbsp;&nbsp; 3.2 ''Your site name'' may use user''s contact information for non-personal statistical purposes.</p>', 'privacy'),
(7, '<p style="text-align: justify;">Becoming a reseller is pretty simple, and does not require you to know anything about programming or web design. You will get a fully designed website, registered and hosted, with an online SMS application already set-up for you to get going.</p>\r\n<p style="text-align: justify;">Resellers have the advantage of selling SMS at any price they wish and can manage their subscribers on their own using their own control panel. Join the enterprising and smart group of individuals today if you have decided that you want to be financially free.</p>\r\n<p style="text-align: justify;">Our Reseller Program offers you a way to build income while offering your customers first-class bulk SMS services under your own company name. Earn money selling bulk SMS services! Become A Reseller : Own Your Own Website. Start your own business and become your own boss, let us help you to achieve your dream of bringing in more income. See Below our reseller Plans.</p>\r\n<p style="text-align: justify;"><strong>Here Are Some Rich Features Of The Portal We''ll Create For You</strong></p>\r\n<p style="text-align: justify;"><strong>&gt;&gt;</strong> <strong>Targeted NO Generator</strong> - Here the admin has to set the quantity of numbers he want the front users to be able to generate per state. Unlike the unreliable Joomla GSM Number generator that randomly generate 12 digit numbers. THIS module gives you the option to select a any particular state of your choice and also to specify the amount of numbers you want to generate.</p>\r\n<p style="text-align: justify;">Admin also have the ability to set the maximum amount of gsm numbers that can be generated by users. This is actually to enable users and prospects verify the authenticity of your database.<br /> <br /> <strong>&gt;&gt;</strong> <strong>Mobile Business Card</strong> - This give users of your sms portal the ability to create business cards and send it to prospects via their mobile number. It is a WOW!<br /> <br /><strong> &gt;&gt; SMS Auto-responder</strong> - You can create fields like name, email, phone number etc. Add a single message to be delivered at a particular time or load multiple messages to be delivered at different time and date. After which you generate an html code that can be embedded into a web page to create a webform. Whenever this form is filled it delivers the programmed sms at the specify time and date. Marketers can use this feature for follow ups, Churches can use this feature to follow up new coverts etc<br /> <br /> <strong>&gt;&gt; Refer-and-Earn Module</strong> - The admin first of all at the back end composes a message which he want to be delivered to any number his platform if referred to including the sender id. He also specify the amount of sms that will be added to the users account who refer the site to a valid number. Then at the front end the user just click on the function and type a number then the admin specified number of credit is added to his account.<br /> <br /> <strong>&gt;&gt;&nbsp; Location Based Marketing</strong> - It has an inbuilt verified state sorted gsm database across the federation. The admin upload his number sorted state by state. Then the users at the front end can view the total in each state to enable them specify the volume of campaign they can run for different states.<br /> <br /> <strong>&gt;&gt; Duplicate Remover</strong> - This enables you to delete multiple numbers so as to avoid sending a message more than once to a mobile user. You can load up to 100,000 numbers at once on the duplicator field.<br /> <br /> <strong>&gt;&gt; Mobile Coupon Rewarding System</strong> - This module enable the admin of the sms portal to create a coupon code, assign a particular amount of sms units in it, set validity. So that anyone can reward people with<br /> sms units. In the sense that they can sign in your sms portal and when they redeem the coupon the specify number of sms units will be added to their account. Organizations, politicians can use this solution to reward people for a cause.</p>\r\n<p style="text-align: justify;"><strong>&nbsp;&gt;&gt; Reserved SMS Functionality</strong> - This is a new feature we have innovated into the sms industry. This is because in most cases some users will schedue a message/s for later delivery and then go on to exhuast their sms units. Such that when the time for the scheduled message reach to be delivered it can''t fly because there is no available units to push it through. So with this <span data-dobid="hdw">reserved</span> function wenever a message is scheduled the equivalent number of sms needed to deliver it is allocated in the reserved unit showing in the dashboard. Thereby preventing the user to exhuast is sms unit beyond what is allocated to the reserved units.</p>\r\n<p style="text-align: justify;"><strong>Here Is How To Grab This Offer </strong></p>\r\n<p style="text-align: justify;">Firstly, text ''Set Up My Portal'' to ''080''</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p style="text-align: justify;">Secondly, proceed to the bank to deposit the sum of N20,000 into the bamk details below.</p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p style="text-align: justify;"><span style="text-decoration: underline;"><strong>Bank Details</strong></span></p>\r\n<p style="text-align: justify;"><strong>Bank Name:<br /></strong></p>\r\n<p style="text-align: justify;"><strong>Account Name:</strong></p>\r\n<p style="text-align: justify;"><strong>Account Number: </strong></p>\r\n<p style="text-align: justify;">&nbsp;</p>\r\n<p style="text-align: justify;">After payment send us your payment information and your desired domain name to info@yourwebsitename.com</p>', 'reseller'),
(8, '<p>SMS credit is required to send SMS. There are two payment methods available: Bank payment method and Online payment method. To buy credit, log into your account and navigate to the \\''buy sms credit\\'' page by clicking the \\''buy sms credit\\'' link on the side menu in the user area. The first step to take here is to select the payment method you want to use. After payment your account will be credited.</p>', 'pricing');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `online` varchar(100) NOT NULL,
  `bank` longtext NOT NULL,
  `rbank` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rate`
--

CREATE TABLE IF NOT EXISTS `rate` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lower` bigint(20) NOT NULL,
  `upper` bigint(20) NOT NULL,
  `cost` decimal(3,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `rate`
--

INSERT INTO `rate` (`id`, `lower`, `upper`, `cost`) VALUES
(1, 1, 1000, '1.50'),
(2, 1000, 10000, '1.00'),
(3, 10000, 100000, '0.90'),
(4, 100000, 1000000, '0.75');

-- --------------------------------------------------------

--
-- Table structure for table `referral`
--

CREATE TABLE IF NOT EXISTS `referral` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tell` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `user` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ref_setup`
--

CREATE TABLE IF NOT EXISTS `ref_setup` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `credit` double NOT NULL,
  `message` longtext NOT NULL,
  `sender` varchar(20) NOT NULL,
  `text` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `report_url`
--

CREATE TABLE IF NOT EXISTS `report_url` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user` bigint(20) NOT NULL,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `return_url_unique` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `return_url`
--

CREATE TABLE IF NOT EXISTS `return_url` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user` bigint(20) NOT NULL,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `return_url_unique` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `senderid` varchar(20) NOT NULL,
  `destination` longtext NOT NULL,
  `message` longtext NOT NULL,
  `entrydate` varchar(100) NOT NULL,
  `senddate` varchar(100) NOT NULL,
  `user` bigint(20) NOT NULL,
  `status` varchar(10) NOT NULL,
  `credit` double NOT NULL,
  `no_count` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
(1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `smslog`
--

CREATE TABLE IF NOT EXISTS `smslog` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `senderid` varchar(20) NOT NULL,
  `destination` longtext NOT NULL,
  `message` longtext NOT NULL,
  `credit` double NOT NULL,
  `user` bigint(20) NOT NULL,
  `date` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `smsrequest`
--

CREATE TABLE IF NOT EXISTS `smsrequest` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user` bigint(20) NOT NULL,
  `quantity` bigint(20) NOT NULL,
  `date` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `social`
--

CREATE TABLE IF NOT EXISTS `social` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `facebook` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `target`
--

CREATE TABLE IF NOT EXISTS `target` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `num` varchar(50) NOT NULL,
  `state` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `target_unique` (`num`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `target_job`
--

CREATE TABLE IF NOT EXISTS `target_job` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sid` varchar(20) NOT NULL,
  `message` longtext NOT NULL,
  `count` bigint(20) NOT NULL,
  `state` varchar(100) NOT NULL,
  `start` bigint(20) NOT NULL,
  `user` bigint(20) NOT NULL,
  `mcount` bigint(20) NOT NULL,
  `cost` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE IF NOT EXISTS `token` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(15) NOT NULL,
  `date` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `credit` double NOT NULL,
  `user` bigint(20) NOT NULL,
  `tuser` varchar(20) NOT NULL,
  `date` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ulic`
--

CREATE TABLE IF NOT EXISTS `ulic` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lkey` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `upload`
--

CREATE TABLE IF NOT EXISTS `upload` (
  `img_no` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`img_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `upload`
--

INSERT INTO `upload` (`img_no`) VALUES
(1),
(2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `balance` double NOT NULL,
  `reserved` double NOT NULL,
  `date_created` varchar(100) NOT NULL,
  `log_date` varchar(100) NOT NULL,
  `reseller` varchar(5) DEFAULT 'N',
  `rate` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
-- Table structure for table `voucher`
--

CREATE TABLE IF NOT EXISTS `voucher` (
  `pin` varchar(20) NOT NULL,
  `stat` varchar(15) NOT NULL,
  `date` varchar(100) NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `value` bigint(20) NOT NULL,
  `unit` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
