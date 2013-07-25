<!DOCTYPE html>
<html>
<head>
    <title>WIT Repository Depositor Form</title>  

    
    <link rel="stylesheet" type="text/css" href="/depositforms/css/ui-lightness/jquery-ui.css">
    <script src="/depositforms/js/modernizr.js"></script>        
    <script src="/depositforms/js/jquery.js"></script>
    <script src="/depositforms/js/jquery-ui.min.js"></script>
    
    <script type="text/javascript">
        $( document ).ready(function() {
			
			$("form").on('submit',function(){				
                $("fieldset.essentialfields:hidden input").attr('disabled', 'disabled');
                $("fieldset.essentialfields:hidden select").attr('disabled', 'disabled');
			});
			
            var n = 1;
            $("input.removeparent").click(function(){
               //alert('dddd');               
               $(this).parent().slideUp(800,function(){$(this).remove();});
              //$(this).parent().remove();
               
            });
            $("select#type").change(function(){
                var visibleclass = $("select#type option:selected").val();
			    $("fieldset.essentialfields input").removeAttr("disabled");
                $("fieldset.essentialfields select").removeAttr("disabled");
                $("fieldset.essentialfields:visible").hide();
                $("fieldset#"+visibleclass).fadeIn(600);
            });
			
			
			
            //add extra author fields
            $("#addauthor").on("click", function(){    
                n+=1;
                var authorFormString = "\
\t\t<div class=\"ep_author\" style=\"display: none;\">\
\t\t\t<input type=\"text\" name=\"authorgiven[]\" id=\"authorgiven"+n+"\"  value=\"\" /> <label for=\"authorgiven"+n+"\">First Name <sup class=\"required\">*</sup></label><br/>\
\t\t\t<input type=\"text\" name=\"authorfamily[]\" id=\"authorfamily"+n+"\"  value=\"\" /> <label for=\"authorfamily"+n+"\">Family Name <sup class=\"required\">*</sup></label><br/>\
\t\t\t<input type=\"email\" name=\"authorID[]\" id=\"authorID"+n+"\"  value=\"\" /> <label for=\"authorID"+n+"\">Email <sup class=\"required\">*</sup></label><br/>\
\t\t\t<select id=\"authorType"+n+"\" name=\"authorType[]\" style=\"width: 120px; margin-bottom:0px;\">\n\t\t\t\t<option selected=\"selected\" value=\"author\">Author</option>\n\t\t\t\t<option value=\"editor\">Editor</option>\n\t\t\t</select> \n\t\t\t<label for=\"authorType"+n+"\">Author or Editor?</label>\
\t\t\t<input type=\"button\" value=\"remove\" onclick=\"$(this).parent().slideUp(800,function(){$(this).remove();});\" class=\"removeparent\" style=\"cursor:pointer\" />\
\t\t</div>";
                $("#author_metadata").append(authorFormString);
                $("div.ep_author:hidden").each(function() {
                    $(this).slideDown('slow');
                });
            });   
            //add extra author fields
            $("#addfile").click(function(){           
                n+=1;
                var fileFormString = "\
\t<div class=\"ep_file_upload\"  style=\"display: none;\">\
\t\t<label for=\"fileupload_"+n+"\">Choose File <sup class=\"required\">*</sup></label>\
\t\t<input type=\"file\" name=\"fileupload[]\" id=\"fileupload_"+n+"\" /><br/>\
\t\t\t\
\t\t<label for=\"filesecurity_"+n+"\">Visible To:</label>\
\t\t<select id=\"filesecurity_"+n+"\" name=\"filesecurity[]\">\
\t\t\t<option value = \"public\" selected=\"selected\">public</option>\
\t\t\t<option value = \"staffonly\">staffonly</option>\
\t\t\t<option value = \"validuser\">validuser</option>\
\t\t</select>\
\t\t<br/>   \
\t\t<label for=\"fileformat_"+n+"\">Format of the file</label>\
\t\t<select id=\"fileformat_"+n+"\" name=\"fileformat[]\">\
\t\t\t<option value = \"text\" selected=\"selected\">text</option>\
\t\t\t<option value = \"slideshow\">slideshow</option>\
\t\t\t<option value = \"image\">image</option>\
\t\t\t<option value = \"video\">video</option>\
\t\t\t<option value = \"audio\">audio</option>\
\t\t\t<option value = \"archive\">archive</option>\
\t\t\t<option value = \"other\">other</option>\
\t\t</select><br/>\
\t\t<label for=\"fileembargo_"+n+"\">Publicly available after: </label>\
\t\t<input type=\"date\" name=\"fileembargo[]\" id=\"fileembargo_"+n+"\" value=\"\" />\
\t\t<input type=\"button\" value=\"remove\" onclick=\"$(this).parent().slideUp(800,function(){$(this).remove();});\" class=\"removeparent\" style=\"cursor:pointer;\" />\
\t</div>";
                $("#file_metadata").append(fileFormString);
                
                $("div.ep_file_upload:hidden").each(function() {
                    $(this).slideDown('slow');
                });
                
                $('input[type=date]').each(function() {
                        var $input = $(this);
                        $input.datepicker({
                                minDate: $input.attr('min'),
                                maxDate: $input.attr('max'),
                                dateFormat: 'yy-mm-dd'
                        });
                });
            });   
        });
        
        /* Datepicker
         * input[type=date] fallback
         *
         * using jQuery UI Datepicker
         */
        var initDatepicker = function() {
                $('input[type=date]').each(function() {
                        var $input = $(this);
                        $input.datepicker({
                                minDate: $input.attr('min'),
                                maxDate: $input.attr('max'),
                                dateFormat: 'yy-mm-dd'
                        });
                });
        };
        
        if(!Modernizr.inputtypes.date){
                $(document).ready(initDatepicker);
        };
        

    </script>    
    
    
    
    
    
    <style>

