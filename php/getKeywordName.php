<?php

require_once("sparqlConnection.php");

$templateid = $_POST['template_id'];
//test values
//$templateid=2;



$get_input_keyword = "  
  PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
  PREFIX owl: <http://www.w3.org/2002/07/owl#>
  PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
  PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
  PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
  SELECT DISTINCT ?input ?topicname
  FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
  WHERE 
  { 
   
    ?topicsinstance pibas:hasTemplate ?template.
    ?template pibas:id ?templateID.
    ?template pibas:hasInput ?input.
    ?template pibas:topicName ?topicname.
    FILTER(?templateID=" . $templateid . ").
   }";


$db = sparql_connect($sparqlEndPoint);
$result = $db->query($get_input_keyword);
if (!$result) {
    echo "Error: " . $db->errno();
    exit;
}

if (sparql_num_rows($result) > 0) {

    $json = '{"Type": "' . $templateid . '", "children": [';
    while ($row = $result->fetch_array()) {
        $json = $json . '{"Input": "' . $row['input'] . '","Topicname":"'.$row['topicname'].'"}';
    }

    $json .= ']}';

    echo $json;
} else {
    echo "Currently do not exist keyword name for the selected template!";
}


  