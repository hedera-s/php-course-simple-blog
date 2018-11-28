<?php
/*******************************************************************************************/


				/***************************************/
				/********** CLASS CATEGORY *************/
				/***************************************/
				
				// Represents category of Entry
				
				class Category {
					
				/************************************/
				/************* ATTRIBUTES ***********/
				/************************************/
					
					private $cat_id;
					private $cat_name;
					
					
					
/*******************************************************************************************/	

					/*************************************/
					/*********** KONSTRUKTOR *************/
					/*************************************/
					
					public function __construct(
												$cat_id = NULL,
												$cat_name = NULL
																							
												){
if(DEBUG_C)				echo "<h3 class='debugClass'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "( $cat_id, $cat_name)  (<i>" . basename(__FILE__) . "</i>)</h3>";						
						// Setter nur aufrufen, wenn der jeweilige Parameter einen gültigen Wert enthält
						
						if($cat_id) 			$this->setCat_id($cat_id);
						if($cat_name) 			$this->setCat_name($cat_name);
						
if(DEBUG_C)				echo "<pre class='debugClass'><b>Line  " . __LINE__ .  "</b> <i>(" . basename(__FILE__) . ")</i>:<br>";					
if(DEBUG_C)				print_r($this);					
if(DEBUG_C)				echo "</pre>";						
	
					}
				
				
/*******************************************************************************************/		
			
					/*************************************/
					/********** GETTER & SETTER **********/
					/*************************************/
					
					/************** cat_id ****************/
				
					public function getCat_id(){
						return $this->cat_id;
					}
					
					public function setCat_id($cat_id){
						$this->cat_id = cleanString($cat_id);
					}
					
					/*************** cat_name **************/
				
					public function getCat_name(){
						return $this->cat_name;
					}
					
					public function setCat_name($cat_name){
						$this->cat_name = cleanString($cat_name);
					}
					
										
/*******************************************************************************************/	
									
					/**************************************/
					/************** METHODEN **************/
					/**************************************/
					
					/*****************************************************/
					/********** Alle Kategorien aus DB auslesen **********/
					/*****************************************************/
					
					/** 
					*	Statische Methode.
					*	Holt alle Category-Object-Daten aus DB
					*	Schreibt alle Daten aus Datensatz in entscprechende Objekte
					*
					*	@param 	PDO		DB-Connection Object
					*
					*	@return 		Array (assotiatives) mit Category-Objekte.
					*
					*/
					
					public static function fetchFromDb($pdo){
if(DEBUG_C)				echo "<h3 class='debugClass'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
						
						$sql = "SELECT * FROM category ORDER BY cat_name";
						$params = NULL;
						
						$statement = $pdo->prepare($sql);
						$statement->execute($params) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . "ERROR: You have an error in your SQL-syntax. Check near... <i>(" . basename(__FILE__) . ")</i></p>" );
						
						while($row = $statement->fetch(PDO::FETCH_ASSOC)){
							// Je Datensatz ein Objekt erstellen und in Array speichern
							$categoriesArray[] = new Category( $row['cat_id'], $row['cat_name']);
							
						}
						
						return $categoriesArray;
						
					}
					
					/****************************************************/
					/********** Prüfen, ob die eingegebe ****************/
					/******* Kategorie in DB bereits existiert **********/
					/****************************************************/
					
					/** 
					*
					*	Prüft, ob die eingegebe cat_name bereits existiert in DB
					*
					*	@param 	PDO		DB-Connection Object
					*
					*	@return 		Int 	Die Summe von gefundener Datensätze
					*
					*/
					
					
					public function categoryExists($pdo){
if(DEBUG_C)				echo "<h3 class='debugClass'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "(\$pdo) (<i>" . basename(__FILE__) . "</i>)</h3>";						
						
						$sql = "SELECT * FROM category 
								WHERE 	cat_id = ? 
								OR 		cat_name = ?";
						$params = array($this->getCat_id(), $this->getCat_name());
						
						$statement = $pdo->prepare($sql);
						$statement->execute($params) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" );
						return $statement->fetchColumn();
						
					}
					
					/***********************************************/
					/********** Neue Kategorie anlegen *************/
					/***********************************************/
					
					/** 
					*
					*	Speichert neues Category-Objekt in DB
					*						
					*	@param 	PDO		DB-Connection Object
					*
					*	@return 		Int 	ID des gespeicherten Datensatzes
					*
					*/
					
					public function saveToDb($pdo){
if(DEBUG_C)				echo "<h3 class='debugClass'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "(\$pdo) (<i>" . basename(__FILE__) . "</i>)</h3>";						
						$sql = "INSERT INTO category (cat_name)
								VALUES (?)";
						$params = array($this->getCat_name());
						
						$statement = $pdo->prepare($sql);
						$statement->execute($params) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>"); 
				
						// Last Insert-ID abholen und prüfen:
						return $newCategoryId = $pdo->lastInsertId();
						
					}
					
					
					
					
/*******************************************************************************************/				
				}
					