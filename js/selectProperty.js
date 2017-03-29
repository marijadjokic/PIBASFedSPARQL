function Add(obj_id, obj_name) {

    //alert(obj_id+' '+obj_name);



    if ($('.' + obj_id + 'chkNumber:checked').length === 0) {

        var array_of_all_properties = (document.getElementById("property_array").value).split(';');
        //alert(array_of_all_properties);
        if (array_of_all_properties.length > 1) {

            for (x = 0; x < array_of_all_properties.length; x++) {
                //alert(array_of_all_properties[x]);
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

        if (chkId !== "") {

            var array_of_all_properties = (document.getElementById("property_array").value).split(';');
            //alert(array_of_all_properties);
            if (array_of_all_properties.length > 1) {

                for (x = 0; x < array_of_all_properties.length; x++) {
                    //alert(array_of_all_properties[x]);
                    var ind = (array_of_all_properties[x]).indexOf(obj_name);
                    if (ind > -1) {
                        array_of_all_properties.splice(ind, 1);
                        document.getElementById("property_array").value = array_of_all_properties.join(';');
                        break;
                    }
                }
            }



            var selected_properties_array_new = array_of_all_properties.join(';');


            selected_properties_array_new += obj_name + ":" + chkId.substring(0, chkId.length - 1) + ";";

            document.getElementById("property_array").value = selected_properties_array_new;

            document.getElementById(obj_id).value = "Update";
            document.getElementById("run_all").value = 'Run new query';
            document.getElementById("run_all").style.backgroundColor = '#3bab9b';
            document.getElementById("run_all").setAttribute("onclick", "RunFiltredQuery()");
            alert('Done!');

        }
    }

}
