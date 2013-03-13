<?php

class EPrintsWrapper
{
    public $repoURL;
    public $username;
    public $password;
    public $EPrintID = 0;
    public $currentEPrintStructure;
    private $debug = 1;
    private $uploadsdir = "/tmp/uploads/";
    private $referer = "http://library.wit.ie/eprints/deposit/form";
    private $useragent = "MozillaXYZ/1.0";
    private $unique_stamp;
    private $errorMessage = "";
    const ERROR_VALUE = -1;
    
    private function debugOutput($string)
    {
        if($this->debug)
        {
            if(is_array($string))
            {
                print_r($string);
            }else{
                print $string;
            }
        }
    }
    
    function  __construct($servicedocument, $username, $password, $EPid = false)
    {
        $this->username = $username;
        $this->password = $password;
        $xmlStruct = $this->getEPrintsXMLFromURL($servicedocument, $username, $password);
        $repository = explode('/', str_replace('http://', '', $servicedocument));
        $this->unique_stamp = time();
	$this->repoURL = 'http://' . $repository[0] . '/'; $this->debugOutput("<h2 style=\"color: red\">".$this->repoURL."</h2>");
        if($EPid)
	{
            $this->EPrintID = $EPid;
        }
	else
	{
            $this->newEPrint();
        }
    }

    
    private function newEPrint()
    {
        // generate XML for new blank generic EPrint
        // read file, and turn into SimpleXMLElement();
	$filestring = "<?xml version='1.0' encoding='utf-8'?>
                        <eprints xmlns='http://eprints.org/ep2/data/2.0'>
                          <eprint>
                            <eprint_status>archive</eprint_status>
                            <userid>1</userid>
                            <type>image</type>
                            <creators>
                              <item>
                                <name>
                                  <family>Sword</family>
                                  <given>Tester</given>
                                </name>
                                <id>test@test.com</id>
                              </item>
                            </creators>
                            <title>using the eprintid from the initial creation</title>
                          </eprint>
                        </eprints>";
        $this->currentEPrintStructure = new SimpleXMLElement($filestring);
    }


    public function getErrorMessage()
    {
	return $this->errorMessage;
    }

    
    public function setXML($xmlString)
    {
	$this->currentEPrintStructure = new SimpleXMLElement($xmlString);
    }


    private function getEPrintsXMLFromURL($repoURL)
    {
        $username = $this->username;
        $password = $this->password;
        $ch = curl_init();          
        curl_setopt($ch, CURLOPT_URL, $repoURL);
        curl_setopt($ch, CURLOPT_REFERER, $this->referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);     
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/vnd.eprints.data+xml')); // using eprints xml
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username.":".$this->password); 
        curl_setopt($ch, CURLOPT_HEADER, 0);            // Include header in result? (0 = yes, 1 = no)   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should cURL return or print out the data? (true = return, false = print)   
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);          // Timeout in seconds     
        $output = curl_exec($ch);  // Download the given URL, and return output
        curl_close($ch);  // Close the cURL resource, and free system resources
        $this->debugOutput("<textarea>$output</textarea>");
        $outputstruct = new SimpleXMLElement($output);
        return $outputstruct;
    }
    public function getEPrintsMetadata(){
        if($this->EPrintID != 0)
        {
            $this->currentEPrintStructure = $this->getEPrintsXMLFromURL($this->repoURL . '/id/eprint/' . $this->EPrintID);
        }        
    }


    public function addFile($filepath, $EPrintID, $contenttype)
    {
        if($this->EPrintID == 0)
        {
            $this->errorMessage = "Eprint ID not set";
            return -1;
        }else{
            $handle = fopen($filepath, "r");
            $contentlen = filesize($filepath);
            $data = fread($handle, $contentlen);
            fclose($handle);
            $expl = explode('/', $filepath);
            $filename = $expl[count($expl)-1];
            $this->addFileData($filename, $data, $contenttype);
            return 1;
        }
        
    }
    private function addFileData($filename, $data, $contenttype)
    {
        
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $this->repoURL . "/id/eprint/" . $this->EPrintID . "/contents");
	//curl_setopt($ch, CURLOPT_URL, $EPrintURL);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: ' . $contenttype, "Content-Disposition: attachment; filename=$filename"));
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE); // --data-binary
	curl_setopt($ch, CURLOPT_USERPWD, $this->username.":".$this->password);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	
	// The headers returned are different from commitNewEPrint, there is now a leading 100 Continue
	// that needs to be removed
	$response = curl_exec($ch);
	curl_close($ch);  // Close the cURL resource, and free system resources

	list($responseHeader, $responseBody) = explode("\r\n\r\n", $response, 2);
	$statusCode = $this->checkStatusCode($responseBody);    // cannot just call checkStatusCode(), must use $this

	if($statusCode == 201)
	{
	    $this->debugOutput("<div class=\"textarea2\"><textarea rows='30' cols='120'>Output: ".$response."</textarea></div>");
	}
	else
	{
	    $this->errorMessage = $response;
	    return false;
	}
	return true;
    }


    public function commitNewEPrint()
    {
        $post = $this->currentEPrintStructure->asXML();
        $ch = curl_init();
        $fh = tmpfile();
        fwrite($fh, $post);
        fseek($fh, 0);
        $contentlen = strlen($post);
	curl_setopt($ch, CURLOPT_URL, $this->repoURL . "/id/contents");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/vnd.eprints.data+xml'));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username.":".$this->password);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
	curl_close($ch);              // Close the cURL resource, and free system resources
        fclose($fh);
        
	list($responseHeader, $responseBody) = explode("\r\n\r\n", $response, 2);
	$statusCode = $this->checkStatusCode($responseHeader);
	if($statusCode == 201)
	{
	    $this->EPrintID = $this->getEPrintID($responseBody);
	    $this->debugOutput("<textarea class=\"textarea2\">Output: ".$responseHeader."\n".$responseBody."</textarea>");
	    return $this->getEPrintID($responseBody);
	    //return $this->EPrintID;
	}
	else
	{
	    $this->errorMessage = $response;
	    return EPrintsWrapper::ERROR_VALUE;
	}
    }


    private function checkStatusCode($header)
    {
	$cleanedHeader = $this->cleanHeader($header);
	$pattern = '/^http\/1.1\s+(\d{3})\s+.*/';
	$headerLines = preg_split('/$\R?^/m', $header);$this->debugOutput($headerLines); $cnt = sizeOf($headerLines);
	foreach($headerLines as $line)
	{
	    $lowerCase = strtolower($line);
	    if($lowerCase != "http/1.1 100 continue")
	    {
		if(preg_match($pattern, $lowerCase, $matches) == 1)
		{
		    return $matches[1];
		}
	    }
	}	
	// Unable to parse out any status code at all
	return -1;
    }


    // Remove superflous leading '100' code from headers returned when you've added a new file to an existing EPrint
    private function cleanHeader($header)
    {
	$pattern = '/^(HTTP\/1.1 100 Continue\r\n\r\n)(.*)/';
	$replacement = '$2';
	
	return preg_replace($pattern, $replacement, $header);
    }


    private function getEPrintID($response)
    {
	$outputstruct = new SimpleXMLElement($response);
	$eprintURL = (string)$outputstruct->id;
	$eprintID = str_replace($this->repoURL . 'id/eprint/', "", $eprintURL);
	return $eprintID;
    }
}
?>
