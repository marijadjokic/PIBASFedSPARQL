function getSubTopic()
{
    var topic_id = document.getElementById('topic').value;
	document.getElementById("for_new_dataset").value="";
	document.getElementById("my_accordion").style.display = "none";
    if (topic_id !== "0") {
        jQuery.ajax({
             
            type: "post",
            dataType: "json",
            url: "php/getSubtopics.php",
            data: {'topic_id': topic_id},
            success: function (data) {
                successmessage = 'Data was succesfully captured';
                var subtopic = document.getElementById("get_subtopics");
                for (var i = 0; i = subtopic.length; i++)
                    subtopic.remove(0);
                subtopic.style.display = "block";
                document.getElementById("subtopics").style.display = "block";

                var option = document.createElement("option");
                option.value = '0';
                option.text = 'Select one';
                subtopic.add(option, null);
                for (var x in data.children)
                {

                    var option = document.createElement("option");
                    option.value = data.children[x].SubTopicId;
                    option.text = data.children[x].SubTopicName;
                    subtopic.add(option, null);
                }
                document.getElementById("templates").style.display = "none";
                document.getElementById("get_templates").style.display = "none";
                document.getElementById("keywords").style.display = "none";
                document.getElementById("get_keywords").style.display = "none";
                document.getElementById("run_all").style.display = "none";
                document.getElementById("filter_query").style.display = "none";
                document.getElementById("myDynamicTable").style.display = "none";
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
        document.getElementById("subtopics").style.display = "none";
        document.getElementById("get_subtopics").style.display = "none";
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
        document.getElementById("my_accordion").style.display = "none";
    }


}