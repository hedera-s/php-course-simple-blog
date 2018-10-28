<?php
/**********************************************************************************************/
				/***********************************************/
				/************* Session fortführen **************/
				/***********************************************/
				
				// session_start() legt eine neue Session an, ODER führt eine bestehende Session fort
				// session_start() holt sich das Session-Cookie vom Browser und vergleicht, ob es eine 
				// passende Session dazu auf dem Server gibt. Falls ja, wird diese Session fortgeführt;
				// falls nein (Cookie existiert nicht/Session existiert nicht), wird eine neue Session angelegt
				
				session_name("blog");
				session_start();
	
/**********************************************************************************************/	
				
				
				/*********************************/
				/********** INCLUDES *************/
				/*********************************/
				
				require_once("include/config.inc.php");
				require_once("include/db.inc.php");
				require_once("include/form.inc.php");
					
					
/**********************************************************************************************/
				/*******************************************/
				/******** VARIABLEN INITIALIZIEREN *********/
				/*******************************************/

				$loginMessage = NULL;
				$categoryToShow = NULL;
				$params = NULL;

	
/**********************************************************************************************/	
				
				/***** Datenbankverbindung herstellen: *****/
						
				$pdo = dbConnect();

/**********************************************************************************************/

				/********************************************************/
				/******************* LOGIN-FORMULAR *********************/
				/********************************************************/
				
				/************ Daten aus Formular auslesen ***************/
				
				// 1 FORM: Prüfen, ob Formular abgechickt wurde:
				if(isset($_POST['formsentLogin'])){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Formular 'formsentLogin' wurde abgeschickt. <i>(" . basename(__FILE__) . ")</i></p>";	
				
					// 2 FORM: Werte auslesen, entshärfen
					$email = cleanString($_POST['email']);
					$password = cleanString($_POST['password']);
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: email: $email<i>(" . basename(__FILE__) . ")</i></p>";								
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: password: $password<i>(" . basename(__FILE__) . ")</i></p>";		
					
					// 3 FORM: Werte Validierung
					$errorEmail = checkEmail($email);
					$errorPasswort = checkInputString($password);
					
					// Abschließende Formularprüfung
					if($errorEmail || $errorPasswort){
						//Fehler
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Logindaten sind ungültig<i>(" . basename(__FILE__) . ")</i></p>";		
						$loginMessage = "Benutzername oder Passwort falsch!";
					} else {
						//Erfolg
if(DEBUG) 				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Formular ist korrekt ausgefüllt. Daten werden nun verarbeitet... <i>(" . basename(__FILE__) . ")</i></p>";		
						
						// 4 FORM: Daten weiterverarbeiten
						
						/************* DATENBANKOPERATION ****************/
											
						
						
						/****************** DATENSATZ ZUM EMAIL AUSLESEN *******************/
						
						//2 DB: SQL-Statement vorbereiten:
						$statement = $pdo->prepare("SELECT usr_email, usr_password, usr_id, usr_firstname 
													FROM users 
													WHERE usr_email = :ph_usr_email
													");
													
						//3 DB: SQL-Statement ausführen:
						$statement->execute( array(
											"ph_usr_email" => $email
											)) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>"); 
						
						//4 DB: Datenweiterverarbeiten:
						$row = $statement->fetch(PDO::FETCH_ASSOC);
						
						
						/********** EMAIL PRÜFEN **********/

						// Prüfen, ob ein Datensatz geliefert wurde
						// Wenn Datensatz geliefert wurde, muss die Email-Adresse stimmen
						
						if( !$row ){
							// Fehlerfall:
if(DEBUG) 					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Email $email existiert nicht in der DB! <i>(" . basename(__FILE__) . ")</i></p>";
							$loginMessage = "Benutzername oder Passwort falsch!";			
							
						}else{
							// Erfolgsfall:
if(DEBUG) 					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: User mit Email $email wurde in der DB gefunden. <i>(" . basename(__FILE__) . ")</i></p>";	
if(DEBUG) 					echo "<pre class='debug'>";
if(DEBUG) 					print_r($row);
if(DEBUG) 					echo "</pre>";
							
							/**************** PASSWORT PRÜFEN ****************/
							
							if(!password_verify($password, $row['usr_password'])){
								// Fehlerfall: PW wurde nicht gefunden
if(DEBUG) 						echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Passwort stimmn nicht! <i>(" . basename(__FILE__) . ")</i></p>";								
								$loginMessage = "Benutzername oder Passwort falsch!";	
																
							}else{
								// Erfolgsfall: PW wurde gefunden
if(DEBUG) 						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Passwort stimmt mit Passwort aus DB überein <i>(" . basename(__FILE__) . ")</i></p>";									
								
								
								/********************************************/
								/******** Session starten und Daten *********/
								/*********** in Session schreiben ***********/
								/********************************************/
								
								session_name("blog");
								session_start();
								
								$_SESSION['usr_id'] = $row['usr_id'];
								$_SESSION['usr_firstname'] = $row['usr_firstname'];
																
								
if(DEBUG)						echo "<pre class='debug'>";
if(DEBUG)						print_r($_SESSION);
if(DEBUG)						echo "</pre>";								
								
								// Automatische Weiterleitung auf die Seite "dashboard.php" :
								header("Location: dashboard.php");
								exit;
								
								
								
							} // PW Prüfung - Ende
							
						} // Datensatz mit Email geliefert wurde - Ende
							
					} // Prüfen, ob Datensatz mit Email existiert - Ende
					
				} // Prüfung, ob das Formular fehlerfrei ist  - Ende

				
/**********************************************************************************************/	
				
				/************************************************/
				/********* URL-Parameterverarbeitung ************/
				/************************************************/
				
				//1 URL: Prüfen, ob Parameter übergeben wurde:
				if(isset($_GET['action'])){
if(DEBUG)			echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL-Parameter 'action' wurde übergeben<i>(" . basename(__FILE__) . ")</i></p>";
	
					//2 URL: Auslesen, entschärfen
					$action = cleanString($_GET['action']);
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: action: $action'<i>(" . basename(__FILE__) . ")</i></p>";
					
					
				/*************************************************/
				/***************** LOGOUT ************************/
				
					// 3 URL: Verzweigen
					if($action == "logout"){
if(DEBUG)				echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Logout wird durchgeführt: action = $action'<i>(" . basename(__FILE__) . ")</i></p>";	
						
						//4 URL: Daten weiterverarbeiten
						
						// Session löschen:
						session_destroy();
						
						// Die Seite neuladen:
						header("Location: index.php");
						exit;
										
					}

				
				/***************** LOGOUT ENDE *******************/	
				/*************************************************/


				/*************************************************/
				/*********** URL-SchowCategory auslesen **********/
				/*************************************************/

					if($action == "showCategory"){
if(DEBUG)				echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: SchowCategory wird durchgeführt: action = $action'<i>(" . basename(__FILE__) . ")</i></p>";
						if(isset($_GET['categoryToShow'])){
if(DEBUG)					echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL-Parameter 'categoryToShow' wurde übergeben<i>(" . basename(__FILE__) . ")</i></p>";
							$categoryToShow = cleanString($_GET['categoryToShow']);
if(DEBUG)					echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: categoryToShow: $categoryToShow'<i>(" . basename(__FILE__) . ")</i></p>";
							


						}
					}
				}
				
				
/**********************************************************************************************/	
				/************************************************/
				/******* Kategorienliste aus DB auslesen  *******/
				/************************************************/
				
				// 2. DB: SQL-Statement Vorbereiten
				$statement = $pdo->prepare("SELECT cat_name, cat_id FROM categories");
				
				// 3. DB: SQL-Statement ausführen und Platzhalter füllen
				$statement->execute() OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" ); 

				// 4 DB:  Daten weiterverarbeiten
				// Bei SELECT: Datensätze auslesen
				// fetchAll liefert zweidimensionales Array zurück, 
				// das ALLE Datensätze beinhaltet
				
				while($row = $statement->fetch(PDO::FETCH_ASSOC)){
					$categoriesArray[$row['cat_id']] = $row['cat_name'];
				}

				

				/************************************************/
				/******* Blogbeiträge aus DB auslesen  **********/
				/************************************************/
				$sql = "SELECT * FROM blogs
											INNER JOIN users USING(usr_id)
											INNER JOIN categories USING(cat_id)
											";

				if($categoryToShow){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: categoryToShow wurde übergeben: $categoryToShow'<i>(" . basename(__FILE__) . ")</i></p>";		
					$statement = $pdo->prepare("SELECT * FROM categories WHERE cat_id = :ph_cat_id");
					$statement->execute( array("ph_cat_id" => $categoryToShow)
										) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" );
					$categoryExists = $statement->fetchColumn();
					
					if(!$categoryExists){
						// Fehlerfall:
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Kategorie existiert in DB nicht <i>(" . basename(__FILE__) . ")</i></p>";	
													
					} else {
						// Erfolgsfall:
if(DEBUG)				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Kategorie existiert in DB<i>(" . basename(__FILE__) . ")</i></p>";	
						
						$sql .= " WHERE cat_id = :ph_cat_id"; 
						$params = array("ph_cat_id" => $categoryToShow);							

					}
				}

				// 2. DB: SQL-Statement Vorbereiten
				$statement = $pdo->prepare($sql);
				
				// 3. DB: SQL-Statement ausführen und Platzhalter füllen
				$statement->execute($params) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" ); 

				// 4 DB:  Daten weiterverarbeiten
				// Bei SELECT: Datensätze auslesen
				// fetchAll liefert zweidimensionales Array zurück, 
				// das ALLE Datensätze beinhaltet
				
				$entriesArray = $statement->fetchAll(PDO::FETCH_ASSOC);







/**********************************************************************************************/	
?>
<!doctype html>

<html>
<head>
		<meta charset="utf-8">
		<title>Blog über Essen</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/debug.css">
		<link href="https://fonts.googleapis.com/css?family=Dancing+Script|Roboto:300" rel="stylesheet">
	</head>

	<body>
		<header>
			<?php if(isset($_SESSION['usr_id'])):?>
				<div class="hello">	
					<p>Hallo, <?=$_SESSION['usr_firstname']?>!  |  <a href="?action=logout">Logout</a></p>
					<p><a href="dashboard.php">Zum Dashboard >></a></p>
				</div>
			<?php endif?>
			
			
			<span class="error"><?=$loginMessage?></span>
			<?php if(!isset($_SESSION['usr_id'])):?>
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST" class="login">
					<input type="hidden" name="formsentLogin">
					<input type="text" name="email" placeholder="Email">
					<input type="password" name="password" placeholder="Passwort">
					<input type="submit" value="Anmelden">
				</form>
			<?php endif ?>

		
		</header>
		<div class="wrapper">
			
			
			<main class="">
				<div class="blog-headline">
					<h1>Blog über Essen</h1>
					<h2>(und Trinken)</h2>
				</div>
				<!--
					$entriesArray enthält ein zweidimensionales Array. Jedes darin 
					enthaltene Array entspricht einem Datensatz aus der DB.
					Je Schleifendurchlauf enthält $dataset einen anderen Datensatz in Form 
					eines eindimensionalen Arrays, dessen Indizes den Namen der Spalten in 
					der Tabelle 'products' entsprechen.
				-->
				<?php foreach ($entriesArray AS $entry): ?>
					<article>
						
							<ul class="category-list">
								<li><?=$entry['cat_name']?></li>
							</ul>
						
						<p class="whowrote"><?=$entry['usr_firstname']?> <?=$entry['usr_lastname']?> aus <?=$entry['usr_city']?> shrieb am <?=$entry['blog_date']?>:</p>
						<h3 class="headline"><?=$entry['blog_headline']?></h3>
						<?php if($entry['blog_image']): ?>
							<img src="<?=$entry['blog_image']?>" class="<?=$entry['blog_imageAlignment']?> article-image" />
						<?php endif ?>
						<p class="content"><?=$entry['blog_content']?></p>
						<div class="clear"></div>

					</article>
					<br>
				<?php endforeach ?>
			</main>

			<aside>
				<p class="categories-header">Kategorien:</p>
				<ul>
					<li><a href="<?=$_SERVER['SCRIPT_NAME']?>">Alle Kategorien</a></li><br>
					<?php foreach ($categoriesArray AS $key=>$value): ?>
						<li><a href="?action=showCategory&categoryToShow=<?=$key?>"><?=$value?></a></li>
					<?php endforeach ?>
				</ul>
			</aside>
			<div class="clear"></div>
		</div>
		<footer>
			<p>Copyright Irina Serdiuk</p>
		</footer>

</body>

</html>