<?php

class Database{

	private $db_host = '';
	private $db_user = '';
	private $db_pass = '';
	private $db_name = ''; 
	private static $connection; 
	private $result = array(); 
	
 /**
  * Constructor.
  * Private function to be called internally.
  */
      
	private function __construct()
	{
		
   $this->connection = mysql_connect($this->db_host,$this->db_user,$this->db_pass);
   
   if ($this->connection) {
     return mysql_select_db($this->db_name, $this->connection);
   } else {
     return false;
   }
        
	}
    
 /**
  * Set connection. Singlton to return database connection
  * 
  * @return mixed
  */
    
    
 public static function setConnection () {
      
   if (!self::$connection) {
     self::$connection = new Database;
   }
   
   return self::$connection;
 }
	
	
    
}




