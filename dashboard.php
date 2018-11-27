<?php
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
				/***************** Seitenzugrifschutz *******************/
				/********************************************************/
				
				if(!isset($_SESSION['usr_id'])){
					//Session löschen:
					session_destroy();
					//Umleiten auf index.php:
					header("Location: index.php");
					exit;
				}
	
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
				
/**********************************************************************************************/
				
				/********************************************************/
				/**************** START OUTPUT BUFFERING ****************/
				/********************************************************/
								
				if( !ob_start() ) {
					// Fehlerfall:
if(DEBUG)			echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Output Buffering konnte nicht aktiviert werden! <i>(" . basename(__FILE__) . ")</i></p>";								
				} else {
					// Erfolgsfall:
if(DEBUG)			echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Output Buffering ist aktiviert. <i>(" . basename(__FILE__) . ")</i></p>";								
				}
					
/**********************************************************************************************/

				/********************************************************/
				/******************** DB-Verbindung *********************/
				/********************************************************/
				
				$pdo = dbConnect();
					
					
/**********************************************************************************************/
				
				/********************************************************/
				/**************** VARIABLEN INITIALIZIEREN **************/
				/********************************************************/
				
				$entryMessage 		= NULL;
				$categoryMessage 	= NULL;
				
				$errorHeadline 		= NULL;
				$errorImageUpload	= NULL;
				$errorText 			= NULL;
					
				// Objects:
				$user				= new User();
				$user->setUsr_id($_SESSION['usr_id']); 
				$user->setUsr_firstname($_SESSION['usr_firstname']);
				$user->setUsr_lastname($_SESSION['usr_lastname']);
				
				$newCategory		= new Category();
				$newEntry 			= new Blog();
				
				$newEntry->setCategory($newCategory);
				$newEntry->setUser($user);
				
if(DEBUG)		echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>";					
if(DEBUG)		print_r($newEntry);					
if(DEBUG)		echo "</pre>";				
			
				
/**********************************************************************************************/
				
				/********************************************************/
				/**************** FORMULARVERARBEITUNG ******************/
				/********************************************************/
				
				/*************** für Kategorienformular *****************/

				// Prüfen, ob Formular abgeschickt wurde:
				if(isset($_POST['formsentNewCategory'])){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Formular 'newCategory' wurde abgeschickt. <i>(" . basename(__FILE__) . ")</i></p>";
					
					$newCategory->setCat_name($_POST['newCategory']);
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: newCategory: ".$newCategory->getCat_name()." <i>(" . basename(__FILE__) . ")</i></p>";
					$errorNewCategory = checkInputString($newCategory->getCat_name(), 3);
					
					// Abschließende Prüfung:
					if($errorNewCategory){
						//Fehlerfall:
if(DEBUG) 				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Kein gültiger Kategoriename <i>(" . basename(__FILE__) . ")</i></p>";									
						$categoryMessage = "<p class='error'>$errorNewCategory</p>";
						
					}else{
						//Erfolgsfall:
if(DEBUG) 				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Das Formular 'Neue Kategorie' ist fehlerfrei, die Daten können in DB geschrieben werden<i>(" . basename(__FILE__) . ")</i></p>";							
						// Einen Objekt "Kategorie" mit dem eingegebenen Namen anlegen:
						
						
				/********************************************************/	
				/*************** Prüfen, ob die Kategorie ***************/  
				/*************** bereits in DB existiert ****************/
				/********************************************************/
				
				/******************** DB-OPERATION **********************/
						
						if($newCategory->categoryExists($pdo)){
							// Fehlerfall:
if(DEBUG)					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Kategorie schon existiert <i>(" . basename(__FILE__) . ")</i></p>";
							$categoryMessage = "<p class='error'>Die Kategorie <b>".$newCategory->getCat_name()."</b> schon existiert</p>";							
							
						}else{
							// Erfolgsfall:
if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Kategorie ist noch nicht angelegt...<i>(" . basename(__FILE__) . ")</i></p>";							
							
				/********************************************************/
				/************* Kategorie in DB Screiben *****************/
				/********************************************************/
							
if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Kategorie ". $newCategory->getCat_name() ."wird in DB gescrieben...<i>(" . basename(__FILE__) . ")</i></p>";	
						
							if(!$newCategory->saveToDb($pdo)){
								// Fehlerfall:
if(DEBUG)						echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Fehler beim Speichern der neuen Kategorie <i>(" . basename(__FILE__) . ")</i></p>";
								$categoryMessage = "<p class='error'>Es ist ein Fehler aufgetreten, versuchen Sie es nochmal</p>";
																
							}else{
								// Erfolgsfall:
if(DEBUG)						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Kategorie ".$newCategory->getCat_name()." wurde mit der ID ".$newCategory->getCat_id()." erfolgreich geschpeichert. <i>(" . basename(__FILE__) . ")</i></p>";
								$categoryMessage = "<p class='success'>Kategorie <b>".$newCategory->getCat_name()."</b> wurde erfolgreich geschpeichert.</p>";
								$newCategory = NULL;
								
							} // Prüfen, ob Kategorie gespeichert ist - Ende
							
						} // Kategorie in DB Screiben - Ende
				
					} // Abschließende Formularprüfung - Ende
					
				} // Formularverarbeitung - Ende
				


