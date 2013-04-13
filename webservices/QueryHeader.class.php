<?php

class QueryHeader{
  
  private $Authentication;
  
  private $Security;
  
  /**
   * Constructor.
   * 
   * @param string $username User name.
   * @param string $password Password.
   */
  public function __construct($username, $password) 
  {
    $this->Authentication = new Authentication($username, $password);
    $this->Security = null;
  }
}

?>