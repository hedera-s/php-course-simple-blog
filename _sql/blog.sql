-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 30. Okt 2018 um 11:44
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
(1, 'Pfannkuchen „Elvis Presley“', 'uploaded_images/250947bpfzrjxlsyvtoghamwqeckiund1540807887041e13f55.jpg', 'left', 'Zucker, 1 Prise Salz und Mehl mischen. Eier und Milch verquirlen. Eiermilch nach und nach in die Mehlmischung einrühren, bis ein glatter Teig entsteht. 10 Min. quellen lassen.\r\n\r\nErdnüsse grob hacken. Erdnussbutter und Nuss-Nougat-Creme in ein heißes Wasserbad stellen und leicht schmelzen.\r\n\r\n1⁄2 El Butterschmalz in einer beschichteten Pfanne erhitzen. 1⁄4 des Teigs in der Pfanne verteilen und von jeder Seite 1-2 Min. goldbraun backen. Pfannkuchen zwischen 2 Tellern warm halten. Den restlichen Teig zu 3 weiteren Pfannkuchen verarbeiten und warm halten.\r\nBananen schälen und längs halbieren. Butter in einer Pfanne erhitzen. Bananen darin bei mittlerer Hitze 2 Min. braten und mit Honig beträufeln. Pfannkuchen mit Honigbananen belegen. Mit Erdnussbutter und Nuss-Nougat-Creme beträufeln und mit Erdnüssen bestreut sofort servieren.\r\n\r\nDie Bananen sollten nicht mehr grün sein, aber auch nicht überreif. Die Hälften nur kurz in der heißen Butter-Honig-Mischung braten, damit sie fest bleiben und nicht matschig werden.', '2018-10-29 10:11:27', 1, 2),
(2, 'Gemüsepfanne mit Hackbällchen', 'uploaded_images/820251wgyqsdtpxucbeoimvnhfjrzkal1540808311c575bf7.jpg', 'right', 'Kartoffeln schälen und in 1,5 cm breite Spalten schneiden. Kürbis putzen, entkernen und in 2 cm breite Spalten schneiden. Karotte schälen und schräg in 1/2 cm dicke Scheiben schneiden. Lauch putzen, in 1 cm breite Ringe schneiden, waschen und abtropfen lassen. Paprika vierteln, entkernen und in 2,5 cm große Stücke schneiden.\r\nOfen auf 200 Grad (Umluft 180 Grad) vorheizen. Hack, ei, Brösel und Sahne in eine Schüssel geben, Knoblauch dazupressen. Mit Majoran, Salz und Pfeffer kräftig würzen und zu einer glatten Hackmasse vermischen. Ca. 12 walnussgroße Bällchen formen. In einer heißen ofenfesten Pfanne mit 2 El Öl rundum hellbraun anbraten. Auf einen Teller geben.\r\n2 El Öl in die Pfanne geben, Kartoffeln, Kürbis, Karotten, Lauch und Paprika rundum 5 Min. anbraten, salzen und pfeffern. Mit Brühe ablöschen. Im heißen Ofen auf einem Rost in der Ofenmitte 30 Min. garen. Hackbällchen und Tomaten auf dem Gemüse verteilen und weitere 15 Min. garen.\r\nPetersilienblättchen hacken. Feta zerbröseln und mit dem Joghurt mischen. Gemüsepfanne mit Petersilie bestreuen und mit dem Joghurt servieren.', '2018-10-29 10:18:31', 2, 2),
(3, 'Bete-Carpaccio mit Artischocken und Pfifferlingen', 'uploaded_images/485035boydzlguampeqwcthfknxjsriv154080848959367dc.jpg', 'right', 'Mandeln und Knoblauch grob hacken. Salate und Kräuter waschen, verlesen, gut abtropfen lassen (einige junge Blätter und Blüten zum Garnieren beiseitelegen). Mandeln, Knoblauch und die restlichen Kräuter und Salate mit 150 ml Sonnenblumenöl, Salz, Pfeffer, Zitronensaft und 1 Prise Zucker mit dem Schneidstab sehr fein pürieren. Kalt stellen.\r\n\r\nPfifferlinge sorgfältig putzen. Bete schälen (am besten mit Küchenhandschuhen arbeiten!) und möglichst auf einer Mandoline in sehr dünnen Scheiben direkt in eine Arbeitsschale hobeln. Damit nicht alles rot wird, mit der gelben Bete beginnen, dann die marmorierte, zuletzt die rote. Mit dem restlichen Sonnenblumenöl und Essig begießen, mit Salz und Pfeffer würzen und kurz mit den Händen durchkneten.\r\nVon den Artischocken die äußeren Blätter entfernen, Stiele und die oberen Blattspitzen abschneiden. Artischocken längs in dünne Scheiben schneiden. Je 1 El Kräutersauce auf 4 Teller streichen. Marinierte Bete darauf verteilen.\r\n\r\nOlivenöl in einer großen Pfanne erhitzen und die Artischocken darin bei starker Hitze  5 Minuten knackig braten. In den letzten  2 Minuten die Pfifferlinge zugeben und mitbraten. Mit Salz und Pfeffer abschmecken. Schafskäse zerbröseln. \r\n\r\nArtischocken, Pfifferlinge und Schafskäse auf der Bete verteilen. Die feinen Salate, Kräuterblätter und eventuell einige Blüten darauf anrichten und mit der restlichen Kräutersauce servieren.', '2018-10-29 10:21:29', 3, 2),
(4, 'Pilz-Cannelloni', 'uploaded_images/317006qhckviwxdysamnglroutefpjbz1540809019060948501b00b6160058f8a84781c30a.jpg', 'left', 'Für die Sauce Zwiebeln und Knoblauch in feine Würfel schneiden. Öl in einem breiten Topf erhitzen, Zwiebeln und Knoblauch darin bei mittlerer Hitze glasig dünsten. Möhre schälen, Sellerie waschen, putzen und entfädeln. Möhren und Sellerie in kleine Würfel schneiden und zu den Zwiebeln geben. 5 Minuten andünsten. Tomatenmark einrühren und 30 Sekunden rösten. Tomaten grob zerkleinern, mit Fond und Lorbeer in den Topf geben. Mit Salz und Pfeffer würzen und zugedeckt 30 Minuten bei mittlerer Hitze kochen. Lorbeer entfernen.\r\n\r\nFür die Füllung Spinat putzen, waschen, trocken schleudern und grob hacken. Zwiebeln in feine Würfel schneiden. Champignons und Kräuterseitlinge putzen und grob hacken. Öl in einer großen Pfanne erhitzen, Zwiebeln darin bei mittlerer Hitze glasig dünsten. Pilze zugeben und bei starker Hitze goldbraun anbraten. Spinat zugeben, zusammenfallen lassen und mit Salz und Pfeffer würzen. Majoranblättchen von den Stielen zupfen, grob hacken und unterrühren.\r\n\r\nEine Auflaufform (ca. 25 x 25 cm) fetten. Pilzfüllung in ein Sieb geben, kurz abtropfen lassen und in einen Einwegspritzbeutel füllen. Vom Spritzbeutel die Spitze abschneiden und die Pilzfüllung in die Cannelloni spritzen. Gefüllte Nudeln in die Auflaufform legen und mit der Tomatensauce bedecken. Scamorza grob reiben und über den Auflauf streuen. Im vorgeheizten Backofen bei 200 Grad (Gas 3, Umluft 180 Grad) auf der 2. Schiene von unten 25-30 Minuten garen.\r\n\r\nFür mehr Aroma: Wir überbacken den Auflauf mit Scamorza, die feine Rauchnote macht’s.', '2018-10-29 10:30:19', 4, 2),
(5, 'Kartoffel-Liebstöckel-Suppe', 'uploaded_images/347981afquriojhzkpwbvmdcelngxtys154080931172d92.jpg', 'left', 'Knäckebrot:\r\n1 Tl Kümmelsaat\r\n200 g Roggenvollkornmehl\r\n75 g Weizenvollkornmehl\r\n1.5 Tl Trockenhefe\r\nSalz\r\n40 g Butter\r\n80 ml Milch\r\nSuppe\r\n500 g mehlig kochende Kartoffeln\r\n100 g Knollensellerie\r\n2 Zwiebeln\r\n20 g Butter\r\n100 ml Wermut\r\n750 ml Gemüsebrühe\r\n3 Stiele Liebstöckel\r\nSalz, Pfeffer\r\n0.25 Bund Schnittlauch\r\n100 g Ziegenfrischkäse\r\n \r\n\r\nZubereitung\r\nKümmel in einer Pfanne ohne Fett rösten, bis er duftet, abkühlen lassen und im Mörser grob zerstoßen. Mehle, Hefe, 1 1⁄2 Tl Salz und Kümmel in einer Schüssel mischen. Butter zerlassen, Milch und 80 ml Wasser zugeben, lauwarm abkühlen lassen und zum Mehl geben. Mit den Knethaken des Handrührers 8 Minuten zu einem geschmeidigen Teig verkneten. Zugedeckt an einem warmen Ort 1 Stunde (oder über Nacht im Kühlschrank) gehen lassen.\r\n\r\nTeig auf der bemehlten Arbeitsfläche dünn auf 40 x 30 cm ausrollen, auf ein mit Backpapier belegtes Backblech legen, mehrmals mit der Gabel einstechen und mit dem Teigrad in 2 cm breite Streifen schneiden. Im vorgeheizten Backofen bei 200 Grad (Gas 3, Umluft 180 Grad) auf der 2. Schiene von unten 15 Minuten backen. Brot wenden und weitere 5 Minuten backen. Im Backofen auskühlen lassen. Kurz vorm Servieren in Portionsstücke brechen.\r\n\r\nKartoffeln und Sellerie schälen und grob würfeln. Zwiebeln fein würfeln. Butter im Topf zerlassen, Zwiebeln darin bei mittlerer Hitze andünsten. Kartoffeln und Sellerie zu den Zwiebeln geben und andünsten. Mit Wermut ablöschen und 2 Minuten einkochen. Brühe zugießen, zugedeckt 20 Minuten bei milder Hitze kochen. Suppe mit dem Schneidstab fein pürieren. Liebstöckelblätter abzupfen, in feine Streifen schneiden und zur Suppe geben. Mit Salz und Pfeffer würzen. Schnittlauch in feine Röllchen schnei- den. Knäckebrot mit Käse bestreichen und mit Schnittlauch bestreut zur Suppe servieren.', '2018-10-29 10:35:11', 5, 2),
(6, 'Blumenkohl einfrieren – so geht\'s!', NULL, 'left', 'Achte auf die Qualität des Blumenkohls, bevor du ihn einfrierst. Sind die Blätter schön grün und ist der Kohlkopf fleckenfrei, weiß und fest, kannst du davon ausgehen, dass der Blumenkohl frisch ist.\r\n\r\n1. Entferne das Grünzeug entweder mit einem Messer oder breche es ab.\r\n\r\n2. Schneide den dicken, holzigen Strunk in der Mitte des Kohls ab. Anders als beim Brokkoli ist der Strunk beim Blumenkohl eher nicht genießbar.\r\nTeile den Blumenkohl in kleine Stückchen. Wie groß die Blumenkohlröschen sein sollen, ist abhängig von deinen späteren Zubereitungswünschen.\r\n\r\n3. Säubere den Blumenkohl gründlich.\r\n\r\n4. Stammt dein Blumenkohl aus eigener Ernte, kann es sein, dass sich in den Röschen noch kleine Insekten verstecken. Fülle eine große Schüssel mit Wasser, gebe pro Liter einen Teelöffel Salz hinzu und lege die Kohlröschen in das Salzwasser, um die Eindringlinge loszuwerden. Nach etwa 30 Minuten sollten alle Insekten aus den Röschen gespült worden sein. Wasche den Blumenkohl nun nochmals, ehe du mit dem Blanchieren beginnst.', '2018-10-29 10:37:40', 6, 2),
(7, 'Quark-Beeren-Dessert', 'uploaded_images/514948kqxedcazrhvbfnmgoyiwtlspuj1540809598264fa.jpg', 'right', '1 Pk. TK-Beerenmischung, (300 g)\r\n40 g Puderzucker\r\n500 g Speisequark, (20 %)\r\n1 Pk. Vanillezucker\r\n250 ml Schlagsahne\r\n150 g Schokoladen-Cookies\r\n\r\n1 Pk. TK-Beerenmischung (300 g) und 20 g Puderzucker mischen, in eine Form (ca. 30 x 20 cm) geben.\r\n\r\n500 g Speisequark (20 %), 20 g Puderzucker und 1 Pk. Vanillezucker mit den Quirlen des Handrührers 5 Min. cremig aufschlagen. 250 ml Schlagsahne steif schlagen und unter den Quark heben. Auf die Beeren streichen.\r\n\r\n150 g Schokoladencookies in einen Gefrierbeutel geben und mit einem Rollholz fein zerstoßen. Auf den Quark streuen. Dessert abgedeckt 5 Std. im Kühlschrank durchziehen lassen.', '2018-10-29 10:39:58', 1, 2),
(8, 'Pizza mit Zucchini und Fenchelhack', 'uploaded_images/481222sdpknutbayqejigzmwvfchoxlr1540809722nchelhack.jpg', 'left', '250 g Wasser\r\n15 g Hefe\r\n400 g Mehl (Type 550)\r\n1 Tl Salz\r\n30 g Olivenöl\r\nBelag\r\n1 El Fenchelsaat\r\n250 g Mett (gewürztes Schweinehack)\r\n300 g Zucchini\r\n40 g Parmesan\r\n125 g Mozzarella\r\n1 Glas Tomaten-Sugo\r\n50 g Rauke\r\n\r\nZubereitung\r\nFür den Teig Wasser, Hefe, Mehl, Salz und Olivenöl in den Mixtopf geben und 5 Min./Knetmodus(5 Min.//Knetmodus) kneten. In dieser Zeit eine Kunststoffdose mit Deckel (ca. 27 x 17 cm) dünn einfetten. Teig in die Dose geben, Dose verschließen und 1 Stunde bei Zimmertemperatur gehen lassen.\r\n\r\nBackofen auf 250 °C vorheizen.\r\n\r\nFür den Belag Fenchelsaat in den Mitopf geben und 6 Sek./Stufe 8 zerkleinern. Fenchel und Mett in eine Schüssel (1 l) geben, mit den Händen verkneten und abgedeckt beiseitestellen.\r\n\r\nZucchini in den Mixtopf geben, 4 Sek./Stufe 4 zerkleinern, umfüllen und zur Seite stellen.\r\n\r\nParmesan in den Mixtopf geben und 15 Sek./Stufe 10 zerkleinern.\r\n\r\nMozzarella zugeben und Turbo/1 Sek./1 mal (/Turbo/1 Sek./1 mal) zerkleinern.\r\n\r\nEin Backblech (ca. 30 x 35 cm) dünn einfetten und mit Mehl bestäuben. Teig an den Dosenkanten mit dem Spatel lockern und Dose auf das Blech stürzen, sodass der Teig langsam herausgleiten kann. Teig erst mit den Händen flach drücken, dann mit einer kleinen Teigrolle auf dem Backblech ausrollen.\r\n\r\nTeig mit Tomaten-Sugo bestreichen, Zucchini darauf verteilen, Fenchelmett zerzupfen und dazwischenstreuen. Käsemischung gleichmäßig daraufstreuen und Pizza auf der untersten Schiene 20–25 Minuten (250 °C) backen.\r\n\r\nBlech auf ein Gitter stellen und vor dem Anschneiden 5 Minuten abkühlen lassen. In dieser Zeit die Rauke verlesen, waschen und trocken schleudern. Pizza in Stücke schneiden, mit Rauke bestreuen und servieren.', '2018-10-29 10:42:02', 2, 2),
(9, 'Forelle aus dem Ofen mit Speckbohnen', 'uploaded_images/760631tkspmcohadrqnjvwizlfgeybux1540809801kbohnen.jpg', 'right', '2 Forellen, küchenfertig (à ca. 250 g)\r\n1 Knoblauchzehe\r\n1 Bio-Zitrone\r\nSalz\r\nPfeffer\r\n6 El Olivenöl\r\n6 Stiele Thymian\r\nFür die Speckbohnen\r\n400 g grüne Bohnen\r\nSalz\r\n0.5 Tl getrocknetes Bohnenkraut\r\n40 g durchwachsener Speck, in Scheiben\r\n1 Zwiebel\r\n2 El Öl\r\n0.5 El Butter\r\n4 Stiele glatte Petersilie\r\nPfeffer\r\n \r\nFür die Forelle aus dem Ofen\r\nForellen von innen und außen mit kaltem Wasser abspülen und mit Küchenpapier trocken tupfen. Die Fischhaut mehrmals schräg leicht einschneiden. Knoblauch und Zitrone in dünne Scheiben schneiden. Den Ofen auf 200 Grad vorheizen (Umluft nicht empfehlenswert).\r\n\r\nForellen von innen und außen mit Salz und Pfeffer würzen und mit je 2 El Öl einreiben. Fische auf ein mit Backpapier belegtes Blech legen. Bauchraum mit je 3 Stielen Thymian, Knoblauchscheiben und je 3 Zitronenscheiben füllen. Restliche Zitronenscheiben auf dem Blech verteilen. Forellen mit 2 El Öl beträufeln. Im heißen Ofen auf der mittleren Schiene 20 Min. garen.\r\n\r\nFür die Speckbohnen\r\nBohnen putzen, in kochendem Salzwasser mit dem Bohnenkraut zugedeckt bei mittlerer Hitze 10 Min. garen. Dann gut abtropfen lassen.\r\n\r\nSpeck in feine Streifen schneiden. Zwiebel fein würfeln. Speck im Öl in einer Pfanne kross ausbraten. Zwiebeln und Butter zugeben und 1 Min. weiterbraten. Petersilie abzupfen und hacken.\r\n\r\nAbgetropfte Bohnen zugeben und kurz erhitzen. Mit etwas Pfeffer würzen und mit Petersilie bestreut zu den Forellen aus dem Ofen servieren.', '2018-10-29 10:43:21', 4, 2),
(10, 'Fenchel aus dem Ofen', 'uploaded_images/194540itvdcobnfhgjreukmlapxqwzys15408099003092fc460.jpg', 'left', '3 Fenchelknollen\r\nSalz\r\n1 El Zitronensaft\r\n4 El Öl\r\n3 Stiele Petersilie\r\n6 Ziegenkäse, Taler\r\n2 El Honig\r\n \r\nZubereitung\r\nVon 3 Fenchelknollen die dicken grünen Stiele abschneiden. Das zarte Grün beiseitestellen. Fenchel längs vierteln und den dicken Strunk abschneiden.\r\n\r\nViertel in 2 cm breite Spalten schneiden. In einer Schüssel mit Salz und 1 El Zitronensaft würzen, mischen und 10 Minuten ziehen lassen.\r\n\r\nOfen auf 220 Grad vorheizen (Umluft nicht empfehlenswert). Fenchel mit 4 El Öl mischen und in eine flache ofenfeste Form (ca. 25 x 15 cm) geben. Im heißen Ofen auf dem Rost auf der mittleren Schiene 30 Minuten backen.\r\n\r\nInzwischen Fenchelgrün und die Blättchen von 3 Stielen Petersilie fein hacken. 4 Fenchel aus dem Ofen nehmen, Ofengrill (240 Grad) einschalten. Fenchel mit 6 Ziegenkäsetalern (200 g) belegen und alles mit 2 El Honig beträufeln. Unter dem heißen Ofengrill im oberen Ofendrittel 3-5 Minuten hellbraun überbacken. Mit Kräutern bestreuen und mit Baguette servieren.', '2018-10-29 10:45:00', 4, 2),
(11, 'Süßkartoffel-Limetten-Suppe', 'uploaded_images/798512vhkponltumxygfaejdqibwzcrs154080996990fdfafb.jpg', 'right', '1 Dose Kichererbsen\r\n0.5 Tl Currypulver\r\nSalz\r\n1 El Öl\r\nSuppe\r\n450 g Süßkartoffeln\r\n1 Knoblauchzehe\r\n4 El Olivenöl\r\n100 ml Portwein\r\n400 ml Gemüsefond\r\nSalz, Pfeffer\r\n2 Limetten\r\n400 ml Kokosmilch\r\nRelish\r\n1 Rote Bete\r\n6 g Ingwer\r\nFleur de sel\r\n0.5 Tl Orangenschale\r\n1 El Orange, Saft\r\n1 El Olivenöl\r\n \r\nZubereitung\r\nKichererbsen im Sieb abspülen und sehr gut abtropfen lassen (siehe Zutaten-Info). Kichererbsen sorgfältig mit Curry, 1/2 Tl Salz und Öl vermengen und auf ein mit Backpapier belegtes Backblech geben. Im vorgeheizten Backofen bei 180 Grad (Gas 2-3, Umluft 160 Grad) auf der 2. Schiene von unten 35-40 Minuten knusprig backen. Herausnehmen und abkühlen lassen.\r\nInzwischen für die Suppe Süßkartoffeln schälen und in 1-2 cm große Stücke schneiden. Zwiebeln in Würfel schneiden, Knoblauch grob hacken. 1 El Öl in einem Topf erhitzen. Zwiebeln und Knoblauch darin glasig dünsten, mit Portwein auffüllen und auf die Hälfte einkochen lassen. Fond zugießen, Kartoffeln zugeben und mit wenig Salz und Pfeffer würzen. Suppe bei mittlerer Hitze 15 Minuten kochen.\r\nInzwischen für das Relish Rote Bete schälen und in ca. 1 mm dünne Scheiben hobeln (mit Küchenhandschuhen arbeiten!), Scheiben in feine Streifen schneiden. Ingwer in feine Würfel schneiden. Rote Bete mit Ingwer, Orangenschale und -saft, und Öl 2-3 Minuten mit den Händen weich kneten (mit Küchenhandschuhen arbeiten!).\r\nFür die Suppe Limetten waschen, trocken tupfen, Schale fein abreiben und 4-5 El Saft auspressen. Kokosmilch und Limettenschale zur Suppe geben und kurz aufkochen lassen. Suppe mit dem Schneidstab sehr fein pürieren und mit Salz, Pfeffer und Limettensaft abschmecken.\r\nSuppe mit dem Schneidstab kurz aufmixen und in vorgewärmten Schalen anrichten. Mit je 1 El Kichererbsen und Relish garnieren und mit je 1/2 El Olivenöl beträufeln. Restliche Kichererbsen und restliches Relish dazu servieren.', '2018-10-29 10:46:09', 5, 2),
(12, 'Zubereitung der Paprika', 'uploaded_images/423943auozkfxcbevqnjghidlstrypmw15408100505c8b15.jpg', 'left', 'Gefüllte Paprika:\r\nEinen Deckel von der Frucht abschneiden und Kerne und Rippen herausschneiden. Restliche Kerne entweder heraus klopfen oder die Frucht kurz auswaschen.\r\n\r\nPaprikastücke schneiden:\r\nDen Stiel entfernen und die Paprika vierteln. Die Rippen und Kerne entfernen und die Paprika mit einem Sparschäler schälen. Das Fruchtfleisch je nach Rezept in Streifen, Würfel oder Rauten schneiden.\r\n\r\n\r\nPaprika grillen:\r\nDen Stiel abschneiden, die Paprika vierteln und Rippen und Kerne herausschneiden. Die Paprikastücke mit der Hautseite nach oben auf ein Backblech legen und gut 8 Minuten unter dem Grill rösten. Das Blech aus dem Ofen nehmen und kurz mit einem feuchten Geschirrtuch abdecken. Dann die Haut abziehen.', '2018-10-29 10:47:30', 6, 2),
(13, 'Fisch braten - so geht\'s', 'uploaded_images/617444lgoxfejraihwmbtdzuypvqcnsk1540810123984ababe6.jpg', 'left', 'Die Hautseite der Fischfilets mehrmals leicht einritzen, damit die Filets sich beim Braten nicht wölben. Etwas Öl in eine beschichtete gießen. Fisch auf der Fleischseite mit Zitronensaft beträufeln und salzen.\r\n\r\nZuerst von der Hautseite der Fischfilets anbraten. Die Fleischseite am Ende nur kurz anbraten. Rosmarinstiele, eine große Knoblauchzehe und ein Lorbeerblatt in die Pfanne geben. Pfanne schwenken. Etwas Butter hinzugeben und erneut schwenken. Einen Esslöffel nehmen und den Fisch mehrmals mit dem aromatisierten Fett übergießen. Nach etwa 5 Minuten ist das Fischfilet gar.', '2018-10-29 10:48:43', 6, 2),
(14, 'Gemüse-Tajine', 'uploaded_images/20455ctrjgemfbuwzhsakdnvpoxyilq1540810239bcb0ce.jpg', 'left', '100 g Zwiebeln\r\n2 Knoblauchzehen\r\n600 g Fenchelknollen\r\n2 rote Paprikaschoten, (ca. 400 g)\r\n300 g Möhren\r\n1 Dose Kichererbsen, (400 g)\r\n3 El Olivenöl\r\n100 g Cashewkerne\r\nSalz, Pfeffer\r\n2 Lorbeerblätter\r\n1 El Raz el Hanout, (am besten selbst gemacht, Rezept unten; sonst z. B. im türkischen Laden)\r\n1 Tl Piment d\'Espelette\r\nSaft von 1 Zitrone\r\n0.5 Bund glatte Petersilie\r\nJoghurt\r\n300 g Bio-Salatgurke\r\nSalz, Pfeffer\r\n3 El Olivenöl\r\n500 g Naturjoghurt, (am besten griechischer Joghurt)\r\n \r\nZubereitung\r\nZwiebeln in schmale Spalten schneiden, Knoblauch fein hacken. Fenchel putzen und längs in 8 Spalten schneiden. Paprika mit einem Sparschäler schälen, putzen, vierteln und die Kerne entfernen. Paprika in mittelgroße Stücke schneiden. Möhren schälen, längs halbieren, dickere Exemplare eventuell erneut längs halbieren. Kichererbsen in ein Sieb geben, kalt abspülen und gut abtropfen lassen.\r\n\r\nÖl in einem großen ofenfesten Topf langsam erhitzen. Zwiebeln, Knoblauch, Fenchel, Paprika und Cashewkerne zugeben. Mit Salz und Pfeffer würzen und bei milder Hitze andünsten. Lorbeer, Raz el Hanout und Piment d’Espelette zugeben und kurz dünsten. Kichererbsen unterrühren. Gemüse sternförmig mit Möhren belegen, Zitronensaft und 150 ml heißes Wasser zugießen. Tajine zudecken und im vorgeheizten Backofen bei 180 Grad (Gas 2-3, Umluft nicht empfehlenswert) 1 Stunde garen.\r\nInzwischen für den Joghurt Gurke waschen und ungeschält grob raspeln. Gurke, salz, Pfeffer und Olivenöl mit dem Joghurt verrühren.\r\n\r\nPetersilienblätter von den Stielen zupfen, waschen, trocken schleudern und grob schneiden. Sobald das Gemüse gar ist, Topf aus dem Ofen nehmen und das Gemüse mit Petersilie bestreuen. Gemüse-Tajine mit dem Gurkenjoghurt servieren. Dazu passt Fladenbrot.\r\n\r\nRaz-el-Hanout-Gewürzmischung: 1 El Kreuzkümmel,  1 Tl Koriandersaat, 1 Tl Fenchelsaat, 1 kleine getrocknete Chilischote, 1 Tl Kurkuma, 1⁄2 Tl getrocknete Rosenblätter und 2 Gewürznelken im Mörser fein zerstoßen. Gewürzmischung in ein Twist-off-Glas füllen, verschließen und lichtgeschützt aufbewahren. Mischung möglichst innerhalb von 4 Wochen verbrauchen.', '2018-10-29 10:50:39', 3, 2),
(15, 'Kürbiseintopf', 'uploaded_images/773339xcodibvalztfyksmphrjwnqueg15408962141b7d20.jpg', 'right', '180 g Staudensellerie\r\n180 g Lauch\r\n180 g Möhren\r\n200 g Zwiebeln\r\n2 Knoblauchzehen\r\n300 g grüne Bohnen\r\n500 g Butternusskürbis\r\n500 g Hokkaido-Kürbis\r\n1 Dose Kichererbsen, (250 g)\r\n2 Zweige Rosmarin\r\n5 El Olivenöl\r\n200 g Schmand\r\nSalz, Pfeffer\r\n1.5 l Gemüsefond\r\n1 Bund Bohnenkraut\r\n4 El Apfelessig\r\n2 El Honig\r\n\r\nZubereitung\r\nStaudensellerie waschen, putzen, entfädeln und sehr fein würfeln. Lauch waschen, das Weiße und Hellgrüne sehr fein würfeln. Möhren schälen und fein würfeln. Zwiebeln und Knoblauch fein würfeln. Bohnen putzen. Butternusskürbis schälen, Hokkaidokürbis sorgfältig waschen, beide Kürbisse mit einem Löffel entkernen und das Fruchtfleisch in ca. 2 cm große Würfel schneiden. Kichererbsen im Sieb abspülen und abtropfen lassen.\r\n\r\nRosmarinnadeln abzupfen und fein hacken. 1 El Öl in einer beschichteten Pfanne erhitzen, Rosmarin darin 2-3 Minuten andünsten. Abkühlen lassen, mit dem Schmand verrühren und mit Salz und Pfeffer würzen. Abgedeckt kalt stellen.\r\n\r\n2 El Öl in einem großen Topf erhitzen. Kürbiswürfel darin scharf braun anbraten und aus dem Topf nehmen. Restliches Öl in den Topf geben und Zwiebeln darin dünsten. Möhren zugeben und 1-2 Minuten mitdünsten. Knoblauch, Sellerie und Lauch zugeben und weich dünsten. Gemüsefond in einem kleinen Topf erhitzen. Bohnenkraut waschen und trocken tupfen.\r\n\r\nKürbis zurück in den Topf geben und kurz mitdünsten. Mit Essig ablöschen und mit Salz, Pfeffer und Honig würzen und mit heißem Gemüsefond auffüllen. \r\n\r\nKichererbsen und 3 Stiele Bohnenkraut zugeben und offen 25 Minuten garen. Inzwischen die Bohnen in kochendem Salzwasser 5 Minuten garen. In einem Sieb abgießen, abschrecken und abtropfen lassen.\r\n\r\nRestliche Bohnenkrautblätter von den Stielen zupfen und grob schneiden. Bohnen kurz vor Ende der Garzeit in den Eintopf geben, erneut mit Salz und Pfeffer abschmecken. Eintopf mit Bohnenkraut bestreuen und mit Rosmarinschmand servieren.', '2018-10-30 10:43:34', 2, 2);

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
(1, 'Desserts'),
(2, 'Hauptgerichte'),
(3, 'Vegetarisch'),
(4, 'Ofengerichte'),
(5, 'Suppen'),
(6, 'Tipps');

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
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT für Tabelle `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
