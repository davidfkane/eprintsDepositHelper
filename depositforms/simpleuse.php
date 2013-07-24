<!DOCTYPE html>
<?php
error_reporting(E_ALL);
?>
<html>
<head>
    <title>Usage Explanation</title>
</head>

<body>

<h1>Depositing a file</h1>
<p>This is what an 'action' page would look like</p>
<?php

// Data needed for instance of class

$eprintsServiceDocument = "http://witeprints/sword-app/servicedocument";
#$eprintsServiceDocument = "http://authorstest.library.caltech.edu/sword-app/servicedocument";
$username = 'dkane';
$password = 'password';

#$file_path = $_POST['file_path'];
#$contenttype = $_POST['contenttype'];
#$name = $_POST['name'];

###################################################################################################

$username = $_POST['username'];
$password = $_POST['password'];

$divisions = $_POST['divisions'];
$subjects = $_POST['subjects'];


$date_type = $_POST['datetype'];
$date = $_POST['date'];
$date_ymd = explode('-', $date);
$type = $_POST['type'];
$ispublished = $_POST['status'];


if($type == 'article'){
    $title = $_POST['journal_articletitle'];
    $publication = $_POST['journal_title'];
    $volume = $_POST['journal_volume'];
    $issue = $_POST['journal_issue'];
    $issn = $_POST['journal_issn'];
    
}elseif($type == 'conference_item'){
    $title = $_POST['pres_title'];
    $pres_type = $_POST['pres_type'];
    $event_title = $_POST['event_title'];
    $event_type = $_POST['event_type'];    
    $event_location = $_POST['event_location'];    
}
elseif($type == 'book_section'){
    $title = $_POST['title'];
    $book_title = $_POST['book_title'];
    $publisher = $_POST['publisher'];
    $isbn = $_POST['isbn'];
    
}elseif($type == 'book'){
    $title = $_POST['book_title'];
    $publisher = $_POST['publisher'];
    $isbn = $_POST['isbn'];
}

$ep_abstract = $_POST['abstract'];
$official_url = $_POST['url'];
$refereed = $_POST['refereed'];

#$given = $_POST['authorgiven'][];
#$family = $_POST['authorfamily'][];
#$id = $_POST['authorID'][];
#$author_editor = $_POST['authorType'][];

#$full_text_status = $_POST['full_text_status'][];
#$mime_type = $_POST['fileformat'][];
#$security = $_POST['filesecurity'][];
#$date_embargo = $_POST['fileembargo'][];

/*
$ = $_FILES['fileupload']['name'][];
$ = $_FILES['fileupload']['type'][];
$ = $_FILES['fileupload']['tmp_name'][];
$ = $_FILES['fileupload']['error'][];
$ = $_FILES['fileupload']['size'][];
*/

require_once('../EPrintsWrapper.php');
// we requre the class

$wrapper = new EPrintsWrapper($eprintsServiceDocument, $username, $password);

for($i=0; $i<count($_POST['authorgiven']); $i++)
{
    $wrapper->addCreator(trim($_POST['authorfamily'][$i]), trim($_POST['authorgiven'][$i]), trim($_POST['authorID'][$i]));
}

#$wrapper->note = trim($note);
$wrapper->title = trim($title);
$wrapper->ispublished = $ispublished;
$wrapper->refereed = $refereed;
$wrapper->subjects = $subjects;
$wrapper->divisions = $divisions;
$wrapper->ep_abstract = $ep_abstract;
$wrapper->official_url = $official_url;
$wrapper->date = $date;
$wrapper->date_type = $date_type;

if($type == 'article'){
    $wrapper->journalName = trim($publication);
    $wrapper->volume = trim($volume);
    $wrapper->issue = trim($issue);  
    $wrapper->issn = $issn;
}elseif($type == 'conference_item'){
    $wrapper->pres_title = $pres_title;
    $wrapper->event_title = $event_title;
    $wrapper->event_type = $event_type;
    $wrapper->pres_type = $pres_type;
    $wrapper->event_location = $event_location;
}elseif($type == 'book'){
    $wrapper->publisher = $publisher;
    $wrapper->isbn = $isbn;
}elseif($type == 'book_section'){
    $wrapper->book_title = $book_title;
    $wrapper->publisher = $publisher;
    $wrapper->isbn = $isbn;
}
$wrapper->year = trim($date_ymd[0]);
$wrapper->month = trim($date_ymd[1]);
$wrapper->day = trim($date_ymd[2]);

$wrapper->depositorName = trim($username);
$wrapper->depositorEmail = trim($username."@wit.ie");
//$wrapper->depositorAffiliation = trim($affiliation);
$wrapper->addEPrintMetadata($wrapper->title, $type); // construct the eprint metadata, adding type (type should actually be set beforehand)
#$wrapper->addFile($file_path, $contenttype, $name);  // add the files

// phpinfo();
$filecount = count($_FILES['fileupload']['tmp_name']);
for($i=0; $i<$filecount; $i++)
{
    if(is_uploaded_file($_FILES['fileupload']['tmp_name'][$i]))
    {
       
        $wrapper->addFile(
            $_FILES['fileupload']['tmp_name'][$i],
            $_FILES['fileupload']['type'][$i],
            $_FILES['fileupload']['name'][$i],
            $_POST['filesecurity'][$i],
            $_POST['fileformat'][$i],
            $_POST['fileembargo'][$i]
        );
    }
}
$wrapper->debugOutput("<hr/>");
$new_id = $wrapper->commitNewEPrint();

print("<h2>Success!</h2>");
print("New eprint will be at: <a href=\"http://witeprints/cgi/users/home?screen=EPrint%3A%3AView&eprintid=".$new_id."\" target=\"_blank\">".$new_id."</a>");
if($new_id == EPrintsWrapper::ERROR_VALUE)
{
$wrapper->debugOutput($wrapper->getErrorMessage());
}


print("<pre>");
print_r($_POST);
print("</pre>")
?>
</body>
</html>
