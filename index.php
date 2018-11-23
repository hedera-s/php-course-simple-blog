<?php
/**********************************************************************************************/

				/********************************************************/
				/****************** Session fortführen ******************/
				/********************************************************/
				
				// session_start() legt eine neue Session an, ODER führt eine bestehende Session fort
				// session_start() holt sich das Session-Cookie vom Browser und vergleicht, ob es eine 
				// passende Session dazu auf dem Server gibt. Falls ja, wird diese Session fortgeführt;
				// falls nein (Cookie existiert nicht/Session existiert nicht), wird eine neue Session angelegt
				
				//session_name("blog_oop");
				//session_start();
	
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


				/********************************************/
				/********** START OUTPUT BUFFERING **********/
				/********************************************/
								
				if( !ob_start() ) {
					// Fehlerfall
if(DEBUG)		echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Output Buffering konnte nicht aktiviert werden! <i>(" . basename(__FILE__) . ")</i></p>";								
				} else {
					// Erfolgsfall
if(DEBUG)		echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Output Buffering ist aktiviert. <i>(" . basename(__FILE__) . ")</i></p>";								
				}
					
/**********************************************************************************************/

				/********************************************************/
				/************** VARIABLEN INITIALIZIEREN ****************/
				/********************************************************/
				
				$user = new User();
				
				$loginMessage 		= NULL;
				$categoryToShow 	= NULL;
				$params 			= array();
				$categoriesArray 	= array();
				$date 				= NULL;
				$time 				= NULL;

	
/**********************************************************************************************/	

				/********************************************************/
				/*********** Datenbankverbindung herstellen: ************/
				/********************************************************/
						
				$pdo = dbConnect();

/**********************************************************************************************/
				/********************************************************/
				/*********** Kategorienliste aus DB auslesen  ***********/
				/********************************************************/
				
				$categoriesArray = Category::fetchAllCategoriesFromDb($pdo);
			

				/********************************************************/
				/******** Alle Blogbeiträge aus DB auslesen  ************/
				/********************************************************/
				
				$entriesArray = Blog::fetchAllEntriesFromDb($pdo);

				/********************************************************/
				/******************* LOGIN-FORMULAR *********************/
				/********************************************************/
				
				/************ Daten aus Formular auslesen ***************/
				
				if(isset($_POST['formsentLogin'])){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Formular 'formsentLogin' wurde abgeschickt. <i>(" . basename(__FILE__) . ")</i></p>";	
					
					$user->setUsr_email($_POST['email']);
					$user->setUsr_password($_POST['password']);
					
					$passwordForm = cleanString($_POST['password']);
					
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: passwordForm: $passwordForm<i>(" . basename(__FILE__) . ")</i></p>";								

					
					$errorEmail 	= checkEmail($user->getUsr_email());
					$errorPasswort 	= checkInputString($user->getUsr_password(),4);
					
					// Abschließende Formularprüfung: 
					if($errorEmail || $errorPasswort){
						
						//Fehlerfall:
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Logindaten sind ungültig<i>(" . basename(__FILE__) . ")</i></p>";		
						$loginMessage = "Benutzername oder Passwort falsch!";
					
					} else {
						//Erfolgsfall:
if(DEBUG) 				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Formular ist korrekt ausgefüllt. Daten werden nun weiterverarbeitet... <i>(" . basename(__FILE__) . ")</i></p>";		

						
						/********************************************************/
						/********************* DB-OPERATION *********************/
						/********************************************************/
												
						
						
						
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
								
								
								/****************************************************/
								/************ Session starten und Daten *************/
								/*************** in Session schreiben ***************/
								/****************************************************/
								
								session_name("blog_oop");
								if(!session_start()){
									// Fehlerfall:
if(DEBUG)							echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER beim Starten der Session! <i>(" . basename(__FILE__) . ")</i></p>";				
										$loginMessage = "<p class='error'>Es ist ein Fehler aufgetreten! Bitte versuchen Sie es später noch einmal.</p>";									
								}else{
									// Erfolgsfall:
if(DEBUG)							echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Session '" . session_name() . "' erfolgreich gestartet. <i>(" . basename(__FILE__) . ")</i></p>";									
									
									// Userdaten in Session screiben:
									
									$_SESSION['usr_id'] 		= $user->getUsr_id();
									$_SESSION['usr_firstname'] 	= $user->getUsr_firstname();
									$_SESSION['usr_lastname']	= $user->getUsr_lastname();
																	
									
if(DEBUG)							echo "<pre class='debug'>";
if(DEBUG)							print_r($_SESSION);
if(DEBUG)							echo "</pre>";								
									
									// Automatische Weiterleitung auf die Seite "dashboard.php" :
									//header("Location: dashboard.php");
									//exit;
									
								
								} // Session starten - Ende
								
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
							
							$categoryToShow = new Category($categoryToShow_id);
							
							// Wenn eine bestimmte Kategorie ausgewählt wurde, 
							// prüfen, ob sie in DB existiert:
							if(!$categoryToShow->categoryExists($pdo)){
								// Fehlerfall: Kategorie existiert in DB nicht
if(DEBUG)						echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Kategorie ".$categoryToShow->getCat_id()." existiert in DB nicht <i>(" . basename(__FILE__) . ")</i></p>";	
																
							} else {
								// Erfolgsfall: Kategorie existiert in DB
if(DEBUG)						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Kategorie ".$categoryToShow->getCat_id()." existiert in DB<i>(" . basename(__FILE__) . ")</i></p>";	
								$entriesArray = Blog::fetchAllEntriesFromDb($pdo, $categoryToShow->getCat_id());	
														

							} // Prüfen, ob kategorie existiert - Ende
							
							
						} // Prüfen, welche Kategorie ausgewählt wurde - Ende

					} // URL-SchowCategory auslesen - Ende
						
				} // URL-Parameterverarbeitung - Ende 
					
				 
				
				
