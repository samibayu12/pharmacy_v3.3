-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 21, 2022 at 05:09 AM
-- Server version: 5.7.19
-- PHP Version: 7.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmacy`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CatID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Parent` int(11) NOT NULL,
  `Ordering` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CatID`, `Name`, `Description`, `Parent`, `Ordering`) VALUES
(1, 'Respiratory Diseases Section', 'any Medicine related to Respiratory System ', 0, 1),
(4, 'Brain Diseases', 'concerning the Brain and its diseases', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `CommentID` int(11) NOT NULL,
  `Comment` text NOT NULL,
  `Status` tinyint(4) NOT NULL,
  `CommentDate` date NOT NULL,
  `Item_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `ItemID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `AddDate` date NOT NULL,
  `CountryMade` varchar(255) NOT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) DEFAULT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `Tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`ItemID`, `Name`, `Description`, `Price`, `AddDate`, `CountryMade`, `Image`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`, `Tags`) VALUES
(1, 'Histalix', 'very good medicine for cough', '1300', '2022-07-18', 'sudan', '46045_pharmacy3.jpg', '4', NULL, 1, 1, 32, 'cough, Respiratory-System'),
(2, 'panadol', 'good for heaadache', '1200', '2022-07-18', 'syria', '91306_pill2.jpg', '4', NULL, 1, 4, 31, 'headache, brain , relaxation , feel '),
(5, 'test2', 'this is just test', '1222', '2022-07-21', 'Egypt', '43124_pill1.jpg', '4', NULL, 1, 1, 36, 'relax'),
(6, 'amidol', 'this is antibiotic', '500', '2022-07-21', 'sudan', '49850_pharmacy22.jpg', '1', NULL, 0, 1, 33, 'cough, relax');

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `PicID` int(15) NOT NULL,
  `Pic` varchar(255) NOT NULL,
  `Item_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `GroupID` int(11) NOT NULL DEFAULT '0',
  `RegStatus` int(11) NOT NULL DEFAULT '0',
  `Date` date DEFAULT NULL,
  `Avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `RegStatus`, `Date`, `Avatar`) VALUES
(1, 'PetrosHaile', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'PetrosHaile@gmail.com', 'Petros Haile ', 1, 1, '2022-07-16', '85901_vlcsnap-2017-10-12-11h43m56s190.png'),
(30, 'SamiBayo', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'SamiBayo@gmail.com', 'Sami Bayo', 1, 1, '2022-07-16', '79172_IMG-20191207-WA0004.jpg'),
(31, 'SweetYohannes', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'SweetYohannes@gmail.com', 'Sweet Yohannes', 1, 1, '2022-07-16', '19903_IMG-20210429-WA0011.jpg'),
(32, 'SamiHamid', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'SamiHamid@gmail.com', 'SamiHamid', 1, 1, '2022-07-16', '52530_vlcsnap-2017-10-12-10h33m14s201.png'),
(33, 'Petros2', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Petros2@gmail.com', NULL, 0, 0, '2022-07-18', NULL),
(34, 'noobie', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'noobie@gmail.com', 'noobie noobie', 1, 1, '2022-07-20', '32596_vlcsnap-2017-10-12-10h31m25s114.png'),
(35, 'newuser', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'newuser@gmail.com', 'new user', 1, 1, '2022-07-20', '34827_vlcsnap-2017-10-12-11h38m21s118.png'),
(36, 'yassir', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'yassir@gmail.com', 'yassir Hassan', 1, 1, '2022-07-20', '18695_vlcsnap-2017-09-05-19h24m40s79.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CatID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `items_comment` (`Item_ID`),
  ADD KEY `users_comment` (`User_ID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ItemID`),
  ADD UNIQUE KEY `Name` (`Name`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `cat_1` (`Cat_ID`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`PicID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `PicID` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`Item_ID`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_comment` FOREIGN KEY (`User_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`CatID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
