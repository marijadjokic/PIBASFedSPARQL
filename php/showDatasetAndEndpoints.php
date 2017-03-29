<?php

$parts = parse_url($_SERVER['REQUEST_URI']);

parse_str($parts['query'], $query);
$templateid =$query['template_id'];

//echo $templateid;
//test value
//$templateid="2";

require_once("sparqlConnection.php");



$dataset_name_and_endpoint = "  
  PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
  PREFIX owl: <http://www.w3.org/2002/07/owl#>
  PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
  PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
  PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
  SELECT DISTINCT ?DatasetName ?InitiativeName ?endpoint
  FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
  WHERE 
  { 
   
    ?topicsinstance pibas:hasTemplate ?template.
    ?template pibas:id ?templateID.
    ?template pibas:connectedWith ?datasetinstance.
?datasetinstance pibas:hasName ?DatasetName;
                 pibas:endpoint ?endpoint;
                 pibas:fromDataSource ?InitiativeName.
    FILTER(?templateID=$templateid).
   }";


$db = sparql_connect($sparqlEndPoint);
$result = $db->query($dataset_name_and_endpoint);
if (!$result) {
    echo "Error: " . $db->errno();
    exit;
}

if (sparql_num_rows($result) > 0) {
    
    $table = '<table border="1px" align="center" width="auto" margin-left="auto" margin-right="auto"><tr><td><b>Dataset</b></td><td><b>Initiative</b></td><td><b>Endpoint</b></td></tr>';
    while ($row = $result->fetch_array()) {
        $table = $table . '<tr><td>'.$row['DatasetName'].'</td><td>'.$row['InitiativeName'].'</td><td>'.$row['endpoint'].'</td></tr>';
    }

    $table .= '</table>';

    echo $table;
} else {
    echo "Currently do not exist datasets and their endpoints!";
}
?>