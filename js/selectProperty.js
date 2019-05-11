function Add(obj_id, obj_name) {

    if ($('.' + obj_id + 'chkNumber:checked').length === 0) {

        var array_of_all_properties = (document.getElementById("property_array").value).split(';');
        if (array_of_all_properties.length > 1) {

            for (x = 0; x < array_of_all_properties.length; x++) {
                var ind = (array_of_all_properties[x]).indexOf(obj_name);
                if (ind > -1) {
                    array_of_all_properties.splice(ind, 1);
                    document.getElementById("property_array").value = array_of_all_properties.join(';');
                    break;
                }
            }
        }

        if (document.getElementById(obj_id).value !== "Update") {
            alert("Please, select some predicate!");
        }


        document.getElementById(obj_id).value = "Add to query";
        document.getElementById("run_all").value = 'Run query';
        document.getElementById("run_all").style.backgroundColor = 'white';
        document.getElementById("run_all").setAttribute("onclick", "RunAll()");
        if ((document.getElementById("property_array").value).length === 0) {
            document.getElementById("run_all").value = 'Run query';
            document.getElementById("run_all").style.backgroundColor = 'white';
            document.getElementById("run_all").setAttribute("onclick", "RunAll()");
        } else {
            document.getElementById("run_all").value = 'Run new query';
            document.getElementById("run_all").style.backgroundColor = '#3bab9b';
            document.getElementById("run_all").setAttribute("onclick", "RunFiltredQuery()");
        }
    } else {

        var chkId = "";
        $('.' + obj_id + 'chkNumber:checked').each(function () {
            chkId += $(this).val() + ",";
        });
        
        var values_for_filter = "";

        if (chkId !== "") {

            var array_of_all_properties = (document.getElementById("property_array").value).split(';');
            var array_of_values_for_filter = (document.getElementById("property_filter_array").value).split(';');
            if (array_of_all_properties.length > 1) {

                for (x = 0; x < array_of_all_properties.length; x++) {
                    var ind = (array_of_all_properties[x]).indexOf(obj_name);
                    if (ind > -1) {
                        array_of_all_properties.splice(ind, 1);
                        document.getElementById("property_array").value = array_of_all_properties.join(';');
                        break;
                    }
                }
            }
            
             if (array_of_values_for_filter.length > 1) {

                for (x = 0; x < array_of_values_for_filter.length; x++) {
                    var ind_filter = (array_of_values_for_filter[x]).indexOf(array_of_values_for_filter[x]);
                    if (ind_filter > -1) {
                        array_of_values_for_filter.splice(ind, 1);
                        document.getElementById("property_filter_array").value = array_of_all_properties.join(';');
                        break;
                    }
                }
            }
            



            var selected_properties_array_new = array_of_all_properties.join(';');
            var values_values_for_filter_new = array_of_values_for_filter.join(';');

  
            selected_properties_array_new += obj_name + ":" + chkId.substring(0, chkId.length - 1) + ";";
            var fv='filter_value'+String(chkId.substring(0, chkId.length - 1));
            var filter_value = $("input[name='"+fv+"']").val();
            values_values_for_filter_new += chkId.substring(0, chkId.length - 1) + ":" + filter_value + ";";

            document.getElementById("property_array").value = selected_properties_array_new;
            document.getElementById("property_filter_array").value = values_values_for_filter_new;


            document.getElementById(obj_id).value = "Update";
            document.getElementById("run_all").value = 'Run new query';
            document.getElementById("run_all").style.backgroundColor = '#3bab9b';
            document.getElementById("run_all").setAttribute("onclick", "RunFiltredQuery()");
            alert('Done!');

        }
    }

}
