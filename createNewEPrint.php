<html><body>
<head>
    <style>
        .textarea1{background-color: pink;}
        .textarea2{background-color: lightgreen;}
    </style>    
</head>

<h1>CURL Eprints test</h1>
<p>CURL test for CRUD.pm</p>
<p>Getting and Setting parameters is easy</p>
<hr/>
<?php

error_reporting(E_ALL);
require('./EPrintsWrapper.php');
$crl = new EPrintsWrapper('username', 'password');  // no eprintid means that this is a new eprint.
//print "<table><tr><td valign=\"top\"><pre>"; print_r($crl->currentEPrintStructure); print "</pre></td>";
$crl->currentEPrintStructure->eprint->title = "David Kane's second Paper";
$crl->currentEPrintStructure->eprint->documents->document[0]->files->file[0]->filename = "my filename";
$crl->currentEPrintStructure->eprint->documents->document[0]->files->file[0]->datasetid = "document";
$crl->currentEPrintStructure->eprint->documents->document[0]->files->file[0]->filename = "example.php";
$crl->currentEPrintStructure->eprint->documents->document[0]->files->file[0]->mimetype = "text/x-php";
$crl->currentEPrintStructure->eprint->documents->document[0]->files->file[0]->hash = "f00c59a0339482631528e5e07dd99ccd";
$crl->currentEPrintStructure->eprint->documents->document[0]->files->file[0]->data = "PD9waHAKLy8gaW5jbHVkZSBjbGFzcyBmaWxlCnJlcXVpcmUgJ2NVUkwucGhwJzsKCi8vIGluc3Rh
bnRpYXRlCiRjdXJsID0gbmV3IEN1cmwoMSk7CgovLyBXZSBwYXNzIHZhcmlhYmxlcyBpbiBQT1NU
CiRjdXJsLT5hZGRQb3N0VmFyKCdsb2dpbicsJ2N5cmlsJyk7CiRjdXJsLT5hZGRQb3N0VmFyKCdw
YXNzd29yZCcsJ3MzY3VyZVBAc3N3MHJkJyk7CgovLyBDb25uZWN0aW9uIHRvIGZhY2Vib29rLCBh
IGNvb2tpZSBpcyBjcmVhdGVkIGFuZCBzdG9yZWQgaW4gYSBmaWxlCiRjdXJsLT5leGVjKCdodHRw
Oi8vd3d3LmZhY2Vib29rLmNvbS9sb2cucGhwJyk7CgovLyBOb3cgd2UgY2FuIGdldCBhIHBhZ2Ug
YXMgYSBsb2dnZWQgdXNlcgokdCA9ICRjdXJsLT5leGVjKCdodHRwOi8vd3d3LmZhY2Vib29rLmNv
bS9wcm9maWxlLnBocCcpOwoKLy8gQW5kIGRvIHdoYXQgd2Ugd2FudCB3aXRoIHRoZSByZXN1bHQg
Li4uIGdlbmVyYWxseSBwYXJzaW5nIGl0IHdpdGggcmVndWxhciBleHByZXNzaW9ucwo/Pg==
";
$crl->currentEPrintStructure->eprint->documents->document[0]->files->file[0]->hash_type = "MD5";
$crl->currentEPrintStructure->eprint->documents->document[0]->files->file[0]->filesize = "508";
$crl->currentEPrintStructure->eprint->documents->document[0]->files->file[0]->mtime = "2013-02-23 15:19:45";
#$crl->currentEPrintStructure->eprint->documents->document[0]->files->file[0]->url = "asdf";
#$crl->currentEPrintStructure->eprint->documents->document[0]->files->file[0]->data->addAttribute('encoding', 'base64 belong to us');
$crl->currentEPrintStructure->eprint->documents->document[0]->files->file[0]->data->attributes()->encoding = "base64";

$crl->currentEPrintStructure->eprint->documents->document[0]->mime_type = "text/x-php";
$crl->currentEPrintStructure->eprint->documents->document[0]->language = "en";
$crl->currentEPrintStructure->eprint->documents->document[0]->security = "public";
$crl->currentEPrintStructure->eprint->documents->document[0]->format = "text";
$crl->currentEPrintStructure->eprint->documents->document[0]->main = "example.php";
$crl->currentEPrintStructure->eprint->eprint_status = "inbox";
$crl->currentEPrintStructure->eprint->userid = "1";
$crl->currentEPrintStructure->eprint->type = "article";
$crl->currentEPrintStructure->eprint->metadata_visibility = "show";
#$crl->currentEPrintStructure->eprint->sword_depositor = "1"; // don't actually need this.
$crl->currentEPrintStructure->eprint->creators->item[0]->name->family = "Kane";
$crl->currentEPrintStructure->eprint->creators->item[0]->name->given = "David";
$crl->currentEPrintStructure->eprint->creators->item[0]->id = "dkane@wit.ie";
$crl->currentEPrintStructure->eprint->title = "My Amazing EPrint!!!";

//print "<td valign=\"top\"><pre>"; print_r($crl->currentEPrintStructure); print "</pre></td></tr></table><hr/>";

// now we upload our revised eprint structure.
#$crl->commitNewEPrint();  // this sets the new EPrintID.
$crl->addFile('testing 123 123 123 123 123 456xxxx', 'testing123.txt', 25, $type = "text");


#<!--
#http://oarepojunction.wordpress.com/2013/01/16/sword-1-3-vs-sword-2/
#-->



?>
<hr/>
<p>This is a simple test of the php CURL functionality in EPrints.</p>
</body></html>
