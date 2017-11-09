-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 09, 2017 at 06:14 PM
-- Server version: 10.1.26-MariaDB-0+deb9u1
-- PHP Version: 7.0.19-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nerdDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `attackers`
--

CREATE TABLE `attackers` (
  `attacker_id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `corporation_id` int(11) NOT NULL,
  `alliance_id` int(11) NOT NULL,
  `faction_id` int(11) NOT NULL,
  `security_status` float NOT NULL,
  `damage_done` int(11) NOT NULL,
  `final_blow` tinyint(1) NOT NULL,
  `ship_type_id` int(11) NOT NULL,
  `weapon_type_id` int(11) NOT NULL,
  `killmail_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE `characters` (
  `character_id` int(11) NOT NULL,
  `character_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `killmail_id` int(11) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `singleton` tinyint(1) NOT NULL,
  `flag` int(11) NOT NULL,
  `quantity_destroyed` int(11) NOT NULL,
  `quantity_dropped` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `killmails`
--

CREATE TABLE `killmails` (
  `killmail_id` int(11) NOT NULL,
  `killmail_hash` varchar(45) NOT NULL,
  `killmail_time` datetime NOT NULL,
  `character_id` int(11) NOT NULL,
  `damage_taken` int(11) NOT NULL,
  `ship_type_id` int(11) NOT NULL,
  `corporation_id` int(11) NOT NULL,
  `alliance_id` int(11) NOT NULL,
  `faction_id` int(11) NOT NULL,
  `position` varchar(255) NOT NULL,
  `solar_system_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `zkill_history`
--

CREATE TABLE `zkill_history` (
  `killID` int(10) UNSIGNED NOT NULL,
  `hash` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attackers`
--
ALTER TABLE `attackers`
  ADD PRIMARY KEY (`attacker_id`),
  ADD KEY `character_id` (`character_id`),
  ADD KEY `ship_type_id` (`ship_type_id`),
  ADD KEY `killmail_id` (`killmail_id`);

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD UNIQUE KEY `character_id` (`character_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `killmails`
--
ALTER TABLE `killmails`
  ADD PRIMARY KEY (`killmail_id`),
  ADD UNIQUE KEY `killmail_id` (`killmail_id`),
  ADD UNIQUE KEY `killmail_id_2` (`killmail_id`),
  ADD KEY `killmail_id_3` (`killmail_id`),
  ADD KEY `killmail_time` (`killmail_time`),
  ADD KEY `solar_system_id` (`solar_system_id`),
  ADD KEY `character_id` (`character_id`),
  ADD KEY `ship_type_id` (`ship_type_id`);

--
-- Indexes for table `zkill_history`
--
ALTER TABLE `zkill_history`
  ADD UNIQUE KEY `killID` (`killID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attackers`
--
ALTER TABLE `attackers`
  MODIFY `attacker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6407345;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19963648;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
