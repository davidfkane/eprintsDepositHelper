<?php

class EPrintsWrapper
{
    public $repoURL;
    public $username;
    public $password;
    public $EPrintID = 0;
    public $EPrintCreators = array();
    public $currentEPrintStructure;
    
    public $journalName;
    public $volume;
    public $issue;
    public $year;
    public $month;
    public $day;
    public $depositorName;
    public $depositorEmail;
    public $depositorAffiliation;
    public $additionalInformation;
    public $note = "";
    
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


    public function addFile($filepath, $EPrintID, $contenttype, $name = NULL)
    {
        if($this->EPrintID == 0)
        {
            $this->errorMessage = "Eprint ID not set";
            return -1;
        }
	else
	{
            $handle = fopen($filepath, "r");
            $contentlen = filesize($filepath);
            $data = fread($handle, $contentlen);
            fclose($handle);

            if($name == NULL)
	    {
                $expl = explode('/', $filepath);
                $filename = $expl[count($expl)-1];
            }
	    else $filename = $name;
            $this->addFileData($filename, $data, $contenttype);
            return 1;
        }
        
    }


    public function addFile2($filepath, $contenttype, $name = NULL)
    {
	$handle = fopen($filepath, "r");
	$contentlen = filesize($filepath);
	//$data = fread($handle, $contentlen);
	$data = file_get_contents($filepath);
	fclose($handle);
	
	if($name == NULL)
	{
	    $expl = explode('/', $filepath);
	    $filename = $expl[count($expl)-1];
	}
	else $filename = $name;
	//$this->addFileData($filename, $data, $contenttype);

	//$this->addDocument($filepath, $data, $filename, "application/pdf", "public", "text");
	$this->addDocument2($filepath, $data, $filename, "application/pdf", "public", "text");
	return 1;
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
	//$post = file_get_contents("/tmp/7.xml");
        $ch = curl_init();file_put_contents("/tmp/post.xml", $post);
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
    

    ########################################################################################################
    #                                                                                                      #
    #                                                                                                      #
    #      Public Functions for Building XML = to replace/incorporate the function of XMLWriter.php        #
    #                                                                                                      #
    #                                                                                                      #
    ########################################################################################################
    
    
    /**
     * Adds creators to a multidimensional array, which is a class variable.  This class variable is then used in the
     * addEprintMetaData() function, which must be used only when all the creators have been added.
     *
     * @param string $family
     * @param string $given
     * @param string $id
     *
     */
    public function addCreator($family, $given, $id)
    {
        array_push($this->EPrintCreators, array('family'=>$family, 'given'=>$given, 'id'=>$id));
    }
    

    /**
     * Adds a journal name for the new eprint. 
     * 
     * @param string $journal
     *
     */
    public function addJournal($journal)
    {
	$this->journalName = $journal;
    }


    /**
     * Adds a volume for the new eprint.
     *
     * @param int $volume
     *
     */
    public function addVolume($volume)
    {
	$this->volume = $v;
    }

    
    /**
     * Adds an issue number for the new eprint.
     *
     * @param int $volume
     *
     */
    public function addIssue($issue)
    {
	$this->issue = $i;
    }


    /**
     * Adds year of publication for the new eprint.
     *
     * @param int $year
     *
     */
    public function addYear($year)
    {
	$this->year = $y;
    }

    
    /**
     * Adds month of publication for the new eprint.
     *
     * @param int $month
     *
     */
    public function addMonth($month)
    {
	$this->month = $m;
    }
    

    /**
     * Adds day of publication for the new eprint.
     *
     * @param int $day
     *
     */
    public function addDay($day)
    {
	$this->day = $day;
    }


    /**
     * Adds information about the depositor for the new eprint.
     * @param string name
     * @param string email
     * @param string affiliation
     *
     */
    public function addDepositor($name, $email, $affiliation)
    {
	$this->depositorName = $name;
	$this->depositorEmail = $email;
	$this->depositorAffiliation = $affiliation;
    }


    /**
     * Adds a note to for the new eprint. This could be e.g. a message from the depositor.
     *
     * @param string note
     *
     */
    public function addNote($note)
    {
	$this->additionalInformation = $note;
    }

    
    /**
     * Adds the general EPrint metadata to the class representation of the EPrint. This actually creates XML
     * and so must be called only once.
     *
     * @param string $title title of paper or whatever
     * @param string $type mime-type
     * @param array $creators multidimensional array of creator names and emails
     *
     */
    public function addEprintMetaData($title, $type)
    {
        $status = "inbox";
        $mdataVis = "show";
        $count = 0;
        $ep = $this->currentEPrintStructure->eprint;
        
        $ep->title = $title;
        $ep->eprint_status = $status;
        $ep->type = $type;
        $ep->metadata_visibility = $mdataVis;
        foreach($this->EPrintCreators as $creator)
        {
            $ep->creators->item[$count]->name->family = $creator['family'];
            $ep->creators->item[$count]->name->given = $creator['given'];
            $ep->creators->item[$count]->id = $creator['id'];
            $count++;
        }

	$ep->publication = $this->journalName;
	$ep->volume = $this->volume;
	$ep->issue = $this->issue;
	$ep->date = $this->generateDateString();
	$ep->contact_email = $this->depositorEmail;
	$ep->note = $this->additionalInformation;
    }
    
    
    
    /**
     * Adds individual documents to the class representation of the EPrint, including the hashes
     * and the base64 encoded files themselves.
     *
     * @param string $data very long string representing the file
     * @param string $filename name of file
     * @param string $mimetype mime-type
     * @param string $security
     * @param string $format
     * @param integer $docnum there could be several documents per EPrint (normally just one)
     * @param integer $filenum there could be several files per document (normally just one)
     *
     */
    public function addDocument($filepath, $data, $filename, $mimetype, $security, $format, $docNum = 1, $filenum = 1)
    {
        $filesize = strlen($data);
        $docNum -= 1;
        $filenum -= 1;
        $hashtype = "MD5";
        $encoding = "base64";
        $lang = "en";
        $datasetid = "document";
        $DocumentFragment = $this->currentEPrintStructure->eprint->documents->document[$docNum];
	$documentCount = $this->countDocuments();
	error_log("filename = $filename and currently documentcount = $documentCount");

	$differentCount = count($this->currentEPrintStructure->eprint->documents->children('document'));
	error_log("differentCount = $differentCount");
	
	// DocumentFragment will be NULL if no documents have been added yet. Also note that we're not allowing
	// more than one file per document.
	//	if($DocumentFragment == NULL)        --- try using documentCount instead
	//{
	//	    $ep = $this->currentEPrintStructure->eprint;
	//    $ep->documents->document[$docNum]->files->file->filename = $filename;
	//    $ep->documents->document[$docNum]->files->file->mime_type = $mimetype;
	//    $ep->documents->document[$docNum]->files->file->datasetid = $datasetid;
	//    $ep->documents->document[$docNum]->files->file->hash = md5($data);
	//    $ep->documents->document[$docNum]->files->file->hash_type = $hashtype;
	//    $ep->documents->document[$docNum]->files->file->filesize = $filesize;
	//    $ep->documents->document[$docNum]->files->file->mtime = date("Y-m-d H:i:s");
	//    $ep->documents->document[$docNum]->files->file->data = $this->encodeData($filepath);
	//    $ep->documents->document[$docNum]->files->file->data->addAttribute("encoding", $encoding);
	//    $ep->documents->document[$docNum]->files->file->security = $security;
	//    $ep->documents->document[$docNum]->files->file->language = $lang;
	//    $ep->documents->document[$docNum]->format = $format;


	    //$xmlstring = $this->currentEPrintStructure->asXML();
	    //error_log("xmlstring = $xmlstring");error_log("");
	//}
	//else
	//	{
	//    
	//}
	
	$ep = $this->currentEPrintStructure->eprint;
	$this->currentEPrintStructure->eprint->documents->document[$documentCount]->files->file->filename = $filename;
	$this->currentEPrintStructure->eprint->documents->document[$documentCount]->files->file->mime_type = $mimetype;
	$this->currentEPrintStructure->eprint->documents->document[$documentCount]->files->file->datasetid = $datasetid;
	$this->currentEPrintStructure->eprint->documents->document[$documentCount]->files->file->hash = md5($data);
	$this->currentEPrintStructure->eprint->documents->document[$documentCount]->files->file->hash_type = $hashtype;
	$this->currentEPrintStructure->eprint->documents->document[$documentCount]->files->file->filesize = $filesize;
	$this->currentEPrintStructure->eprint->documents->document[$documentCount]->files->file->mtime = date("Y-m-d H:i:s");
	$this->currentEPrintStructure->eprint->documents->document[$documentCount]->files->file->data = $this->encodeData($filepath);
	$this->currentEPrintStructure->eprint->documents->document[$documentCount]->files->file->data->addAttribute("encoding", $encoding);
	$this->currentEPrintStructure->eprint->documents->document[$documentCount]->files->file->security = $security;
	$this->currentEPrintStructure->eprint->documents->document[$documentCount]->files->file->language = $lang;
	$this->currentEPrintStructure->eprint->documents->document[$documentCount]->format = $format;
	
	$endCount = $this->countDocuments();
	error_log("endCount = $endCount");
    }


    public function addDocument2($filepath, $data, $filename, $mimetype, $security, $format, $docNum = 1, $filenum = 1)
    {
        $filesize = strlen($data);
        $docNum -= 1;
        $filenum -= 1;
        $hashtype = "MD5";
        $encoding = "base64";
        $lang = "en";
        $datasetid = "document";


        if($this->countDocuments() == 0)
	    $documentFragment = $this->currentEPrintStructure->eprint->addChild('documents');
	else $documentFragment = $this->getDocumentFragment();

	if($documentFragment == null)
	{
	    error_log("Got nULL df!!");
	    exit(1);
	}
	$newDocument = $documentFragment->addChild('document');
	$filesFragment = $newDocument->addChild('files');
	$fileFragment = $filesFragment->addChild('file');
	$fileFragment->addChild('filename', $filename);
	$fileFragment->addChild('mime_type', $mimetype);
	$fileFragment->addChild('datasetid', $datasetid);
	$fileFragment->addChild('hash', md5($data));
	$fileFragment->addChild('hash_type', $hashtype);
	$fileFragment->addChild('filesize', $filesize);
	$fileFragment->addChild('mtime', date("Y-m-d H:i:s"));
	$fileFragment->addChild('data', $this->encodeData($filepath));
	$fileFragment->addChild('security', $security);
	$fileFragment->addChild('language', $lang);
	$newDocument->addChild('format', $format);
    }


    private function getDocumentFragment()
    {
	foreach($this->currentEPrintStructure->eprint->children() as $currentChild)
	{
	    if($currentChild->getName() == 'documents')
		return $currentChild;
	}
	return null;
    }
    
    
    
    // Generates a date of the form "YYYY-MM-DD
    private function generateDateString()
    {
	$date = "";
	if(!is_null($this->year) && strlen($this->year) > 0)
	    $date = $date . $this->year;
	else return $date;
	
	if(!is_null($this->month) && strlen($this->month) > 0 && $this->month != "Unspecified")
	    $date = $date . "-" . $this->month;
	else return $date;
	
	if(!is_null($this->date) && strlen($this->date) > 0 && $this->date != "Unspecified")
	    $date = $date . "-" . $this->date;
	return $date;
    }
    
    
    // Generates a short informational note for the reviewer. EPrints XML doesn't support much depositor
    // information, so leave it in the comments(?)
    private function generateNote()
    {
	return $this->additionalInformation . "\n    SWORD: deposited by $this->depositorName ($this->depositorAffiliation)\n";
    }


    // make sure to check for successes in the various calls here!
    private function encodeData($filepath)
    {
	$result = "";
	$plainFile = file_get_contents($filepath);
	$encodedFileString = base64_encode($plainFile);

	// For some reason, EPrints refuses to recognize the contents unless they're split just like an EPrints
	// export does - 77 characters per line.
	$splitString = str_split($encodedFileString, 76);
	foreach($splitString as $entry)
	    $result = $result . "$entry\n";
	return $result;
    }


    private function countDocuments()
    {
	return $this->currentEPrintStructure->eprint->documents->count();
    }
}
?>