/**********************************************************************************************/

				/********************************************************/
				/************** Kategorien für Selectboxen **************/
				/******************	aus DB auslesen *********************/
				/********************************************************/
				
				$categoriesArray = Category::fetchFromDb($pdo);
				
/**********************************************************************************************/

				/********************************************************/
				/***************** FORMULARVERARBEITUNG *****************/
				/********************************************************/
				
				/***************** für Blogformular  ********************/
				
				if(isset($_POST['formsentNewEntry'])){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Formular 'NewEntry' wurde abgeschickt. <i>(" . basename(__FILE__) . ")</i></p>";
					
					// Eingetragene daten in Objekt schreiben:
					$newEntry->setBlog_headline($_POST['headline']);
					$newEntry->setBlog_imageAlignment($_POST['imageAlignment']);
					$newEntry->setBlog_content($_POST['content']);
					$newEntry->getCategory()->setCat_id($_POST['category']);

					
					$errorHeadline 	= checkInputString($newEntry->getBlog_headline(), 4);
					$errorText 		= checkInputString($newEntry->getBlog_content(), 10, 10000); 
					
					if($errorHeadline || $errorText){
						// Fehlerfall:
if(DEBUG) 				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Das Formular enthält noch Fehler <i>(" . basename(__FILE__) . ")</i></p>";							
							
					}else{
						// Efolgsfall:
						
						// Nur wenn Formularfelder fehlerfrei sind, soll der Bildupload durchgeführt werden,
						// da ansonsten trotz Feld-Fehler im Formular das neue Bild auf dem Server gespeichert 
						// und das alte Bild gelöscht wäre
						
				/********************************************************/
				/******************** FILE UPLOAD ***********************/

						// Prüfen, ob eine Bilddatei hochgeladen wurde
						if($_FILES['image']['tmp_name']){
if(DEBUG) 					echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Bildupload aktiv...<i>(" . basename(__FILE__) . ")</i></p>";		
							
							$image 					= $_FILES['image'];
							$imageUploadReturnArray = imageUpload($image);
							
							//Prüfen, ob es einen Bildupload-Fehler gab:
							if($imageUploadReturnArray['imageError']){
								//Fehlerfall:
if(DEBUG) 						echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Fehler: $imageUploadReturnArray[imageError] <i>(" . basename(__FILE__) . ")</i></p>";	
								$errorImageUpload = $imageUploadReturnArray['imageError'];
							
							}else{
								//Erfolgsfall:
if(DEBUG) 						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Das Bild wird auf dem Server geladen <i>(" . basename(__FILE__) . ")</i></p>";										
									
								// Neuen Bildpfad speichern:
								$newEntry->setBlog_image($imageUploadReturnArray['imagePath']);
								
if(DEBUG) 						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: image: ".$newEntry->getBlog_image()." <i>(" . basename(__FILE__) . ")</i></p>";	
								
								
							}
							
						}
						
				/***************** FILE UPLOAD ENDE *********************/
				/********************************************************/

							
						// Abschließende Formularprüfung:
						
						if(!$errorImageUpload){
							
if(DEBUG) 					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Das Formular ist fehlerfrei, die Daten können in DB geschrieben werden<i>(" . basename(__FILE__) . ")</i></p>";	
							
				/*********************************************************/			
				/*********** Neuer Eintrag in DB speichern ***************/
				/*********************************************************/

							// Last Insert-ID abholen und prüfen:
							$newEntryId = $newEntry->saveToDb($pdo);
if(DEBUG)					echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$newEntryId: $newEntryId <i>(" . basename(__FILE__) . ")</i></p>";		
						
							// Prüfen, ob der Eintrag gespeichert wurde:
							if(!$newEntryId){
								// Fehlerfall:
if(DEBUG)						echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Fehler beim Speichern des neuen Eintrags <i>(" . basename(__FILE__) . ")</i></p>";
								$entryMessage = "<p class='error'>Es ist ein Fehler aufgetreten, versuchen Sie es nochmal</p>";
																
							}else{
								// Erfolgsfall:
if(DEBUG)						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Neuer Eintrag wurde mit der ID $newEntryId erfolgreich geschpeichert. <i>(" . basename(__FILE__) . ")</i></p>";
								$entryMessage = "<p class='success'>Eintrag <b>".$newEntry->getBlog_headline()."</b> wurde erfolgreich geschpeichert.</p>";
								
								// Objekt löschen:
								$newEntry = NULL;
								$_POST = NULL;
								

							} // Prüfen, ob Eintrag gespeichert wurde - Ende
							
						} // Eintrag in DB speichern - Ende
						
					} // Fileupload - Ende
				
				} // Formularverarbeitung für Blogformular - Ende

