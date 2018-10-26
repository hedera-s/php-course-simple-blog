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
											
						//1 DB: Verbindung herstellen:
						
						$pdo = dbConnect();
						
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
				
				}
				
				
/**********************************************************************************************/	

				/************************************************/
				/******* Blogbeiträge aus DB auslesen  **********/
				/************************************************/
				
				// 2. DB: SQL-Statement Vorbereiten
			/*	$statement = $pdo->prepare("SELECT * FROM blogs
											INNER JOIN users USING(usr_id)
											INNER JOIN categories USING(cat_id)
											WHERE usr_id = :ph_usr_id
											");
				
				// 3DB. SQL-Statement ausführen und Platzhalter füllen
				$statement->execute( array(
								"ph_accountname" 	=> $accountname,
								"ph_password" 		=> $passwordHash,
								"ph_regHash" 		=> $regHash,
								"ph_newUserId" 		=> $newUserId
								)) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" ); */

/**********************************************************************************************/	
?>
<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>Blog über Essen</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/debug.css">
	</head>

	<body>
		<header>
			
			<?php if(isset($_SESSION['usr_id'])):?>
				<p>Hallo, <?=$_SESSION['usr_firstname']?>!  |  <a href="?action=logout">Logout</a></p>
				<p><a href="dashboard.php">Zum Dashboard >></a></p>
			<?php endif?>
			
			
			<span class="error"><?=$loginMessage?></span>
			<?php if(!isset($_SESSION['usr_id'])):?>
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
					<input type="hidden" name="formsentLogin">
					<input type="text" name="email" placeholder="Email">
					<input type="password" name="password" placeholder="Passwort">
					<input type="submit" value="Anmelden">
				</form>
			<?php endif ?>
		
		</header>
	
		<h1>Blog über Essen</h1>
		<h3>(und Trinken)</h3>
		
		


</body>

</html>