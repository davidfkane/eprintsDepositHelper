<?php
/**
 * EPrintsWrapper Class
 *
 * This class encapsulates essential remote EPrints deposit functionality to allow it to be used in other websites.
 * 
 */

class EPrintsWrapper
{
    public $repoURL;
    public $username;
    public $password;
    public $EPrintID = 0;
    public $EPrintCreators = array();
    public $currentEPrintStructure;    // EPrints XML structure that is built up and eventually submitted to EPrints
    
    #journal
    public $journalName;
    public $volume;
    public $issue;
    public $issn;
    
    #conference
    public $event_title;
    public $event_type;
    public $pres_type;
    public $event_location;
    
    #book_section, book
    public $book_title;
    public $isbn;
    public $publisher;
    
    public $year;
    public $month;
    public $day;
    public $date;
    public $depositorName;
    public $depositorEmail;
    public $depositorAffiliation;
    public $additionalInformation;
    public $note = "";
    public $ispublished;
    public $refereed;
    
    
    public $subjects;
    public $divisions;
    public $ep_abstract;
    public $official_url;
    public $date_type;
    
    private $debug = 0;
    private $referer = "http://library.wit.ie/eprints/deposit/form";
    private $useragent = "MozillaXYZ/1.0";
    private $unique_stamp;
    private $errorMessage = "";
    const ERROR_VALUE = -1;

    
    public function debugOutput($string)
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
	$this->repoURL = 'http://' . $repository[0] . '/'; 

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

        #$this->debugOutput("<hr/><textarea style=\"width: 100%\">$output</textarea>");

