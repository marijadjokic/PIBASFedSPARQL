<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$number_of_data_for_new_dataset=$_POST['number_of_data_for_new_dataset'];


require_once("sparqlConnection.php");

$db = sparql_connect($sparqlEndPoint);
$result = $db->query($number_of_data_for_new_dataset);
if (!$result) {
    echo "Error: " . $db->errno();
    exit;
}

if (sparql_num_rows($result) > 0) {
    $json = '{"Type": "Data", "children": [';
    while ($row = $result->fetch_array()) {
        $json = $json . '{"Number": "' . $row['Number'] .'"}';
    }
    
    $json .= ']}';

    echo $json;
} else {
    echo "Currently, data do not exist for this dataset!";
}
