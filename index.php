<?php
/**********************************************************************************************/

				/********************************************************/
				/****************** Session fortführen ******************/
				/********************************************************/
				
				// session_start() legt eine neue Session an, ODER führt eine bestehende Session fort
				// session_start() holt sich das Session-Cookie vom Browser und vergleicht, ob es eine 
				// passende Session dazu auf dem Server gibt. Falls ja, wird diese Session fortgeführt;
				// falls nein (Cookie existiert nicht/Session existiert nicht), wird eine neue Session angelegt
				
				session_name("blog");
				session_start();
	
/**********************************************************************************************/	
				
				/********************************************************/
				/*********************** INCLUDES ***********************/
				/********************************************************/
				
				require_once("include/config.inc.php");
				require_once("include/db.inc.php");
				require_once("include/form.inc.php");
				require_once("include/datetime.inc.php");
					
					
/**********************************************************************************************/

				/********************************************************/
				/************** VARIABLEN INITIALIZIEREN ****************/
				/********************************************************/

				$loginMessage 		= NULL;
				$categoryToShow 	= NULL;
				$entryToDelete 		= NULL;
				$entryToEdit 		= NULL;
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
				/******************* LOGIN-FORMULAR *********************/
				/********************************************************/
				
				/************ Daten aus Formular auslesen ***************/
				
				if(isset($_POST['formsentLogin'])){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Formular 'formsentLogin' wurde abgeschickt. <i>(" . basename(__FILE__) . ")</i></p>";	
				
					$email 		= cleanString($_POST['email']);
					$password 	= cleanString($_POST['password']);
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: email: $email<i>(" . basename(__FILE__) . ")</i></p>";								
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: password: $password<i>(" . basename(__FILE__) . ")</i></p>";		
					
					$errorEmail 	= checkEmail($email);
					$errorPasswort 	= checkInputString($password);
					
					// Abschließende Formularprüfung: 
					if($errorEmail || $errorPasswort){
						
						//Fehlerfall:
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Logindaten sind ungültig<i>(" . basename(__FILE__) . ")</i></p>";		
						$loginMessage = "Benutzername oder Passwort falsch!";
					
					} else {
						//Erfolgsfall:
if(DEBUG) 				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Formular ist korrekt ausgefüllt. Daten werden nun verarbeitet... <i>(" . basename(__FILE__) . ")</i></p>";		

						
						/********************************************************/
						/********************* DB-OPERATION *********************/
						/********************************************************/
											
						/********** DATENSATZ ZUM EMAIL AUS DB AUSLESEN *********/
						
						$statement = $pdo->prepare("SELECT usr_email, usr_password, usr_id, usr_firstname, usr_lastname 
													FROM users 
													WHERE usr_email = :ph_usr_email
													");
													
						$statement->execute( array(
											"ph_usr_email" => $email
											)) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>"); 
						
						$row = $statement->fetch(PDO::FETCH_ASSOC);
						
						
						
						/********************* EMAIL PRÜFEN **********************/

						// Prüfen, ob ein Datensatz geliefert wurde
						// Wenn Datensatz geliefert wurde, muss die Email-Adresse stimmen
						
						if( !$row ){
							// Fehlerfall:
if(DEBUG) 					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Email $email existiert nicht in der DB! <i>(" . basename(__FILE__) . ")</i></p>";
							$loginMessage = "Benutzername oder Passwort falsch!";			
							
						}else{
							// Erfolgsfall:
if(DEBUG) 					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: User mit Email $email wurde in der DB gefunden. <i>(" . basename(__FILE__) . ")</i></p>";	

							
							/******************* PASSWORT PRÜFEN *******************/
							
							if(!password_verify($password, $row['usr_password'])){
								// Fehlerfall: PW wurde nicht gefunden
if(DEBUG) 						echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Passwort stimmn nicht! <i>(" . basename(__FILE__) . ")</i></p>";								
								$loginMessage = "Benutzername oder Passwort falsch!";	
																
							}else{
								// Erfolgsfall: PW wurde gefunden
if(DEBUG) 						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Passwort stimmt mit Passwort aus DB überein <i>(" . basename(__FILE__) . ")</i></p>";									
								
								
								/****************************************************/
								/************ Session starten und Daten *************/
								/*************** in Session schreiben ***************/
								/****************************************************/
								
								session_name("blog");
								session_start();
								
								$_SESSION['usr_id'] 		= $row['usr_id'];
								$_SESSION['usr_firstname'] 	= $row['usr_firstname'];
								$_SESSION['usr_lastname']	= $row['usr_lastname'];
																
								
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
if(DEBUG)			echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL-Parameter 'action' wurde übergeben<i>(" . basename(__FILE__) . ")</i></p>";
	
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
										
					}

				
					/********************* LOGOUT ENDE **********************/	
					/********************************************************/


					/********************************************************/
					/************** URL-SchowCategory auslesen **************/
					/********************************************************/

					if($action == "showCategory"){
if(DEBUG)				echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: SchowCategory wird durchgeführt: action = $action'<i>(" . basename(__FILE__) . ")</i></p>";
						
						// Prüfen, ob und welche Kategorie ausgewählt/übergeben wurde:
						if(isset($_GET['categoryToShow'])){
if(DEBUG)					echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL-Parameter 'categoryToShow' wurde übergeben<i>(" . basename(__FILE__) . ")</i></p>";
							$categoryToShow = cleanString($_GET['categoryToShow']);
if(DEBUG)					echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: categoryToShow: $categoryToShow'<i>(" . basename(__FILE__) . ")</i></p>";
							


						} // Prüfen, welche Kategorie ausgewählt wurde - Ende
						
					} // URL-SchowCategory auslesen - Ende
					
					/********************************************************/
					/***************** URL-delete auslesen ******************/
					/********************************************************/
					
					if($action == "delete"){
if(DEBUG)				echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: 'delete' wird durchgeführt: action = $action'<i>(" . basename(__FILE__) . ")</i></p>";
						// Prüfen, ob und welche Kategorie ausgewählt/übergeben wurde:
						if(isset($_GET['entryToDelete'])){
if(DEBUG)					echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL-Parameter 'entryToDelete' wurde übergeben<i>(" . basename(__FILE__) . ")</i></p>";
							$entryToDelete = cleanString($_GET['entryToDelete']);
if(DEBUG)					echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: entryToDelete: $entryToDelete<i>(" . basename(__FILE__) . ")</i></p>";
						}
					}
					
					/********************************************************/
					/******************* URL-Edit auslesen ******************/
					/********************************************************/
					
					if($action=="edit"){
if(DEBUG)				echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: 'edit' wird durchgeführt: action = $action'<i>(" . basename(__FILE__) . ")</i></p>";
						// Prüfen, ob und welcher Beitrag ausgewählt/übergeben wurde:
						if(isset($_GET['entryToEdit'])){
if(DEBUG)					echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL-Parameter 'entryToEdit' wurde übergeben<i>(" . basename(__FILE__) . ")</i></p>";
							$entryToEdit = cleanString($_GET['entryToEdit']);
if(DEBUG)					echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: entryToEdit: $entryToEdit<i>(" . basename(__FILE__) . ")</i></p>";
						}
					}
					
				} // URL-Parameterverarbeitung - Ende 
				
				
/**********************************************************************************************/	



				/********************************************************/
				/************* Blogbeiträg aus DB löschen  **************/
				/********************************************************/
				
				if($entryToDelete){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Blogbeitrag $entryToDelete wird gelöscht... <i>(" . basename(__FILE__) . ")</i></p>";
					
					//prüfen, ob der Beitrag in DB existiert:
					$statement = $pdo->prepare("SELECT blog_id FROM blogs WHERE blog_id = :ph_blog_id");
					$statement->execute( array("ph_blog_id" => $entryToDelete)
										) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" );
					$entryExists = $statement->fetchColumn();
					
					if(!$entryExists){
						// Fehlrfall: Beitrag existiert nicht
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Blogbeitrag $entryToDelete existiert nicht <i>(" . basename(__FILE__) . ")</i></p>";
					}else{
						// Erfolgsfall: Beitrag existiert und wird gelöscht...
if(DEBUG)				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Blogbeitrag $entryToDelete existiert und wird gelöscht... <i>(" . basename(__FILE__) . ")</i></p>";
						
						// Beitrag aus DB löschen:
						$statement = $pdo->prepare("DELETE FROM blogs WHERE blog_id = :ph_blog_id");
						$statement->execute( array("ph_blog_id" => $entryToDelete)
										) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" );
						$deletedEntries = $statement->rowCount();
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Beitrag wurde erfolgreich gelöscht, deletedEntries: $deletedEntries <i>(" . basename(__FILE__) . ")</i></p>";
						
					}
					
				}
				
				
				if($entryToEdit){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Blogbeitrag $entryToEdit wird redaktiert... <i>(" . basename(__FILE__) . ")</i></p>";
					//prüfen, ob der Beitrag in DB existiert:
					$statement = $pdo->prepare("SELECT blog_id FROM blogs WHERE blog_id = :ph_blog_id");
					$statement->execute( array("ph_blog_id" => $entryToEdit)
										) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" );
					$entryExists = $statement->fetchColumn();
					if(!$entryExists){
						// Fehlrfall: Beitrag existiert nicht
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Blogbeitrag $entryToEdit existiert nicht <i>(" . basename(__FILE__) . ")</i></p>";
					}else{
						// Erfolgsfall: Beitrag existiert und wird gelöscht...
if(DEBUG)				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Blogbeitrag $entryToEdit existiert und wird redaktiert... <i>(" . basename(__FILE__) . ")</i></p>";
						
						// Alle Daten zum Datensatz aus BD auslesen und in $_SESSION schreiben
						
						$statement = $pdo->prepare("SELECT * FROM blogs 
													WHERE blog_id = :ph_blog_id ");
						$statement->execute(array("ph_blog_id" => $entryToEdit)) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" ); 
						
						// Daten über Radaktierenden Beitrag in Session schreiben, damit sie auf "dashboard.php" verfügbar sind
				
						while($row = $statement->fetch(PDO::FETCH_ASSOC)){
							$_SESSION['cat_id'] 			= $row['cat_id'];
							$_SESSION['blog_headline'] 		= $row['blog_headline'];
							$_SESSION['blog_image'] 		= $row['blog_image'];
							$_SESSION['blog_imageAlignment']= $row['blog_imageAlignment'];
							$_SESSION['blog_content'] 		= $row['blog_content'];
									
						}
						$_SESSION['edit'] = true;
						// Redirect zu "dashboard":
						header("Location: dashboard.php");
						exit;
					
					
					
					}	
					
					
				}
				
				
				
				/********************************************************/
				/*********** Kategorienliste aus DB auslesen  ***********/
				/********************************************************/
				
				$statement = $pdo->prepare("SELECT cat_name, cat_id FROM categories ORDER BY cat_name");
				$statement->execute() OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" ); 
				
				while($row = $statement->fetch(PDO::FETCH_ASSOC)){
					$categoriesArray[$row['cat_id']] = $row['cat_name'];
					
				}

				

				/********************************************************/
				/*********** Blogbeiträge aus DB auslesen  **************/
				/********************************************************/
				$sql = "SELECT * FROM blogs 
						INNER JOIN users USING(usr_id)
						INNER JOIN categories USING(cat_id)
						";
						
				// Wenn eine bestimmte Kategorie ausgewählt wurde, prüfen, ob sie in DB existiert:
				
				if($categoryToShow){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: categoryToShow wurde übergeben: $categoryToShow'<i>(" . basename(__FILE__) . ")</i></p>";		
					$statement = $pdo->prepare("SELECT * FROM categories WHERE cat_id = :ph_cat_id");
					$statement->execute( array("ph_cat_id" => $categoryToShow)
										) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" );
					$categoryExists = $statement->fetchColumn();
					
					if(!$categoryExists){
						// Fehlerfall: Kategorie existiert in DB nicht
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Kategorie existiert in DB nicht <i>(" . basename(__FILE__) . ")</i></p>";	
													
					} else {
						// Erfolgsfall: Kategorie existiert in DB
if(DEBUG)				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Kategorie existiert in DB<i>(" . basename(__FILE__) . ")</i></p>";	
						
						$sql .= " WHERE cat_id = :ph_cat_id"; 
						$params = array("ph_cat_id" => $categoryToShow);							

					} // Prüfen, ob kategorie existiert - Ende
					
				} // Versuch Kategorienumber aus DB auszulesen - Ende
				
				
				
				
				
				$sql .= " ORDER BY blog_id DESC"; // Neuste Beiträge oben anzeigen
				$statement = $pdo->prepare($sql);
				$statement->execute($params) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" ); 

				// $entriesArray ist ein zweidimensionales Array, 
				// das ALLE Datensätze beinhaltet
				
				$entriesArray = $statement->fetchAll(PDO::FETCH_ASSOC);

				
				





/**********************************************************************************************/	
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
					Je Schleifendurchlauf enthält $entry einen anderen Datensatz in Form 
					eines eindimensionalen Arrays, dessen Indizes den Namen der Spalten in 
					der Tabellen entsprechen.
				-->
				<?php foreach ($entriesArray AS $entry): ?>
					<article>
						
						<ul class="category-list">
							<li><?=$entry['cat_name']?></li>
						</ul>
						
						<!------------ Editor-Tools -------------->
						
						<?php if(isset($_SESSION['usr_id'])):?>
							<a href="?action=delete&entryToDelete=<?=$entry['blog_id']?>" class="entry-del" onclick="return confirm('Wollen Sie wirklich den Beitrag löschen?')">X</a>
							<a href="?action=edit&entryToEdit=<?=$entry['blog_id']?>" class="entry-edit">Edit</a>
						<?php endif ?>
						
						
						
						<?php 
							// Convertierung in EU Datum und Zeit
							$dateTime = isoToEuDateTime($entry['blog_date']); 
					
						?>
						
						<p class="whowrote">
							<?=$entry['usr_firstname']?> <?=$entry['usr_lastname']?> 
							aus <?=$entry['usr_city']?> schrieb am <?=$dateTime['date']?> um <?=$dateTime['time']?> Uhr:
						</p>
						<h3 class="headline"><?=$entry['blog_headline']?></h3>
						<?php if($entry['blog_image']): ?>
							<img src="<?=$entry['blog_image']?>" class="<?=$entry['blog_imageAlignment']?> article-image" />
						<?php endif ?>
						<?php $entry['blog_content'] = str_replace("\r\n", "<br>", $entry['blog_content']) ?>
						<p class="content"><?=$entry['blog_content']?></p>
						<div class="clear"></div>

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

						foreach ($categoriesArray AS $key=>$value){
												
							if($categoryToShow == $key){
								echo "<li class='selected-category'><a href='?action=showCategory&categoryToShow=$key'>$value</a></li>";
							}else{
								echo "<li><a href='?action=showCategory&categoryToShow=$key'>$value</a></li>";
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