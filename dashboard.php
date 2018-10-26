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

				/************************************************/
				/************ Seitenzugrifschutz ****************/
				/************************************************/
				
				if(!isset($_SESSION['usr_id'])){
					//Session löschen:
					session_destroy();
					//Umleiten auf index.php
					header("Location: index.php");
					exit;
					
				}
	
									
				/*************************************************/
				/******************* INCLUDES ********************/
				/*************************************************/
				
				require_once("include/config.inc.php");
				require_once("include/db.inc.php");
				require_once("include/form.inc.php");
				
				/************************************************/
				/***************** DB-Verbindung ****************/
				/************************************************/
				
				//1DB: Verbindung
				$pdo = dbConnect();
					
					
/**********************************************************************************************/
				
				/************************************************/
				/*********** VARIABLEN INITIALIZIEREN ***********/
				/************************************************/
				
				
				
				
				$categoryMessage = NULL;
				$categoriesArray  = array();
				
				
/**********************************************************************************************/
				
				/************************************************/
				/************ FORMULARVERARBEITUNG **************/
				/************************************************/
				
				/*********** für Kategorienformular *************/
				
				// 1. FORM: Prüfen, ob Formular abgeschickt wurde:
				if(isset($_POST['formsentNewCategory'])){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Formular 'newCategory' wurde abgeschickt. <i>(" . basename(__FILE__) . ")</i></p>";
					
					// 2. FORM: Auslesen, entschärfen
					$newCategory = cleanString($_POST['newCategory']);
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: newCategory = $newCategory <i>(" . basename(__FILE__) . ")</i></p>";

					//3. Validieren, abschließende Prüfung
					$errorNewCategory = checkInputString($newCategory, 3);
					
					// Abschließende Prüfung:
					if($errorNewCategory){
						//Fehlerfall:
if(DEBUG) 				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Kein gültiger Kategoriename <i>(" . basename(__FILE__) . ")</i></p>";									
						$categoryMessage = "<p class='error'>$errorNewCategory</p>";
					}else{
						//Erfolgsfall:
if(DEBUG) 				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Das Formular 'Neue Kategorie' ist fehlerfrei, die Daten können in DB geschrieben werden<i>(" . basename(__FILE__) . ")</i></p>";							
						
						
						
					/********** Prüfen, ob die Kategorie *********/  
					/********* bereits in DB existiert ***********/
					
					/************* DB-OPERATION ******************/
						
						// 2DB. SQL-Statement vorbereiten
						$statement = $pdo->prepare("SELECT COUNT(cat_name) FROM categories
														WHERE cat_name = :ph_cat_name");
						// 3DB. SQL-Statement ausführen und Platzhalter füllen
						$statement->execute( array(
													"ph_cat_name" => $newCategory
													)) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" );
						
						// 4DB. Daten weiterverarbeiten
						$categoryExists = $statement->fetchColumn();
					
						if($categoryExists){
							// Fehlerfall:
if(DEBUG)					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Kategorie schon existiert <i>(" . basename(__FILE__) . ")</i></p>";
							$categorieMessage = "<p class='error'>Die Kategorie <b>$newCategory</b> schon existiert";							
							
						}else{
							// Erfolgsfall:
if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Kategorie ist noch nicht angelegt...<i>(" . basename(__FILE__) . ")</i></p>";							
							
							
						/**************** Kategorie in DB Screiben *******************/

if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Kategorie $newCategory wird in DB gescrieben...<i>(" . basename(__FILE__) . ")</i></p>";	
						
							// 2. DB: SQL-Statement vorbereiten
							$statement = $pdo->prepare("INSERT INTO categories (cat_name)
																	VALUES (:ph_cat_name)
														");
													
							// 3. DB: SQL-Statement ausführen
							$statement->execute(array("ph_cat_name"=>$newCategory) ) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>"); 
						
						
							// 4. DB: Datenweiterverarbeiten
							// Bei INSERT Last Insert-ID abholen und prüfen
						
							$newCategoryId = $pdo->lastInsertId();
if(DEBUG)					echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$newCategoryId: $newCategoryId <i>(" . basename(__FILE__) . ")</i></p>";		
							
							
							if(!$newCategoryId){
								// Fehlerfall:
if(DEBUG)						echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Fehler beim Speichern der neuen Kategorie <i>(" . basename(__FILE__) . ")</i></p>";
								$categoryMessage = "<p class='error'>Es ist ein Fehler aufgetreten, versuchen Sie es nochmal</p>";
																
							}else{
								// Erfolgsfall:
if(DEBUG)						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Kategorie $newCategory wurde mit der ID $newCategoryId erfolgreich geschpeichert. <i>(" . basename(__FILE__) . ")</i></p>";
								$categoryMessage = "<p class='success'>Kategorie <b>$newCategory</b> wurde erfolgreich gescheichert.</p>";
								
							} // Prüfen, ob Kategorie gespeichert ist - Ende
							
						} // Kategorie in DB Screiben - Ende
				
					} // Abschließende Formularprüfung - Ende
					
				} // Formularverarbeitung - Ende
				


/**********************************************************************************************/

				/************************************************/
				/********** Kategorien füe Selectbox ************/
				/**************	aus DB auslesen *****************/
				/************************************************/
				
				//2. DB: SQL-Statement Vorbereiten
				$statement = $pdo->prepare("SELECT * FROM categories ORDER by cat_name
											");
				//3. DB: Statement ausführen
				$statement->execute() OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>"); 

				//4. DB: Weiterverarbeiten
				//Bei SELECT: Datensätze auslesen
				// $categoriesArray enthält ein zweidimensionales Array. Jedes darin 
				// enthaltene Array entspricht einem Datensatz aus der DB
					
				$categoriesArray = $statement->fetchAll(PDO::FETCH_ASSOC);
				
								
								

/**********************************************************************************************/

				/************************************************/
				/********* URL-Parameterverarbeitung ************/
				/************************************************/
				
				//1 URL: Prüfen, ob Parameter übergeben wurde:
				if(isset($_GET['action'])){
if(DEBUG)			echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL-Paranmeter 'action' wurde übergeben<i>(" . basename(__FILE__) . ")</i></p>";
	
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
						
						// Weiterleiten auf Indexseite:
						header("Location: index.php");
						exit;
										
					}
				/***************** LOGOUT ENDE *******************/	
				/*************************************************/
				
				}
				
/**********************************************************************************************/

	
/**********************************************************************************************/	
?>
<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>Blog über Essen - Dashboard</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/debug.css">
	</head>

	<body>
		<header>
			<p>Hallo, <?=$_SESSION['usr_firstname']?>!  |  <a href="?action=logout">Logout</a></p>
			<p><a href="index.php"><< Zum Frontend</a></p>
		</header>
		<h1>Blog über Essen - Dashboard</h1>
		
		<p>Aktiver Benutzer: </p>
		
		<h3>Neuen Blog-Eintrag Verfassen</h3>
		<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="formsentNewEntry">
			<select class="category" name="category">
				<?php foreach($categoriesArray AS $categoryRow): ?>
					<option value="<?=$categoryRow['cat_id']?>">	
						<?=$categoryRow['cat_name']?>
					</option>
				<?php endforeach ?>
			</select>
			<br>
			<input type="text" name="headline" placeholder="Überschrift"><br>
			<input type="file" name="image">
			<select class="imageAlignment" name="imageAlignment">
				<option value="left">Align left</option>
				<option value="right">Align right</option>
			</select><br>
			<textarea name="text" placeholder="Text..."></textarea>
			<br>
			<input type="submit" value="Veröffentlichen">
		</form>
		
		<h3>Neue Kategorie anlegen</h3>
		
		<?=$categoryMessage?>
		<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
			<input type="hidden" name="formsentNewCategory">
			<input type="text" name="newCategory"><br>
			<input type="submit" value="Kategorie anlegen">
		</form>
		
		
		
		


</body>

</html>