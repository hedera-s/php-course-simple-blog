-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 28. Okt 2018 um 17:30
-- Server-Version: 10.1.36-MariaDB
-- PHP-Version: 7.2.10

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
  `blog_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blog_imageAlignment` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cat_id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `blogs`
--

INSERT INTO `blogs` (`blog_id`, `blog_headline`, `blog_image`, `blog_imageAlignment`, `blog_content`, `blog_date`, `cat_id`, `usr_id`) VALUES
(3, 'Italienischer Chefsalat', 'uploaded_images/744711whdiunxkfpvqtrbgomjeazcsyl15407301917cf70241f72806538eb64c7f171eaf28.jpg', 'right', 'Zubereitung\r\nPinienkerne in einer Pfanne ohne Fett anrösten, in einen Mörser geben. Knoblauch zugeben und alles fein zerstoßen. Parmesan, Zitronensaft, 100 ml Olivenöl, 3 El Wasser, 1 Tl Zucker, 1⁄2 Tl Salz und etwas Pfeffer untermischen.\r\nÄpfel in dicke Scheiben schneiden und das Kerngehäuse ausstechen. Mit übrigem Olivenöl beträufeln und in einer heißen Grillpfanne oder Pfanne in 2 Portionen von beiden Seiten braun anbraten.\r\nRömersalat putzen, in mundgerechte Stücke zerzupfen, mit der Hälfte des Parmesan-Dressings mischen. Selleriestangen in schräge, sehr dünne Scheiben schneiden, das Grün zerzupfen. Fenchel putzen und in sehr dünne Scheiben hobeln oder schneiden. Das Fenchelgrün zerzupfen. Tomaten in dünne Scheiben schneiden.\r\nRömersalat auf 4 Tellern oder Platten verteilen, Tomaten- und Apfelscheiben darauf verteilen. Mozzarella in 4 Stücke teilen, mit den Mortadella-Scheiben auf die Tomaten geben. Sellerie und Fenchel mit dem Grün daraufgeben. Übriges Dressing darüberträufeln.\r\nVerwenden Sie möglichst junge, kleine Fenchelknollen. Sie sind besonders zart. Bei großen Knollen die äußere faserige Schicht entfernen.', '2018-10-28 12:36:31', 6, 2),
(5, 'Apfeltorte mit Florentiner-Deckel', 'uploaded_images/455961wumjbzdyfhnaskgoqcplirvext1540730341e693b88e3fbf1ed75d134485818b261b.jpg', 'left', 'Für den Apfelbiskuit Äpfel schälen, vierteln, entkernen, quer in dünne Scheiben schneiden und mit dem Zitronensaft mischen. Eier trennen. Eiweiße mit 3 El lauwarmem Wasser und 1 Prise Salz mit den Quirlen des Handrührers fast steif schlagen. Zucker einrieseln lassen und 3 Minuten zu cremig-festem Eischnee schlagen. Eigelbe kurz unterrühren. Mehl und Backpulver daraufsieben, unterheben, dann die Apfelscheiben vorsichtig unterheben. In eine mit Backpapier ausgelegte Springform (26 cm Ø) streichen. Im vorgeheizten Backofen bei 180 Grad (Gas 2-3, Umluft 160 Grad) auf der untersten Schiene 40 Minuten backen.\r\nFür den Florentiner-Deckel Butter, Zucker, Honig und Sahne in einem Topf kurz aufkochen, bis der Zucker gelöst ist. Mandelstifte und Kürbiskerne zugeben und noch einmal kurz aufkochen. Masse etwas abkühlen lassen. Auf dem vorgebackenen Biskuitboden verteilen und weitere 10 Minuten backen. Dann in der Form auf einem Kuchengitter vollständig abkühlen lassen.\r\nBoden aus der Form lösen und mit einem scharfen Sägemesser waagerecht in der Mitte durchschneiden. Den oberen Boden mit der Mandel-Kürbiskern-Kruste in 12 Tortenstücke schneiden.\r\nFür die Apfelsahne Äpfel waschen, in dünnen Scheiben vom Kerngehäuse schneiden, sehr fein würfeln, mit Zitronensaft mischen. Vanilleschote einritzen, Mark herauskratzen. Sahne steif schlagen. Zucker und Vanillemark mischen, unterrühren. Apfelwürfel unterheben. Apfelsahne auf den unteren Boden streichen, den eingeteilten Florentiner-Deckel daraufsetzen.', '2018-10-28 12:39:01', 5, 2),
(6, 'Ofen!', NULL, 'left', '&lt;p&gt;Das ist ein Paragraph&lt;/p&gt;&lt;p&gt;Das ist der zweite Paragraph&lt;/p&gt;', '2018-10-28 16:27:07', 13, 1);

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
(13, 'Ofengerichte'),
(14, 'Something');

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
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
