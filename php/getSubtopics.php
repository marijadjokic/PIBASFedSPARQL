<?php


require_once("sparqlConnection.php");


$topicid = $_POST['topic_id'];
//test value
//$topicid = 2;



$subtopics = "
  PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
  PREFIX owl: <http://www.w3.org/2002/07/owl#>
  PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
  PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
  PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
  SELECT ?subTopicName ?subtopicid
  FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
  WHERE 
  { 
    ?topics rdfs:subClassOf pibas:Topics.
    ?topicsinstance rdf:type ?topics.
    ?topicsinstance pibas:hasName ?topicname.
    ?topicsinstance pibas:id ?id.
    ?topicsinstance pibas:hasSubTopic ?subtopic.
    ?subtopic pibas:hasName ?subTopicName.
    ?subtopic pibas:id ?subtopicid.
    FILTER(?id=" . $topicid . "). 
   }
   order by ?subtopicid";


$result = $db->query($subtopics);
if (!$result) {
    echo "Error: " . $db->errno();
    exit;
}


if (sparql_num_rows($result) > 0) {
    $i = 0;
    $json = '{"Type": "' . $topicid . '", "children": [';
    while ($row = $result->fetch_array()) {

        $i++;
        if ($i > 1)
            $json .= ',';
        $json .= '{"Data": "' . $topicid . '"';

        $json = $json . ', "SubTopicName": "' . $row['subTopicName'] . '","SubTopicId": "' . $row['subtopicid'] . '"}';
    }
    $json .= ']}';
    echo $json;
}
else{
    echo "Currently do not exist subtopics!";
}



