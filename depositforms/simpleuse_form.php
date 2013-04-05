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
<form action="./simpleuse.php" method="post"  enctype="multipart/form-data">
<fieldset>
    <legend>General Deposit Metadata</legend>
    
    <input type="text"  name="title" required value="Testing Title for EPrintsDeposit helper" /><label for="title">title</label><br/> 
    <input type="text"  name="journal_title"  value="The Journal of Testing Repository Functionality" /> <label for="journal_title">journal_title</label><br/> 
    <input type="text"  name="journal_volume"  value="1" /> <label for="journal_volume">journal_volume</label><br/> 
    <input type="text"  name="journal_issue"  value="2" /> <label for="journal_issue">journal_issue</label><br/> 
    <input type="text"  name="year"  value="1234" /> <label for="year">year</label><br/> 
    <input type="text"  name="month"  value="3" /> <label for="month">month</label><br/> 
    <input type="text"  name="day"  value="3" /> <label for="day">day</label><br/> 
    <input type="text"  name="name" required value="David Kane" /> <label for="name">name</label><br/> 
    <input type="email" name="email" required value="dkane@wit.ie" /> <label for="email">email</label><br/> 
    <input type="text"  name="affiliation"  value="affiliation" /> <label for="affiliation">affiliation</label><br/> 
    <input type="text"  name="file_path"  value="/var/www/eprintsDepositHelper/depositforms/images/wit.jpeg" /> <label for="file_path">file_path</label><br/> 
    <input type="text"  name="contenttype"  value="image/jpeg" /> <label for="contenttype">contenttype</label><br/> 
    <input type="text"  name="name"  value="witlogo" /> <label for="name">name</label><br/><br/> 
    
    <fieldset>
        <legend>Author Metadata (repeating 'n' times)</legend>
        <hr/>
        <input type="text" name="authorgiven[]"  value="David" /> <label for="authorgiven">authorgiven</label><br/> 
        <input type="text" name="authorfamily[]"  value="Kane" /> <label for="authorfamily">authorfamily</label><br/> 
        <input type="email" name="authorID[]"  value="dkane@wit.ie" /> <label for="authorID">authorID</label><br/> 
        <hr/>
        <input type="text" name="authorgiven[]"  value="Tommy" /> <label for="authorgiven">authorgiven</label><br/> 
        <input type="text" name="authorfamily[]"  value="Ingulfsen" /> <label for="authorfamily">authorfamily</label><br/> 
        <input type="email" name="authorID[]"  value="tingulfsen@library.caltech.edu" /> <label for="authorID">authorID</label><br/> 
        <hr/>
    </fieldset>
</fieldset>

<br/>
<br/>

<fieldset>
    <legend>Files (repeating 'n' times)</legend>
    <!-- put files metadata form fields here -->
    
    <hr/>
    <label for="fileupload_1">Choose file:</label>
    <input type="file" name="fileupload[]" id="fileupload_1 value="/var/www/eprintsdeposithelper/depositforms/images/caltech.jpeg"><br/>
        
    <label for="filesecurity_1">Visible To:</label>
    <select id="filesecurity_1" name="filesecurity[]">
        <option value = "public" selected="selected">public</option>
        <option value = "staffonly">staffonly</option>
        <option value = "validuser">validuser</option>
    </select><br/>
    
    <label for="fileformat_1">Format of the file</label>
    <select id="fileformat_1" name="fileformat[]">
        <option value = "text" selected="selected">text</option>
        <option value = "slideshow">slideshow</option>
        <option value = "image">image</option>
        <option value = "video">video</option>
        <option value = "audio">audio</option>
        <option value = "archive">archive</option>
        <option value = "data">data</option>
        <option value = "other">other</option>
    </select><br/>
    <label for="fileembargo_1">Publicly available after: (yyyy-mm-dd)</label>
    <input type="date" name="fileembargo[]" id="fileembargo_1 value="2013-05-21" pattern="\d{4}-\d{1,2}-\d{1,2}"><br/>
        
    <hr/>
    <label for="fileupload_2">Choose file:</label>
    <input type="file" name="fileupload[]" id="fileupload_2 value="/var/www/eprintsdeposithelper/depositforms/images/caltech.jpeg"><br/>
        
    <label for="filesecurity_2">Visible To:</label>
    <select id="filesecurity_2" name="filesecurity[]">
        <option value = "public" selected="selected">public</option>
        <option value = "staffonly">staffonly</option>
        <option value = "validuser">validuser</option>
    </select><br/>
    
    <label for="fileformat_2">Format of the file</label>
    <select id="fileformat_2" name="fileformat[]">
        <option value = "text" selected="selected">text</option>
        <option value = "slideshow">slideshow</option>
        <option value = "image">image</option>
        <option value = "video">video</option>
        <option value = "audio">audio</option>
        <option value = "archive">archive</option>
        <option value = "other">other</option>
    </select><br/>
    <label for="fileembargo_2">Publicly available after: (yyyy-mm-dd)</label>
    <input type="date" name="fileembargo[]" id="fileembargo_2 value="2013-05-21" pattern="\d{4}-\d{1,2}-\d{1,2}"><br/>
    <hr/>
</fieldset>
<input type="submit" value="deposit" />
</form>
<br/>
<br/>
</body>
</html>