        $outputstruct = new SimpleXMLElement($output);
        return $outputstruct;
    }


    /**
     * Adds a file to the SWORD request. Note that this method must be called once per file AND
     * that it must be called before commitNewEPrint().
     * 
     * @param string $filepath local server path of uploaded file
     * @param string $contenttype MIME type of file
     * @param string $name name to be given to file
     * @param string $security is it visible to public or only accessible to users or staffonly (Values: public, staffonly, validuser)
     * @param string $type type of content (Values: text, slideshow, image, video ,audio, archive, other)
     * @param string $embargo date of embargo epiry (optional)
     *
     */
    public function addFile($filepath, $contenttype, $filename, $security, $type, $embargo = NULL)
    {
	$handle = fopen($filepath, "r");
	$contentlen = filesize($filepath);
	$data = file_get_contents($filepath);
	fclose($handle);
	
	if($filename == NULL)
	{
	    $expl = explode('/', $filepath);
	    $filename = $expl[count($expl)-1];
	}
	

	$this->addDocument($data, $filepath, $contenttype, $filename, $security, $type, $embargo);
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
        
        #$this->debugOutput("<textarea>".$response."</textarea>");
        $this->debugOutput("<textarea style='background-color: ivory; border dashed 2px red;'>".$post."</textarea>");
        
	list($responseHeader, $responseBody) = explode("\r\n\r\n", $response, 2);
        
	###############################################
	#
	# There's a need for a more robust handling of the headers, as a dual header sometimes comes in twice.
	# E.g.
	#
	#   HTTP/1.1 100 Continue
	#
	#   HTTP/1.1 201 Created
	#   Rest of headers...
        #
        #   <start of body> ...
        #
        ###############################################
        
        if($responseHeader == 'HTTP/1.1 100 Continue'){           
            list($responseHeader, $responseBody) = explode("\r\n\r\n", $responseBody, 2);
        }
        
	$statusCode = $this->checkStatusCode($responseHeader);
	if($statusCode == 201)
	{
	    $this->EPrintID = $this->getEPrintID($responseBody);
	    return $this->EPrintID;
	}
	else
	{
	    $this->errorMessage = $response;
	    return EPrintsWrapper::ERROR_VALUE;
	}
        
        
        $wrapper->debugOutput("<br>From function: eprintID is: " . $this->EPrintID . "<br/>");
        
    }


    /**
     * Returns the status code provided by EPrints in response to a SWORD request. Upon failure, returns EPrintsWrapper::ERROR_VALUE
     * The value should be 201, or we return an error.
     *
     */
    public function checkStatusCode($header)
    {
        $this->debugOutput("header:<br/><pre>$header</pre>");
	$pattern = '/^http\/1.1\s+(\d{3})\s+.*/';
	$headerLines = preg_split('/$\R?^/m', $header);
        $cnt = sizeOf($headerLines);
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
     * Given the second part of an EPrints response to a SWORD deposit (starts with '201'), returns the ID of the new eprint.
     * The *full* response is expected to look like:
     *
     * HTTP/1.1 100 Continue\r\n\r\nHTTP/1.1 201 Created\r\nDate: Mon, 01 Apr 2013 16:59:35 GMT\r\nServer: Apache/2.2.17 (Unix) mod_ssl/2.2.17 OpenSSL/0.9.8e-fips-rhel5 mod_perl/2.0.5 Perl/v5.8.8\r\nLocation: http://myrepo.org/id/eprint/366\r\nContent-Length: 1640\r\nETag: f5fd35f14b8aff5195a966f7abf54949\r\nContent-MD5: 1B2M2Y8AsgTpgAmY7PhCfg\r\nContent-Length: 1640\r\nContent-Type: application/atom+xml;charset=utf-8\r\n\r\n<?xml version="1.0" encoding="utf-8" ?>\n<entry xmlns="http://www.w3.org/2005/Atom" xmlns:sword="http://purl.org/net/sword/">\n  <link rel="self" href="http://myrepo.org/cgi/export/eprint/366/Atom/authorstest-eprint-366.xml"/>\n  <link rel="edit" href="http://myrepo.org/id/eprint/366"/>\n  <link rel="edit-media" href="http://myrepo.org/id/eprint/366/contents"/>\n  <link rel="contents" href="http://myrepo.org/id/eprint/366/contents"/>\n  <link rel="alternate" href="http://myrepo.org/id/eprint/366"/>\n  <published/>\n  <updated>2013-04-01T16:59:36Z</updated>\n  <id>http://myrepo.org/id/eprint/366</id>\n  <category term="article" label="Article" scheme="http://myrepo.org/data/eprint/type"/>\n  <category term="buffer" label="Under Review" scheme="http://eprints.org/ep2/data/2.0/eprint/eprint_status"/>\n  <link rel="http://purl.org/net/sword/terms/statement" href="http://myrepo.org/id/eprint/366"/>\n  <sword:state href="http://eprints.org/ep2/data/2.0/eprint/eprint_status/buffer"/>\n  <sword:stateDescription>This item is in review. It will not appear in the repository until it has been approved by an editor.</sword:stateDescription>\n  <sword:originalDeposit href="http://myrepo.org/id/eprint/366">\n    <sword:depositedOn/>\n    <sword:depositedBy>username</sword:depositedBy>\n  </sword:originalDeposit>\n  <title type="xhtml">titlestring</title>\n  <author>\n    <name>name</name>\n    <email></email>\n  </author>\n</entry>, referer: http://localhost/deposit_article.php
     *
     *
     */
    public function getEPrintID($responseBody)
    {
        $this->debugOutput("<textarea style=\"background-color: lavender\"> ".$responseBody."</textarea>");
	@list($statusStart, $xmlContents) = explode("\r\n\r\n", $responseBody, 2);
        if(!isset($xmlContents)){
            $xmlContents = $statusStart;
            # this is because the response does not always come with a header.
        }
        $outputstruct = new SimpleXMLElement($xmlContents);
	$eprintURL = $outputstruct->id;
        $this->debugOutput("<h2>Eprint URL: ".$eprintURL."</h2>");
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
	$ep->ispublished = $this->ispublished;
        foreach($this->divisions as $division)
        {
            $ep->divisions->item[$count] = $division;
            $count++;
        }
        $count = 0;
        foreach($this->subjects as $subject)
        {
            $ep->subjects->item[$count] = $subject;
            $count++;
        }
        $count = 0;
        $ep->eprint_status = $status;
        $ep->type = $type;
        $ep->metadata_visibility = $mdataVis;
        $ep->abstract = $this->ep_abstract;
        foreach($this->EPrintCreators as $creator)
        {
            $ep->creators->item[$count]->name->family = $creator['family'];
            $ep->creators->item[$count]->name->given = $creator['given'];
            $ep->creators->item[$count]->id = $creator['id'];
            $count++;
        }
	$ep->date = $this->date;
	$ep->date_type = $this->date_type;
        if($type == 'article'){
            $ep->publication = $this->journalName;
            $ep->volume = $this->volume;
            $ep->issue = $this->issue;  
            $ep->issn = $this->issn; 
        }elseif($type == 'conference_item'){
            $ep->pres_type = $this->pres_type;
            $ep->event_title = $this->event_title;
            $ep->event_type = $this->event_type;
            $ep->event_location = $this->event_location;
        }elseif($type == 'book_section'){
            $ep->book_title = $this->book_title;
            $ep->publisher = $this->publisher;
            $ep->isbn = $this->isbn;
        }elseif($type == 'book'){
            $ep->publisher = $this->publisher;
            $ep->isbn = $this->isbn;
        }
	$ep->date = $this->generateDateString();
	$ep->contact_email = $this->depositorEmail;
	$ep->note = $this->additionalInformation;
	$ep->refereed = $this->refereed;
	$ep->official_url = $this->official_url;
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
    
    public function addDocument($data, $filepath, $contenttype, $filename, $security, $type, $embargo)
    {
        $filesize = strlen($data);
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
	$fileFragment->addChild('mime_type', $contenttype);
	$fileFragment->addChild('datasetid', $datasetid);
	$fileFragment->addChild('hash', md5($data));
	$fileFragment->addChild('hash_type', $hashtype);
	$fileFragment->addChild('filesize', $filesize);
	$fileFragment->addChild('mtime', date("Y-m-d H:i:s"));
	$fileFragment->addChild('security', $security);
	$fileFragment->addChild('language', $lang);
	$fileFragment->addChild('data', $this->encodeData($filepath));
        if($embargo != NULL){
            $fileFragment->addChild('date_embargo', $embargo);
        }
	$newDocument->addChild('format', $type);
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
	
	if(!is_null($this->day) && strlen($this->day) > 0 && $this->day != "Unspecified")
	    $date = $date . "-" . $this->day;
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
