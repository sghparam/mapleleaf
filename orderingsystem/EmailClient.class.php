<?php

class EmailClient{

  private $to; 
  private $from;
  private $subject; 
  private $message; 
  private $cc;
  private $bcc;
  private $reply_to;
  private $headers;

  
  /**
   * Set headers.
   * 
   * @return mixed
   */
  private function setHeaders()
  {

    $this->headers  = "MIME-Version: 1.0rn"
                       . "From: ". $this->from . "\r\n"
                       . "To: " . $this->to . "\r\n";
 
    if ( !empty($this->reply_to) ) $this->headers .= "Reply-To: " . $this->reply_to . "\r\n";
    if ( !empty($this->cc) ) $this->headers .= "Cc: " . $this->cc . "\r\n"; 
    if ( !empty($this->bcc) ) $this->headers .= "Bcc: " . $this->bcc . "\r\n"; 
 
    $this->headers .= "X-Priority: 1\r\n"
                   . "X-Mailer: PHP/" . phpversion() . "\r\n"
                   . "Content-type: text/html; charset=iso-8859-1\r\n";
                 
  }

  /**
   * Send email.
   *
   * @param string $_to       To.
   * @param string $_from     FROM.
   * @param string $_subject  Subject of email.
   * @param string $_message  Message text.
   * @param string $_cc       Optional CC.
   * @param string $_bcc      Optional BCC.
   * @param string $_reply_to Reply to.
   * 
   * @return boolean
   */
   
  public static function sendEmail($_to, $_from, $_subject, $_message, $_cc = '', $_bcc = '', $_reply_to = '')
  {
  
    self::$to = $_to; 
    self::$from = $_from; 
    self::$subject = $_subject;
    self::message = $_message;
    self::$cc = $_cc;
    self::$bcc = $_bcc;
    self::$reply_to = $_reply_to;
    
    self::setHeaders();

    if (mail ( self::$to, self::$subject, self::$message, self::$headers)) {
      return true;
    }     
    
    return false;
    

  }



}