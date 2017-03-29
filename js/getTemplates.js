function getTemplates() {
    var subtopic_id = document.getElementById('get_subtopics').value;
    //alert(subtopic_id);    
	document.getElementById("for_new_dataset").value="";
	if (document.getElementById("my_accordion") !== null) {
                    document.getElementById("my_accordion").style.display = "none";
                }
	
    if (subtopic_id !== "0") {


        jQuery.ajax({

            type: "post",
            dataType: "json",
            url: "php/getTemplates.php",
            data: {'subtopic_id': subtopic_id},
            success: function (data) {
                successmessage = 'Data was succesfully captured';
                var templates = document.getElementById("get_templates");
                for (var i = 0; i = templates.length; i++)
                    templates.remove(0);
                templates.style.display = "block";
                document.getElementById("templates").style.display = "block";
                var option = document.createElement("option");
                option.value = '0';
                option.text = 'Select one';
                templates.add(option, null);
                for (var x in data.children)
                {
                    var option = document.createElement("option");
                    option.value = data.children[x].TemplateID;
                    option.text = data.children[x].TemplateName;
                    templates.add(option, null);
                }
                document.getElementById("keywords").style.display = "none";
                document.getElementById("get_keywords").style.display = "none";
                document.getElementById("run_all").style.display = "none";
                document.getElementById("filter_query").style.display = "none";
                if (document.getElementById("finding_similar_data_items") !== null) {
                    document.getElementById("finding_similar_data_items").style.display = "none";
                }
                document.getElementById("add_new_datasets").style.display = "none";
                document.getElementById("questionmark").style.display = "none";
				
                
                
            },
            error: function (data) {
                successmessage = 'Error';
                alert(data.responseText);
            }
        });

    } else
    {
        document.getElementById("templates").style.display = "none";
        document.getElementById("get_templates").style.display = "none";
        document.getElementById("keywords").style.display = "none";
        document.getElementById("get_keywords").style.display = "none";
        document.getElementById("run_all").style.display = "none";
        document.getElementById("myDynamicTable").style.display = "none";
        document.getElementById("questionmark").style.display = "none";
        document.getElementById("filter_query").style.display = "none";
        if (document.getElementById("finding_similar_data_items") !== null) {
            document.getElementById("finding_similar_data_items").style.display = "none";
        }
        document.getElementById("add_new_datasets").style.display = "none";
		
    }
}
