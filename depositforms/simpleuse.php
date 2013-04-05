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

#$note = $_POST['note'];
$title = $_POST['title'];
$journal_title = $_POST['journal_title'];
$journal_volume = $_POST['journal_volume'];
$journal_issue = $_POST['journal_issue'];
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
$name = $_POST['name'];
$email = $_POST['email'];
$affiliation = $_POST['affiliation'];


#$file_path = $_POST['file_path'];
#$contenttype = $_POST['contenttype'];
#$name = $_POST['name'];




require_once('../EPrintsWrapper.php');
// we requre the class

$wrapper = new EPrintsWrapper($eprintsServiceDocument, $username, $password);

for($i=0; $i<count($_POST['authorgiven']); $i++)
{
    $wrapper->addCreator(trim($_POST['authorfamily'][$i]), trim($_POST['authorgiven'][$i]), trim($_POST['authorID'][$i]));
}

#$wrapper->note = trim($note);
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

?>
</body>
</html>
