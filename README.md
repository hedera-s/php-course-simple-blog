learn_blog
======
Learn PHP Project

## Projektaufgabe:

Es soll ein rudimentäres Blog-System programmiert werden, das folgende Aufgaben erfüllen muss:

---

### Seite dashboard.php:

---

- Anlegen von neuen Kategorien (Formular – Input Text)

- Vor dem Anlegen einer neuen Kategorie muss geprüft werden, ob der Kategorie-Name bereits in der DB existiert

- Erstellen eines Blogeintrags (Formular):

  - Zuweisen einer Kategorie (Select-Box)

  - Verfassen einer Überschrift (Input Text)

  - Hochladen eines Bildes (Input File)

  - Festlegen der Ausrichtung des Bildes (rechts/links vom Text – Select-Box)

  - Verfassen eines Textes (Textarea)

  - Hochladen eines Bildes darf nicht zwingend erforderlich sein; es sollen also auch Texte ohne Bild eingestellt werden können

- Logout des/der Autoren (Link)



### Seite index.php:

---

- Login für den/die Blog-Autoren auf die gesicherte Editoren-Seite (Formular): Der Login soll über die Email-Adresse des Users erfolgen.

- Anzeigen aller vorhandener Blogeinträge, die jeweils folgendes beinhalten sollen (Standard bei Seitenaufruf, zusätzlich über Link):

  - Kategorie

  - Überschrift

  - Autor, Ort, Datum, Uhrzeit

  - Blogtext gff. mit Bild

- Anzeigen der Blogeinträge nach Kategorie (über Links)
