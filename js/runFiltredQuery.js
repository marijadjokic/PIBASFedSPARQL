function RunFiltredQuery() {

    var predicates = document.getElementById("property_array").value;
    var template_id = document.getElementById("get_templates").value;
    var select_value = document.getElementById("get_keywords").value;
    var topicname = document.getElementById("template_topic").value;
    var for_new_filter = document.getElementById("for_new_filter").value;
    
    jQuery("#wait").css("display", "block"); 
    jQuery.ajax({
        
        type: "post",
        dataType: "json",
        url: "php/newFiltredQuery.php",
        data: {'template_id':template_id,'select_value':select_value,'predicates':predicates,'topicname':topicname,'for_new_filter':for_new_filter},
        success: function (data) {   
            //alert(data);
            successmessage = 'Data was succesfully captured';
            document.getElementById("my_accordion").style.display = "none";
            var myTableDiv = document.getElementById("myDynamicTable");
            myTableDiv.innerHTML = "";            
                
            var j = 0;
           
            for (var y = 0; y < data.length; y++) {
                
                var keys = Object.keys(data[y]).length;
                
               
                myTableDiv.innerHTML+="Dataset:<b>"+data[y].children[0][data[y].Variable2]+"</b>";
                var table = '<table id="example_' + j + '" class="display" cellspacing="0" style="width: 1014px;word-break: break-all;">';
                table += '<thead><tr>';
                for (var z = 1; z < keys; z++) {
                    if (z!==2){                     
                     var selected_predicates = "data[y].Variable" + z;
                     table += '<th>' + eval(selected_predicates) + '</th>';
                 }
                }
                table += '</tr></thead>';
                table += '<tbody>';
                for (var x in data[y].children) {
                    function isUrl(s) {
                        var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
                        if (regexp.test(s)) {
                            return true;
                        } else {
                            return false;
                        }
                    }

                    var topicname_for_show = data[y].children[x][data[y].Variable1];
                    table += '<tr><td><a href="' + data[y].children[x][data[y].Variable1] + '" target="_blank">'+topicname_for_show+'</a></td>';
                    for (var z = 3; z < keys; z++) {
                        var selected_predicates_value = "data[y].children[x][data[y].Variable" + z + "]";
                        if (isUrl("'" + eval(selected_predicates_value) + "'") === true) {

                            table += '<td><a href="' + eval(selected_predicates_value) + '" target="_blank">' + eval(selected_predicates_value) + '</a></td>';
                        } else {
                            table += '<td>' + eval(selected_predicates_value) + '</td>';
                        }
                    }
                }
                

                table += '</tbody></table><hr/>';
                
                myTableDiv.innerHTML += table;

                $(document).ready(function () {
                    $('#example_' + j + '').DataTable({
                        "pagingType": "full_numbers"
                    });
                });
                j = j + 1;
                myTableDiv.innerHTML += "<br/>";
				jQuery("#wait").css("display", "none");

            }

            myTableDiv.style.display = "block";          
            document.getElementById("run_all").value = 'Run query';
            document.getElementById("run_all").style.backgroundColor = 'white';
            document.getElementById("run_all").setAttribute("onclick","RunAll()");
            document.getElementById("questionmark").style.display = "none";
			
        },
        error: function (data) {
            successmessage = 'Error';
            alert(data.responseText);
            jQuery("#wait").css("display", "none");
            document.getElementById("run_all").value = 'Run query';
            document.getElementById("run_all").style.backgroundColor = 'white';
            document.getElementById("run_all").setAttribute("onclick","RunAll()");
            document.getElementById("questionmark").style.display = "none";
        }
    });



}