/**********************************************************************************************/

				/********************************************************/
				/************** URL-Parameterverarbeitung ***************/
				/********************************************************/
				
				if(isset($_GET['action'])){
if(DEBUG)			echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL-Paranmeter 'action' wurde übergeben<i>(" . basename(__FILE__) . ")</i></p>";
	
					$action = cleanString($_GET['action']);
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: action: $action'<i>(" . basename(__FILE__) . ")</i></p>";
					
					
				/********************************************************/
				/******************** LOGOUT ****************************/
				
					if($action == "logout"){
if(DEBUG)				echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Logout wird durchgeführt: action = $action'<i>(" . basename(__FILE__) . ")</i></p>";	
						
						// Session löschen:
						session_destroy();
						
						// Weiterleiten auf Indexseite:
						header("Location: index.php");
						exit;
										
					}
				/********************* LOGOUT ENDE **********************/	
				/********************************************************/
				
				}
				
/**********************************************************************************************/
/**********************************************************************************************/	
?>
<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>Blog über Essen - Dashboard</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/debug.css">
		<link href="https://fonts.googleapis.com/css?family=Dancing+Script|Roboto:300" rel="stylesheet">
	</head>

	<body>
		<header>
			<div class="hello">	
			
			<!--------- Begrüßung und Logout-Link ------------>
			
				<p>Hallo, <?=$_SESSION['usr_firstname']?>!  |  <a href="?action=logout">Logout</a></p>
				<p><a href="index.php"><< Zum Frontend</a></p>
			</div>
			
			
		</header>
		<br>
		<div class="wrapper-dashboard">
			<div class="blog-headline">
				<h1>Blog über Essen - Dashboard</h1>
				<h2>Aktiver Benutzer: <?=$user->getUsr_Fullname()?></h2>
			</div>
			
			
			<!----------- Formular Neuer Eintrag ------------>
			
			<main class="new-entry">
				<h3>Neuen Blog-Eintrag Verfassen</h3>
				
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST" enctype="multipart/form-data">
					<span><?=$entryMessage?></span>
					<input type="hidden" name="formsentNewEntry">
					<select class="category" name="category">
						<?php foreach($categoriesArray AS $categoryObject): ?>
							<?php if($_POST AND $categoryObject->getCat_id() == $_POST['category']): ?>
									<option value='<?=$categoryObject->getCat_id()?>' selected>
							<?php else: ?>
								<option value='<?=$categoryObject->getCat_id()?>'>
							<?php endif ?>
							<?=$categoryObject->getCat_name()?>
							</option>
						<?php endforeach ?>
					</select>
					<br><br>
					<span class="error"><?=$errorHeadline?></span><br>
					<input type="text" name="headline" placeholder="Überschrift" class="headline" 
					value="<?php if($newEntry) echo $newEntry->getBlog_headline()?>">
					<br>
					
					<span class="error"><?=$errorImageUpload?></span>
					<br>
					
					<input type="file" name="image" class="file">
					<select class="imageAlignment" name="imageAlignment">
						<?php if($newEntry AND $newEntry->getBlog_imageAlignment() == "right"): ?>
							<option value='left'>Align left</option>
							<option value='right' selected>Align right</option>
						<?php else?>
							<option value='left' selected>Align left</option>
							<option value='right'>Align right</option>	
						<?php endif ?>
						
					</select><br><br>
					<span class="error"><?=$errorText?></span><br>
					<textarea name="content" placeholder="Text...">
						<?php if($newEntry) echo $newEntry->getBlog_content()?>
					</textarea>
					<br>
					<input type="submit" value="Veröffentlichen" class="button">
				</form>
			</main>
			
			<!----------- Formular Neue Kategorie ----------->
			
			<aside class="new-category">
				<h3>Neue Kategorie anlegen</h3>
				
				<br>
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
					<span><?=$categoryMessage?></span><br>
					<input type="hidden" name="formsentNewCategory">
					<input type="text" name="newCategory" value="<?php if($newCategory) echo $newCategory->getCat_name()?>"><br>
					<input type="submit" value="Kategorie anlegen" class="button">
				</form>
			</aside><br>
		</div>
		<footer class="clear">
			<p>Copyright Irina Serdiuk</p>
		</footer>		
		
	</body>
	<script>
		var jump2 = document.getElementsByTagName("HEADER");
		jump2[0].scrollIntoView();
	</script>

</html>