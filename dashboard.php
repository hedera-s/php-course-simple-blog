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
				/***************** Seitenzugrifschutz *******************/
				/********************************************************/
				
				if(!isset($_SESSION['usr_id'])){
					//Session löschen:
					session_destroy();
					//Umleiten auf index.php
					header("Location: index.php");
					exit;
					
				}
	
									
				/********************************************************/
				/*********************** INCLUDES ***********************/
				/********************************************************/
				
				require_once("include/config.inc.php");
				require_once("include/db.inc.php");
				require_once("include/form.inc.php");
				require_once("include/datetime.inc.php");
				
				
				/********************************************************/
				/******************** DB-Verbindung *********************/
				/********************************************************/
				
				$pdo = dbConnect();
					
					
/**********************************************************************************************/
				
				/********************************************************/
				/**************** VARIABLEN INITIALIZIEREN **************/
				/********************************************************/
				
				$categoriesArray  	= array();
				
				
				$entryMessage 		= NULL;
				$categoryMessage 	= NULL;
				$errorHeadline 		= NULL;
				$errorImageUpload	= NULL;
				$errorText 			= NULL;
				
				$image 				= NULL;
				$headline 			= NULL;
				$content			= NULL;
				$newCategory		= NULL;
				
/**********************************************************************************************/
				
				/********************************************************/
				/**************** FORMULARVERARBEITUNG ******************/
				/********************************************************/
				
				/*************** für Kategorienformular *****************/
				
				
				// Prüfen, ob Formular abgeschickt wurde:
				if(isset($_POST['formsentNewCategory'])){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Formular 'newCategory' wurde abgeschickt. <i>(" . basename(__FILE__) . ")</i></p>";
					$newCategory = cleanString($_POST['newCategory']);
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: newCategory = $newCategory <i>(" . basename(__FILE__) . ")</i></p>";
					$errorNewCategory = checkInputString($newCategory, 3);
					
					// Abschließende Prüfung:
					if($errorNewCategory){
						//Fehlerfall:
if(DEBUG) 				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Kein gültiger Kategoriename <i>(" . basename(__FILE__) . ")</i></p>";									
						$categoryMessage = "<p class='error'>$errorNewCategory</p>";
						
					}else{
						//Erfolgsfall:
if(DEBUG) 				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Das Formular 'Neue Kategorie' ist fehlerfrei, die Daten können in DB geschrieben werden<i>(" . basename(__FILE__) . ")</i></p>";							
						
						
						/********************************************************/	
						/*************** Prüfen, ob die Kategorie ***************/  
						/*************** bereits in DB existiert ****************/
						/********************************************************/
						
						/******************** DB-OPERATION **********************/
						
						
						$statement = $pdo->prepare("SELECT COUNT(cat_name) FROM categories
														WHERE cat_name = :ph_cat_name");
						$statement->execute( array(
													"ph_cat_name" => $newCategory
													)) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" );
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
						
							$statement = $pdo->prepare("INSERT INTO categories (cat_name)
																	VALUES (:ph_cat_name)
														");
							$statement->execute(array("ph_cat_name"=>$newCategory) ) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>"); 
				
				
							// Last Insert-ID abholen und prüfen:
							
							$newCategoryId = $pdo->lastInsertId();
if(DEBUG)					echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$newCategoryId: $newCategoryId <i>(" . basename(__FILE__) . ")</i></p>";		
							
							
							if(!$newCategoryId){
								// Fehlerfall:
if(DEBUG)						echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Fehler beim Speichern der neuen Kategorie <i>(" . basename(__FILE__) . ")</i></p>";
								$categoryMessage = "<p class='error'>Es ist ein Fehler aufgetreten, versuchen Sie es nochmal</p>";
																
							}else{
								// Erfolgsfall:
if(DEBUG)						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Kategorie $newCategory wurde mit der ID $newCategoryId erfolgreich geschpeichert. <i>(" . basename(__FILE__) . ")</i></p>";
								$categoryMessage = "<p class='success'>Kategorie <b>$newCategory</b> wurde erfolgreich geschpeichert.</p>";
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
				
				
				$statement = $pdo->prepare("SELECT * FROM categories ORDER by cat_name
											");
				$statement->execute() OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>"); 

				// $categoriesArray enthält ein zweidimensionales Array. Jedes darin 
				// enthaltene Array entspricht einem Datensatz aus der DB
					
				$categoriesArray = $statement->fetchAll(PDO::FETCH_ASSOC);
				


/**********************************************************************************************/

				/********************************************************/
				/*************** Ein Eintag redaktieren *****************/
				/********************************************************/
				
			
				// Nur wenn ein Editmodus eingeschaltet ist:
				if(isset($_SESSION['edit'])){
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Die Session läuft mit Flag 'edit'. <i>(" . basename(__FILE__) . ")</i></p>";					
					$category 		= $_SESSION['cat_id'];
					$headline 		= $_SESSION['blog_headline'];
					$image 			= $_SESSION['blog_image'];
					$imageAlignment = $_SESSION['blog_imageAlignment'];
					$content 		= $_SESSION['blog_content'];
					
					
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: category = $category <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: headline = $headline <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: image = $image <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: imageAlignment = $imageAlignment <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: content = $content <i>(" . basename(__FILE__) . ")</i></p>";
					
					
					/********** Ein Einttarg in DB aktuelisieren *********/
					
					// 1. Formulag abschicken
					if(isset($_POST['formsentNewEntry'])){
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Formular 'NewEntry' wurde abgeschickt in Editmodus. <i>(" . basename(__FILE__) . ")</i></p>";
						
						// Daten aus Formulr auslesen:
						
						$category 		= cleanString($_POST['category']);
						$headline 		= cleanString($_POST['headline']);
						$imageAlignment = cleanString($_POST['imageAlignment']);
						$content 		= cleanString($_POST['content']);
						
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: category 		= $category <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: headline 		= $headline <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: image 			= $image <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: imageAlignment = $imageAlignment <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: content 		= $content <i>(" . basename(__FILE__) . ")</i></p>";						
						
						$errorHeadline 	= checkInputString($headline);
						$errorText 		= checkInputString($content, 10, 10000); 
						
						if($errorHeadline || $errorText){
							// Fehlerfall:
if(DEBUG) 					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Das Formular enthält noch Fehler <i>(" . basename(__FILE__) . ")</i></p>";		
						
						}else{
							// Efolgsfall:
							
							// Nur wenn Formularfelder fehlerfrei sind, soll der Bildupload durchgeführt werden,
							// da ansonsten trotz Feld-Fehler im Formular das neue Bild auf dem Server gespeichert 
							// und das alte Bild gelöscht wäre
							
							/********************************************************/
							/******************** FILE UPLOAD ***********************/
							

							// Prüfen, ob eine neue Bilddatei hochgeladen wurde:
							
							if($_FILES['image']['tmp_name']){
if(DEBUG)						echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Eine neue Bilddatei wird geladen...<i>(" . basename(__FILE__) . ")</i></p>";		
								
								$image 					= $_FILES['image'];
								$imageUploadReturnArray = imageUpload($image);
								
								//Prüfen, ob es einen Bildupload-Fehler gab:
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
							}
							/***************** FILE UPLOAD ENDE *********************/
							/********************************************************/
							
							// Abschließende Formularprüfung:
							
							if(!$errorImageUpload){
if(DEBUG) 						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Das Formular ist fehlerfrei, die Daten können in DB aktualisiert werden<i>(" . basename(__FILE__) . ")</i></p>";									
								
								/******************* DB-Operation ********************/
								
								$statement = $pdo->prepare("UPDATE blogs SET	
															blog_headline 		= :ph_blog_headline, 
															blog_image 			= :ph_blog_image, 
															blog_imageAlignment = :ph_blog_imageAlignment, 
															blog_content 		= :ph_blog_content,
															cat_id 				= :ph_cat_id, 
															usr_id 				= :ph_usr_id
													WHERE	blog_id 			= :ph_blog_id
															");
								
								$statement->execute(array(
														"ph_blog_headline" 		=> $headline,
														"ph_blog_image" 		=> $image,
														"ph_blog_imageAlignment"=> $imageAlignment,
														"ph_blog_content" 		=> $content,
														"ph_cat_id" 			=> $category,
														"ph_usr_id" 			=> $_SESSION['usr_id'],
														"ph_blog_id"			=> $_SESSION['blog_id']
															) ) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>");
								
								$editedEntries = $statement->rowCount();
if(DEBUG)						echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Beitrag wurde erfolgreich aktualisiert, editedEntries: $editedEntries <i>(" . basename(__FILE__) . ")</i></p>";
								
								// Prüfen, ob der Eintrag gespeichert wurde:
								if(!$editedEntries){
									// Fehlerfall:
if(DEBUG)							echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Es ist ein Fehler aufgetreten beim Speichern  <i>(" . basename(__FILE__) . ")</i></p>";									
									$entryMessage = "<p class='error'>Es ist ein Fehler aufgetreten, versuchen Sie es nochmal</p>";
								}else{
									//Erfolgsfall:
									
if(DEBUG)							echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Der Eintrag wurde erfolgreich aktualisiert. <i>(" . basename(__FILE__) . ")</i></p>";
									$entryMessage = "<p class='success'>Eintrag <b>\"$headline\"</b> wurde erfolgreich aktualisiert.</p>";									
								
									
									// Flag "Edit" am Ende löschen:
									$_SESSION['edit'] = NULL; 
									
									// Felder leeren:
									$category 		= NULL;
									$headline 		= NULL;
									$image 			= NULL;
									$imageAlignment = NULL;
									$content 		= NULL;
									
									
								}
								
							}
													
						}
												
					} // Formularabschicken - Ende
					
				}else{
					// Nur wenn das Editmodus ausgeschaltet ist, neuen Eintrag Verfassen:
/**********************************************************************************************/

					/********************************************************/
					/***************** FORMULARVERARBEITUNG *****************/
					/********************************************************/
					
					/***************** für Blogformular  ********************/
					/*************** Neuen Eintag verfassen *****************/
					
					
					if(isset($_POST['formsentNewEntry'])){
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Formular 'NewEntry' wurde abgeschickt. <i>(" . basename(__FILE__) . ")</i></p>";
						
						$category 		= cleanString($_POST['category']);
						$headline 		= cleanString($_POST['headline']);
						$imageAlignment = cleanString($_POST['imageAlignment']);
						$content 		= cleanString($_POST['content']);
						
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: category = $category <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: headline = $headline <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: imageAlignment = $imageAlignment <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: content = $content <i>(" . basename(__FILE__) . ")</i></p>";
						
						$errorHeadline 	= checkInputString($headline);
						$errorText 		= checkInputString($content, 10, 10000); 
						
						if($errorHeadline || $errorText){
							// Fehlerfall:
if(DEBUG) 					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Das Formular enthält noch Fehler <i>(" . basename(__FILE__) . ")</i></p>";							
								
						}else{
							// Efolgsfall:
							
							// Nur wenn Formularfelder fehlerfrei sind, soll der Bildupload durchgeführt werden,
							// da ansonsten trotz Feld-Fehler im Formular das neue Bild auf dem Server gespeichert 
							// und das alte Bild gelöscht wäre
							
							/********************************************************/
							/******************** FILE UPLOAD ***********************/
							

							// Prüfen, ob eine Bilddatei hochgeladen wurde
							if($_FILES['image']['tmp_name']){
if(DEBUG) 						echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Bildupload aktiv...<i>(" . basename(__FILE__) . ")</i></p>";		
								
								$image 					= $_FILES['image'];
								$imageUploadReturnArray = imageUpload($image);
								
								//Prüfen, ob es einen Bildupload-Fehler gab:
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
								
							}
							
							/***************** FILE UPLOAD ENDE *********************/
							/********************************************************/

								
							// Abschließende Formularprüfung:
							
							if(!$errorImageUpload){
								
if(DEBUG) 						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Das Formular ist fehlerfrei, die Daten können in DB geschrieben werden<i>(" . basename(__FILE__) . ")</i></p>";	
								
								
								/******************* DB-Operation ********************/
								
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
								
								$statement->execute(array(
														"ph_blog_headline" 		=> $headline,
														"ph_blog_image" 		=> $image,
														"ph_blog_imageAlignment"=> $imageAlignment,
														"ph_blog_content" 		=> $content,
														"ph_cat_id" 			=> $category,
														"ph_usr_id" 			=> $_SESSION['usr_id']
															) ) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>"); 
								
								// Last Insert-ID abholen und prüfen
							
								$newEntryId = $pdo->lastInsertId();
if(DEBUG)						echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$newEntryId: $newEntryId <i>(" . basename(__FILE__) . ")</i></p>";		
								
								// Prüfen, ob der Eintrag gespeichert wurde:
								if(!$newEntryId){
									// Fehlerfall:
if(DEBUG)							echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Fehler beim Speichern des neuen Eintrags <i>(" . basename(__FILE__) . ")</i></p>";
									$entryMessage = "<p class='error'>Es ist ein Fehler aufgetreten, versuchen Sie es nochmal</p>";
																	
								}else{
									// Erfolgsfall:
if(DEBUG)							echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Neuer Eintrag wurde mit der ID $newEntryId erfolgreich geschpeichert. <i>(" . basename(__FILE__) . ")</i></p>";
									$entryMessage = "<p class='success'>Eintrag <b>\"$headline\"</b> wurde erfolgreich gescheichert.</p>";
									
									// Felder leeren:
									$category 		= NULL;
									$headline 		= NULL;
									$image 			= NULL;
									$imageAlignment = NULL;
									$content 		= NULL;
									
								} // Prüfen, ob Eintrag gespeichert wurde - Ende
								
							} // Eintrag in DB speichern - Ende
							
						} // Fileupload - Ende
					
					} // Formularverarbeitung für Blogformular - Ende
				
				
				}
				

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
							

					// Editmodus ausschalten:
							
					}elseif($action == "deleteEditMode"){
						$_SESSION['edit'] = NULL;
						
						//Felder leeren:
						$category 		= NULL;
						$headline 		= NULL;
						$image 			= NULL;
						$imageAlignment = NULL;
						$content 		= NULL;
						
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
				
				<!------- Begrüßung und Logout-Link --------->
			
				<p>Hallo, <?=$_SESSION['usr_firstname']?>!  |  <a href="?action=logout">Logout</a></p>
				<p><a href="index.php?action=deleteEditMode"><< Zum Frontend</a></p>
			</div>
		</header>
		<br>
		<div class="wrapper-dashboard">
			<div class="blog-headline">
				<h1>Blog über Essen - Dashboard</h1>
				<h2>Aktiver Benutzer: <?=$_SESSION['usr_firstname']?> <?=$_SESSION['usr_lastname']?></h2>
			</div>
			
			
			<!------------- Formular Neuer Eintrag / Redaktieren ---------->
			<?php
				if(isset($_SESSION['edit'])){
					$mainClass = "edit-entry";
					$asideClass = "edit-category";
				} else {
					$mainClass = "new-entry";
					$asideClass = "new-category";
				}
			?>
			<main class="new-entry <?=$mainClass?>">
				<?php
					if(isset($_SESSION['edit'])){
						$header = "Blog-Eintrag Redaktieren";
					}else{
						$header = "Neuen Blog-Eintrag Verfassen";
					}
				?>
				<h3><?=$header?></h3>
				
				<?php if(isset($_SESSION['edit'])): ?>
					<h5><a href="?action=deleteEditMode">Neuer Eintrag</a></h5>
				<?php endif ?>
				
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST" enctype="multipart/form-data">
					<span><?=$entryMessage?></span>
					<input type="hidden" name="formsentNewEntry">
					<select class="category" name="category">
						<?php foreach($categoriesArray AS $categoryRow): ?>
							<?php 
								if($categoryRow['cat_id'] == $category){
									echo "<option value=\"$categoryRow[cat_id]\" selected>";
								}else{
									echo "<option value=\"$categoryRow[cat_id]\">";
								}
							?>
							<?=$categoryRow['cat_name']?>
							</option>
						<?php endforeach ?>
					</select>
					<br>
					<span class="error"><?=$errorHeadline?></span><br>
					<input type="text" name="headline" placeholder="Überschrift" class="headline" value="<?=$headline?>"> <br>
					
					<span class="error"><?=$errorImageUpload?></span>
					<br>
					<input type="file" name="image" class="file">
					<select class="imageAlignment" name="imageAlignment">
					
					<?php 
						
												
						if($imageAlignment == "right"){
							echo "<option value='left'>Align left</option>";
							echo "<option value='right' selected>Align right</option>";
						} else {
							echo "<option value='left' selected>Align left</option>";
							echo "<option value='right'>Align right</option>";	
						}		
						
						?>
						
					</select><br>
					<?php
						if(isset($_SESSION['edit'])){
							
							if(isset($image)){
								echo "<img src='$image' class='edit-image'>";
							}
					
						}
					
					?>
					
					
					<span class="error"><?=$errorText?></span><br>
					<textarea name="content" placeholder="Text..."><?=$content?></textarea>
					<br>
					<input type="submit" value="Veröffentlichen" class="button">
				</form>
			</main>
			
			<!----------- Formular Neue Kategorie ----------->
			
			<aside class="new-category <?=$asideClass?>">
				<h3>Neue Kategorie anlegen</h3>
				
				<br>
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
					<span><?=$categoryMessage?></span><br>
					<input type="hidden" name="formsentNewCategory">
					<input type="text" name="newCategory" value="<?=$newCategory?>"><br>
					<input type="submit" value="Kategorie anlegen" class="button">
				</form>
			</aside><br>
		</div>
		<footer class="clear">
			<p>Copyright Irina Serdiuk</p>
		</footer>		
		
	</body>

</html>