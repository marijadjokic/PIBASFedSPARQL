function showDataForStatistics() {

// Get the modal
    var modal_for_statistics_data = document.getElementById('for_statistics_data');

// Get the <span> element that closes the modal
    var span1 = document.getElementsByClassName("close")[0];
// When the user clicks the button, open the modal 


    var template_id = document.getElementById('get_templates').value;

    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: "php/getDatasets.php",
        data: {'template_id': template_id},
        success: function (data) {
            successmessage = 'Data was succesfully captured';
            
            var keys = [];

            for (var key in statistics_data) {
                keys.push(key);
            }

            var myTableDiv = document.getElementById("statistics_data_show_table");


            var table = '<thead><tr>';
            table += '<th>Dataset</th>';
            table += '<th>Number of results</th>';
            table += '</tr></thead>';
            table += '<tbody id="statistics_data_show_table_body">';




            for (var x in data.children) {
                var dataset = data.children[x].DatasetName + '/' + data.children[x].DataSource;
                if (keys.indexOf(dataset) > -1)
                    table += '<tr><td>' + dataset + '</td><td>' + statistics_data[dataset] + '</td></tr>';
                else
                    table += '<tr><td>' + dataset + '</td><td>' + 0 + '</td></tr>';

            }
            table += '</tbody>';
            myTableDiv.innerHTML = table;

            modal_for_statistics_data.style.display = "block";

            span1.onclick = function () {
                modal_for_statistics_data.style.display = "none";

            };

            window.onclick = function (event) {
                if (event.target === modal_for_statistics_data) {
                    modal_for_statistics_data.style.display = "none";
                }
            };
        },
        error: function (data) {
            successmessage = 'Error';
            alert(data.responseText);
        }
    });



}



