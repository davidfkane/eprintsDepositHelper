<html><body>
<head>
    <title>eprintsDepositHelper Index.php</title>
    <style>
        .textarea1{background-color: pink;}
        .textarea2{background-color: lightgreen;}
    </style>    
</head>

<h1>eprintsDepositHelper</h1>
<hr/>
<ul>
<li><a href="addfile.php">Add file example</a></li>
<li><a href="createNewEPrint.php">Create New EPrint example</a></li>
<li><a href="/depositforms/deposit_article.php">Caltech Deposit Form Example</a></li>
<li><a href="/depositforms/simpleuse.php">Simple Action Page Example, with pre-filled variables</a> (for developers to get a quick handle on how the class is implemented)</li>
</ul>
<hr/>

<p>This is an implementation of the php CURL functionality in EPrints.</p>

<!--
<pre>
    
          <file id='http://witeprints/id/file/213'>
            <fileid>213</fileid>
            <datasetid>document</datasetid>
            <objectid>70</objectid>
            <filename>example.php</filename>
            <mime_type>text/x-php</mime_type>
            <hash>f00c59a0339482631528e5e07dd99ccd</hash>
            <hash_type>MD5</hash_type>
            <filesize>508</filesize>
            <mtime>2013-03-14 19:56:11</mtime>
            <url>http://witeprints/2/1/example.php</url>
            <data encoding='base64'>PD9waHAKLy8gaW5jbHVkZSBjbGFzcyBmaWxlCnJlcXVpcmUgJ2NVUkwucGhwJzsKCi8vIGluc3Rh
bnRpYXRlCiRjdXJsID0gbmV3IEN1cmwoMSk7CgovLyBXZSBwYXNzIHZhcmlhYmxlcyBpbiBQT1NU
CiRjdXJsLT5hZGRQb3N0VmFyKCdsb2dpbicsJ2N5cmlsJyk7CiRjdXJsLT5hZGRQb3N0VmFyKCdw
YXNzd29yZCcsJ3MzY3VyZVBAc3N3MHJkJyk7CgovLyBDb25uZWN0aW9uIHRvIGZhY2Vib29rLCBh
IGNvb2tpZSBpcyBjcmVhdGVkIGFuZCBzdG9yZWQgaW4gYSBmaWxlCiRjdXJsLT5leGVjKCdodHRw
Oi8vd3d3LmZhY2Vib29rLmNvbS9sb2cucGhwJyk7CgovLyBOb3cgd2UgY2FuIGdldCBhIHBhZ2Ug
YXMgYSBsb2dnZWQgdXNlcgokdCA9ICRjdXJsLT5leGVjKCdodHRwOi8vd3d3LmZhY2Vib29rLmNv
bS9wcm9maWxlLnBocCcpOwoKLy8gQW5kIGRvIHdoYXQgd2Ugd2FudCB3aXRoIHRoZSByZXN1bHQg
Li4uIGdlbmVyYWxseSBwYXJzaW5nIGl0IHdpdGggcmVndWxhciBleHByZXNzaW9ucwo/Pg==
</data>
          </file>
    
    
</pre>
-->

<?php
/*
$hash = "f00c59a0339482631528e5e07dd99ccd";

$encoded = "PD9waHAKLy8gaW5jbHVkZSBjbGFzcyBmaWxlCnJlcXVpcmUgJ2NVUkwucGhwJzsKCi8vIGluc3RhbnRpYXRlCiRjdXJsID0gbmV3IEN1cmwoMSk7CgovLyBXZSBwYXNzIHZhcmlhYmxlcyBpbiBQT1NUCiRjdXJsLT5hZGRQb3N0VmFyKCdsb2dpbicsJ2N5cmlsJyk7CiRjdXJsLT5hZGRQb3N0VmFyKCdwYXNzd29yZCcsJ3MzY3VyZVBAc3N3MHJkJyk7CgovLyBDb25uZWN0aW9uIHRvIGZhY2Vib29rLCBhIGNvb2tpZSBpcyBjcmVhdGVkIGFuZCBzdG9yZWQgaW4gYSBmaWxlCiRjdXJsLT5leGVjKCdodHRwOi8vd3d3LmZhY2Vib29rLmNvbS9sb2cucGhwJyk7CgovLyBOb3cgd2UgY2FuIGdldCBhIHBhZ2UgYXMgYSBsb2dnZWQgdXNlcgokdCA9ICRjdXJsLT5leGVjKCdodHRwOi8vd3d3LmZhY2Vib29rLmNvbS9wcm9maWxlLnBocCcpOwoKLy8gQW5kIGRvIHdoYXQgd2Ugd2FudCB3aXRoIHRoZSByZXN1bHQgLi4uIGdlbmVyYWxseSBwYXJzaW5nIGl0IHdpdGggcmVndWxhciBleHByZXNzaW9ucwo/Pg==";

print  "<br/>";
print  "Filesize = " . strlen(base64_decode($encoded)) . "<br/>";
print $hash . "<br/>";
print md5(base64_decode($encoded)) .  "<br/>";
*/
?>





</body></html>
