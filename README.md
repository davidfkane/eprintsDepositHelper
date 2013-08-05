eprintsDepositHelper
====================

This is a PHP tool for creating and modifying records within the [EPrints repository software](http://www.eprints.org/)
using the [SWORD protocol](http://swordapp.org/).


System Requirements
-------------------

eprintsDepositHelper is written for EPrints version 3.3.x. It will not work with any earlier versions, because of major
changes in the way EPrints handles SWORD between version 3.2 and 3.3. Whereas EPrints 3.2 supported the SWORD v1, EPrints
3.3 is written for [SWORD v2](http://swordapp.org/sword-v2/sword-v2-specifications/). You can read more about the SWORD
interface in EPrints 3.3 [here](http://wiki.eprints.org/w/API:EPrints/Apache/CRUD).


Use
---

EPrintsWrapper.php is the core tool, and can be used to create new eprints on your EPrints server. Note that while the
EPrints API supports the full set of CRUD operations, EPrintsWrapper.php does not (yet) support reading, updating or
deleting existing eprints. To explain how to use it, let's take a quick look at the sample form [depositforms/simpleuse_form.php](https://github.com/davidfkane/eprintsDepositHelper/blob/master/depositforms/simpleuse_form.php).

simpleuse_form.php first sets up a basic HTML form, and then creates a new EPrintsWrapper:

```
require_once('../EPrintsWrapper.php');
$wrapper = new EPrintsWrapper($eprintsServiceDocument, $username, $password);
```

When creating the wrapper, you need to supply the URL of the [service document](http://swordapp.github.io/SWORDv2-Profile/SWORDProfile.html#protocoloperations_retreivingservicedocument). The SWORD service document specifies the available collections, upload sizes and formats that
EPrints will accept. A user name and password for EPrints is also required. It is recommended that you create a separate account for
use with SWORD, with only the minimal permissions necessary to deposit new eprints.

Having created the wrapper, the metadata that the user entered in the form is then added:

```
for($i=0; $i<count($_POST['authorgiven']); $i++)
{
    $wrapper->addCreator(trim($_POST['authorfamily'][$i]), trim($_POST['authorgiven'][$i]), trim($_POST['authorID'][$i]));
}

$wrapper->title = trim($title);
$wrapper->ispublished = $ispublished;
...
$wrapper->addEPrintMetadata($wrapper->title, $type);
```

Now that the metadata is complete, any files associated with the deposit are added:

```
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
```

Now the new eprint deposit is ready, and can be shipped off to the EPrints server like so:

```
$new_id = $wrapper->commitNewEPrint();
    
print("<h2>Success!</h2>");
print("New eprint will be at: <a href=\"http://witeprints/cgi/users/home?screen=EPrint%3A%3AView&eprintid=".$new_id."\" target=\"_blank\">".$new_id."</a>");
if($new_id == EPrintsWrapper::ERROR_VALUE)
{
    $wrapper->debugOutput($wrapper->getErrorMessage());
}
```