/**********************************************************************************************/	

				
	
	
				
					
				
				
/*					
				
				$statement = $pdo->prepare($sql);
				$statement->execute($params) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" ); 

				// $entriesArray ist ein zweidimensionales Array, 
				// das ALLE Datensätze beinhaltet
				
				$entriesArray = $statement->fetchAll(PDO::FETCH_ASSOC);



*/


/**********************************************************************************************/	
if(DEBUG)	echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>";					
if(DEBUG)	print_r($entriesArray);					
if(DEBUG)	echo "</pre>";

?>
<!doctype html>

<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Blog über Essen</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/debug.css">
		<link href="https://fonts.googleapis.com/css?family=Dancing+Script|Roboto:300" rel="stylesheet">
	</head>

	<body>
		<header>
		
			<!------- Begrüßung und Logout-Link --------->
			
			<?php if(isset($_SESSION['usr_id'])):?>
				<div class="hello">	
					<p>Hallo, <?=$_SESSION['usr_firstname']?>!  |  <a href="?action=logout">Logout</a></p>
					<p><a href="dashboard.php">Zum Dashboard >></a></p>
				</div>
			<?php endif?>
			
			
			<!--------------- Login-Form ----------------->
			
			
			<?php if(!isset($_SESSION['usr_id'])):?>
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST" class="login clear">
					<span class="error login-message"><?=$loginMessage?></span><input type="hidden" name="formsentLogin">
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
					Je Schleifendurchlauf enthält $entryObject einen anderen Datensatz in Form 
					eines Objekts, dessen Indizes den Namen der Spalten in 
					der Tabellen entsprechen.
				-->
				<?php foreach ($entriesArray AS $entryObject): ?>
					<article>
						
							<ul class="category-list">
								<li><?=$entryObject->getCategory()->getCat_name()?></li>
							</ul>
						
						<?php 
							// Convertierung in EU Datum und Zeit
							$dateTime = isoToEuDateTime($entryObject->getBlog_date()); 
					
						?>
						<!--
						<p class="whowrote">
							<?=$entryObject['usr_firstname']?> <?=$entryObject['usr_lastname']?> 
							aus <?=$entryObject['usr_city']?> schrieb am <?=$dateTime['date']?> um <?=$dateTime['time']?> Uhr:
						</p>
						<h3 class="headline"><?=$entryObject['blog_headline']?></h3>
						<?php if($entryObject['blog_image']): ?>
							<img src="<?=$entryObject['blog_image']?>" class="<?=$entryObject['blog_imageAlignment']?> article-image" />
						<?php endif ?>
						<?php $entryObject['blog_content'] = str_replace("\r\n", "<br>", $entryObject['blog_content']) ?>
						<p class="content"><?=$entryObject['blog_content']?></p>
						<div class="clear"></div>
						-->
					</article>
					
					<br>
				<?php endforeach ?>
			</main>
			
			
			<!--------------- Kategorienliste ----------------->
			
			<aside>
				<p class="categories-header">Kategorien:</p>
				<ul>
					<li><a href="<?=$_SERVER['SCRIPT_NAME']?>">Alle Kategorien</a></li><br>
					<?php 
						
						foreach ($categoriesArray AS $categoryObject){
												
							if($categoryToShow == $categoryObject->getCat_id()){
								echo "<li class='selected-category'>
										<a href='?action=showCategory&categoryToShow=".$categoryObject->getCat_id()."'>
											".$categoryObject->getCat_name()."
										</a>
									</li>";
							}else{
								echo "<li>
										<a href='?action=showCategory&categoryToShow=".$categoryObject->getCat_id()."'>
											".$categoryObject->getCat_name()."
										</a>
									</li>";
							}
						}
					?>	
			
				</ul>
			</aside>
			
			
			<div class="clear"></div>
		</div>
		<footer>
			<p>Copyright Irina Serdiuk</p>
		</footer>

	</body>

</html>