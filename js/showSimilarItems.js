/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var similar_items = document.getElementById('simlar_data').value;
var myTableDiv = document.getElementById('show_similar_items');
if (similar_items.trim() =="No similar items!") {
    myTableDiv.innerHTML = "No similar items!";
    myTableDiv.style.display = 'block';
} else {

    var similar_items_array = similar_items.split(",");
    var table = '<table id="myTable" class="table-sort table-sort-search table-sort-show-search-count">';
    for (var x = 0; x < similar_items_array.length; x++) {
        table += '<tr><td><a href=' + similar_items_array[x] + '>' + similar_items_array[x] + '</a></td></tr>';
    }
    table += '</table>';
    myTableDiv.innerHTML = table;

    myTableDiv.style.display = 'block';
    $('table.table-sort').tablesort();
}