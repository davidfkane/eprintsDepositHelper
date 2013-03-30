 <?php

class EPrintsWrapper
{
    public $repoURL;
    public $username;
    public $password;
    public $EPrintID = 0;
    public $EPrintCreators = array();
    public $currentEPrintStructure;    // EPrints XML structure that is built up and eventually submitted to EPrints
    
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


    /**
     * Creates a new EPrintsWrapper object.
     * @param string $servicedocument  The servicedocument URL for an EPrints repository
     * @param string $username         A username that has the correct permissions for the given EPrints repository
     * @param string $password         The password
     * @param int $EPid                (Optional) An eprint ID. If not provided, a new eprint will be created.
     * 
     */    
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

    
    /**
     * Initialize the EPrints XML for a blank generic eprint.
     *
     */
    private function newEPrint()
    {
	$filestring = "<?xml version='1.0' encoding='utf-8'?>
                        <eprints xmlns='http://eprints.org/ep2/data/2.0'>
                          <eprint>
                          </eprint>
                        </eprints>";
        $this->currentEPrintStructure = new SimpleXMLElement($filestring);
    }


    /**
     * Returns the current error message.
     *
     */
    public function getErrorMessage()
    {
	return $this->errorMessage;
    }


    /**
     * Returns the EPrints XML response for an HTTP request to a given repository URL.
     * @param string $repoURL
     *
     */
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
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        curl_close($ch);

        $this->debugOutput("<textarea>$output</textarea>");

        $outputstruct = new SimpleXMLElement($output);
        return $outputstruct;
    }


    /**
     * Adds a file to the SWORD request. Note that this method must be called once per file AND
     * that it must be called before commitNewEPrint().
     *
     */
    public function addFile($filepath, $contenttype, $name = NULL)
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

	$this->addDocument($filepath, $data, $filename, "application/pdf", "public", "text");
	return 1;
    }


    /**
     * Finally deposits the new EPrint using the SWORD protocol.
     * If the deposit is successful, the ID of the new eprint is returned.
     * If the deposit fails, EPrintsWrapper::ERROR_VALUE is returned.
     *
     */
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
	curl_close($ch);
        fclose($fh);
        
	list($responseHeader, $responseBody) = explode("\r\n\r\n", $response, 2);
	$statusCode = $this->checkStatusCode($responseHeader);
	if($statusCode == 201)
	{
	    $this->EPrintID = $this->getEPrintID($responseBody);
	    $this->debugOutput("<textarea class=\"textarea2\">Output: ".$responseHeader."\n".$responseBody."</textarea>");
	    return $this->getEPrintID($responseBody);
	}
	else
	{
	    $this->errorMessage = $response;
	    return EPrintsWrapper::ERROR_VALUE;
	}
    }


    /**
     * Returns the status code provided by EPrints in response to a SWORD request. Upon failure, returns EPrintsWrapper::ERROR_VALUE
     *
     */
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
	return EPrintsWrapper::ERROR_VALUE;
    }


    /**
     * Remove superflous leading '100' code from headers returned when you've added a new file to an existing EPrint.
     *
     */
    private function cleanHeader($header)
    {
	$pattern = '/^(HTTP\/1.1 100 Continue\r\n\r\n)(.*)/';
	$replacement = '$2';
	
	return preg_replace($pattern, $replacement, $header);
    }
    

    /**
     * Given an EPrints response to a SWORD deposit, returns the ID of the new eprint.
     *
     */
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
     * and the base64 encoded files themselves (embedded in EPrints XML).
     *
     * @param string $data very long string representing the file
     * @param string $filename name of file
     * @param string $mimetype mime-type
     * @param string $security
     * @param string $format
     *
     */
    public function addDocument($filepath, $data, $filename, $mimetype, $security, $format)
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


    /**
     * Returns the current <document> node, or NULL if none exists.
     *
     */
    private function getDocumentFragment()
    {
	foreach($this->currentEPrintStructure->eprint->children() as $currentChild)
	{
	    if($currentChild->getName() == 'documents')
		return $currentChild;
	}
	return NULL;
    }
    
    
    
    /**
     * Returns a date in the form 'YYYY-MM-DD' using the current state variables.
     *
     */
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
    
    
    
    /**
     * Generates a short informational note for the reviewer.
     *
     */
    private function generateNote()
    {
	return $this->additionalInformation . "\n    SWORD: deposited by $this->depositorName ($this->depositorAffiliation)\n";
    }


    /**
     * Reads the given (full) file path into a string, which is then encoded using base64 and returned.
     * Upon failure, returns NULL and sets EPrintsWrapper::errorMessage.
     *
     * @param string $filepath
     *
     */
    private function encodeData($filepath)
    {
	$result = "";
	$plainFile = file_get_contents($filepath);

	if($plainFile == FALSE)
	{
	    $this->errorMessage = "Unable to open file $filepath for reading. Does the file exist? Are the permissions sufficient?";
	    return NULL;
	}

	$encodedFileString = base64_encode($plainFile);
	if($encodedFileString == FALSE)
	{
	    $this->errorMessage = "Unable to encode $filepath using base64_encode";
	    return NULL;
	}
	return $encodedFileString;
    }


    /**
     * Counts the number of <document> occurrences in the current EPrints XML.
     *
     */
    private function countDocuments()
    {
	return $this->currentEPrintStructure->eprint->documents->count();
    }
}
?>
