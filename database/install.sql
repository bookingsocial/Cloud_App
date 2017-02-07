--
-- Paste your SQL dump into this file
--

--
-- Table structure for table `appointments`
--

CREATE TABLE IF NOT EXISTS `appointments` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `uId` varchar(15) NOT NULL,
  `Salesforce_Id` varchar(18) DEFAULT NULL,
  `Name` varchar(200) DEFAULT NULL,
  `Start_Date` datetime NOT NULL,
  `End_Date` datetime NOT NULL,
  `Service_Id` varchar(18) NOT NULL,
  `Expert_Id` varchar(18) DEFAULT NULL,
  `Status` varchar(200) NOT NULL,
  `LastModifiedDate` datetime NOT NULL,
  `Organization_Id` varchar(18) NOT NULL,
  `Contact_Id` varchar(18) NOT NULL,
  `Provider` varchar(200) NOT NULL,
  `urlId` varchar(3) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `uId` (`uId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;



--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `uId` varchar(15) NOT NULL,
  `Organization_Id` varchar(18) DEFAULT NULL,
  `Name` varchar(200) DEFAULT NULL,
  `Salesforce_Id` varchar(18) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(100) DEFAULT NULL,
  `AccountId` varchar(18),
  `FirstName` varchar(100) DEFAULT NULL,
  `LastName` varchar(100) NOT NULL,
  `Salutation` varchar(5) DEFAULT NULL,
  `Mobile` varchar(100) DEFAULT NULL,
  `MailingCity` varchar(100) DEFAULT NULL,
  `MailingCountry` varchar(100) DEFAULT NULL,
  `MailingPostalCode` varchar(100) DEFAULT NULL,
  `MailingState` varchar(100) DEFAULT NULL,
  `MailingStreet` varchar(100) DEFAULT NULL,
  `Critical_Notification` text,
  `Description` text,
  `Do_Not_Distrub` tinyint(1) DEFAULT NULL,
  `urlId` varchar(3) NOT NULL,
  `Provider` varchar(100) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `uId` (`uId`),
  UNIQUE KEY `Salesforce_Id` (`Salesforce_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1280 ;


--
-- Table structure for table `experts`
--

CREATE TABLE IF NOT EXISTS `experts` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `uId` varchar(15) NOT NULL,
  `Provider` varchar(100) DEFAULT NULL,
  `Active` tinyint(1) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(100) DEFAULT NULL,
  `Salesforce_Id` varchar(100) DEFAULT NULL,
  `Organization_id` varchar(18) DEFAULT NULL,
  `Date_Format` text,
  `Call_Notification` tinyint(1) DEFAULT NULL,
  `SMS_Notification` tinyint(1) DEFAULT NULL,
  `Email_Notification` tinyint(1) DEFAULT NULL,
  `Working_Hour` varchar(18) DEFAULT NULL,
  `Profile_Pic` text NOT NULL,
  `urlId` varchar(3) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `uId` (`uId`),
  UNIQUE KEY `Salesforce_Id` (`Salesforce_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=155 ;

-- --------------------------------------------------------

--
-- Table structure for table `expert_service`
--

CREATE TABLE IF NOT EXISTS `expert_service` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `uId` varchar(15) NOT NULL,
  `ES_Name` varchar(200) NOT NULL,
  `Expert_Id` varchar(18) NOT NULL,
  `Service_Id` varchar(18) NOT NULL,
  `Salesforce_Id` varchar(18) NOT NULL,
  `urlId` varchar(3) NOT NULL,
  `Provider` varchar(100) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `uId` (`uId`),
  UNIQUE KEY `Salesforce_Id` (`Salesforce_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=259 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE IF NOT EXISTS `organization` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Salesforce_Id` varchar(18) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `Integration_Username` varchar(100) NOT NULL,
  `Integration_Password` varchar(100) NOT NULL,
  `Integration_Token` varchar(200) NOT NULL,
  `LastModifiedDateAndTime` datetime NOT NULL,
  `Location` varchar(200) NOT NULL,
  `Session_Id` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`Id`, `Salesforce_Id`, `Name`, `Description`, `Integration_Username`, `Integration_Password`, `Integration_Token`, `LastModifiedDateAndTime`, `Location`, `Session_Id`) VALUES
(1, '00D28000000Iggw', 'Php Integration', '', 'ramesh.k@bookingsocial.com.php', '$KS726php', 'hS7dgoxzLTvxcasts63zJH5UP', '2016-04-30 13:13:34', 'https://ap2.salesforce.com/services/Soap/u/36.0/00D280000011q29', '00D280000011q29!ARQAQEkc4jcIB7EUEiKG.tznIyzykGztURWr1DKP9F3GSW4BhcTvA93WS5dCvvJFE2NpxABZlCvU56w8BWXoyVsKKJFY8f0Q');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `uId` varchar(15) NOT NULL,
  `Organization_id` varchar(18) DEFAULT NULL,
  `Name` varchar(200) NOT NULL,
  `Active` tinyint(1) DEFAULT NULL,
  `Description` text,
  `DisplayType` varchar(200) DEFAULT NULL,
  `DurationTime` varchar(200) DEFAULT NULL,
  `DurationUnit` varchar(200) DEFAULT NULL,
  `PreferredExpert` varchar(200) DEFAULT NULL,
  `Provider` varchar(200) DEFAULT NULL,
  `Salesforce_Id` varchar(200) DEFAULT NULL,
  `Working_Hour` varchar(18) DEFAULT NULL,
  `PreferredExpertuId` varchar(50) NOT NULL,
  `urlId` varchar(3) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `uId` (`uId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=146 ;


--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Value1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Value2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Value3` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Extended_Value` text COLLATE utf8_unicode_ci NOT NULL,
  `Provider` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Organization_Id` varchar(18) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`Id`, `Name`, `Value1`, `Value2`, `Value3`, `Extended_Value`, `Provider`, `Organization_Id`) VALUES
(1, 'CUSTOMEROBJECT', 'CONTACT', '', '', '', 'cloudapp', '00D28000000Iggw'),
(2, 'CALENDARAPPOINTEMENTSTATUS', 'Pending', '', '', '', 'cloudapp', '00D28000000Iggw'),
(3, 'CALENDAREVENTSETTINGS', '', '', '', '{"color": "#FEFEFE","UI": {"SERVICENAMELABEL": "","DATETIMELABEL": "","CONTACTNAMELABEL": ""}}', 'cloudapp', '00D28000000Iggw'),
(4, 'CALDEFAULTCLIENTTYPE', 'NEW', '', '', '', 'cloudapp', '00D28000000Iggw'),
(5, 'CALPOPOVERBUTTONS', '', '', '', '[{"type":"standardreschedule"}, {"type":"standardedit"}, {"type":"standardnew"}, {"type":"custom", "icon":"fa fa-google", "launchURL":"/apex/Booking_Social_Add_Product","title":"Product","target":"_blank"}]', 'cloudapp', '00D28000000Iggw'),
(6, 'CALENDARALLOWPASTBOOKING', 'true', '', '', '', 'cloudapp', '00D28000000Iggw'),
(7, 'CALENDARPOPUPTYPE', 'Normal', '', '', '', 'cloudapp', '00D28000000Iggw'),
(8, 'CALENDARSTATUSSETTINGS', '', '', '', '{"Pending": {"color": "#FECEA0"},"Completed": {"color": "#99CC94"},"Approved": {"color": "#65CDCE"},"No Show": {"color": "#D296D2"}}', 'cloudapp', '00D28000000Iggw'),
(9, 'CALENDARENDTIME', '17', '', '', '', 'cloudapp', '00D28000000Iggw'),
(10, 'CALENDARSTARTTIME', '8', '', '', '', 'cloudapp', '00D28000000Iggw');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `Organization_Id` varchar(18) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '2016-06-29 16:31:35',
  `created` datetime NOT NULL DEFAULT '2016-06-29 16:31:35',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_type` varchar(200) COLLATE utf8_bin NOT NULL,
  `related_to` varchar(18) COLLATE utf8_bin NOT NULL,
  `profile` varchar(250) COLLATE utf8_bin NOT NULL,
  `provider` varchar(100) COLLATE utf8_bin NOT NULL,
  `active_directory` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=27 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `Organization_Id`, `password`, `email`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `last_login`, `created`, `modified`, `user_type`, `related_to`, `profile`, `provider`, `active_directory`) VALUES
(1, 'ramkosal', '00D28000000Iggw', '$2a$08$iSjXJ9Wgs9WPLVSYKDh1y.YbmZTnzg3Qv5cXg0EkbDAAt1c9wtGpi', 'ramesh03k@gmail.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '180.215.123.187', '2016-06-29 18:31:41', '2016-06-29 16:31:35', '2016-06-29 16:31:35', 'CONTACT', '1269', '25fd0942327eb8c29895ccbd9f0c2be92.jpg', 'cloudapp', ''),
(2, 'ramkosal@expert.com', '00D28000000Iggw', '$2a$08$iSjXJ9Wgs9WPLVSYKDh1y.YbmZTnzg3Qv5cXg0EkbDAAt1c9wtGpi', 'ramesh03k@gmail.com', 0, 0, NULL, NULL, NULL, NULL, NULL, '182.65.203.228', '2016-04-30 13:10:54', '2016-06-29 16:31:35', '2016-05-19 17:43:25', 'EXPERT', '151', '67c64d88b573c7732ecc3a8f016c14f2.png', 'cloudapp', ''),
(3, 'ramkosal@admin.com', '00D28000000Iggw', '$2a$08$iSjXJ9Wgs9WPLVSYKDh1y.YbmZTnzg3Qv5cXg0EkbDAAt1c9wtGpi', 'ramesh03k@gmail.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '180.215.123.187', '2016-06-29 18:33:22', '2016-06-29 16:31:35', '2016-06-29 16:33:17', 'SUPERADMIN', '', '', 'cloudapp', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_autologin`
--

CREATE TABLE IF NOT EXISTS `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `working_hours`
--

CREATE TABLE IF NOT EXISTS `working_hours` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `uId` varchar(18) COLLATE utf8_unicode_ci NOT NULL,
  `Salesforce_Id` varchar(18) COLLATE utf8_unicode_ci NOT NULL,
  `Active` tinyint(1) DEFAULT NULL,
  `Friday_Break_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Friday_Break_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Friday_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Friday_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Monday_Break_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Monday_Break_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Monday_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Monday_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Saturday_Break_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Saturday_Break_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Saturday_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Saturday_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Sunday_Break_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Sunday_Break_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Sunday_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Sunday_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Thursday_Break_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Thursday_Break_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Thursday_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Thursday_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tuesday_Break_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tuesday_Break_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tuesday_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tuesday_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Wednesday_Break_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Wednesday_Break_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Wednesday_End_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Wednesday_Start_Time` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Working_Hours` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Organization_Id` varchar(18) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Provider` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `urlId` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Salesforce_Id` (`Salesforce_Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=159 ;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

create table `accounts` (
	`Id` int(11) not null primary key AUTO_INCREMENT,
	`uId` varchar(15)not null unique,
	`Organization_Id` varchar(18),
	`Name` varchar(200),
	`Salesforce_Id` varchar(18),
	`Company` varchar(200),
	`Employee_Size` varchar(200),
	`Email` varchar(200),
	`Phone` varchar(200),
	`Provider` varchar(100) not null,
	`Mobile` varchar(200),
	`Street` varchar(200),
	`City` varchar(200),
	`State` varchar(200),
	`Country` varchar(200),
	`PostalCode` int(6) 
);


-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

create table `leads`(
   `Id` int(11) not null primary key AUTO_INCREMENT, 
   `uId` varchar(15) not null unique,
   `Organization_Id` varchar(18), 
   `Name` varchar(255), 
   `Salesforce_Id` varchar(18),
   `Email` varchar(100), 
   `Phone` varchar(100),
   `FirstName` varchar(100) not null,  
   `LastName` varchar(100), 
   `Salutation` varchar(5), 
    `Mobile` varchar(100), 
	`City` varchar(100),
	`Country` varchar(100), 
	`PostalCode` varchar(100), 
	`State` varchar(100),
	`Street` varchar(100),
	`urlId` varchar(3) not null, 
	`Provider` varchar(100) not null,
	`LeadSource` varchar(100), 
	`Status` varchar(200), 
	`Company` varchar(100), 
	`Description` text, 
	`NumberOfEmployees` varchar(100)
);


-- --------------------------------------------------------

--
-- Table structure for table `opportunities`
--

create table `opportunities`(
	`Id` int(11) not null primary key AUTO_INCREMENT, 
	`uId` varchar(15) not null unique,
	`Organization_Id` varchar(18), 
	`OpportunityName` varchar(120), 
	`OpportunityOwner` varchar(200), 
	`Amount` varchar(20),
	`CloseDate` date, 
	`AccountId` varchar(18), 
	`Probability` varchar(100), 
	`Private` boolean,
	`Primary_Campaign_Source` varchar(200),
	`LeadSource` varchar(200), 
	`Type` varchar(200), 
	`Stage` varchar(200),
	`Salesforce_Id` varchar(18), 
	`urlId` varchar(3) not null, 
	`Provider` varchar(100) not null
);
