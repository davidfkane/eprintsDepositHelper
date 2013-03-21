<!DOCTYPE html>

<html>
<head>
    <title>Usage Explanation</title>
</head>

<body>

<h1>Depositing a file</h1>
<p>This is what an 'action' page would look like</p>
<?php

// Data needed for instance of class
$eprintsServiceDocument = "http://authorstest.library.caltech.edu/sword-app/servicedocument";
$username = 'user';
$password = 'pass';

// Data needed to create a new EPrint
$authors = array(
    array(
        'authorgiven' => 'David',
        'authorfamily' => 'Kane',
        'authorID' => 'dkane@wit.ie'
    ),
    array(
        'authorgiven' => 'Tommy',
        'authorfamily' => 'Ingulfsen',
        'authorID' => 'tommy@library.caltech.edu'
    )
)
$note = '';
$title = '';
$journal_title = '';
$journal_volume = '';
$journal_issue = '';
$year = '';
$month = '';
$day = '';
$name = '';
$email = '';
$affiliation = '';
$file_path = '/path/to/file.doc';
$file_path2 = '/path/to/file.gif';
$file_path3 = '/path/to/file.xls';




require_once('../EPrintsWrapper.php');
// we requre the class

$wrapper = new EPrintsWrapper($eprintsServiceDocument, $username, $password);
for($i=0; $i<count($authors); $i++)
{
$wrapper->addCreator(trim($authors[$i]['authorfamily']), trim($authors[$i]['authorgiven']), trim($authors[$i]['authorID']));
}

$wrapper->note = trim($note);
$wrapper->title = trim($title);
$wrapper->journalName = trim($journal_title);
$wrapper->volume = trim($journal_volume);
$wrapper->issue = trim($journal_issue);
$wrapper->year = trim($year);
$wrapper->month = trim($month);
$wrapper->day = trim($day);
$wrapper->depositorName = trim($name);
$wrapper->depositorEmail = trim($email);
$wrapper->depositorAffiliation = trim($affiliation);

$wrapper->addEPrintMetadata($wrapper->title, "article"); // construct the eprint metadata, adding type (type should actually be set beforehand)


$wrapper->addFile2($file_path, $file_path2, $file_path3);  // add the files

$new_id = $wrapper->commitNewEPrint();  // add new eprint, returns the unique ID of that eprint
echo("new_id = $new_id");

if($new_id == EPrintsWrapper::ERROR_VALUE)
{
echo($wrapper->getErrorMessage());
}





?>
</body>
</html>
