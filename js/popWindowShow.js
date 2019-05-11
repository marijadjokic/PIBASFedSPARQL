function showData() {

    var pop_up_window = document.getElementById("myModal");

    var for_data_show = "<div class='modal-content' style='z-index:1;'>";

    for_data_show += "<span class='close-popUp'>x</span>";
    for_data_show += '<br></br>';
    for_data_show += '<table width="100%">';
    for_data_show += '<tr><td></td><td align="right"><i><b>Variable Name:' + document.getElementById("template_topic").value + '</b></i></td></tr>'
    for_data_show += '</table>';
    for_data_show += '<table width="100%">';
    for_data_show += '<tr><td>Dataset Name</td><td><input id="name" type="text" size="50x;"required/></td></tr>';
    for_data_show += ' <tr><td>Dataset Initiative</td><td><input id="initiative" type="text" size="50x;" required/></td></tr>';
    for_data_show += '<tr><td>Dataset Link</td><td><input id="link" type="text" size="50x;" required/></td></tr>';
    for_data_show += ' <tr><td>Comment</td><td><textarea id="comment" cols="69" rows="5" required></textarea></td></tr>';
    for_data_show += '<tr><td>Endpoint</td><td><input id="endpoint" type="text" size="50x;" required/></td></tr>';
    for_data_show += ' <!--<tr><td>Prefix</td><td><input id="prefix" type="text" size="50x;" required/></td><td style="font-size: 10px;">(e.g, <i>http://chem2bio2rdf.org/bindingdb/resource/</i>)</td></tr>-->';
    for_data_show += '<tr><td>Query Pattern</td><td><textarea id="pattern" cols="69" rows="5" required></textarea></td></tr>';
    for_data_show += '<tr><td>Public dataset</td><td><input type="checkbox" id="public"></td></tr>';
    for_data_show += '<tr style="font-size: 11px;"><td><b>Notes</b></td><td><i>*Dataset name, dataset inititative and endpoint must be different form those included in predefined query for running template. List of datasets could be seen <a href="php/showDatasetAndEndpoints.php?template_id=' + document.getElementById('get_templates').value + '" target="_blank">here</a></i>.';
    for_data_show += '<br/><i>**Query pattern should be related to running template. SELECT clause must contain only variable shown in top right corner. Please, use full URIs in query pattern.</i>';
    for_data_show += '</table>';
    for_data_show += '<p><input type="button" value="Add dataset" id="' + document.getElementById('get_templates').value + '" name="' + document.getElementById("template_topic").value + '" onclick="AddNewDataset(this.name,this.id);"align="right"/></p>';
    for_data_show += '</div>';

    pop_up_window.innerHTML = for_data_show;

    pop_up_window.style.display = "block";



// Get the <span> element that closes the modal
    var span0 = document.getElementsByClassName("close-popUp")[0];
// When the user clicks the button, open the modal 
   


// When the user clicks on <span> (x), close the modal
    span0.onclick = function () {
        pop_up_window.style.display = "none";
    };


// When the user clicks anywhere outside of the modal, close it
    //window.onclick = function (event) {
        //if (event.target === modal_for_new_dataset) {
      //      pop_up_window.style.display = "none";
       // }
   //s };



}