-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2020 at 04:13 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nerddb`
--

-- --------------------------------------------------------

--
-- Table structure for table `alliances`
--

CREATE TABLE `alliances` (
  `alliance_id` int(11) NOT NULL,
  `alliance_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Table structure for table `corporations`
--

CREATE TABLE `corporations` (
  `corporation_id` int(11) NOT NULL,
  `corporation_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `corp_assets`
--

CREATE TABLE `corp_assets` (
  `is_blueprint_copy` tinyint(1) DEFAULT NULL,
  `is_singleton` tinyint(1) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `location_flag` varchar(255) NOT NULL,
  `location_id` bigint(20) NOT NULL,
  `location_type` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `type_id` bigint(20) NOT NULL
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
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `login_name` varchar(255) NOT NULL,
  `state` text NOT NULL,
  `character_name` varchar(255) NOT NULL,
  `character_id` int(11) NOT NULL,
  `login_pass` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `expires` datetime NOT NULL,
  `auth_code` varchar(255) NOT NULL,
  `refresh_token` varchar(255) NOT NULL,
  `character_owner_hash` varchar(255) NOT NULL,
  `token_type` varchar(255) NOT NULL,
  `scopes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `name` text NOT NULL,
  `constellationId` int(11) NOT NULL,
  `starId` int(11) DEFAULT NULL,
  `security` text NOT NULL,
  `truesec` float NOT NULL,
  `securityStatus` text NOT NULL,
  `securityClass` text DEFAULT NULL,
  `effect` text DEFAULT NULL,
  `x` double NOT NULL,
  `y` double NOT NULL,
  `z` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `published` int(11) NOT NULL,
  `radius` double NOT NULL,
  `volume` double NOT NULL,
  `capacity` double NOT NULL,
  `mass` double NOT NULL,
  `groupId` int(11) NOT NULL,
  `marketGroupId` int(11) NOT NULL,
  `packagedVolume` double NOT NULL,
  `portionSize` double NOT NULL,
  `graphicId` int(11) NOT NULL
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
-- Indexes for table `alliances`
--
ALTER TABLE `alliances`
  ADD PRIMARY KEY (`alliance_id`),
  ADD KEY `alliance_name` (`alliance_name`);

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
  ADD UNIQUE KEY `character_id` (`character_id`),
  ADD KEY `character_name` (`character_name`);

--
-- Indexes for table `corporations`
--
ALTER TABLE `corporations`
  ADD PRIMARY KEY (`corporation_id`),
  ADD KEY `corporation_name` (`corporation_name`);

--
-- Indexes for table `corp_assets`
--
ALTER TABLE `corp_assets`
  ADD KEY `location_id` (`location_id`);

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
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`character_id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`(768));

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`(768));

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
  MODIFY `attacker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
