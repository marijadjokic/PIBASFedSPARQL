function myFunction(object) {
    var dataset_id = object.id;
    var dataset_endpoint = $(object).attr('endpoint');
    var dataset_initiative = $(object).attr('initiative');
    var dataset_name = $(object).attr('datasetname');
    var new_dataset = $(object).attr('new');

    $("#" + dataset_id).next("div").find("p:eq(1)").next("#loader").show();
    var template_id = document.getElementById("get_templates").value;
    var topicname = document.getElementById("template_topic").value;
    var dataset_instances=document.getElementById("dataset_instances").value;
    
    if (new_dataset === "yes") {
        var query_for_new_predicates = document.getElementById("for_filter_initial_query").value;
    } else {

        var query_for_new_predicates = "";
    }

    
    var instances= JSON.parse(dataset_instances);

    jQuery.ajax({
        
        type: "post",
        dataType: "json",
        url: "php/getDatasetProperties.php",
        data: {'template_id': template_id, 'dataset_name': dataset_name, 'dataset_initiative': dataset_initiative, 'dataset_endpoint': dataset_endpoint, 'topicname': topicname, 'query_for_new_predicates': query_for_new_predicates, 'dataset_instances':JSON.stringify(instances[dataset_name+'/'+dataset_initiative])},
        success: function (data) {
            successmessage = 'Data was succesfully captured';
            $("#" + dataset_id).next("div").find("p:eq(1)").html("<p>Filter your query on the basis of a given structure. </p><br/>");
            var dataset_name_and_initiative = dataset_name + '/' + dataset_initiative;

            if (document.getElementById("property_array").value !== "") {

                var array_of_all_selected_predicates = (document.getElementById("property_array").value).split(';');
                for (var z = 0; z < array_of_all_selected_predicates.length - 1; z++) {
                    if (((array_of_all_selected_predicates[z]).indexOf(dataset_name_and_initiative)) > -1) {
                        var set_of_properties = array_of_all_selected_predicates[z];
                        break;
                    } else {
                        var set_of_properties = "";
                    }
                }
            } else {
                var set_of_properties = "";
            }

            var table_property = '<table id="showAccordionElements" style="border-spacing:0 10px">';
            for (var x in data.children) {
                table_property += '<tr>';
                table_property += '<td style="position:relative"><div style="float:left"><a href=' + data.children[x].Predicate + ' target="_blank">';
                table_property += data.children[x].Predicate;
               
                table_property += '</a></div><div class="help-tip"><p>'+data.children[x].Description+'</p></div></td>';
                
                table_property += '<td style="padding: 0 10px 0 50px;"></td>';
                
                var dataset_name_and_initiative = dataset_name + '/' + dataset_initiative;

                if (set_of_properties !== "") {
                    if (set_of_properties.indexOf(data.children[x].Predicate) > -1) {
                        table_property += '<td><input id="' + data.children[x].Predicate + '" name="' + dataset_name + '"initiative="' + dataset_initiative + '" value="' + data.children[x].Predicate + '" type="checkbox" class="' + dataset_name + dataset_initiative + 'chkNumber" checked="checked"></td></tr>';
                    } else {
                        table_property += '<td><input id="' + data.children[x].Predicate + '" name="' + dataset_name + '"initiative="' + dataset_initiative + '" value="' + data.children[x].Predicate + '" type="checkbox" class="' + dataset_name + dataset_initiative + 'chkNumber"></td></tr>';
                    }
                } else {
                    table_property += '<td><input id="' + data.children[x].Predicate + '" name="' + dataset_name + '"initiative="' + dataset_initiative + '" value="' + data.children[x].Predicate + '" type="checkbox" class="' + dataset_name + dataset_initiative + 'chkNumber"></td></tr>';
                }
            }
            
            table_property += '</table>';


            if ((document.getElementById("property_array").value).indexOf(dataset_name_and_initiative) === -1) {
                $("#" + dataset_id).next("div").find("p:eq(1)").next("#loader").next("div").html(table_property + '<p align="right"><input type="button" name="' + dataset_name + '/' + dataset_initiative + '" id="' + dataset_name + dataset_initiative + '" value="Add to query" name="add" onclick="Add(this.id,this.name);"></p>');
                $("#" + dataset_id).next("div").find("p:eq(1)").next("#loader").hide();
            } else {
                $("#" + dataset_id).next("div").find("p:eq(1)").next("#loader").next("div").html(table_property + '<p align="right"><input type="button" name="' + dataset_name + '/' + dataset_initiative + '" id="' + dataset_name + dataset_initiative + '" value="Update" name="add" onclick="Add(this.id,this.name);"></p>');
                $("#" + dataset_id).next("div").find("p:eq(1)").next("#loader").hide();

            }

        },
        error: function (data) {
            successmessage = 'Error';
            alert(data.responseText);
        }
    });

}