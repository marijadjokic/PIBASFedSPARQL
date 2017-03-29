function FindSimilarItems() {


    if (document.getElementById("myDynamicTable") !== null)
        document.getElementById("myDynamicTable").style.display = "none";
    if (document.getElementById("questionmark") !== null)
        document.getElementById("questionmark").style.display = "none";
    if (document.getElementById("filter_query") !== null)
        document.getElementById("filter_query").style.display = "none";
    if (document.getElementById("finding_similar_data_items") !== null)
        document.getElementById("finding_similar_data_items").style.display = "none";
    if (document.getElementById("add_new_datasets") !== null)
        document.getElementById("add_new_datasets").style.display = "none";




    var template_id = document.getElementById("get_templates").value;
    var select_value = document.getElementById("get_keywords").value;

    //alert(template_id);
    //var input_element = document.getElementById("input_elements").innerText;
    var cols = document.getElementById('myTable').getElementsByTagName('td');
    var colslen = cols.length;

    var subject_uris = [];
    var dataset_inistiatives = [];

    var for_similar = "";
    var new_endpoints = "";
    var new_initiatives = "";

    for (i = 1; colslen > i; i++) {
        if (i % 2 !== 0) {
            var element = cols[i].innerText;
            subject_uris.push(element);
        } else {
            var name_of_dataset_initiative = cols[i].innerText;
            dataset_inistiatives.push(name_of_dataset_initiative);

        }

    }
    if  (dataset_inistiatives.length>1){
    document.getElementById("name_of_dataset_initiative").value = dataset_inistiatives;


    if (document.getElementById("for_similar_endpoints").value !== "") {
        var new_endpoints = document.getElementById("for_similar_endpoints").value;
    }

    if (document.getElementById("for_similar_initiatives").value !== "") {
        var new_initiatives = document.getElementById("for_similar_initiatives").value;
    }

    if (document.getElementById("for_similar_properties").value !== "") {
        var for_similar = document.getElementById("for_similar_properties").value;
    }


    jQuery("#wait").css("display", "block");

    jQuery.ajax({

        type: "post",
        dataType: "",
        url: "php/collectSimilarItems.php",
        data: {'template_id': template_id, 'select_value':select_value,'subject_uris': subject_uris, 'dataset_inistiatives': dataset_inistiatives, 'new_endpoints': new_endpoints, 'new_initiatives': new_initiatives, 'for_similar': for_similar},
        success: function (data) {
            //alert(data);
            jQuery("#wait").css("display", "none");
            
            document.getElementById("myDynamicTable").style.display = "block";
            document.getElementById("questionmark").style.display = "block";
            document.getElementById("filter_query").style.display = "block";
            document.getElementById("finding_similar_data_items").style.display = "block";
            document.getElementById("add_new_datasets").style.display = "block";
            //alert(data);
            var win = window.open('showSimilarItems.php', '_blank');
            //win.document.write("<p>This window's name is: " + data + "</p>");
            if (win) {
                //Browser has allowed it to be opened
                win.focus();
            } else {
                //Browser has blocked it
                alert('Please allow popups for this website');
            }
        },
        error: function (data) {
            successmessage = 'Error';
            alert(data.responseText);


        }
    });

}
else{
    alert("No data to comapre!");
	document.getElementById("filter_query").style.display = "block";
    document.getElementById("myDynamicTable").style.display = "block";
    document.getElementById("questionmark").style.display = 'block';
	document.getElementById("add_new_datasets").style.display = "block";
	document.getElementById("finding_similar_data_items").style.display = "block";
}
}
