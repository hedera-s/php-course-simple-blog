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
				
				
				$categoryMessage 	= NULL;
				$categoriesArray  	= array();
				$errorHeadline 		= NULL;
				$errorText 			= NULL;
				$entryMessage 		= NULL;
				$image 				= NULL;
				$errorImageUpload	= NULL;
				
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
							$categoryMessage = "<p class='error'>Die Kategorie <b>$newCategory</b> schon existiert";							
							
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
				/********** Kategorien für Selectbox ************/
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
				/************ FORMULARVERARBEITUNG **************/
				/************************************************/
				
				/************** für Blogformular  ***************/
				
				// 1. FORM: Prüfen, ob Formular abgeschickt wurde:
				if(isset($_POST['formsentNewEntry'])){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Formular 'NewEntry' wurde abgeschickt. <i>(" . basename(__FILE__) . ")</i></p>";
					
					// 2. FORM: Auslesen, entschärfen
					$category = cleanString($_POST['category']);
					$headline = cleanString($_POST['headline']);
					$imageAlignment = cleanString($_POST['imageAlignment']);
					$content = cleanString($_POST['content']);
					
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: category = $category <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: headline = $headline <i>(" . basename(__FILE__) . ")</i></p>";

if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: imageAlignment = $imageAlignment <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: content = $content <i>(" . basename(__FILE__) . ")</i></p>";

					//3. Validieren, abschließende Prüfung
					
					$errorHeadline = checkInputString($headline);
					$errorText = checkInputString($content, 10, 10000); 
					
					if($errorHeadline || $errorText){
						// Fehlerfall:
if(DEBUG) 				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Das Formular enthält noch Fehler <i>(" . basename(__FILE__) . ")</i></p>";							
							
					}else{
						// Efolgsfall:
						
						// Nur wenn Formularfelder fehlerfrei sind, soll der Bildupload durchgeführt werden,
						// da ansonsten trotz Feld-Fehler im Formular das neue Bild auf dem Server gespeichert 
						// und das alte Bild gelöscht wäre
						
						/***********************************************/
						/**************** FILE UPLOAD ******************/
						/***********************************************/

						// Prüfen, ob eine Bilddatei hochgeladen wurde
						if($_FILES['image']['tmp_name']){
if(DEBUG) 						echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Bildupload aktiv...<i>(" . basename(__FILE__) . ")</i></p>";		
							
							$image = $_FILES['image'];
							$imageUploadReturnArray = imageUpload($image);
							
							//Prüfen, ob es einen Bildupload Fehler gab
							if($imageUploadReturnArray['imageError']){
								
								//Fehlerfall:
if(DEBUG) 							echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Fehler: $imageUploadReturnArray[imageError] <i>(" . basename(__FILE__) . ")</i></p>";	
								$errorImageUpload = $imageUploadReturnArray['imageError'];
								
							}else{
								//Erfolgsfall:
if(DEBUG) 							echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Das Bild wird auf dem Server geladen <i>(" . basename(__FILE__) . ")</i></p>";										
									
								// Neuen Bildpfad speichern:
								$image = $imageUploadReturnArray['imagePath'];
if(DEBUG) 							echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: image = $image <i>(" . basename(__FILE__) . ")</i></p>";	
								
								
							}
							
						} 			//Ende Fileupload
						/**********************************************/

							
						// Abschließende Formularprüfung TEIL 2:
						
						if(!$errorImageUpload){
if(DEBUG) 					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Das Formular ist fehlerfrei, die Daten können in DB geschrieben werden<i>(" . basename(__FILE__) . ")</i></p>";	
							
							/************** DB-Operation **************/
													
							
							
							// 2. DB: SQL-Statement vorbereiten:
							$statement = $pdo->prepare("INSERT INTO blogs (	blog_headline, 
																			blog_image, 
																			blog_imageAlignment, 
																			blog_content, 
																			cat_id, 
																			usr_id ) 
																	VALUES (:ph_blog_headline,
																			:ph_blog_image,
																			:ph_blog_imageAlignment,
																			:ph_blog_content,
																			:ph_cat_id,
																			:ph_usr_id )
														");
							
							//	3. DB: SQL-Statement ausführen:
							$statement->execute(array(
														"ph_blog_headline" => $headline,
														"ph_blog_image" => $image,
														"ph_blog_imageAlignment" => $imageAlignment,
														"ph_blog_content" => $content,
														"ph_cat_id" => $category,
														"ph_usr_id" => $_SESSION['usr_id']
														) ) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>"); 
							
							// 4. DB: Daten weiterverarbeiten:
							// Bei INSERT Last Insert-ID abholen und prüfen
						
							$newEntryId = $pdo->lastInsertId();
if(DEBUG)					echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$newEntryId: $newEntryId <i>(" . basename(__FILE__) . ")</i></p>";		
							
							
							if(!$newEntryId){
								// Fehlerfall:
if(DEBUG)						echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Fehler beim Speichern des neuen Eintrags <i>(" . basename(__FILE__) . ")</i></p>";
								$entryMessage = "<p class='error'>Es ist ein Fehler aufgetreten, versuchen Sie es nochmal</p>";
																
							}else{
								// Erfolgsfall:
if(DEBUG)						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Neuer Eintrag wurde mit der ID $newEntryId erfolgreich geschpeichert. <i>(" . basename(__FILE__) . ")</i></p>";
								$entryMessage = "<p class='success'>Eintrag <b>\"$headline\"</b> wurde erfolgreich gescheichert.</p>";
								
							} // Prüfen, ob Eintrag gespeichert wurde - Ende
							
						} // Eintrag in DB speichern - Ende
						
					} // Fileupload - Ende
				
				} // Formularverarbeitung für Blogformular - Ende

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
		
		<div class="main">
			<h3>Neuen Blog-Eintrag Verfassen</h3>
			<?=$entryMessage?>
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
				<span class="error"><?=$errorHeadline?></span><br>
				<input type="text" name="headline" placeholder="Überschrift"> <br>
				<input type="file" name="image">
				<select class="imageAlignment" name="imageAlignment">
					<option value="left">Align left</option>
					<option value="right">Align right</option>
				</select><br>
				<span class="error"><?=$errorText?></span><br>
				<textarea name="content" placeholder="Text..."></textarea>
				<br>
				<input type="submit" value="Veröffentlichen">
			</form>
		</div>
		<div class="aside">
			<h3>Neue Kategorie anlegen</h3>
			
			<?=$categoryMessage?>
			<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
				<input type="hidden" name="formsentNewCategory">
				<input type="text" name="newCategory"><br>
				<input type="submit" value="Kategorie anlegen">
			</form>
		</div>
		
		
		


</body>

</html>