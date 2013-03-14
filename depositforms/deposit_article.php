<?php
    session_start();
    $_SESSION['form_valid'] = true;
    require_once("../SWORDMetaData.php");
    require_once("../EPrintsXMLWriter.php");
    require_once("../EPrintsWrapper.php");

    // Generates a date of the form "YYYY-MM-DD
    // The error checking is pretty minimal at the moment, e.g. nothing stops the user from entering
    // a string value for the year.
    function parseDate($y, $m, $d)
    {
	$date = "";
	if(!is_null($y) && strlen($y) > 0)
	    $date = $date . $y;
	else return $date;
	
	if(!is_null($m) && strlen($m) > 0 && $m != "Unspecified")
	    $date = $date . "-" . $m;
	else return $date;
	
	if(!is_null($d) && strlen($d) > 0 && $d != "Unspecified")
	    $date = $date . "-" . $d;
	return $date;
    }
 ?>


<!DOCTYPE html SYSTEM "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Deposit Article into CaltechAUTHORS</title>
	<link rel="stylesheet" type="text/css" href="deposit_article.css" media="all"/>
	<script type="text/javascript" src="js/animation.js"></script>
	<script type="text/javascript" src="js/Tooltip.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
	    var author_count = 1;
	    var file_count = 1;
            var tooltip_style = { "fontSize" : "0.85em", "backgroundColor" : "#CEE3F6" };
            var title_label_text = "The title of the article";
            var surname_label_text = "Author surname";
            var firstname_label_text = "Author first name and initials";
            var journal_label_text = "Publication where article appeared";
            var volume_label_text = "Volume where article appeared";
            var issue_label_text = "Issue where article appeared";
            var year_label_text = "Year of publication";
            var month_label_text = "Month of publication";
            var day_label_text = "Day of publication";
            var name_label_text = "Depositor's name (may be different from author)";
            var email_label_text = "Depositor's email";
            var affiliation_label_text = "Depositor's Caltech affiliation";
            var note_label_text = "Anything that doesn't fit elsewhere";
            var file_label_text = "PDF file";

	    $(function()
	    {
	        $('p#add_author').click(function(){
		    author_count += 1;
                    if(author_count <= 10)
                    {
		        $('#authorcontainer').append('<p><label id="familylabel_' + author_count + '" class="floatable" for="authorfamily_"' + author_count + '">Author Family Name: </label>' + '<input id="authorfamily_' + author_count + '" name="authorfamily[]" size="55"/></p>');
		        $('#authorcontainer').append('<p><label id="givenlabel_'+ author_count + '" class="floatable" for="authorgiven_"' + author_count + '">Author Given Name/Initials: </label>' + '<input id="authorgiven_' + author_count + '" name="authorgiven[]" size="55"/></p>');

			var dynamic_family_label = document.getElementById("familylabel_" + author_count);
			var dynamic_family_label_tooltip = document.createElement("span");
			dynamic_family_label_tooltip.innerHTML = surname_label_text;
			dynamic_family_label.addTooltip(dynamic_family_label_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

			var dynamic_given_label = document.getElementById("givenlabel_" + author_count);
			var dynamic_given_label_tooltip = document.createElement("span");
			dynamic_given_label_tooltip.innerHTML = firstname_label_text;
			dynamic_given_label.addTooltip(dynamic_given_label_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);
                    }
                });
            });


	    $(function()
	    {
	        $('p#add_file').click(function(){
		    file_count += 1;
		    if(file_count <=10)
                    {
		        $('#filecontainer').append('<p><label id="filelabel_' + file_count + '" class="floatable" for="fileupload_' + file_count + '">Attach File: </label>' + '<input id="fileupload_' + file_count + '" name="fileupload[]"' + '" type="file"/></p>');
                    }

		    var dynamic_file_label = document.getElementById("filelabel_" + file_count);
		    var dynamic_file_label_tooltip = document.createElement("span");
		    dynamic_file_label_tooltip.innerHTML = file_label_text;
		    dynamic_file_label.addTooltip(dynamic_file_label_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);
                });
            });

	    function defineTooltips()
	    {
		var title_label = document.getElementById("titlelabel");
		var title_label_tooltip = document.createElement("span");
		title_label_tooltip.innerHTML = title_label_text;
		title_label.addTooltip(title_label_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var family_label1 = document.getElementById("familylabel_1");
		var family_label1_tooltip = document.createElement("span");
		family_label1_tooltip.innerHTML = surname_label_text;
		family_label1.addTooltip(family_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var given_label1 = document.getElementById("givenlabel_1");
		var given_label1_tooltip = document.createElement("span");
		given_label1_tooltip.innerHTML = firstname_label_text;
		given_label1.addTooltip(given_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var journal_label1 = document.getElementById("journallabel");
		var journal_label1_tooltip = document.createElement("span");
		journal_label1_tooltip.innerHTML = journal_label_text;
		journal_label1.addTooltip(journal_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var volume_label1 = document.getElementById("volumelabel");
		var volume_label1_tooltip = document.createElement("span");
		volume_label1_tooltip.innerHTML = volume_label_text;
		volume_label1.addTooltip(volume_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var issue_label1 = document.getElementById("issuelabel");
		var issue_label1_tooltip = document.createElement("span");
		issue_label1_tooltip.innerHTML = issue_label_text;
		issue_label1.addTooltip(issue_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var year_label1 = document.getElementById("yearlabel");
		var year_label1_tooltip = document.createElement("span");
		year_label1_tooltip.innerHTML = year_label_text;
		year_label1.addTooltip(year_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var month_label1 = document.getElementById("monthlabel");
		var month_label1_tooltip = document.createElement("span");
		month_label1_tooltip.innerHTML = month_label_text;
		month_label1.addTooltip(month_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var day_label1 = document.getElementById("daylabel");
		var day_label1_tooltip = document.createElement("span");
		day_label1_tooltip.innerHTML = day_label_text;
		day_label1.addTooltip(day_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var name_label1 = document.getElementById("namelabel");
		var name_label1_tooltip = document.createElement("span");
		name_label1_tooltip.innerHTML = name_label_text;
		name_label1.addTooltip(name_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var email_label1 = document.getElementById("emaillabel");
		var email_label1_tooltip = document.createElement("span");
		email_label1_tooltip.innerHTML = email_label_text;
		email_label1.addTooltip(email_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var affiliation_label1 = document.getElementById("affiliationlabel");
		var affiliation_label1_tooltip = document.createElement("span");
		affiliation_label1_tooltip.innerHTML = affiliation_label_text;;
		affiliation_label1.addTooltip(affiliation_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var note_label1 = document.getElementById("notelabel");
		var note_label1_tooltip = document.createElement("span");
		note_label1_tooltip.innerHTML = note_label_text;
		note_label1.addTooltip(note_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

		var file_label1 = document.getElementById("filelabel_1");
		var file_label1_tooltip = document.createElement("span");
		file_label1_tooltip.innerHTML = file_label_text;
		file_label1.addTooltip(file_label1_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);
	    }
	</script>
    </head>
    <body onload="defineTooltips()">
        <h3>Deposit Article into CaltechAUTHORS</h3>
        <p>
            <a href="help.html" onclick="window.open('help.html', 'popup', 'width=1550,height=1225,scrollbars=yes,resizable=yes,toolbar=no,location=no,menubar=no,status=no,titlebar=no');return false">How to fill in this form</a>
        </p>
        <!-- Required fields: authorfirst/last title journal name email affiliation file -->
	<?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
	    <?php if(!isset($_POST['title']) || strlen($_POST['title']) <= 0): ?>
	        <p><font color="#FF0000">Missing title</font></p>
	        <?php $_SESSION['form_valid'] = false; ?>
   	    <?php endif; ?>

            <?php
	        $author_count = count($_POST['authorgiven']);
                $invalid_author = false;
                for($i=0; $i<$author_count; $i++)
	        {
		    if((strlen($_POST['authorgiven'][$i]) == 0) ||
		       (strlen($_POST['authorfamily'][$i]) == 0))
		    {
			$invalid_author = true;
                    }
		}
                if($invalid_author)
		{
                    echo("<p><font color=\"#FF0000\">For all authors, please provide both a given name and a surname</font></p>\n");
		    $_SESSION['form_valid'] = false;
		}

                if(!isset($_POST['journal_title']) || strlen($_POST['journal_title']) <= 0)
		{
                    echo("<p><font color=\"#FF0000\">Missing journal title</font></p>\n");
		    $_SESSION['form_valid'] = false;
		}

                if($_POST['affiliation'] == "Choose an affiliation...")
		{
                    echo("<p><font color=\"#FF0000\">Missing affiliation</font></p>\n");
		    $_SESSION['form_valid'] = false;
		}
                
                if(!isset($_POST['name']) || strlen($_POST['name']) <= 0)
		{
                    echo("<p><font color=\"#FF0000\">Missing your name</font></p>\n");
		    $_SESSION['form_valid'] = false;
		}

                if(!isset($_POST['email']) || strlen($_POST['email']) <= 0)
		{
                    echo("<p><font color=\"#FF0000\">Missing your email</font></p>\n");
		    $_SESSION['form_valid'] = false;
		}


                $upload_max_filesize = ini_get("upload_max_filesize");

                // If any one file is larger than upload_max_filesize (set in php.ini), then
                // $_FILES['fileupload'] will be undefined
                if(!isset($_FILES['fileupload']))
		{
                    echo("<p><font color=\"#FF0000\">File upload failed - file too large (max is $upload_max_filesize)?</font></p>\n");
		    $_SESSION['form_valid'] = false;
		}
                else
		{
                    // If none of the files selected for upload are too large then $filecount will evaluate 
                    // to at least 1, even if no files are selected.
                    $filecount = count($_FILES['fileupload']['tmp_name']);
                    $any_upload_attempts = false;
                    for($i=0; $i<$filecount; $i++)
                    {
			if(strlen($_FILES['fileupload']['name'][$i]) > 0)
			{
			    $any_upload_attempts = true;
			    if(!is_uploaded_file($_FILES['fileupload']['tmp_name'][$i]))
			    {
				echo("<p><font color=\"#FF0000\">Uploading the file \"" . $_FILES['fileupload']['name'][$i] . "\" failed - file too large (max is $upload_max_filesize)?</font></p>\n");
				$_SESSION['form_valid'] = false;
			    }
			    elseif(substr(strtolower($_FILES['fileupload']['name'][$i]), -4) != ".pdf")
			    {
				echo("<p><font color=\"#FF0000\">Uploading the file \"" . $_FILES['fileupload']['name'][$i] . "\" failed - only PDF files are allowed</font></p>\n");
				$_SESSION['form_valid'] = false;
			    }
			}
		    }
                    if(!$any_upload_attempts)
		    {
			echo("<p><font color=\"#FF0000\">At least one file is required</font></p>\n");
			$_SESSION['form_valid'] = false;
		    }
		}
	    ?>
        <?php endif; ?>

        <form method="post" accept-charset="utf-8" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <p>
                <label id="titlelabel" class="floatable" for="title">Title: </label> <input id="title" name="title" size="55" value="<?php if(($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['form_valid'] == true) || $_SERVER['REQUEST_METHOD'] == 'GET') { echo ''; } else { echo $_POST['title']; } ?>"/>
            </p>

	    <div id="authorcontainer">
		<p>
                    <label id="familylabel_1" class="floatable" for="authorfamily_1">Author Family Name: </label> <input id="authorfamily_1" name="authorfamily[]" size="55" value="<?php if(($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['form_valid'] == true) || $_SERVER['REQUEST_METHOD'] == 'GET') { echo ''; } else { echo $_POST['authorfamily'][0]; } ?>"/>
                </p>
                <p>
                    <label id="givenlabel_1" class="floatable" for="authorgiven_1">Author Given Name/Initials: </label> <input id="authorgiven_1" name="authorgiven[]" size="55" value="<?php if(($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['form_valid'] == true) || $_SERVER['REQUEST_METHOD'] == 'GET') { echo ''; } else { echo $_POST['authorgiven'][0]; } ?>"/>
		</p>
            </div>
            <p id="add_author"><a href="#"><span>&raquo; Add more authors (max 10)</span></a></p>
            <div id="journalcontainer">
		<p>
                    <label id="journallabel" class="floatable" for="journal_title">Journal: </label> <input id="journal_title" name="journal_title" size="55" value="<?php if(($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['form_valid'] == true) || $_SERVER['REQUEST_METHOD'] == 'GET') { echo ''; } else { echo $_POST['journal_title']; } ?>"/>
                </p>
		<p>
	            <label id="volumelabel" class="floatable" for="journal_volume">Volume <span class="optional">(optional)</span>: </label> <input id="journal_volume" name="journal_volume" size="55" value="<?php if(($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['form_valid'] == true) || $_SERVER['REQUEST_METHOD'] == 'GET') { echo ''; } else { echo $_POST['journal_volume']; } ?>"/>
                </p>
		<p>
                    <label id="issuelabel" class="floatable" for="journal_issue">Issue <span class="optional">(optional)</span>: </label> <input id="journal_issue" name="journal_issue" size="55" value="<?php if(($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['form_valid'] == true) || $_SERVER['REQUEST_METHOD'] == 'GET') { echo ''; } else { echo $_POST['journal_issue']; } ?>"/>
                </p>
		<p>
                    <label class="floatable" id="yearlabel" for="year">Year <span class="optional">(optional)</span>: </label> <input id="year" name="year" maxlength="4" value="<?php if(($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['form_valid'] == true) || $_SERVER['REQUEST_METHOD'] == 'GET') { echo ''; } else { echo $_POST['year']; } ?>"/>
                </p>
                <p>
		    <label id="monthlabel" class="floatable" for="month">Month <span class="optional">(optional)</span>: </label>
	            <select id="month" name="month">
                      <option selected="selected">
		          <?php if(($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['form_valid'] == true) || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'Unspecified'; } else { echo $_POST['month']; } ?>
                      </option>
		      <option value="01">January</option>
		      <option value="02">February</option>
		      <option value="03">March</option>
		      <option value="04">April</option>
		      <option value="05">May</option>
		      <option value="06">June</option>
		      <option value="07">July</option>
		      <option value="08">August</option>
		      <option value="09">September</option>
		      <option value="10">October</option>
		      <option value="11">November</option>
		      <option value="12">December</option>
                    </select>
                </p>
                <p>
		    <label id="daylabel" class="floatable" for="day">Day <span class="optional">(optional)</span>: </label>
		      <select id="day" name="day">
			<option selected="selected">
			    <?php if(($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['form_valid'] == true) || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'Unspecified'; } else { echo $_POST['day']; } ?>
                        </option>
			<option>01</option>
			<option>02</option>
			<option>03</option>
			<option>04</option>
			<option>05</option>
			<option>06</option>
			<option>07</option>
			<option>08</option>
			<option>09</option>
			<option>10</option>
			<option>11</option>
			<option>12</option>
			<option>13</option>
			<option>14</option>
			<option>15</option>
			<option>16</option>
			<option>17</option>
			<option>18</option>
			<option>19</option>
			<option>20</option>
			<option>21</option>
			<option>22</option>
			<option>23</option>
			<option>24</option>
			<option>25</option>
			<option>26</option>
			<option>27</option>
			<option>28</option>
			<option>29</option>
			<option>30</option>
			<option>31</option>
	              </select>
                </p>
             </div>
             <div id="submittercontainer">
		<p>
                    <label id="namelabel" class="floatable" for="name">Your name: </label> <input id="name" name="name" size="55"/ value="<?php if(isset($_POST['email'])) { echo $_POST['name']; } else { echo ''; } ?>">
                </p>
		<p>
                    <label id="emaillabel" class="floatable" for="email">Your email: </label> <input id="email" name="email" size="55" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } else { echo ''; } ?>"/>
                </p>
		<p>
		  <label id="affiliationlabel" class="floatable" for="affiliation">Your Caltech affiliation: </label>
		  <select name="affiliation" id="affiliation">
	            <option selected="selected"> <?php if(isset($_POST['affiliation'])) { echo $_POST['affiliation']; } else { echo "Choose an affiliation..."; }  ?></option>
		    <option>Faculty</option>
		    <option>Postdoc</option>
		    <option>Staff</option>
		    <option>Student</option>
		    <option>Other (please explain in Additional Information field)</option>
		    <option>None</option>
                  </select>
                </p>
            </div>
            <div id="textcontainer">
                <p>
	            <label id="notelabel" class="floatable" for="note">Additional Information <span class="optional">(optional)</span>: </label><textarea id="note" name="note" rows="10" cols="93"><?php if(($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['form_valid'] == true) || $_SERVER['REQUEST_METHOD'] == 'GET') { echo ''; } else { echo $_POST['note']; } ?></textarea>
                </p>                
            </div>
	    <div id="filecontainer">
		<p>
                    <label id="filelabel_1" class="floatable" for="fileupload_1">Attach File: </label> <input id="fileupload_1" name="fileupload[]" type="file"/>
                </p>
	    </div>
	    <p id="add_file"><a href="#"><span>&raquo; Attach more files (max 10)</span></a></p>
	    <p>
	        <input id="submit" type="submit" value="Deposit Article"/>
            </p>
        </form>
        <div id="delaymessage">
            <p>
                <br/><br/>
                Submissions will be visible in the repository only after review by CODA staff.
            </p>
        </div>
	<?php
            if($_SERVER['REQUEST_METHOD'] == 'POST')
	    {
		if($_SESSION['form_valid'] == true)
		{
		    // Empty the session array and terminate the session
		    $_SESSION = array();
		    session_destroy();

		    // Everything validated fine, so store all of the data entered
		    $title = trim($_POST['title']);

		    $metadata = new SWORDMetaData($title);

		    $filecount = count($_FILES['fileupload']['tmp_name']);
		    for($i=0; $i<$filecount; $i++)
		    {
			if(is_uploaded_file($_FILES['fileupload']['tmp_name'][$i]))
			{
			    $metadata->addPDF($_FILES['fileupload']['name'][$i], $_FILES['fileupload']['tmp_name'][$i]);
			}
		    }
		    
		    $author_count = count($_POST['authorgiven']);
		    for($i=0; $i<$author_count; $i++)
			$metadata->addAuthor(trim($_POST['authorgiven'][$i]), trim($_POST['authorfamily'][$i]));

		    $metadata->note = trim($_POST['note']);
		    $metadata->publication = trim($_POST['journal_title']);
		    $metadata->volume = trim($_POST['journal_volume']);
		    $metadata->issue_number = trim($_POST['journal_issue']);
		    $metadata->depositor_name = trim($_POST['name']);
		    $metadata->depositor_email = trim($_POST['email']);
		    $metadata->depositor_affiliation = trim($_POST['affiliation']);
		    $metadata->date = parseDate(trim($_POST['year']), trim($_POST['month']), trim($_POST['day']));
		    
		    // Write an XML file for the metadata
		    #$xml_writer = new EPrintsXMLWriter($metadata, "eprints.xml");
		    
		    print("<textarea cols=\"200\" rows=\"100\"");
		    print_r($metadata);
		    print("</textarea>");
		    
		    /*
		     * put eprint xml building functions here
		     *
		     */
		    
		     
		    $wrapper = new EPrintsWrapper('http://witeprints/sword-app/servicedocument', 'dkane', 'dkpass');
		    
		    #$eprints_xml = $xml_writer->writeString();
		    
		    #$wrapper->setXML($eprints_xml);
		    
		    
		    /*
		     * put eprint xml building functions here
		     *
		     */
		    
		    $new_id = $wrapper->commitNewEPrint();
		    if($new_id != -1)
		    {
			$success = TRUE;
			$filecount = count($_FILES['fileupload']['tmp_name']);
			for($i=0; $i<$filecount; $i++)
			{
			    if(is_uploaded_file($_FILES['fileupload']['tmp_name'][$i]))
			    {
				//$success = $wrapper->addFile($_FILES['fileupload']['name'][$i], $_FILES['fileupload']['tmp_name'][$i]);
				// The first arg is the actual filename, the second arg is the name it should take in the repository

				if($success)
				{
				    $success = $wrapper->addFile($_FILES['fileupload']['tmp_name'][$i], $new_id, "application/pdf");
				}
			    }
			}
		    }


		    // Generate an email for the user
		    $success_message = "Your CaltechAUTHORS submission was successful. Please allow a few business days until your submission is approved.\nIf you have any questions, email coda@library.caltech.edu and include the details beneath.\n\nBelow is some of the information that was recorded.\n\n";
		    $fail_message = "Your CaltechAUTHORS submission failed. The coda team has been notified of this failure and will attend to it.\nIf you have any questions, email coda@library.caltech.edu and include the details beneath.\n\nBelow is some of the information that was recorded.\n\n";
		    $timestamp = date("Y-m-d H:i:s");

                    // Various email clients don't deal well with whitespace, so there's no point in trying to pretty-print
		    $meta_message = "Time: " . $timestamp . "\n";
		    $meta_message = $meta_message . "Depositor's IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
		    $meta_message = $meta_message . "Depositor's Name: " . $metadata->depositor_name . "\n";
		    $meta_message = $meta_message . "Depositor's Email: " . $metadata->depositor_email . "\n";
		    $meta_message = $meta_message . "Depositor's Affiliation: " . $metadata->depositor_affiliation . "\n";
		    $meta_message = $meta_message . "Title: " . $title . "\n";
		    $meta_message = $meta_message . "First author: " . trim($_POST['authorgiven'][0]) . " " . trim($_POST['authorfamily'][0]) . "\n";
		    $meta_message = $meta_message . "Publication: " . $metadata->publication . "\n";
		    
		    if(!is_null($metadata->volume) && strlen($metadata->volume) > 0)
			$meta_message = $meta_message . "Volume: " . $metadata->volume . "\n";
		    else $meta_message = $meta_message . "Volume: <empty>\n";
		    
		    if(!is_null($metadata->issue_number) && strlen($metadata->issue_number) > 0)
			$meta_message = $meta_message . "Issue: " . $metadata->issue_number . "\n";
		    else $meta_message = $meta_message . "Issue: <empty>\n";
		    
		    if(!is_null($metadata->date) && strlen($metadata->date) > 0)
			$meta_message = $meta_message . "Date: " . $metadata->date . "\n";
		    else $meta_message = $meta_message . "Date: <empty>\n";

		    if(!is_null($metadata->note) && strlen($metadata->note) > 0)
			$meta_message = $meta_message . "Additional Information: " . $metadata->note . "\n";
		    else $meta_message = $meta_message . "Additional Information: <empty>\n";
		    

		    if($success)
		    {
			echo("<br/>\n");
			echo("<p class=\"submitmessage\">\n");
			echo("The deposit was successful and has been submitted to CODA staff for review. Please note that your submission will not be visible until approved. <br/> Email coda@caltech.edu if you have any questions.\n");
			echo("</p>\n");
			
			$message = $success_message . $meta_message;
			$bcc = "tommy@library.caltech.edu";  // ADD coda@library.caltech.edu!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
			$headers = "From: coda@library.caltech.edu\r\n" . "Bcc: " . $bcc . "\r\n";
			$mail_accepted = mail($_POST['email'], "Your CaltechAUTHORS submission", $message, $headers);
			if($mail_accepted)
			{
			    echo("<p class=\"submitmessage\">\n");
			    echo("An email has been sent to " . $_POST['email'] . " as confirmation\n");
			    echo("</p>\n");
			}
		    }
		    else
		    {
			echo("<p class=\"submitmessage\">\n");
			echo("Deposit failed.\n");
			echo("Server status code $response->sac_status.\n");
			echo("Server response $response->sac_xml.\n");
			echo("</p>\n");
			echo("<p class=\"submitmessage\">Please email coda@caltech.edu for assistance.<p>\n");

			$message = $fail_message . $meta_message;
			$message = $message . "Server status code: " . $response->sac_status . "\n";
			$message = $message . "Server response:    " . $response->sac_xml . "\n";
			$bcc = "tommy@library.caltech.edu";  // ADD coda@library.caltech.edu!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1
			$headers = "From: coda@library.caltech.edu\r\n" . "Bcc: " . $bcc . "\r\n";
			$mail_accepted = mail($_POST['email'], "Your CaltechAUTHORS submission", $message, $headers);
			if($mail_accepted)
			{
			    echo("<p class=\"submitmessage\">\n");
			    echo("An email has been sent to " . $_POST['email'] . " as confirmation\n");
			    echo("</p>\n");
			}
		    }
		}
	    }
        ?>
    </body>
</html>



