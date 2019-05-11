<?php

set_time_limit(18000000000000000);
require_once("sparqlConnection.php");


$templateid = $_POST['template_id'];
$selectvalue = $_POST['select_value'];
$for_new_datasetes = $_POST['for_new_datasetes'];
if (!empty($for_new_datasetes)) {
}


$initilaqeury = "
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
SELECT ?initilaquery
WHERE 
{ 
  ?template pibas:id '$templateid'^^xsd:int.
  ?template pibas:hasInitialQuery ?initilaquery.
}";


$result = $db->query($initilaqeury);
if (!$result) {
    echo "Error: " . $db->errno();
    exit;
}


$row = $result->fetch_array();
$initial_query = $row['initilaquery'];


$number_of_selected_value = substr_count($initial_query, '%s') - 1;


$selected_values = array();

for ($j = 0; $j < $number_of_selected_value; $j++) {
    array_push($selected_values, $selectvalue);
}

array_push($selected_values, urldecode($for_new_datasetes));

$result1 = $db->query(vsprintf($initial_query, $selected_values));

if (!$result1) {
    echo "Error: " . $db->errno();
    exit;
}



if (sparql_num_rows($result) > 0) {
    $j = 0;
    $json = '{';


    $fields = sparql_field_array($result1);
    foreach ($fields as $field) {
        $j++;
        $json .= '"Variable' . $j . '": "' . $field . '", ';
    }
    $json .= '"children": [';

    $i = 0;


    while ($row = $result1->fetch_array()) {

        $i++;
        if ($i > 1) {
            $json .= ',';
        }

        if (!empty($row[$fields[0]]) && !empty($row[$fields[1]])) {
            $json .= '{';
            foreach ($fields as $field) {
                $json = $json . ' "' . $field . '": "' . $row[$field] . '",';
            }
            $json = substr_replace($json, "", -1);
            $json .= '}';
        } else {
            $json = substr_replace($json, "", -1);
        }
    }
    $json .= ']}';
    echo $json;
} else {
    $json = "";
    echo $json;
}

