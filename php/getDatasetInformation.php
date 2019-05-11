<?php

$templateid = $_POST['template_id'];
$dataset_name = $_POST['name'];
$dataset_initiative = $_POST['initiative'];
$dataset_link = $_POST['link'];
$dataset_comment = $_POST['comment'];
$dataset_endpoint = $_POST['endpoint'];
$dataset_pattern = $_POST['pattern'];
$public_filed = $_POST['public'];

require_once("sparqlConnection.php");

if(filter_var($public_filed, FILTER_VALIDATE_BOOLEAN)){
    
    $info= "Dataset name: ".$dataset_name.", Dataset initiative: ".$dataset_initiative.", Dataset Link: ".$dataset_link.", Dataset comment: ".$dataset_comment.", Dataset endpoint: ".$dataset_endpoint. ", Dataset pattern: ".str_replace('"', "", rtrim($dataset_pattern));
    $insert_info_public_dataset="
        PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX owl: <http://www.w3.org/2002/07/owl#>
        PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
        PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
        INSERT DATA
        { 
            pibas:Template".$templateid." pibas:new_dataset_".$dataset_name."_".$dataset_initiative." '".$info."'.

        } ";
    
    $db = sparql_connect($sparqlEndPointInsert);
    $insert_result = $db->update($insert_info_public_dataset);
    if (!$insert_result) {
        echo "Error: " . $db->errno();
        exit;
    }
}

$dataset_name_and_endpoint = "  
  PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
  PREFIX owl: <http://www.w3.org/2002/07/owl#>
  PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
  PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
  PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
  SELECT DISTINCT ?DatasetName ?InitiativeName ?endpoint
  #FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
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
    $json = '{"Type": "' . $templateid . '", "children": [';
    while ($row = $result->fetch_array()) {
        $json = $json . '{"DatasetName": "' . $row['DatasetName'] . '","InitiativeName":"' . $row['InitiativeName'] . '", "Endpoint": "' . $row['endpoint'] . '"},';
    }
    $json = substr_replace($json, "", -1);
    $json .= ']}';

    echo $json;
} else {
    echo "Currently do not exist information about template datasets!";
}
?>