<?php
/*********************************************************************************************/


				/**********************************************************/
				/************** CONTROLLER FILE FÜR INDEX.PHP *************/
				/**********************************************************/
				
/**********************************************************************************************/

				/********************************************************/
				/****************** Session fortführen ******************/
				/********************************************************/
				
				// session_start() legt eine neue Session an, ODER führt eine bestehende Session fort
				// session_start() holt sich das Session-Cookie vom Browser und vergleicht, ob es eine 
				// passende Session dazu auf dem Server gibt. Falls ja, wird diese Session fortgeführt;
				// falls nein (Cookie existiert nicht/Session existiert nicht), wird eine neue Session angelegt
				
				session_name("blog_oop");
				session_start();
	
/**********************************************************************************************/	
				
				/********************************************************/
				/*********************** INCLUDES ***********************/
				/********************************************************/
				
				require_once("include/config.inc.php");
				require_once("include/db.inc.php");
				require_once("include/form.inc.php");
				require_once("include/datetime.inc.php");
				
				/********************************************************/
				/******************* INCLUDE CLASSES ********************/
				/********************************************************/
				require_once("Class/User.class.php");
				require_once("Class/Category.class.php");
				require_once("Class/Blog.class.php");
				
/**********************************************************************************/


				/********************************************************/
				/**************** START OUTPUT BUFFERING ****************/
				/********************************************************/
								
				if( !ob_start() ) {
					// Fehlerfall
if(DEBUG)			echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Output Buffering konnte nicht aktiviert werden! <i>(" . basename(__FILE__) . ")</i></p>";								
				} else {
					// Erfolgsfall
if(DEBUG)			echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Output Buffering ist aktiviert. <i>(" . basename(__FILE__) . ")</i></p>";								
				}
					
/**********************************************************************************************/

				/********************************************************/
				/************** VARIABLEN INITIALIZIEREN ****************/
				/********************************************************/
				
				
				$loginMessage 		= NULL;
				$categoryToShow_id 	= NULL;
				$categoriesArray 	= array();
				
				$user = new User();
				$user->setUsr_id($_SESSION['usr_id']); 
				$user->setUsr_firstname($_SESSION['usr_firstname']);
				$user->setUsr_lastname($_SESSION['usr_lastname']);
				
if(DEBUG)		echo "<pre class='debug'>";
if(DEBUG)		print_r($user);
if(DEBUG)		echo "</pre>";	
	
/**********************************************************************************************/	

				/********************************************************/
				/*********** Datenbankverbindung herstellen: ************/
				/********************************************************/
						
				$pdo = dbConnect();

