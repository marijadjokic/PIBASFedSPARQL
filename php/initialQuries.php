<?php

set_time_limit(18000000000000000); 
require_once("sparqlConnection.php");


$templateid = $_POST['template_id'];
$selectvalue = $_POST['select_value'];
$for_new_datasetes = $_POST['for_new_datasetes'];
//$new_prefix = $_POST['new_prefix'];


//test values
//$templateid = 2;
//$selectvalue = 'AAAAKTROWFNLEP-UHFFFAOYSA-N';
//$for_new_datasetes='UNION{SERVICE SILENT <http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql>{?bindingdb bindingdb1:inchikey "%s" .?Target bindingdb1:Monomerid ?bindingdb}BIND("BindingDB1/Chem2Bio2RDF1" AS ?Dataset)}';
//$new_prefix="PREFIX bindingdb1:<http://chem2bio2rdf.org/bindingdb/resource/>";

//$templateid = 2;
//$selectvalue = 'ghjgj';
//$for_new_datasetes='UNION{service <http://147.91.205.66:3030/mydataset/sparql>{?Target <http://147.91.205.66:2020/Tests/TestOntology#hasCompound> ?compund. ?compound <http://147.91.205.66:2020/Tests/TestOntology#hasInChiKey> "AAAAKTROWFNLEP-UHFFFAOYSA-N".}}BIND("TestDataset/TestInitiative" AS ?Dataset)}';
//$new_prefix="PREFIX drugbanknew:<http://bio2rdf.org/drugbank_vocabulary:> ";

//$templateid = 2;
//$selectvalue = 'AAAAKTROWFNLEP-UHFFFAOYSA-N';
//$for_new_datasetes='UNION{SERVICE SILENT <http://147.91.205.66:3030/mydataset/sparql>{?Target <http://147.91.205.66:2020/Tests/TestOntology#hasCompound> ?compund. ?compound <http://147.91.205.66:2020/Tests/TestOntology#hasInChiKey> "AAAAKTROWFNLEP-UHFFFAOYSA-N".}BIND("TestDataset/TestInitiative" AS ?Dataset).}';
//$for_new_datasetes="";
//$new_prefix="";



$initilaqeury = "
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
SELECT ?initilaquery
FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
WHERE 
{ 
  ?template pibas:id ?templateID.
  ?template pibas:hasInitialQuery ?initilaquery.
  FILTER(?templateID=" . $templateid . ").
}";

//echo htmlspecialchars($initilaqeury);

$result = $db->query($initilaqeury);
if (!$result) {
    echo "Error: ".$db->errno();
    exit;
}


$row = $result->fetch_array();
$initial_query = $row['initilaquery'];

//echo htmlspecialchars(urldecode($initial_query));


$number_of_selected_value = substr_count($initial_query, '%s') - 1;



$selected_values = array();

for ($j = 0; $j < $number_of_selected_value; $j++) {
    array_push($selected_values, $selectvalue);
}



array_push($selected_values, urldecode($for_new_datasetes));

$result1 = $db->query(vsprintf($initial_query, $selected_values));
//echo htmlspecialchars(vsprintf(urldecode($initial_query), $selected_values));


if (!$result1) {
    echo "Error: ".$db->errno();
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
        if ($i > 1)
            $json .= ',';
        $json .= '{';
        foreach ($fields as $field) {
            $json = $json . ' "' . $field . '": "' . $row[$field] . '",';
        }
        $json = substr_replace($json, "", -1);
        $json .= '}';
    }
    $json .= ']}';
    echo $json;
}

else{
    $json="";
    echo $json;
}

