<!DOCTYPE html>
<?php

error_reporting(E_ALL);

?>
<html>
<head>
    <title>Usage Explanation</title>
    <style>
        fieldset, fieldset legend{background-color: #c0c0c0;}
        fieldset fieldset, fieldset fieldset legend{background-color: #a0a0a0;}
    </style>
</head>

<body>

<h1>Depositing a file</h1>
<p>This is a simple, illustrative, working form for developers, with no JavaScript</p>
<?php




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
); 
#$note = 'Note text one two three four.';
?>
<form action="./simpleuse.php" method="post">
<fieldset>
    <legend>General Deposit Metadata</legend>
    
    <input type="text" name="title"  value="Testing Title for EPrintsDeposit helper" /><label for="title">title</label><br/> 
    <input type="text" name="journal_title"  value="The Journal of Testing Repository Functionality" /> <label for="journal_title">journal_title</label><br/> 
    <input type="text" name="journal_volume"  value="1" /> <label for="journal_volume">journal_volume</label><br/> 
    <input type="text" name="journal_issue"  value="2" /> <label for="journal_issue">journal_issue</label><br/> 
    <input type="text" name="year"  value="1234" /> <label for="year">year</label><br/> 
    <input type="text" name="month"  value="3" /> <label for="month">month</label><br/> 
    <input type="text" name="day"  value="3" /> <label for="day">day</label><br/> 
    <input type="text" name="name"  value="David Kane" /> <label for="name">name</label><br/> 
    <input type="text" name="email"  value="dkane@wit.ie" /> <label for="email">email</label><br/> 
    <input type="text" name="affiliation"  value="affiliation" /> <label for="affiliation">affiliation</label><br/> 
    <input type="text" name="file_path"  value="/var/www/eprintsDepositHelper/depositforms/images/wit.jpeg" /> <label for="file_path">file_path</label><br/> 
    <input type="text" name="contenttype"  value="image/jpeg" /> <label for="contenttype">contenttype</label><br/> 
    <input type="text" name="name"  value="witlogo" /> <label for="name">name</label><br/><br/> 
    
    <fieldset>
        <legend>Author Metadata (repeating 'n' times)</legend>
        <hr/>
        <input type="text" name="authorgiven"  value="David" /> <label for="authorgiven">authorgiven</label><br/> 
        <input type="text" name="authorfamily"  value="Kane" /> <label for="authorfamily">authorfamily</label><br/> 
        <input type="text" name="authorID"  value="dkane@wit.ie" /> <label for="authorID">authorID</label><br/> 
        <hr/>
        <input type="text" name="authorgiven"  value="Tommy" /> <label for="authorgiven">authorgiven</label><br/> 
        <input type="text" name="authorfamily"  value="Ingulfsen" /> <label for="authorfamily">authorfamily</label><br/> 
        <input type="text" name="authorID"  value="tingulfsen@library.caltech.edu" /> <label for="authorID">authorID</label><br/> 
        <hr/>
    </fieldset>
</fieldset>

<br/>
<br/>

<fieldset>
    <legend>Files (repeating 'n' times)</legend>
    <!-- put files metadata form fields here -->
    <hr/>
    <input type="file" name="fileupload[]" id="fileupload_1"><label for="fileupload_1">fileupload_1</label><br/>
    <input type="text" name="filename[]" id="filename_1"><label for="filename_1">filename_1</label><br/>
    <input type="text" name="filetype[]" id="filetype_1"><label for="filetype_1">filetype_1</label><br/>
    <hr/>
    <input type="file" name="fileupload[]" id="fileupload_2"><label for="fileupload_2">fileupload_2</label><br/>
    <input type="text" name="filename[]" id="filename_2"><label for="filename_2">filename_2</label><br/>
    <input type="text" name="filetype[]" id="filetype_2"><label for="filetype_2">filetype_2</label><br/>
    <hr/>
</fieldset>
<input type="submit" value="deposit" />
</form>
<br/>
<br/>

</body>
</html>
