-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 26. Okt 2018 um 15:00
-- Server-Version: 10.1.25-MariaDB
-- PHP-Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `blog`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blogs`
--

CREATE TABLE `blogs` (
  `blog_id` int(11) NOT NULL,
  `blog_headline` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_imageAlignment` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cat_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`) VALUES
(1, 'Suppen'),
(2, 'Snacks'),
(3, 'Salate'),
(5, 'Desserts'),
(6, 'Italienische Küche'),
(7, 'Vegan'),
(10, 'Hauptgerichte'),
(11, 'Gerichte mit Fisch'),
(12, 'Ohne Milch'),
(13, 'Ofengerichte');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `usr_id` int(11) NOT NULL,
  `usr_firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usr_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`usr_id`, `usr_firstname`, `usr_lastname`, `usr_email`, `usr_city`, `usr_password`) VALUES
(1, 'Iwan', 'Iwanow', 'b@b.c', 'Moskau', '$2y$10$5JhZ/69CjXMcBh5GkBycfOL3R2i8swcys9gkVkvxmq7RirJPcwHVG'),
(2, 'Erika', 'Ericsson', 'a@b.c', 'Hamburg', '$2y$10$MCj/aXGV/CjHksJruWrh/.JlQJpdfkMPV2zj8T8DCZYsGkaiPTTe.');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`blog_id`);

--
-- Indizes für die Tabelle `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`usr_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `blogs`
--
ALTER TABLE `blogs`
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
