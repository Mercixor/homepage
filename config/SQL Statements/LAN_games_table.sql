-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 28. Aug 2015 um 21:14
-- Server Version: 5.6.21
-- PHP-Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `homepage`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lan`
--

CREATE TABLE IF NOT EXISTS `lan` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `lan`
--

INSERT INTO `lan` (`id`, `name`) VALUES
(1, 'September LAN bei Mercix');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lan_games`
--

CREATE TABLE IF NOT EXISTS `lan_games` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `art` varchar(255) NOT NULL,
  `user_min` int(11) NOT NULL,
  `user_max` int(11) NOT NULL,
  `description` text NOT NULL,
  `link` varchar(255) NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `lan_games`
--

INSERT INTO `lan_games` (`id`, `name`, `art`, `user_min`, `user_max`, `description`, `link`, `creator_id`) VALUES
(2, 'C&C Generals', 'Strategie', 2, 8, '', '', 1),
(3, 'CoD 4', 'Ego-Shooter', 1, 16, 'Call of Duty 4!!!', '', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lan_games_rating`
--

CREATE TABLE IF NOT EXISTS `lan_games_rating` (
`id` int(11) NOT NULL,
  `lan_id` int(11) NOT NULL COMMENT 'Ref: lan.id',
  `games_id` int(11) NOT NULL COMMENT 'Ref: lan_games.id',
  `user_id` int(11) NOT NULL COMMENT 'Ref: lan_user.id',
  `rating` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `lan_games_rating`
--

INSERT INTO `lan_games_rating` (`id`, `lan_id`, `games_id`, `user_id`, `rating`) VALUES
(1, 1, 2, 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lan_termine`
--

CREATE TABLE IF NOT EXISTS `lan_termine` (
`id` int(11) NOT NULL,
  `lan_id` int(11) NOT NULL COMMENT 'Ref: lan.id',
  `termin` date NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `lan_termine`
--

INSERT INTO `lan_termine` (`id`, `lan_id`, `termin`, `creator_id`) VALUES
(1, 1, '2015-09-25', 1),
(2, 1, '2015-09-26', 1),
(3, 1, '2015-09-18', 1),
(4, 1, '2015-09-19', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lan_termine_zusagen`
--

CREATE TABLE IF NOT EXISTS `lan_termine_zusagen` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Ref: lan_user.id',
  `termin_id` int(11) NOT NULL COMMENT 'Ref: lan_termine.id'
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `lan_termine_zusagen`
--

INSERT INTO `lan_termine_zusagen` (`id`, `user_id`, `termin_id`) VALUES
(19, 1, 1),
(23, 1, 4),
(39, 2, 1),
(35, 2, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lan_user`
--

CREATE TABLE IF NOT EXISTS `lan_user` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ort` varchar(255) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `auth_hash` varchar(30) NOT NULL COMMENT 'for auth check'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `lan_user`
--

INSERT INTO `lan_user` (`id`, `name`, `nickname`, `email`, `ort`, `creator_id`, `auth_hash`) VALUES
(1, 'Dennis', 'Mercixor', 'dennisjandt@gmx.de', 'Papenburg', 0, 'ilfgZU9u3fVA1IJ9qoh4LCGfqw4AEz'),
(2, 'Maiki', '', '', '', 1, 'M2rIvxb1lSsujWFd9bnTrh84EGMg5F');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `lan`
--
ALTER TABLE `lan`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lan_games`
--
ALTER TABLE `lan_games`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lan_games_rating`
--
ALTER TABLE `lan_games_rating`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `lan_id` (`lan_id`,`games_id`,`user_id`);

--
-- Indizes für die Tabelle `lan_termine`
--
ALTER TABLE `lan_termine`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lan_termine_zusagen`
--
ALTER TABLE `lan_termine_zusagen`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `user_id` (`user_id`,`termin_id`);

--
-- Indizes für die Tabelle `lan_user`
--
ALTER TABLE `lan_user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `lan`
--
ALTER TABLE `lan`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `lan_games`
--
ALTER TABLE `lan_games`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `lan_games_rating`
--
ALTER TABLE `lan_games_rating`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `lan_termine`
--
ALTER TABLE `lan_termine`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `lan_termine_zusagen`
--
ALTER TABLE `lan_termine_zusagen`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT für Tabelle `lan_user`
--
ALTER TABLE `lan_user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
