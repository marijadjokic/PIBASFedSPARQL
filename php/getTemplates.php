<?php


require_once("sparqlConnection.php");

$subtopicid = $_POST['subtopic_id'];

$templates = "
  PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
  PREFIX owl: <http://www.w3.org/2002/07/owl#>
  PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
  PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
  PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
  SELECT distinct ?templateName ?templateID
  #FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSourcesLocalTest.owl>
  WHERE 
  { 
   
    ?topicsinstance pibas:hasTemplate ?template.
    ?template pibas:hasName ?templateName.
    ?template pibas:id ?templateID.
    ?topicsinstance pibas:id ?topicsinstanceID.
    FILTER(?topicsinstanceID=".$subtopicid.").
   }";
            
$db = sparql_connect($sparqlEndPoint);
$result = $db->query($templates);
if (!$result) {
    echo "Error: " . $db->errno();
    exit;
}

if (sparql_num_rows($result) > 0) {
$i = 0;
$json = '{"Type": "' . $subtopicid . '", "children": [';
while( $row = $result->fetch_array() )
{

 $i++;
        if ($i > 1)
            $json .=',';
        $json .='{"Data": "' . $subtopicid . '"';

            $json = $json . ', "TemplateName": "' . $row['templateName'] . '","TemplateID": "' . $row['templateID'] . '"}';
}
 $json.= ']}';
 echo $json;
}
else{
    echo "Currently do not exist subtopics!";
}


    
   
