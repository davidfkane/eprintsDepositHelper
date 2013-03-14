<?php

# Simple container class for SWORD metadata
class SWORDMetaData
{
    public $author_given_names;
    public $author_family_names;
    public $note;
    public $title;
    public $publication;
    public $volume;
    public $issue_number;
    public $pdf_tmp_filenames;
    public $pdf_given_filenames;
    public $depositor_name;
    public $depositor_email;
    public $depositor_affiliation;
    public $date;                  // String of the form 'YYYY-MM-DD'

    function __construct($title)
    {
	$this->title = $title;
	$this->pdf_tmp_filenames = array();
	$this->pdf_given_filenames = array();
	$this->author_given_names = array();
	$this->author_family_names = array();
    }


    function addAuthor($agn, $afn)
    {
	array_push($this->author_given_names, $agn);
	array_push($this->author_family_names, $afn);
    }


    function addPDF($fn, $tfn)
    {
	array_push($this->pdf_given_filenames, $fn);
	array_push($this->pdf_tmp_filenames, $tfn);
    }


    function addPublication($pn, $vol, $iss)
    {
	$this->publication = $pn;
	$this->volume = $vol;
	$this->issue_number = $iss;
    }


    function addDate($d)
    {
	$this->date = $d;
    }


    function addDepositorName($dn)
    {
	$this->depositor_name = $dn;
    }


    function addDepositorEmail($de)
    {
	$this->depositor_email = $de;
    }


    function addAffiliation($af)
    {
	$this->depositor_affiliation = $af;
    }
}
?>