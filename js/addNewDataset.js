function AddNewDataset(topic_name, template_id) {


    var name = document.getElementById("name").value;
    var initiative = document.getElementById("initiative").value;
    var link = document.getElementById("link").value;
    var comment = document.getElementById("comment").value;
    var endpoint = document.getElementById("endpoint").value;
    //var prefix = document.getElementById("prefix").value;
    var pattern = document.getElementById("pattern").value;
    var additionalproperties = document.getElementById("additionalproperties").value;
    if (name === '' || initiative === '' || link === '' || comment === '' || endpoint === '' || pattern === '' || additionalproperties === "") {
        alert("All fields are required!");
    } else {

        jQuery.ajax({

            type: "post",
            dataType: "json",
            url: "php/getDatasetInformation.php",
            data: {'template_id': template_id},
            success: function (data) {

                var dataset_names = [];
                var inititative_names = [];
                var endpoints = [];

                for (var x in data.children) {
                    dataset_names.push((data.children[x].DatasetName).toLowerCase());
                    inititative_names.push((data.children[x].InitiativeName).toLowerCase());
                    endpoints.push((data.children[x].endpoint));
                }
                if ((dataset_names.indexOf(name.toLowerCase()) > -1) || (inititative_names.indexOf(initiative.toLowerCase()) > -1) || (endpoints.indexOf(endpoint) > -1)) {
                    alert("Please, check datasets that are already include in predefiend query. Dataset name, inititiavie and endpoint must be diffrent.");
                } else {
                    //var new_prefix = "PREFIX " + name.toLowerCase() + ":<" + prefix + ">";
                    //alert(new_prefix);
                    // document.getElementById("new_prefix").value = new_prefix;


                    var for_initial_query = 'UNION{SERVICE SILENT <' + endpoint + '>{' + pattern + '}BIND("' + name + '/' + initiative + '" AS ?Dataset)}';
                    var number_of_data_for_new_dataset = 'SELECT (count(?' + topic_name + ') as ?Number) WHERE {SERVICE SILENT <' + endpoint + '>{' + pattern + '}}';
                    //alert(number_of_data_for_new_dataset);
                    document.getElementById("for_new_dataset").value = for_initial_query;
                    //alert(for_initial_query);
                    var first_link = 'SELECT ?' + topic_name + ' WHERE{SERVICE SILENT <' + endpoint + '>{' + pattern + '}}LIMIT 1';
                    //alert(first_link);
                    if (first_link.indexOf('%s') > -1) {
                        first_link = first_link.replace('"%s"', '"' + document.getElementById("get_keywords").value + '"');

                    }

                    //alert(first_link);

                    jQuery.ajax({

                        type: "post",
                        dataType: "json",
                        url: "php/first_link.php",
                        data: {'first_link': first_link},
                        success: function (data) {
                            successmessage = 'Data was succesfully captured';
                            document.getElementById("for_filter_initial_query").value = data.children[0].FirstLink;
                            //var topicname = document.getElementById("input_elements").innerText;
                            var for_filter = '{"DatasetName": "' + name + '","Initiatives": "' + initiative + '","Endpoint": "' + endpoint + '","Comment": "' + comment + '","Link": "' + link + '","Topicname": "' + topic_name.trim() + '","new":"yes"}';
                            var for_new_filter = [name + '/' + initiative, endpoint, pattern];

                            //alert(for_filter);
                            document.getElementById("for_filter").value = for_filter;
                            document.getElementById("for_new_filter").value = for_new_filter;
                            jQuery.ajax({

                                type: "post",
                                dataType: "json",
                                url: "php/get_number_of_data.php",
                                data: {'number_of_data_for_new_dataset': number_of_data_for_new_dataset},
                                success: function (data) {

                                    var n = data.children[0].Number;
                                    for (var j = 0; j < n; j++) {
                                        document.getElementById("for_similar_endpoints").value += endpoint + ",";

                                    }
                                },
                                error: function (data) {
                                    successmessage = 'Error';
                                    alert(data.responseText);
                                }
                            });

                            document.getElementById("for_similar_initiatives").value = name + '/' + initiative;
                            document.getElementById("for_similar_properties").value = '"' + name + '/' + initiative + '=>' + additionalproperties;


                            alert("You successfully added new dataset. Please, try to run query again!");

                            var modal = document.getElementById('myModal');
                            modal.style.display = "none";
                            document.getElementById('run_all').value = 'Run new query';
                            document.getElementById('run_all').style.backgroundColor = '#3BAB9B';

                            //document.getElementById('run_all').setAttribute("background-color","#3bab9b");
                        },
                        error: function (data) {
                            successmessage = 'Error';
                            alert(data.responseText);
                        }
                    });
                }


            },
            error: function (data) {
                successmessage = 'Error';
                alert(data.responseText);
            }
        });

    }
}