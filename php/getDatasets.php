<?php

require_once("sparqlConnection.php");

$templateid = $_POST['template_id'];

$datasets = "
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT distinct ?datasetName ?dataSource
#FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
WHERE 
{ 
    ?template pibas:id '" . $templateid . "'^^xsd:int;
              pibas:connectedWith ?dataset.
    ?dataset pibas:fromDataSource ?dataSource;
             pibas:hasName ?datasetName.
    
}";

$result = $db->query($datasets);
if (!$result) {
    echo "Error: " . $db->errno();
    exit;
}

if (sparql_num_rows($result) > 0) {
    $i = 0;
    $json = '{"Type": "' . $templateid . '", "children": [';
    while ($row = $result->fetch_array()) {

        $i++;
        if ($i > 1) {
            $json .= ',';
        }
        $json .= '{"Data": "' . $templateid . '"';

        $json = $json . ', "DatasetName": "' . $row['datasetName'] . '","DataSource": "' . $row['dataSource'] . '"}';
    }
    $json .= ']}';
    echo $json;
} else {
    echo "Currently does not exist datasets!";
}
