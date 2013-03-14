<?php

# Given a filename and metadata, writes an EPrints XML file containing the data.
# Version 7 has more fields that were removed from this version.
class EPrintsXMLWriter
{
    public $metadata;
    public $filename;
    public $fh;
    public $date_time;
    public $xml_string;


    function __construct($data, $filename)
    {
	$this->metadata = $data;	
	//$this->openFile($filename);
	$this->date_time = $this->dateString();
	$this->xml_string = "";
    }


    function write()
    {
	$this->writeHeader();
	$this->writeContents();

	//fclose($this->fh);
    }

    
    function writeString()
    {
	$this->writeHeaderString();
	$this->writeContentString();
	//fclose($this->fh);

	return $this->xml_string;
    }


    function openFile($fn)
    {
	$full_name = "uploadfiles/" . $fn;
	$this->fh = fopen($full_name, "w");
    }


    function writeHeader()
    {
	fwrite($this->fh, "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n");
        fwrite($this->fh, "<eprints xmlns=\"http://eprints.org/ep2/data/2.0\">\n");
	fwrite($this->fh, "  <eprint>\n");
    }

    
    function writeContents()
    {
	// EPrints XML doesn't support much depositor information, so leave it in the comments(?)
	$expanded_note = "{$this->metadata->note}" . "\n    SWORD: deposited by {$this->metadata->depositor_name} ({$this->metadata->depositor_affiliation})\n";

	fwrite($this->fh, "    <datestamp>$this->date_time</datestamp>\n");
	fwrite($this->fh, "    <lastmod>$this->date_time</lastmod>\n");
	fwrite($this->fh, "    <status_changed>$this->date_time</status_changed>\n");
	fwrite($this->fh, "    <type>article</type>\n");
	fwrite($this->fh, "    <metadata_visibility>show</metadata_visibility>\n");
	fwrite($this->fh, "    <creators>\n");
	

	# The <id> field always seems to be empty when exporting EP3 XML from within
	# EPrints, so we skip it
	$author_count = count($this->metadata->author_family_names);
	for($i=0; $i<$author_count; $i++)
	{
	    fwrite($this->fh, "      <item>\n");
	    fwrite($this->fh, "        <name>\n");
	    fwrite($this->fh, "          <family>{$this->metadata->author_family_names[$i]}</family>\n");
	    fwrite($this->fh, "          <given>{$this->metadata->author_given_names[$i]}</given>\n");
	    fwrite($this->fh, "        </name>\n");
	    fwrite($this->fh, "      </item>\n");
	}


	fwrite($this->fh, "    </creators>\n");
	fwrite($this->fh, "    <title>{$this->metadata->title}</title>\n");
	fwrite($this->fh, "    <note>$expanded_note</note>\n");
	fwrite($this->fh, "    <full_text_status>public</full_text_status>\n");
	fwrite($this->fh, "    <date>{$this->metadata->date}</date>\n");
	fwrite($this->fh, "    <publication>{$this->metadata->publication}</publication>\n");
	fwrite($this->fh, "    <volume>{$this->metadata->volume}</volume>\n");
	fwrite($this->fh, "    <number>{$this->metadata->issue_number}</number>\n");
	fwrite($this->fh, "    <contact_email>{$this->metadata->depositor_email}</contact_email>\n");
	fwrite($this->fh, "  </eprint>\n");
	fwrite($this->fh, "</eprints>\n");
	    
    }


    function dateString()
    {
	return date("Y-m-d H:i:s");
    }


    function writeHeaderString()
    {
	$this->xml_string = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
	$this->xml_string = $this->xml_string . "<eprints xmlns=\"http://eprints.org/ep2/data/2.0\">\n";
	$this->xml_string = $this->xml_string . "  <eprint>\n";
    }

    
    function writeContentString()
    {
	// EPrints XML doesn't support much depositor information, so leave it in the comments(?)
	$expanded_note = "{$this->metadata->note}" . "\n    SWORD: deposited by {$this->metadata->depositor_name} ({$this->metadata->depositor_affiliation})\n";

	
	$this->xml_string = $this->xml_string .  "    <datestamp>$this->date_time</datestamp>\n";
	$this->xml_string = $this->xml_string .  "    <lastmod>$this->date_time</lastmod>\n";
	$this->xml_string = $this->xml_string .  "    <status_changed>$this->date_time</status_changed>\n";
	$this->xml_string = $this->xml_string .  "    <type>article</type>\n";
	$this->xml_string = $this->xml_string .  "    <metadata_visibility>show</metadata_visibility>\n";
	$this->xml_string = $this->xml_string .  "    <creators>\n";
	

	# The <id> field always seems to be empty when exporting EP3 XML from within
	# EPrints, so we skip it
	$author_count = count($this->metadata->author_family_names);
	for($i=0; $i<$author_count; $i++)
	{
	    $this->xml_string = $this->xml_string .  "      <item>\n";
	    $this->xml_string = $this->xml_string .  "        <name>\n";
	    $this->xml_string = $this->xml_string .  "          <family>{$this->metadata->author_family_names[$i]}</family>\n";
	    $this->xml_string = $this->xml_string .  "          <given>{$this->metadata->author_given_names[$i]}</given>\n";
	    $this->xml_string = $this->xml_string .  "        </name>\n";
	    $this->xml_string = $this->xml_string .  "      </item>\n";
	}


	$this->xml_string = $this->xml_string .  "    </creators>\n";
	$this->xml_string = $this->xml_string .  "    <title>{$this->metadata->title}</title>\n";
	$this->xml_string = $this->xml_string .  "    <note>$expanded_note</note>\n";
	$this->xml_string = $this->xml_string .  "    <full_text_status>public</full_text_status>\n";
	$this->xml_string = $this->xml_string .  "    <date>{$this->metadata->date}</date>\n";
	$this->xml_string = $this->xml_string .  "    <publication>{$this->metadata->publication}</publication>\n";
	$this->xml_string = $this->xml_string .  "    <volume>{$this->metadata->volume}</volume>\n";
	$this->xml_string = $this->xml_string .  "    <number>{$this->metadata->issue_number}</number>\n";
	$this->xml_string = $this->xml_string .  "    <contact_email>{$this->metadata->depositor_email}</contact_email>\n";
	$this->xml_string = $this->xml_string .  "  </eprint>\n";
	$this->xml_string = $this->xml_string .  "</eprints>\n";
	    
    }
}
?>
