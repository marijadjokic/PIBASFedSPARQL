<?php

require_once("sparqlConnection.php");


$templateid = $_POST['template_id'];
//test value
//$templateid = "4";

$dataset_name_and_endpoint = "  
  PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
  PREFIX owl: <http://www.w3.org/2002/07/owl#>
  PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
  PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
  PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
  SELECT DISTINCT ?hasSimilarItem
  FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
  WHERE 
  { 
   
    ?topicsinstance pibas:hasTemplate ?template.
    ?template  pibas:hasSimilar ?hasSimilarItem;
               pibas:id ?templateID.
    FILTER(?templateID=$templateid).
   }";


$db = sparql_connect($sparqlEndPoint);
$result = $db->query($dataset_name_and_endpoint);
if (!$result) {
    echo "Error: " . $db->errno();
    exit;
}

if (sparql_num_rows($result) > 0) {
    $json = '{"Type": "' . $templateid . '", "children": [';
    while ($row = $result->fetch_array()) {
        $json = $json . '{"hasSimilarItem": "' . $row['hasSimilarItem'] .'"}';
    }
    
    $json .= ']}';

    echo $json;
} else {
    echo "Currently do not exist information about this template!";
}
?>