hr {
    display:block;
    height:1px;
    border:0;   
    border-top:1px solid #cccccc;
    margin:1em 0;
    padding:0;
}

input, select {
    vertical-align:middle;
}


body {
    font-family: Open Sans, Helvetica, sans-serif;
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAEAgMAAADUn3btAAAAAXNSR0IArs4c6QAAAAlQTFRF6urq7e3t0tLSku5FqQAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB90HGQ8tO2c4laIAAAAQSURBVAjXY2hhUGTwYBACAAUzAQDx15BoAAAAAElFTkSuQmCC);
}
        /* ========================================================== */
        form#depositform{width: 500px;}
        table{border: solid 3px black; margin: 3px;}
        table td{border: solid 1px red;}
        table table td{border: solid 1px green;}
        
        div.removeparent{ cursor: pointer;}
        div.removeparent span {color: red;}
        fieldset.essentialfields{display: none;}
        
        fieldset {
            margin:0;
            padding:0;
            border:0;
            outline:0;
            vertical-align:baseline;
            background:transparent;
        }
                
        div.ep_file_upload, div.ep_author{
            padding-left: 0px;
            padding: 10px;
			padding-top: 0px;
			padding-bottom: 10px;
			margin-left: 0px;
            margin-top: 0px;
        }
		div.ep_author, div.ep_file_upload{padding-left: 0px;}
		
        span#addauthor, span#addfile{cursor: pointer;}
        span#addauthor span, span#addfile span{color: green;}
        
        legend{font-size: 18pt; margin-bottom: 0px; padding-left: 160px; margin-bottom: 10px;}
        
        #depositform label {
                width:160px;
                float:left;
                font-size:12px;
                line-height:24px;
                font-weight:bold;
        }
        
        #depositform input {
                line-height:18px;
        }
        /*Text, email & tel input fields*/
        #depositform select,
        #depositform textarea,
        #depositform input[type=date],
        #depositform input[type=password],
        #depositform input[type=file],
        #depositform input[type=text],
        #depositform input[type=url],
        #depositform input[type=email]{
                width:300px;
                margin-bottom:8px;
                padding:2px 5px;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                border-radius: 5px;
                border:1px solid #CCC;
        }
        /*Just the tel field*/
        #depositform input[type=date] {
                width:100px;
        }
        /*The Submit Button */
        #depositform input[type=button] {
                margin-top:5px;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                border-radius: 5px;
                font-weight:bold;
                color:#fff;
        }
        #depositform input#submitbutton {
                border:2px solid #06C;
                background-color:#09F;
                margin-left:0px;
                padding:2px 20px;
        }
        #depositform input#addfile, #depositform input#addauthor {
                border:2px solid #6C0;
                background-color:#9F0;
                margin-bottom: 5px;
                padding-bottom: 1px;
        }
        #depositform input.removeparent {
                border:2px solid #f00;
                background-color:#f76;
                margin-bottom: 3px;
                padding-bottom: 1px;
        }
        
        #depositform select:focus,
        #depositform textarea:focus,
        #depositform input[type=date]:focus,
        #depositform input[type=password]:focus,
        #depositform input[type=text]:focus,
        #depositform input[type=email]:focus,
        #depositform input[type=url]:focus,
        #depositform input[type=tel]:focus{
                border:1px solid #09F;
                -webkit-box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.3);
                -moz-box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.3);
                box-shadow: 0px 1px 3px 0px rgba(0, 0, 0, 0.3);
        }
        
        
        div.ep_author select{ margin-bottom: 0px; border:solid 1px black;}
        div.ep_file_upload input{margin-bottom: 0px;	border:solid 1px black;}
        sup.required{font-weight: bolder; color:red; font-size: larger;}
        
    </style>
