
	    var author_count = 1;
	    var file_count = 1;
            var tooltip_style = { "fontSize" : "0.85em", "backgroundColor" : "#CEE3F6" };
            var title_label_text = "The title of the article";
            var surname_label_text = "Author surname";
            var firstname_label_text = "Author first name and initials";
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
		        $('#authorcontainer').append('<hr/><p><label id="familylabel_' + author_count + '" class="floatable" for="authorfamily_"' + author_count + '">Author '+author_count+' Family Name: </label>' + '<input id="authorfamily_' + author_count + '" name="authorfamily[]" size="55"/></p>');
		        $('#authorcontainer').append('<p><label id="givenlabel_'+ author_count + '" class="floatable" for="authorgiven_"' + author_count + '">Author '+author_count+' Given Name/Initials: </label>' + '<input id="authorgiven_' + author_count + '" name="authorgiven[]" size="55"/></p>');
		        $('#authorcontainer').append('<p><label id="emaillabel_'+ author_count + '" class="floatable" for="authoremail_"' + author_count + '">Author '+author_count+' Email/Initials: </label>' + '<input id="authoremail_' + author_count + '" name="authoremail[]" size="55"/></p>');

			var dynamic_family_label = document.getElementById("familylabel_" + author_count);
			var dynamic_family_label_tooltip = document.createElement("span");
			dynamic_family_label_tooltip.innerHTML = surname_label_text;
			dynamic_family_label.addTooltip(dynamic_family_label_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

			var dynamic_given_label = document.getElementById("givenlabel_" + author_count);
			var dynamic_given_label_tooltip = document.createElement("span");
			dynamic_given_label_tooltip.innerHTML = firstname_label_text;
			dynamic_given_label.addTooltip(dynamic_given_label_tooltip, 0, 0, 0, 0, 0, 1, tooltip_style);

			var dynamic_given_label = document.getElementById("authorlabel_" + author_count);
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