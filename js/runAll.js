function RunAll() {


    var template_id = document.getElementById("get_templates").value;
    var select_value = document.getElementById("get_keywords").value;
    if (select_value !== "") {


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
        if (document.getElementById("my_accordion") !== null)
            document.getElementById("my_accordion").style.display = "none";


        jQuery("#wait").css("display", "block");


        if (document.getElementById("for_new_dataset") !== null) {
            var for_new_datasetes = document.getElementById("for_new_dataset").value;
            //var new_prefix = document.getElementById("new_prefix").value;

        } else {

            var for_new_datasetes = "";
            //var new_prefix = "";
        }
        //                          alert(template_id+' '+select_value+' '+for_new_datasetes);

        jQuery.ajax({

            type: "post",
            dataType: "json",
            url: "php/initialQuries.php",
            data: {'template_id': template_id, 'select_value': select_value, 'for_new_datasetes': for_new_datasetes},
            success: function (data) {
                
                successmessage = 'Data was succesfully captured';
                var myTableDiv = document.getElementById("myDynamicTable");

                var table = '<table id="myTable" style="width: 1014px;word-break: break-word;" class="table-sort table-sort-search table-sort-show-search-count">';
                table += '<thead><tr>';
                table += '<th id="input_elements" class="table-sort">' + data.Variable1 + '</th>';
                table += '<th class="table-sort">' + data.Variable2 + '</th>';

                table += '</tr></thead>';
                table += '<tbody>';
                function isUrl(s) {
                        var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
                        if (regexp.test(s)) {
                            return true;
                        } else {
                            return false;
                        }
                    }

                window.statistics_data = {};
                for (var x in data.children) {
				    if(isUrl(data.children[x][data.Variable1])){
                    table += '<tr><td><a href=' + data.children[x][data.Variable1] + ' target="_blank">' + data.children[x][data.Variable1] + '</a></td>';
					}
					else{
					table += '<tr><td style="width: 1014px;word-break: break-word;">' + data.children[x][data.Variable1] + '</td>';
					}
                    table += '<td>' + data.children[x].Dataset + '</td>';
                    if (data.children[x].Dataset in statistics_data)
                        statistics_data[data.children[x].Dataset] += 1;
                    else
                        statistics_data[data.children[x].Dataset] = 1;
                }

                table += '</tbody></table>';
                myTableDiv.innerHTML = table;

                myTableDiv.style.display = 'block';
                $('table.table-sort').tablesort();
                jQuery("#wait").css("display", "none");
                document.getElementById("filter_query").style.display = 'block';
                jQuery.ajax({

                    type: "post",
                    dataType: "json",
                    url: "php/showSimilarDataItemsButton.php",
                    data: {'template_id': template_id},
                    success: function (data) {
                        var show_similar = data.children[0].hasSimilarItem;
                        //alert(show_similar);
                        if (show_similar === "yes") {
                            document.getElementById("finding_similar_data_items").style.display = 'block';
                        } else {
                            document.getElementById("finding_similar_data_items").style.display = 'none';
                        }
                    }, error: function (data) {
                        successmessage = 'Error';
                        alert(data.responseText);
                    }
                });

                document.getElementById("add_new_datasets").style.display = 'block';
                document.getElementById("questionmark").style.display = 'block';
                if (document.getElementById("accordion") !== null) {
                    document.getElementById("accordion").style.display = 'none';
                }
                document.getElementById('run_all').value = 'Run query';
                document.getElementById('run_all').style.backgroundColor = 'white';
//                for(var key in statistics_data) {
//                var value = statistics_data[key];
//                alert(key+" : "+value);
//                }

            },
            error: function (data) {
                successmessage = 'Error';
                alert(data.responseText);
                document.getElementById("myDynamicTable").style.display = "none";
                document.getElementById("questionmark").style.display = "none";
                document.getElementById("filter_query").style.display = "none";
                if (document.getElementById("finding_similar_data_items") !== null) {
                    document.getElementById("finding_similar_data_items").style.display = "none";
                }
                document.getElementById("add_new_datasets").style.display = "none";
                document.getElementById('run_all').value = 'Run query';
                document.getElementById('run_all').style.backgroundColor = 'white';
                document.getElementById("for_new_dataset").value = '';
                jQuery("#wait").css("display", "none");

            }
        });

    } else {
        alert('Please, enter keyword!');
        document.getElementById("myDynamicTable").style.display = "none";
        document.getElementById("questionmark").style.display = "none";
        document.getElementById("filter_query").style.display = "none";
        if (document.getElementById("finding_similar_data_items") !== null) {
            document.getElementById("finding_similar_data_items").style.display = "none";
        }
        document.getElementById("add_new_datasets").style.display = "none";
        document.getElementById('run_all').value = 'Run query';
        document.getElementById('run_all').style.backgroundColor = 'white';
        document.getElementById("for_new_dataset").value = '';

    }


}