</head>
<body>
<?php
$debug = 0;
if(!isset($_POST['divisions'])){
?>
    <form id="depositform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"  enctype="multipart/form-data" style="margin: 30px;">
    <h1>Deposit Form</h1>
    <hr />
    <fieldset>
        <legend>Log In</legend>
       <label for="username">username <sup class="required">*</sup></label> <input type="text"  name="username" id="username" required value="" /> <br/> 
       <label for="password">password <sup class="required">*</sup></label> <input type="password" name="password" id="password" required value="" /> 
       <label for="divisions">School or Department</label> 
        
    
    <select name="divisions[]" id="divisions" multiple="multiple" size="4">
            <option value="na" selected="selected">*NONE OF THESE*</option>
            <option value="adm">Other Depts:</option>
            <option value="adm_cs">Other Depts: Computer Services</option>
            <option value="adm_lib">Other Depts: Library</option>
            <option value="adm_elearn">Other Depts and Units: eLearning Support Unit</option>
            <option value="bus">Business:</option>
            <option value="bus_acc">Business: Accountancy and Finance</option>
            <option value="bus_gbus">Business: Graduate Buisness Studies</option>
            <option value="bus_morg">Business: Management and Organization</option>
            <option value="edu">Education:</option>
            <option value="edu_ace">Education: Adult and Continuing Education</option>
            <option value="eng">Engineering:</option>
            <option value="eng_arch">Engineering: Architecture</option>
            <option value="eng_constcivil">Engineering: Construction and Civil Engineering</option>
            <option value="eng_tech">Engineering: Engineering Technology</option>
            <option value="eng_trade">Engineering: Trade Studies</option>
            <option value="health">Health Sciences:</option>
            <option value="health_hsport">Health Sciences: Health, Sport and Exercise Studies</option>
            <option value="health_nursing">Health Sciences: Nursing</option>
            <option value="hum">Humanities:</option>
            <option value="um_apparts">Humanities: Applied Arts</option>
            <option value="hum_creatper">Humanities: Creative and Performing arts</option>
            <option value="hum_langtour">Humanities: Languages, Tourism and Hospitality</option>
            <option value="sci">Science:</option>
            <option value="sci_chemlife">Science: Chemical and Life Sciences</option>
            <option value="sci_compmath">Science: Computing, Maths and Physics</option>
    </select>
    
    <br />
    <label for="researchgroup">Research Group: </label>
    
    
    <select name="subjects[]" id="researchgroup" multiple="multiple" size="4">
            <option value="rsrch_na" selected="selected"> *NONE OF THESE*</option>
            <option value="rsrch_alit"> Adult Literacy</option>
            <option value="rsrch_autoelctrncs"> Advanced Automotive Electronics Control Group</option>
            <option value="rsrch_amt"> Advanced Manufacturing Technology Research Group</option>
            <option value="rsrch_atmtv"> Automotive Control Group</option>
            <option value="rsrch_bized"> Business Education &amp; Teaching Research Group</option>
            <option value="rsrch_covip"> Centre for Converged IP Communications Services</option>
            <option value="rsrch_entrprnr"> Centre for Enterprise Development &amp; Regional Economy</option>
            <option value="rsrch_hlthbhvr"> Centre for Health Behaviour Research</option>
            <option value="rsrch_isol"> Centre for INformation SYstems and TEchno-culture</option>
            <option value="rsrch_hlthmgmt"> Centre for Management Research in Healthcare &amp; Healthcare Economics</option>
            <option value="rsrch_newf"> Centre for Newfoundland and Labrador Studies</option>
            <option value="rsrch_tourhosp"> Centre for Research Creativity &amp; Innovation in Tourism</option>
            <option value="rsrch_socialfamily"> Centre for Social and Family Research</option>
            <option value="rsrch_const"> Construction Industry Research &amp; Knowledge Centre</option>
            <option value="rsrch_const_ConstTech"> Construction Technologies (inc. OSC)</option>
            <option value="rsrch_const_GroundEng"> Ground Engineering</option>
            <option value="rsrch_const_HRandIR"> Human Resources (HR) &amp; Industrial Relations (IR)</option>
            <option value="rsrch_const_ICT"> Information &amp; Communication Technologies (ICT)</option>
            <option value="rsrch_const_KM"> Knowledge Management (KM)</option>
            <option value="rsrch_const_Other"> Other</option>
            <option value="rsrch_const_projmgt"> Project Management</option>
            <option value="rsrch_const_Sustain"> Sustainability</option>
            <option value="rsrch_const_TFLearning"> Technology-Facilitated Learning</option>
            <option value="rsrch_intlang"> Content &amp; Language Integrated Learning Research Group</option>
            <option value="rsrch_cultcreat"> Creativity and Culture Research Group</option>
            <option value="rsrch_EIRC">Eco-Innovation Research Centre</option>
            <option value="rschh_EIRC_estrine"> Estuarine Research Group</option>
            <option value="rschh_EIRC_forest"> Forestry Research Group</option>
            <option value="rschh_EIRC_molecol"> Molecular Ecology Research Group</option>
            <option value="rschh_EIRC_susag"> Sustainable Agriculture Research Group</option>
            <option value="rsrch_envsens"> Environmental Sensing Research Group</option>
            <option value="rsrch_fince"> Finance Research Group</option>
            <option value="rsrch_flxwrlss"> Flexible Wireless Research Group</option>
            <option value="rsrch_hlthnfo"> Health Informatics Research Group</option>
            <option value="rsrch_macular"> Macular Pigment Research Group</option>
            <option value="rsrch_mtchar"> Materials Characterisation and Processing Group</option>
            <option value="rsrch_mcroelec"> Microelectronics and Systems Research Group</option>
            <option value="rsrch_music"> Music Composition Contemporary Music History &amp; Analysis</option>
            <option value="rsrch_nano"> Nanotechnology Research Group</option>
            <option value="rsrch_opt"> Optics Research Group</option>
            <option value="rsrch_PMBRC"> Pharmaceutical and Molecular Biotechnology Research Centre</option>
            <option value="rsrch_PMBRC_bfuel"> Biofuel Research Cluster</option>
            <option value="rsrch_PMBRC_biomed"> Biomedical Research Cluster</option>
            <option value="rsrch_PMBRC_listeria"> Listeria Research Group</option>
            <option value="rsrch_PMBRC_mbiol"> Molecular Biology Research Group</option>
            <option value="rsrch_PMBRC_psensing"> Polymer Mediated Sensing Research Group</option>
            <option value="rsrch_PMBRC_sepsci"> Separation Science Research Group</option>
            <option value="rsrch_PMBRC_surf"> Surface Science Research Group</option>
            <option value="rsrch_rurdev"> RIKON (Research in Inovation, Knowledge &amp; Organisational Networks)</option>
            <option value="rsrch_art"> Research in Art Design Theory &amp; Practice</option>
            <option value="rsrch_sstate"> Semiconductor and Solid State Research</option>
            <option value="rsrch_smart"> Smart Card Operations Research Enterprise Group</option>
            <option value="rsrch_seam"> South East Applied Materials Research Centre</option>
            <option value="rsrch_tssg"> Telecommunications Software and Systems Group</option>
            <option value="rsrch_tssg_3cs"> Centre for Converged IP Communications Services (3CS)</option>
            <option value="rsrch_tssg_comminf"> Communications Infrastructure Management</option>
            <option value="rsrch_tssg_perv"> Pervasive Communications Services</option>
            <option value="rsrch_pthouse"> The Poets' House Centre for Creative Writing and Research</option>
            <option value="rsrch_cryst"> Waterford Crystal Marketing Studies Group</option>
            <option value="rsrch_elarnt"> eLearning Technology Group</option>
    </select>
     
        <!-- <input type="email" name="email" id="email" required value="dkane@wit.ie" /> <label for="email">password</label><br/> -->
        <!-- <input type="text"  name="affiliation" id="affiliation"  value="affiliation" /> <label for="affiliation">affiliation</label>--><br/>
        <hr/>
    </fieldset>
    <fieldset>
        <legend>Item Details</legend>
        <label for="type">Type <sup class="required">*</sup></label>
        <select id="type" name="type">
        <option value="article" selected="selected">article</option>
        <option value="conference_item">conference item</option>
        <option value="book_section">book section</option>
        <option value="book">book</option>
      
        <!--  
        <option value="thesis">thesis</option>
        <option value="video">video</option>
        <option value="monograph">monograph</option>
        <option value="other">other</option>
        <option value="audio">audio</option>
        <option value="teachingresource">teaching_resource</option>
        <option value="exhibition">exhibition</option>
        <option value="image">image</option>
        <option value="patent">patent</option>
        -->
        </select><br/>
     
        <select id="datetype" name="datetype" style="width: 120px;">
        <option value="published" selected="selected">published</option>
        <option value="submitted">submitted</option>
        <option value="completed">completed</option>
        </select> on <label for="date">Date <sup class="required">*</sup></label> <input required type="date" name="date" id="date" value="" /> <br/>
    </fieldset>
    <fieldset class="essentialfields" id="article" style="display: block;">
            <label for="journal_articletitle">Article Title <sup class="required">*</sup></label> <input type="text"  name="journal_articletitle" id="journal_articletitle" required value="" /><br/>
            <label for="journal_title">Publication Title <sup class="required">*</sup></label> <input required type="text"  name="journal_title" id="journal_title"  value="" /><br/> 
            <label for="journal_volume">Volume Number</label> <input type="text"  name="journal_volume" id="journal_volume"  value="" /><br/> 
            <label for="journal_issue">Issue Number</label> <input type="text"  name="journal_issue" id="journal_issue"  value="" /><br/>    
            <label for="journal_issn">ISSN</label> <input type="text"  name="journal_issn" id="journal_issn"  value="" /><br/>    
                 
    </fieldset>
    <fieldset class="essentialfields" id="conference_item">   
    
            <label for="pres_title">Presentation Title <sup class="required">*</sup></label> <input type="text"  name="pres_title" id="pres_title" required value="" /><br/>
            <label for="pres_type">Presentation Type <sup class="required">*</sup></label>
            <select id="pres_type" name="pres_type">
                <option value="paper">paper</option>
                <option value="poster">poster</option>
                <option value="lecture">lecture</option>
                <option value="other">other</option>
                <option value="keynote">keynote</option>
                <option value="speech">speech</option>
                <option value="NULL">NULL</option>
            </select><br/>
            <label for="event_title">Event Title <sup class="required">*</sup></label> <input type="text"  name="event_title" id="event_title" required value="" /><br/>        
            <label for="event_type">Event Type <sup class="required">*</sup></label> 
            <select id="event_type" name="event_type">
                <option value="conference">conference</option>
                <option value="workshop">workshop</option>
                <option value="other">OTHER</option>
            </select><br/>
            <label for="event_location">Event Location </label><input type="text"  name="event_location" id="event_location" required value="" /><br/>        
    </fieldset>
    
    <fieldset class="essentialfields" id="book_section">
            <label for="book_title">Book Title <sup class="required">*</sup></label> <input type="text"  name="book_title" id="book_title" required value="" /><br/>
            <label for="title">Chapter/Section Title <sup class="required">*</sup></label> <input type="text"  name="title" id="title" required value="" /><br/>
            <label for="publisher">Publisher <sup class="required">*</sup></label> <input type="text"  name="publisher" id="publisher" required value="" /><br/>
            <label for="isbn">ISBN <sup class="required">*</sup></label> <input type="text"  name="isbn" id="isbn" required value="" /><br/>
    </fieldset>
    <fieldset class="essentialfields" id="book">
            <label for="book_title">Book Title <sup class="required">*</sup></label> <input type="text"  name="book_title" id="book_title" required value="" /><br/>
            <label for="publisher">Publisher <sup class="required">*</sup></label> <input type="text"  name="publisher" id="publisher" required value="" /><br/>
            <label for="isbn">ISBN <sup class="required">*</sup></label> <input type="text"  name="isbn" id="isbn" required value="" /><br/>        
    </fieldset>
    <!--
    <fieldset class="essentialfields" id="thesis">
    
            <label for="thesis_title">article_title</label> <input type="text"  name="thesis_title" id="thesis_title" required value="" /><br/>      
    </fieldset>
    <fieldset class="essentialfields" id="video">
            <label for="video_title">article_title</label> <input type="text"  name="video_title" id="video_title" required value="" /><br/>      
    </fieldset>
    <fieldset class="essentialfields" id="monograph">
        <legend>Monograph Details</legend>
            <label for="monograph_title">article_title</label> <input type="text"  name="monograph_title" id="monograph_title" required value="" /><br/>
    </fieldset>
    <fieldset class="essentialfields" id="other">
        <legend>Other Details</legend>
            <label for="other_title">article_title</label> <input type="text"  name="other_title" id="other_title" required value="" /><br/>       
    </fieldset>
    <fieldset class="essentialfields" id="audio">
        <legend>Audio Details</legend>
            <label for="audio_title">article_title</label> <input type="text"  name="audio_title" id="audio_title" required value="" /><br/>                
    </fieldset>
    -->
    <fieldset>
    <label for="url">URL:</label> <input type="url" name="url" id="url" /><br/>
    <label for="status">Status <sup class="required">*</sup></label>
    <select name="status" id="status">
        <option value="pub" selected="selected">published</option>
        <option value="inpress">in press</option>
        <option value="submitted">submitted</option>
        <option value="unpub">unpublished</option>
    </select>
    <br/>
    <label for="refereed">Is this refereed? <sup class="required">*</sup></label>&nbsp;&nbsp;<input type="radio" name="refereed" id="refereed" value="TRUE" ?>Yes &nbsp; &nbsp; <input type="radio" name="refereed" id="refereed" value="FALSE" ?>No
        <br /><br/> 
       <label for="abstract">Abstract </label> <textarea name="abstract" id="abstract" value=""></textarea><hr/>
    </fieldset>
    <fieldset>
        <legend>Authors / Editors <input type="button" value="add" id="addauthor" style="cursor:pointer" /></legend>      
        <div id="author_metadata">
            <div class="ep_author">
                <label for="authorgiven1">First Name <sup class="required">*</sup></label> <input required type="text" name="authorgiven[]" id="authorgiven1"  value="" /><br/> 
                <label for="authorfamily1">Family Name <sup class="required">*</sup></label> <input required type="text" name="authorfamily[]" id="authorfamily1"  value="" /><br/> 
                <label for="authorID1">Email <sup class="required">*</sup></label> <input required type="email" name="authorID[]" id="authorID1"  value="" /><br/>
                <label for="authorType1">Author or Editor?</label> <select id="authorType1" name="authorType[]" style="width: 120px; margin-bottom:0px;"><option selected="selected" value="author">Author</option><option value="editor">Editor</option></select>
                <input type="button" value="remove" class="removeparent" style="cursor:pointer" />
            </div><!-- more divs with the class .ep_author get added here. -->
        </div>
    </fieldset>    
    <br/><hr/>
    <fieldset>
        <legend>Files <input type="button" value="add" id="addfile" style="cursor:pointer" /></legend>
        <div id="file_metadata">
            <!-- put files metadata form fields here -->
            <div class="ep_file_upload">
                <label for="fileupload_1">Choose File <sup class="required">*</sup></label>
                <input type="file" name="fileupload[]" id="fileupload_1" /><br/>
                <label for="filesecurity_1">Visible To:</label>
                <select id="filesecurity_1" name="filesecurity[]">
                    <option value = "public" selected="selected">public</option>
                    <option value = "staffonly">staffonly</option>
                    <option value = "validuser">validuser</option>
                </select>
                <br/>
                <label for="fileformat_1">Format of the file</label>
                <select id="fileformat_1" name="fileformat[]" style="cursor: pointer;" >
                    <option value = "text" selected="selected">text</option>
                    <option value = "slideshow">slideshow</option>
                    <option value = "image">image</option>
                    <option value = "video">video</option>
                    <option value = "audio">audio</option>
                    <option value = "archive">archive</option>
                    <option value = "data">data</option>
                    <option value = "other">other</option>
                </select><br/>
                <label for="fileembargo_1">Publicly available after:</label>
                <input type="date" name="fileembargo[]" id="fileembargo_1" style="margin-bottom: 0px;' value=""/>        
                <input type="button" value="remove" onclick="$(this).parent().slideUp(800,function(){$(this).remove();});" class="removeparent" style="cursor:pointer" />
            </div>
            <!-- more divs with the class .ep_file_upload get added here. -->
        </div>
    </fieldset><hr/><br/>
    <input type="button" value="deposit" id="submitbutton" onClick="$('#depositform').submit();" style="cursor:pointer" />
    </form>
<?php
}else{
    
    // Data needed for instance of class

    $eprintsServiceDocument = "http://witeprints/sword-app/servicedocument";
    #$eprintsServiceDocument = "http://authorstest.library.caltech.edu/sword-app/servicedocument";
    $username = 'dkane';
    $password = 'password';
    
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
    
    if($debug){
        print("<pre>");
        print_r($_POST);
        print("</pre>");
    }

}
?>
<br/>
<br/>
</body>
</html>
