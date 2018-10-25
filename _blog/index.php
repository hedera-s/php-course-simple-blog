<?php
/**********************************************************************************************/	

				
				/********************************/
				/********* INCLUDES *************/
				/********************************/
				
				// include(Pfad zur Datei): Bei Fehler wird das Skript weiter ausgeführt. Problem mit doppelter Einbindung derselben Datei
				// require(Pfad zur Datei): Bei Fehler wird das Skript gestoppt. Problem mit doppelter Einbindung derselben Datei
				// include_once(Pfad zur Datei): Bei Fehler wird das Skript weiter ausgeführt. Kein Problem mit doppelter Einbindung derselben Datei
				// require_once(Pfad zur Datei): Bei Fehler wird das Skript gestoppt. Kein Problem mit doppelter Einbindung derselben Datei
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
						$loginMessage = "<p class='error'>Benutzername oder Passwort falsch!</p>";
					} else {
						//Erfolg
if(DEBUG) 				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Formular ist korrekt ausgefüllt. Daten werden nun verarbeitet... <i>(" . basename(__FILE__) . ")</i></p>";		
						
						// 4 FORM: Daten weiterverarbeiten
						
						/************* DATENBANKOPERATION ****************/
											
							//1 DB: Verbindung herstellen
							
							$pdo = dbConnect();
							
							/******************DATENSATZ ZUM EMAIL AUSLESEN *******************/
							
							//2 DB: SQL-Statement vorbereiten
							$statement = $pdo->prepare("SELECT usr_email, usr_password, usr_id, usr_city, usr_firstname, usr_lastname 
														FROM users 
														WHERE usr_email = :ph_usr_email
														");
														
							//3 DB: SQL-Statement ausführen
							$statement->execute( array(
												"ph_usr_email" => $email
												)) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>"); 
							
							//4 DB: Datenweiterverarbeiten
							$row = $statement->fetch();
							echo "<pre>";
							print_r($row);
							echo "</pre>";
							
							/********** EMAIL PRÜFEN **********/

							// Prüfen, ob ein Datensatz geliefert wurde
							// Wenn Datensatz geliefert wurde, muss die Email-Adresse stimmen
							
							if( !$row ){
								// Fehler
if(DEBUG) 						echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Email $email existiert nicht in der DB! <i>(" . basename(__FILE__) . ")</i></p>";
								
								
								
							}
							
						}
					
					}

				
/**********************************************************************************************/	
?>
<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>Blog über Essen</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="css/debug.css">
	</head>

	<body>
		<header>
			<?=$loginMessage?>
			<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
				<input type="hidden" name="formsentLogin">
				<input type="text" name="email" placeholder="Email">
				<input type="password" name="password" placeholder="Passwort">
				<input type="submit" value="Anmelden">
			</form>
		</header>
	
		<h1>Blog über Essen</h1>


</body>

</html>