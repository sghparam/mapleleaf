<?php

require_once 'HTTP/Request.php';
require_once 'Server.class.php';

class Wsdl
{

  private $wsdl_remote_url = ''; // Remote URI
  private $wsdl_cache_filename = 'cache/wsdl/cached_wsdl_file.wsdl'; // cache file location
  private $wsdl_cache_file = '';

  private $min_filesize = 10000;

  /**
   * Constructor.
   */
  public function __construct()
  {
    $this->SERVER = new Server();
    $this->setWsdlCacheFilename($this->SERVER->getIncludeRoot() . $this->wsdl_cache_filename);
  }

  /**
   * Set the local WSDL cache filename.
   * 
   * @param string $filename The filename.
   * 
   * @return void
   */
  public function setWsdlCacheFilename($filename)
  {
    $this->wsdl_cache_file = $filename;
  }

  /**
   * Get the local WSDL cache filename.
   * 
   * @return string 
   */
  public function getWsdlCacheFilename()
  {
    return $this->wsdl_cache_file;
  }

  /**
   * Download and cache the WSDL file from the remote site.
   * 
   * @return boolean
   */
  public function getWsdlFile()
  {
    $error = '';
    $valid = true;

    print $this->wsdl_remote_url . "\n";

    $req = new HTTP_Request($this->wsdl_remote_url);
    $res = $req->sendRequest();
    if (PEAR::isError($res)) {
      $error = "WSDL fetch failed. Error was :\n". $res->getMessage()."\n";
      $valid = false;
      break;
    } else {
      $data = $req->getResponseBody();

      // If the downloaded data is greater than our minimum allowed data size, update
      // the WSDL cache file
      //
      if (strlen($data) > $this->min_filesize) {
        $fh = fopen($this->getWsdlCacheFilename(), 'w');
        fwrite($fh, $data);
        fclose($fh);
      } else {
        $error = "WSDL downloaded filesize too small. Cache file not overwritten. Please check WSDL manually.\n";
        $valid = false;
      }
    }

    return ($valid ? true : $error);
  }
}


?>
