<?php

class Interfax{

	private $username = "";
	private $password = "";
	private $filename = "";
	private $filetype = "HTML";
	private $postponetime = '2001-12-31T00:00:00-00:00';  // don't postpone
	private $retries = "3";
	private $csid = "AA CSID";
	private $pageheader = "To: {To} From: {From} Pages: {TotalPages}";
	private $subject = "Anything goes";
	private $replyemail = "";
	private $page_size = "A4";
	private $page_orientation = "Portrait";
	private $high_resolution = FALSE;
	private $fine_rendering = TRUE;
	private $page = ""; // URL from which the form may be posted
	
	private $errcount = 0;
	private $name = "";
	private $faxnumber = "";
	
	private $wsdl_remote_uri = "http://ws.interfax.net/dfs.asmx?WSDL" ;
	private $soap = NULL;
	public $wsdl_error; 
	
	public $faxResult;
	
	/*
	* Constructor.
	*/
	public function __construct()
    {  
		
	  if ($this->checkWsdlUri($this->wsdl_remote_uri)) {
	  
        $this->soap=new SoapClient($this->wsdl_remote_uri,
        array(
         'soap_version'       => SOAP_1_2, 
         'exceptions'         => 0,
         'connection_timeout' => 20,
         'trace'              => TRUE,
        ));
		
      } else {
        return false;
      }
	
    }
	
	/*
	* Check if the wsdl is valid.
	*
	* @param string $uri WSDL Uri.
	*
	* @return boolean
	*/
	private function checkWsdlUri($uri)
	{
	  $valid = true;
		
      if (!http_request(HTTP_METH_PUT, $uri)) {
        $this->wsdl_error = "Web service is currently not available.\n";
        $valid = false;
      } 

      return $valid ;
	}
	
	/*
	* Method to send fax.
	* 
	* @param string $template Fax template.
	*
	* @return void
	*/
	public function sendFax($template){
	
		// load all Web Service parameters
		$params->Username          =  $this->username;
		$params->Password          =  $this->password;
		$params->FaxNumbers        =  $this->faxnumber;
		$params->Contacts          =  $this->name;
		$params->FilesData         =  $template;
		$params->FileTypes         =  $this->filetype;
		$params->FileSizes         =  strlen($template) ;
		$params->Postpone          =  $this->postponetime;
		$params->RetriesToPerform  =  $this->retries;
		$params->CSID              =  $this->csid;
		$params->PageHeader        =  $this->pageheader;
		$params->JobID             =  '';
		$params->Subject           =  $this->subject;
		$params->ReplyAddress      =  $this->replyemail;
		$params->PageSize          =  $this->page_size;
		$params->PageOrientation   =  $this->page_orientation;
		$params->IsHighResolution  =  $this->high_resolution; 
		$params->IsFineRendering   =  $this->fine_rendering;
			
		// dispatch the fax

		$result = $this->soap->SendfaxEx_2($params);
		
		// capture return value
		$this->faxResult = $result->SendfaxEx_2Result;
	
	}
	
	

}
