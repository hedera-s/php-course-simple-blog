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
					
				/****************** cat_id *******************/
				
					public function getCat_id(){
						return $this->cat_id;
					}
					
					public function setCat_id($cat_id){
						$this->cat_id = cleanString($cat_id);
					}
					
				/****************** cat_name *****************/
				
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
					
					/********** Alle Kategorien aus DB auslesen **********/
					/** 
					*
					*	Sets sta_id in table account to 1
					*	Sets acc_reghash in table account to "valid"
					*	Sets acc_regtimestamp in table account to NULL
					*
					*	@param 	PDO		DB-Connection Object
					*
					*	@return 	Boolean	true if writing was successful, else false
					*
					*/
					
					public static function fetchAllCategoriesFromDb($pdo){
if(DEBUG_C)				echo "<h3 class='debugClass'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";
						
						$sql = "SELECT cat_name, cat_id FROM category ORDER BY cat_name";
						$params = NULL;
						
						$statement = $pdo->prepare($sql);
						$statement->execute($params) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . "ERROR: You have an error in your SQL-syntax. Check near... <i>(" . basename(__FILE__) . ")</i></p>" );
						
						while($row = $statement->fetch(PDO::FETCH_ASSOC)){
							// Je Datensatz ein Objekt erstellen und in Array speichern
							$categoriesArray[] = new Category( $row['cat_id'], $row['cat_name']);
							
						}
						
						return $categoriesArray;
						
					}
					
					public function categoryExists($pdo){
if(DEBUG_C)				echo "<h3 class='debugClass'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "(\$pdo) (<i>" . basename(__FILE__) . "</i>)</h3>";						
						
						$sql = "SELECT * FROM category WHERE cat_id = ?";
						$params = array($this->getCat_id());
						
						$statement = $pdo->prepare($sql);
						$statement->execute($params) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" );
						return $statement->fetchColumn();
						
					}
					
					
/*******************************************************************************************/				
				}
					