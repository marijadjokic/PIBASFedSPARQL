function Filter() {


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
    if (dataset_inistiatives.length >= 1) {
        if (document.getElementById("myDynamicTable") !== null)
            document.getElementById("myDynamicTable").style.display = 'none';

        if (document.getElementById("finding_similar_data_items") !== null)
            document.getElementById("finding_similar_data_items").style.display = 'none';

        if (document.getElementById("add_new_datasets") !== null)
            document.getElementById("add_new_datasets").style.display = "none";

        if (document.getElementById("filter_query") !== null)
            document.getElementById("filter_query").style.display = "none";

        if (document.getElementById("questionmark") !== null)
            document.getElementById("questionmark").style.display = "none";
        if (document.getElementById("my_accordion") !== null)
            document.getElementById("my_accordion").style.display = "none";


        jQuery("#wait").css("display", "block");

        var topicname = document.getElementById("template_topic").value;


        if (document.getElementById("for_filter").value !== "") {
            var for_filter = document.getElementById("for_filter").value;
        } else {
            var for_filter = "";
        }

        var template_id = document.getElementById('get_templates').value;
        if (template_id !== "0") {


            if (document.getElementById("for_filter").value !== "") {
                var for_filter = document.getElementById("for_filter").value;
            } else {
                var for_filter = "";
            }


            jQuery.ajax({

                type: "post",
                dataType: "json",
                url: "php/filterQuery.php",
                data: {'template_id': template_id, 'for_filter': for_filter, 'topicname': topicname},
                success: function (data) {
                    successmessage = 'Data was succesfully captured';
                    var myAccordionDiv = document.getElementById("my_accordion");

                    var accordion = '<div id="accordion">';
                    for (var x in data.children) {

                        var dataset_initiative = data.children[x].DatasetName + '/' + data.children[x].Initiatives;
                        for (var key in statistics_data) {
                            if (dataset_initiative === key)
                            {
                                accordion += '<h3 style="background:#3BAB9B;" datasetname="' + data.children[x].DatasetName + '" initiative="' + data.children[x].Initiatives + '" prefix="' + data.children[x].Prefix + '" endpoint="' + data.children[x].Endpoint + '"topicname="' + data.children[x].Topicname + '"new="' + data.children[x].new + '" onclick="myFunction(this)">' + data.children[x].DatasetName + '/' + data.children[x].Initiatives + '</h3>';
                                accordion += '<div>';
                                accordion += '<p>Comment: ' + data.children[x].Comment + '</p><br/><br/>';
                                accordion += 'For more details see <a href="' + data.children[x].Link + '" target="_blank">' + data.children[x].DatasetName + '</a>.</p>';
                                accordion += '<div id="loader" style="text-align:center;display:none;"><img src="images/loading.gif" height="50" width="50"/></div>';
                                accordion += '<div id="table_properties"></div>';
                                accordion += '</div>';
                            }
                        }
                    }
                    accordion += '</div>';
                    myAccordionDiv.innerHTML = accordion;

                    jQuery("#wait").css("display", "none");
                    document.getElementById("filter_query").style.display = "none";
                    myAccordionDiv.style.display = 'block';
                    document.getElementById("questionmark").style.display = 'block';
                    


                    $("#accordion").accordion({
                        collapsible: true,
                        active: false,
                        heightStyle: "content"
                    });
                    $('#accordion input[type="checkbox"]').click(function (e) {
                        e.stopPropagation();
                    });

                }

                ,
                error: function (data) {
                    successmessage = 'Error';
                    alert(data.responseText);
                }
            });



        }
    } else {
        alert("No data for filter!");
        document.getElementById("filter_query").style.display = "block";
        document.getElementById("myDynamicTable").style.display = "block";
        document.getElementById("questionmark").style.display = 'block';
        document.getElementById("add_new_datasets").style.display = "block";
        document.getElementById("finding_similar_data_items").style.display = "block";

    }
}