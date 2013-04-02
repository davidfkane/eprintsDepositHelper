<html><body>
<head>
    <title>eprintsDepositHelper Index.php</title>
    
    <style>
        .textarea1{background-color: pink;}
        .textarea2{background-color: lightgreen;}
        body{font-family: sans-serif;}
    </style>    
</head>

<h1>eprintsDepositHelper</h1>
<h2>What is it?</h2>
<p>It seemed to us that the process of making repository deposits to EPrints through a 3rd party website was too complex.  Many website/repository administrators face a similar challenge to our own, where they wish to give staff the ability to make deposits into the repository through a website.  Therefore, we have developed a wrapper class, specifically designed to make it trivial to enable remote EPrint deposit through PHP.  It implements a subset of the standard protocol, in combination with some uniquely EPrints capabilities, getting around the limitations that have been experienced by others to date.</p>
<p>Because it is a encapsulated in a <em>single</em> class, is should be should be matter of plug-and-play to incorporate into existing PHP fameworks.
</p>

<p>Please feel free to start investigating it, and testing it out.  Be aware though that it is still in alpha-release and will therefore be subject to refinements and extensions of its capability, as and when we get time to work on it.
</p>
<hr/>
<ul>
<li><a href="./depositforms/simpleuse.php">Simple Action Page Example, with pre-filled variables</a>
<br/>This is designed as an easy entry point for developers who want to get a quick handle on how the class is implemented. Replace the three repository-specific variables with your own, and start making test deposits immediately.<br/>&nbsp;</li>
<li><a href="./depositforms/deposit_article.php">Caltech Deposit Form Example</a><br/>This is a working example, as used in Caltech.</li>
</ul>
<hr/>
<p>This is an implementation of the php CURL functionality in EPrints.</p>
<img src="./depositforms/images/caltech.jpeg" alt="California Institute of Technology" />
<img src="./depositforms/images/wit.jpeg" alt="Waterford Institute of Technology" />


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
