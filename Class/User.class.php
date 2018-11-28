
<?php
/*******************************************************************************************/


				/***************************************/
				/************* CLASS USER **************/
				/***************************************/
				
				
				class User {
					
				/***************************************/
				/*************** ATTRIBUTES ************/
				/***************************************/
					private $usr_id;
					private $usr_firstname;
					private $usr_lastname;
					private $usr_email;
					private $usr_city;
					private $usr_password;
					
					
/*******************************************************************************************/	

					/***************************************/
					/************* KONSTRUKTOR *************/
					/***************************************/
					
					public function __construct(
												$usr_email 		= NULL, 
												$usr_password 	= NULL,
												$usr_id 		= NULL, 
												$usr_firstname 	= NULL, 
												$usr_lastname 	= NULL,
												$usr_city 		= NULL												
												){
if(DEBUG_C)				echo "<h3 class='debugClass'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "($usr_email,  $usr_password, $usr_id, $usr_firstname, $usr_lastname,  $usr_city)  (<i>" . basename(__FILE__) . "</i>)</h3>";						
						// Setter nur aufrufen, wenn der jeweilige Parameter einen gültigen Wert enthält
						if($usr_email) 			$this->setUsr_email($usr_email);
						if($usr_password) 		$this->setUsr_password($usr_password);
						if($usr_id) 			$this->setUsr_id($usr_id);
						if($usr_firstname) 		$this->setUsr_firstname($usr_firstname);
						if($usr_lastname)		$this->setUsr_lastname($usr_lastname);
						if($usr_city) 			$this->setUsr_city($usr_city);

if(DEBUG_C)				echo "<pre class='debugClass'><b>Line  " . __LINE__ .  "</b> <i>(" . basename(__FILE__) . ")</i>:<br>";					
if(DEBUG_C)				print_r($this);					
if(DEBUG_C)				echo "</pre>";	
					}
				
				
/*******************************************************************************************/	
				
					/*************************************/
					/********** GETTER & SETTER **********/
					/*************************************/
					
					/*************** usr_id **************/
				
					public function getUsr_id(){
						return $this->usr_id;
					}
					
					public function setUsr_id($usr_id){
						$this->usr_id = cleanString($usr_id);
					}
					
					/*********** usr_firstname ***********/
				
					public function getUsr_firstname(){
						return $this->usr_firstname;
					}
					
					public function setUsr_firstname($usr_firstname){
						$this->usr_firstname = cleanString($usr_firstname);
					}
					
					/************* usr_lastname ***********/
				
					public function getUsr_lastname(){
						return $this->usr_lastname;
					}
					
					public function setUsr_lastname($usr_lastname){
						$this->usr_lastname = cleanString($usr_lastname);
					}
					
					/*************** usr_email *************/
					
					public function getUsr_email(){
						return $this->usr_email;
					}
					
					public function setUsr_email($usr_email){
						$this->usr_email = cleanString($usr_email);
					}
					
					
					/************** usr_city ***************/
				
					public function getUsr_city(){
						return $this->usr_city;
					}
					
					public function setUsr_city($usr_city){
						$this->usr_city = cleanString($usr_city);
					}
					
					/************* usr_password ************/
				
					public function getUsr_password(){
						return $this->usr_password;
					}
					
					public function setUsr_password($usr_password){
						$this->usr_password = cleanString($usr_password);
					}
					
					/***** virtuelles Attribut Fullname *****/
					
					public function getUsr_Fullname() {
						return $this->getUsr_firstname() . " " . $this->getUsr_lastname();
					}
					
					
/*******************************************************************************************/	
									
					/**************************************/
					/************** METHODEN **************/
					/**************************************/
					
					/************************************************/
					/********** Datensatz aus DB auslesen ***********/
					/************************************************/
					
					/** 
					*
					*	Holt User-Object-Daten aus DB
					*	Schreibt alle Daten aus Datensatz in ein entscprechenden Object
					*	
					*	@param 		PDO		DB-Connection Object
					*
					*	@return 	Boolean	true, wenn der Datensatz geliefert wurde, ansonsten false
					*
					*/
					
					public function fetchFromDb($pdo){
if(DEBUG_C)				echo "<h3 class='debugClass'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>";						
						
						$sql = "SELECT * FROM user
								WHERE 	usr_email = ?
								OR		usr_id = ?
								";
						$params = array($this->getUsr_email(),
										$this->getUsr_id());
						$statement = $pdo->prepare($sql);
						$statement->execute($params) OR DIE( "<p class='debug err'>Line <b>" . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>" );
						
						if( !$row = $statement->fetch() ) {
							// Fehlerfall:
							return false;
							
						} else {
							// Erfolgsfall:
							// Daten in Objekt schreiben
							$this->setUsr_id($row['usr_id']);
							$this->setUsr_firstname($row['usr_firstname']);
							$this->setUsr_lastname($row['usr_lastname']);
							$this->setUsr_email($row['usr_email']);
							$this->setUsr_city($row['usr_city']);
							$this->setUsr_password($row['usr_password']);
							
if(DEBUG_C)					echo "<pre class='debugClass'><b>Line  " . __LINE__ .  "</b> <i>(" . basename(__FILE__) . ")</i>:<br>";					
if(DEBUG_C)					print_r($this);					
if(DEBUG_C)					echo "</pre>";							
							
							return true;
						}
					}
					
					
				
				
				
				
				
				
				}
/*******************************************************************************************/				
?>