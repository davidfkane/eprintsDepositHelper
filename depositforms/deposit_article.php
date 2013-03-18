<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Thank you</title>
    </head>
    <body>
        <?php
            require_once("swordappclient.php");
            require_once("SWORDMetaData.php");
            require_once("SWORDFileMaker.php");
            require_once("EPrintsXMLWriter.php");
            require_once("swordappclient.php");


            $title = $_POST['title'];
            $author = $_POST['authorgiven'];
            $abstract = $_POST['abstract'];

            $metadata = new SWORDMetaData($author, $author, $title, $_FILES['fileupload']['name'], $_FILES['fileupload']['tmp_name']);
            $metadata->abstract = $abstract;
            $xml_writer = new EPrintsXMLWriter($metadata, "eprints.xml");
            $xml_writer->write();

            $fileMaker = new SWORDFileMaker("uploadfiles/eprints.xml",$metadata->pdf_tmp_filename, $metadata->pdf_given_filename);
            $zip_filename = $fileMaker->writeZip();
            print "zf = $zip_filename\n";


            print "<p>";
            print "Title = $title";
            print "</p>";
            print "<p>";
            print "author = $author";
            print "</p>";
            print "<p>";
            print "abstract = $abstract";
            print "</p>";
            print "<p>";
            print "<p>";
            if(is_uploaded_file($_FILES['fileupload']['tmp_name']))
            {
                print "File upload successful";
	    }
	    else
            {
		print "File upload failed";
	    }
	    print "</p>";



            $swordclient = new SWORDAPPClient();
            $response = $swordclient->deposit("http://eprints325.localhost/sword-app/deposit/buffer", "tommy", "Ntb45Prk", '',$zip_filename,"http://eprints.org/ep2/data/2.0","application/zip");
#            $response = $swordclient->deposit("http://eprints325.localhost/sword-app/deposit/buffer", "tommy", "Ntb45Prk", '',$zip_filename,"PDF","application/pdf");
            if(($response->sac_status == 200) || ($response->sac_status == 201))
            {
                print "<p>";
		print "Successfully deposited.";
		print "</p>";
	    }
	    else
	    {
		print "<p>";
		print "Deposit failed.";
		print "Server status code $response->sac_status.";
		print "Server response $response->sac_xml. ";
		print "</p>";
	    }

            
        ?>
    </body>
</html>
