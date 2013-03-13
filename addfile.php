<html><body>
<head>
    <title>eprintsDepositHelper Index.html</title>
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


# adds a file to an existing eprint.

# Constructor
$crl = new EPrintsWrapper('http://witeprints/sword-app/servicedocument', 'dkane', 'dkpass', 1);

# Add a file - addFile(file_location, eprint_id, MIME_type)
#$crl->addFile('/var/www/eprintsDepositHelper/dandelion2.JPG', 1, 'image/jpeg');

$crl->addFile('./example_eprint_files.xml', 1, 'application/vnd.eprints.data+xml');


//print "<td valign=\"top\"><pre>"; print_r($crl->currentEPrintStructure); print "</pre></td></tr></table><hr/>";

// now we upload our revised eprint structure.
#$crl->commitNewEPrint();  // this sets the new EPrintID.



#<!--
#http://oarepojunction.wordpress.com/2013/01/16/sword-1-3-vs-sword-2/
#-->



?>
<hr/>
<p>This is a simple test of the php CURL functionality in EPrints.</p>
</body></html>
