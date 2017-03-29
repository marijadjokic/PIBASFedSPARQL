<?php

set_time_limit(18000000000000000); 
require_once("sparqlConnection.php");
//session_start();

$templateid = $_POST['template_id'];
$for_filter = $_POST['for_filter'];
$topicname = $_POST['topicname'];

//test values
//$templateid=2;
//$for_filter='{"DatasetName": "BindingDB1","Initiatives": "Chem2BIO2RDF","Endpoint": "http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql","Comment": "C","Link": "http://www.bindingdb.org/bind/index.jsp","Topicname": "Target","new":"yes"}';
//$for_filter={"DatasetName": "BindingDB1","Initiatives": "Chem2BIO2RDF","Endpoint": "http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql","Prefix": "PREFIX bindingdb1: ","Comment": "C","Link": "http://www.bindingdb.org/bind/index.jsp","Topicname": "Target","new":"yes"}';
//$for_filter="";
//$topicname="Target";                                       

$i = 0;
$json = '{"Type": "' . $templateid . '", "children": [';


$filterdataset = "
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
SELECT ?individuals
FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
WHERE 
{ 
OPTIONAL
{ 

?template pibas:connectedWith ?individuals.
?template pibas:id ?id.
FILTER(?id=" . $templateid . ").
                
}
}";

$result = $db->query($filterdataset);
if (!$result) {
    echo("Greska u pozivu SPARQL upita! \n\n" . $db->errno() . ": " . $db->error() . "\n");
    exit;
}

while ($row = $result->fetch_array()) {
#echo $row['individuals'];
    $individaal_filterdataset = "
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
SELECT ?name ?initiatives ?comment ?link ?endpoint
FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
WHERE 
{ 
OPTIONAL
{ 
<" . $row['individuals'] . "> pibas:hasName ?name;
             pibas:fromDataSource ?initiatives;
             pibas:comment ?comment;
             pibas:link ?link;
             pibas:endpoint ?endpoint.
            
             
                
}
}";
    $result1 = $db->query($individaal_filterdataset);
    $i++;
    if ($i > 1)
        $json .=',';
    $json .='{';
    while ($row1 = $result1->fetch_array()) {
        $json = $json . '"DatasetName": "' . $row1['name'] . '","Initiatives": "' . $row1['initiatives'] . '","Endpoint": "' . $row1['endpoint'] . '","Comment": "' . $row1['comment'] . '","Link": "' . $row1['link'] . '","Topicname": "' . $topicname . '", "new":"no"}';
    }
}

if ($for_filter != "") {
    $json.=',' . $for_filter;
}


$json.= ']}';

echo $json;
