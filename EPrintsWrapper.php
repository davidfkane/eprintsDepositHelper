<?php

class EPrintsWrapper
{
    public $repoURL;
    public $username;
    public $password;
    public $EPrintID;
    public $currentEPrintStructure;
    private $uploadsdir = "/tmp/uploads/";
    private $referer = "http://library.wit.ie/eprints/deposit/form";
    private $useragent = "MozillaXYZ/1.0";
    private $unique_stamp;
    
    
    function  __construct($servicedocument, $username, $password, $EPid = false)
    {
        // leave this empty now
        $xmlStruct = $this->getEPrintsFileFromURL($servicedocument, $username, $password);
        $repository = explode('/', str_replace('http://', '', $servicedocument));
        $this->unique_stamp = time();
	$this->repoURL = 'http://' . $repository[0] . '/'; print "<h2>".$this->repoURL."</h2>";
        $this->username = $username;
        $this->password = $password;
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


    private function getEPrintsFileFromURL($repoURL, $username, $password)
    {
        $this->username = $username;
        $this->password = $password;
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
        print("<textarea>$output</textarea>");
        $outputstruct = new SimpleXMLElement($output);
        return $outputstruct;
    }
 

    public function getEPrintsMetadata($repoURL, $username, $password){
        $this->repoURL = $repoURL;
        $ch = curl_init();        
        curl_setopt($ch, CURLOPT_URL, $this->repoURL);
        curl_setopt($ch, CURLOPT_REFERER, $this->referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);     
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/vnd.eprints.data+xml')); // using eprints xml
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username.":".$this->password); 
        curl_setopt($ch, CURLOPT_HEADER, 0);            // Include header in result? (0 = yes, 1 = no)   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Should cURL return or print out the data? (true = return, false = print)   
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);          // Timeout in seconds     
        $output = curl_exec($ch);  // Download the given URL, and return output
        print $output;
        $this->currentEPrintStructure = new SimpleXMLElement($output);
        
        curl_close($ch);  // Close the cURL resource, and free system resources
    }
 

    public function addFile($filename, $EPrintID, $contenttype)
    {
      
        $handle = fopen($filename, "r");
        $contentlen = filesize($filename);
        $data = fread($handle, $contentlen);
        fclose($handle);
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $this->repoURL . "/id/eprint/" . $EPrintID . "/contents");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: ' . $contenttype, "Content-Disposition: attachment; filename=$filename"));
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
       // curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE); // --data-binary
	curl_setopt($ch, CURLOPT_USERPWD, $this->username.":".$this->password);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	
	// Download the given URL, and return output
        $output = curl_exec($ch);
        print"<div class=\"textarea2\"><textarea rows='30' cols='120'>Output: ".$output."</textarea></div>";
        curl_close($ch);  // Close the cURL resource, and free system resources
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
        $response  = curl_exec($ch);

	list($response_header, $response_body) = explode("\r\n\r\n", $response, 2);
	$outputstruct = new SimpleXMLElement($response_body);

        $this->EPrintID = str_replace($this->repoURL . '/id/eprint/', '', $outputstruct->id);

        print"<textarea class=\"textarea2\">Output: ".$response_header."\n".$response_body."</textarea>";
        curl_close($ch);  // Close the cURL resource, and free system resources
        fclose($fh);
        return $this->EPrintID;
    }    
}
?>
