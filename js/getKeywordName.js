function getKeywordName() {
    var template_id = document.getElementById('get_templates').value;
    //alert(template_id);    
	document.getElementById("for_new_dataset").value="";
	if (document.getElementById("my_accordion") !== null) {
                    document.getElementById("my_accordion").style.display = "none";
    }
    if (template_id !== "0") {

        jQuery.ajax({

            type: "post",
            dataType: "json",
            url: "php/getKeywordName.php",
            data: {'template_id': template_id},
            success: function (data) {
                successmessage = 'Data was succesfully captured';
                document.getElementById("keywords").style.display = "block";

                document.getElementById("keywords").textContent = "Enter " + data.children[0].Input;
                keywords.style.display = "block";
                document.getElementById("get_keywords").value = "";
                document.getElementById("get_keywords").style.display = "block";

                document.getElementById("run_all").style.display = "block";
                document.getElementById("filter_query").style.display = "none";
                document.getElementById("finding_similar_data_items").style.display = "none";
                document.getElementById("add_new_datasets").style.display = "none";
				document.getElementById("template_topic").value = data.children[0].Topicname;
            },
            error: function (data) {
                successmessage = 'Error';
                alert(data.responseText);
            }
        });




    } else
    {
        document.getElementById("keywords").style.display = "none";
        document.getElementById("get_keywords").style.display = "none";
        document.getElementById("run_all").style.display = "none";
        document.getElementById("myDynamicTable").style.display = "none";
        document.getElementById("questionmark").style.display = "none";
        document.getElementById("filter_query").style.display = "none";
        document.getElementById("finding_similar_data_items").style.display = "none";
        document.getElementById("add_new_datasets").style.display = "none";
    }
}



