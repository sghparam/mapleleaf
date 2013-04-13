<?php
require_once 'Authentication.class.php';
require_once 'QueryHeader.class.php';
require_once 'Wsdl.class.php';


class Registration
{

  protected $username = '';
  protected $password = '';
    
  /**
  *  Constructor.
  * 
  * @param string $sEndpointURL Endpoint URL.
  */
  public function __construct($sEndpointURL = null)
  {
    $this->Wsdl = new Wsdl();
  
    if (empty($sEndpointURL)) {
      $sEndpointURL = $this->Wsdl->getWsdlCacheFilename();
    }


    $this->soap=new SoapClient($sEndpointURL, array(
                                                'soap_version'       => SOAP_1_2, 
                                                'exceptions'         => 0,
                                                'connection_timeout' => 20,
                                                'classmap'           => array(
                                                                          'Authentication' => 'Authentication',
                                                                          'QueryHeader'    => 'QueryHeader',
                                                                         ),
                                                'trace'              => true,
                                              )
    );



    if (is_soap_fault($this->soap)) {
      $this->soap=null;
    }
  }
  
  
  /**
  * Add authentication details to soap header.
  *
  * @return void
  */
  private function build_auth_header()
  {
    $queryheader = new QueryHeader($this->username, $this->password);
    
    $authheader = new SoapHeader($this->soap_namespace, 'QueryHeader', $queryheader);
    
    $this->soap->__setSoapHeaders(array($authheader));
  }
  

  /**
  * Check a result for a soap fault object, and log it to the PHP log channel.
  * 
  * @param object $soapResult SOAP Result.
  *
  * @return object SOAP Result.
  */
  public function check_soap($soapResult)
  {
    if (is_soap_fault($soapResult)) {
      $err = "SOAP Fault - " . "Code: {" . $soapResult->faultcode . "}, " . "Description: {" . $soapResult->faultstring . "}";

      error_log($err, 0);

      $soapResult=null;
      throw new Exception($err);
    }
    return ($soapResult);
  }
  

  /**
  * GetSoapResult Get SOAP Result.
  *
  * @return string Soap Fault.
  */
  public function getSoapFault() 
  {
    return (isset($this->soap->__soap_fault) ? $this->soap->__soap_fault->faultstring : null);
  }


  /**
  * Setup a fault string for display.
  *
  * @param string $sFault Fault string.
  *
  * @return string Soap Fault.
  */
  public function getFaultString($sFault)
  {
    if ((!is_string($sFault) || $sFault == "") && ($this->getSoapFault() != null)) {
      return ("[" . $this->getSoapFault() . "]");
    }
    return ($sFault);
  }
  
  
   /**
   * Create Registration Method (CreateRegistration).
   * 
   * @param string $firstName           Customer's first name.
   * @param string $familyName          Customer's family name.
   * @param string $email               Customer's email address.
   * @param string $mobile              Customer's mobile number.
   * @param string $postcode            Postcode.
   * @param string $currentRetailer     Customer's current service provider.
   * @param string $discoverDescription How did customer discover origin smart.
   * @param string $dateOfBirth         Customer's date of birth.  
   * 
   * @return array Response code and Registration ID.
   */
  public function CreateRegistration($firstName, $familyName, $email, $mobile, $postcode, $currentRetailer, $discoverDescription, $dateOfBirth)
  {
    
    $this->build_auth_header();

    # Build  arguments
    $args = array(
             "FirstName"           => $firstName,
             "FamilyName"          => $familyName,
             "Email"               => $email,
             "MobilePhoneNo"       => $mobile,
             "Postcode"            => $postcode,
             "CurrentRetailer"     => $currentRetailer,
             "DiscoverDescription" => $discoverDescription,
             "DateOfBirth"         => $dateOfBirth,
            );

       
    # Perform the web service call and create a CreateRegistration instance with the result
    $result = $this->check_soap($this->soap->CreateRegistration($args));
    

    return array(
            'Code' => $result->CreateRegistrationResult->Code, 
            'ID'   => $result->CreateRegistrationResult->ID,
           ); 
  }
  
  
  
}
  
  