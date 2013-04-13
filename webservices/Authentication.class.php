<?php

class Authentication{
  private $Username;
  private $Password;

  /**
   * Todo.
   *
   * @param string $username Username.
   * @param string $password Password.
   */
  public function __construct($username, $password)
  {
    $this->Username = $username;
    $this->Password = $password;
  }
}

?>
