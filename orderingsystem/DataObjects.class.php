<?php

require_once 'Database.class.php';

class DataObject {
    
  public function __construct()
  {
    
  }
  
   /**
   * Method to check if the table used in query exists
   * 
   * @return boolean
   */
  
  public function tableExists($dbname, $table)
  {
    $tablesInDb = mysql_query('SHOW TABLES FROM '.$dbname.' LIKE "'.$table.'"');
    
    if (mysql_num_rows($tablesInDb)==1) {
      return true;
    } 
    
    return false;
  }
    
  /**
   * Method to execute the select statement to fetch data from table.
   * 
   * @param string $table Table name.
   * @param mixed  $rows  Number of rows needed.
   * @param mixed  $where Where clause of sql statement.
   * @param mixed  $order Order by clause of sql statement.
   * 
   * @return mixed
   */
  public static function select($table, $rows = '*', $where = null, $order = null)
  {
    $q = 'SELECT '.$rows.' FROM '.$table;
    if ($where != null) {
      $q .= ' WHERE '.$where;
    }
    if ($order != null) {
     $q .= ' ORDER BY '.$order;
    }
    if (self::tableExists($table)) {
        echo $q;
      $query = mysql_query($q);
    }
    if ($query) {
          $this->numResults = mysql_num_rows($query);
     for ($i = 0; $i < $this->numResults; $i++) {
        $r = mysql_fetch_array($query);
        $key = array_keys($r);
       for ($x = 0; $x < count($key); $x++) {
         // Sanitizes keys so only alphavalues are allowed
         if (!is_int($key[$x])) {
          if (mysql_num_rows($query) > 1) {
             $this->result[$i][$key[$x]] = $r[$key[$x]];
           } else if (mysql_num_rows($query) < 1) {
             $this->result = null;
           } else {
              $this->result[$key[$x]] = $r[$key[$x]];
           }
         }
       }
     }
       return true;
         
    } else {
      return false;
    }
    
  }
    
    
  /**
   * Method to get the result of the executed query.
   * 
   * @return mixed
   */
  public static function getResult(){
  
    return $this->result;
  
  }
 
 
}


