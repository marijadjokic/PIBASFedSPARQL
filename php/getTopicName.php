<?php


function getTopicName($templateid){

require_once("sparqlConnection.php");

$templates = "
  PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
  PREFIX owl: <http://www.w3.org/2002/07/owl#>
  PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
  PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
  PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
  SELECT DISTINCT ?TopicName
  FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
  WHERE 
  { 
   
    ?topicsinstance pibas:hasTemplate ?template.
    ?template pibas:id ?templateID.
    ?template pibas:topicName ?TopicName.
    FILTER(?templateID=$templateid).
   }";
            
$db = sparql_connect($sparqlEndPoint);
$result = $db->query($templates);
if (!$result) {
    echo "Error: " . $db->errno();
    exit;
}

if (sparql_num_rows($result) > 0) {

while( $row = $result->fetch_array() )
{
  echo $row['TopicName'];
}

}
else{
    echo "Currently do not exist topic name!";
}
}

    
   
#getTopicName("2");