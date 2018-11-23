
<?php
/*******************************************************************************************/


				/***************************************/
				/************* CLASS BLOG **************/
				/***************************************/
				
				// Represents blog-entries
				
				class Blog {
					
					/*******************************/
					/********** ATTRIBUTES *********/
					/*******************************/
					
					private $blog_id;
					private $blog_headline;
					private $blog_image;
					private $blog_imageAlignment;
					private $blog_content;
					private $blog_date;
					private $category;
					private $user;
					
					
/*******************************************************************************************/	

					/*********************************/
					/********** KONSTRUKTOR **********/
					/*********************************/
					
					public function __construct(
												
												$blog_headline 			= NULL, 
												$blog_imageAlignment 	= NULL,
												$blog_content 			= NULL, 
												$category 				= NULL, 
												$user 					= NULL,
												$blog_image 			= NULL,
												$blog_id 				= NULL,																							
												$blog_date 				= NULL											
												){
if(DEBUG_C)				echo "<h3 class='debugClass'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</h3>";						
						// Setter nur aufrufen, wenn der jeweilige Parameter einen gültigen Wert enthält
						if($blog_id) 			$this->setBlog_id($blog_id);
						if($blog_headline) 		$this->setBlog_headline($blog_headline);
						if($blog_image)			$this->setBlog_image($blog_image);
						if($blog_imageAlignment)$this->setBlog_imageAlignment($blog_imageAlignment);
						if($blog_content) 		$this->setBlog_content($blog_content);
						if($blog_date) 			$this->setBlog_date($blog_date);
						if($category) 			$this->setCategory($category);
						if($user) 				$this->setUser($user);
								
						
						
						
if(DEBUG_C)				echo "<pre class='debugClass'><b>Line  " . __LINE__ .  "</b> <i>(" . basename(__FILE__) . ")</i>:<br>";					
if(DEBUG_C)				print_r($this);					
if(DEBUG_C)				echo "</pre>";	
					}
				
				
/*******************************************************************************************/	
				
					/*************************************/
					/********** GETTER & SETTER **********/
					/*************************************/
					
				/***************** blog_id ******************/
				
					public function getBlog_id(){
						return $this->blog_id;
					}
					
					public function setBlog_id($blog_id){
						$this->blog_id = cleanString($blog_id);
					}
					
				/**************** blog_headline **************/
				
					public function getBlog_headline(){
						return $this->blog_headline;
					}
					
					public function setBlog_headline($blog_headline){
						$this->blog_headline = cleanString($blog_headline);
					}
					
				/****************** blog_image ***************/
				
					public function getBlog_image(){
						return $this->blog_image;
					}
					
					public function setBlog_image($blog_image){
						$this->blog_image = cleanString($blog_image);
					}
					
				/************ blog_imageAlignment ************/
					
					public function getBlog_imageAlignment(){
						return $this->blog_imageAlignment;
					}
					
					public function setBlog_imageAlignment($blog_imageAlignment){
						$this->blog_imageAlignment = cleanString($blog_imageAlignment);
					}
					
					
				/*************** blog_content ****************/
				
					public function getBlog_content(){
						return $this->blog_content;
					}
					
					public function setBlog_content($blog_content){
						$this->blog_content = cleanString($blog_content);
					}
					
				/****************** blog_date ****************/
				
					public function getBlog_date(){
						return $this->blog_date;
					}
					
					public function setBlog_date($blog_date){
						$this->blog_date = cleanString($blog_date);
					}
					
				/******************* category ****************/
				
					public function getCategory(){
						return $this->category;
					}
					
					public function setCategory($category){
						if(!$category instanceof Category){
if(DEBUG_C)					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: category muss ein Objekt der Klasse Category sein <i>(" . basename(__FILE__) . ")</i></p>";								
						}else{
							$this->category = $category;
						}
					}
					
				/******************** user *******************/
				
					public function getUser(){
						return $this->user;
					}
					
					public function setUser($user){
						if(!$user instanceof User){
if(DEBUG_C)					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: user muss ein Objekt der Klasse User sein <i>(" . basename(__FILE__) . ")</i></p>";								
						}else{
							$this->user = $user;
						}
					}
					
/*******************************************************************************************/	
									
					/**************************************/
					/************** METHODEN **************/
					/**************************************/
					
				/******* STATISCHE METHODE ZUM AUSLESEN ********/
				/************ ALLER BEITRÄGE AUS DB ************/
				
					public static function fetchAllEntriesFromDb($pdo, $categoryToShow=NULL){
if(DEBUG_C)				echo "<h3 class='debugClass'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "(\$pdo, $categoryToShow) (<i>" . basename(__FILE__) . "</i>)</h3>";							
						
						$sql = "SELECT * FROM blog 
								INNER JOIN user 	USING(usr_id)
								INNER JOIN category USING(cat_id)
								WHERE cat_id = ?
								ORDER BY blog_id DESC";
						
						$params = array($categoryToShow);
						$statement = $pdo->prepare($sql);
						$statement->execute($params) OR DIE( "<p class='debug'>Line <b>" . __LINE__ . "</b>: " . "ERROR: You have an error in your SQL-syntax. Check near... <i>(" . basename(__FILE__) . ")</i></p>" );
						
						$entriesArray = NULL;
						while($row = $statement->fetch(PDO::FETCH_ASSOC)){
						
						// Je Datensatz ein Objekt erstellen und in Array speichern:

							$entriesArray = array( new Blog	(	$row['blog_headline'],
															$row['blog_imageAlignment'],
															$row['blog_content'],
															new Category(	$row['cat_id'],
																			$row['cat_name']
																		),
															new User 	(	$row['usr_id'],
																			$row['usr_firstname'],
																			$row['usr_lastname'],
																			$row['usr_email'],
																			$row['usr_city'],
																			$row['usr_password']
																		),
															$row['blog_image'],
															$row['blog_id'],
															$row['blog_date']
															
														
							
							));
							
						}
						
						 return $entriesArray;
					}
						
						
								
						
					
					
				
				
				
				
				
				
				}
/*******************************************************************************************/				
?>