/**********************************************************************************************/

				/********************************************************/
				/******************* LOGIN-FORMULAR *********************/
				/********************************************************/
				
				/************ Daten aus Formular auslesen ***************/
				
				if(isset($_POST['formsentLogin'])){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Formular 'formsentLogin' wurde abgeschickt. <i>(" . basename(__FILE__) . ")</i></p>";	
					
					$user = new User($_POST['email']);					
					$passwordForm = cleanString($_POST['password']);
					
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: passwordForm: $passwordForm<i>(" . basename(__FILE__) . ")</i></p>";								

					$errorEmail 	= checkEmail($user->getUsr_email());
					$errorPasswort 	= checkInputString($passwordForm,4);
					
					// Abschließende Formularprüfung: 
					if($errorEmail || $errorPasswort){
						
						//Fehlerfall:
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Logindaten sind ungültig<i>(" . basename(__FILE__) . ")</i></p>";		
						$loginMessage = "Benutzername oder Passwort falsch!";
					
					} else {
						//Erfolgsfall:
if(DEBUG) 				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Formular ist korrekt ausgefüllt. Daten werden nun weiterverarbeitet... <i>(" . basename(__FILE__) . ")</i></p>";		

						
				/*********************************************************/
				/********************** DB-OPERATION *********************/
				/*********************************************************/
						
				/********************* EMAIL PRÜFEN **********************/

						// Prüfen, ob ein Datensatz geliefert wurde
						// Wenn Datensatz geliefert wurde, muss die Email-Adresse stimmen
						
						if( !$user->fetchFromDb($pdo) ){
							// Fehlerfall:
if(DEBUG) 					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Email ".$user->getUsr_email() ." existiert nicht in der DB! <i>(" . basename(__FILE__) . ")</i></p>";
							$loginMessage = "Benutzername oder Passwort falsch!";			
							
						}else{
							// Erfolgsfall:
if(DEBUG) 					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: User mit Email ".$user->getUsr_email()." wurde in der DB gefunden. <i>(" . basename(__FILE__) . ")</i></p>";	

							
				/******************* PASSWORT PRÜFEN *******************/
							
							if(!password_verify($passwordForm, $user->getUsr_password())){
								// Fehlerfall: PW wurde nicht gefunden
if(DEBUG) 						echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Passwort stimmt nicht! <i>(" . basename(__FILE__) . ")</i></p>";								
								$loginMessage = "Benutzername oder Passwort falsch!";	
																
							}else{
								// Erfolgsfall: PW wurde gefunden
if(DEBUG) 						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Passwort stimmt mit Passwort aus DB überein <i>(" . basename(__FILE__) . ")</i></p>";									
								
								
				/*********************************************************/
				/*************** Session starten und Daten ***************/
				/***************** in Session schreiben ******************/
				/*********************************************************/
								
								// Userdaten in Session screiben:
								
								$_SESSION['usr_id'] 		= $user->getUsr_id();
								$_SESSION['usr_firstname'] 	= $user->getUsr_firstname();
								$_SESSION['usr_lastname']	= $user->getUsr_lastname();
																
								
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
				
				/********************************************************/
				/************** URL-Parameterverarbeitung ***************/
				/********************************************************/
				
				if(isset($_GET['action'])){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: URL-Parameter 'action' wurde übergeben<i>(" . basename(__FILE__) . ")</i></p>";
	
					$action = cleanString($_GET['action']);
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: action: $action'<i>(" . basename(__FILE__) . ")</i></p>";
					
					
				/********************************************************/
				/********************** LOGOUT **************************/
				
					if($action == "logout"){
if(DEBUG)				echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Logout wird durchgeführt: action = $action'<i>(" . basename(__FILE__) . ")</i></p>";	
						
						
						// Session löschen:
						session_destroy();
						
						// Die Seite neuladen:
						header("Location: index.php");
						exit;
						
					
				/********************* LOGOUT ENDE **********************/	
				/********************************************************/
					
					
				/********************************************************/
				/************ Beiträge bestimmter Kategorie *************/
				/********************* auslesen *************************/
				/********************************************************/
										
					} elseif($action == "showCategory"){
						echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: showCategory wird durchgeführt...<i>(" . basename(__FILE__) . ")</i></p>";
						
						// Prüfen, ob und welche Kategorie ausgewählt/übergeben wurde:
						if(isset($_GET['categoryToShow'])){
if(DEBUG)					echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL-Parameter 'categoryToShow' wurde übergeben<i>(" . basename(__FILE__) . ")</i></p>";
							$categoryToShow_id = cleanString($_GET['categoryToShow']);
if(DEBUG)					echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: categoryToShow_id: $categoryToShow_id'<i>(" . basename(__FILE__) . ")</i></p>";
							
							// Wenn eine bestimmte Kategorie ausgewählt wurde,
							// ein neues Category-Objekt mit dem ID anlegen:
							
														

							

						} // Prüfen, welche Kategorie ausgewählt wurde - Ende

					} // URL-SchowCategory auslesen - Ende
						
				} // URL-Parameterverarbeitung - Ende 
					
				/********************************************************/
				/*********** Kategorienliste aus DB auslesen  ***********/
				/********************************************************/
				
				$categoriesArray = Category::fetchFromDb($pdo);
			

				/********************************************************/
				/********** Blogbeiträge aus DB auslesen  ***************/
				/********************************************************/
				
				// Wenn URL-Parameter Catgory To Show übergeben wurde,
				// Nur die Beiträge dieser Kategorie anzeigen
				
				$blogsArray = ($categoryToShow_id) ? Blog::fetchFromDb($pdo, $categoryToShow_id) : Blog::fetchFromDb($pdo);
				 
				
				
/**********************************************************************************************/	

			
?>