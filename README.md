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
deleting existing eprints. To explain the functionality, let's take a quick look at the sample form depositforms/simpleuse_form.php.

