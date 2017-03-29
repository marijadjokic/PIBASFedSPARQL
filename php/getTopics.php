<?php

require_once("sparqlConnection.php");


$topics = "
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
SELECT ?topicname ?topicid
FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
WHERE { 
 ?topics rdfs:subClassOf pibas:Topics.
 ?topicsinstance rdf:type ?topics.
 ?topicsinstance pibas:hasName ?topicname.
 ?topicsinstance pibas:id ?topicid.
  }
order by ?id";


$result = $db->query($topics);
if (!$result) {
    echo "Error: " . $db->errno();
    exit;
}


if (sparql_num_rows($result) > 0) {
    echo '<script>document.getElementById("topics").style.display = "block";</script>';
    echo '<select name="topic" id="topic" onchange="getSubTopic();">';
    echo '<option value="0">Select topic</option>';
    while ($row = $result->fetch_array()) {

        echo '<option value="' . $row['topicid'] . '">' . $row['topicname'] . '</option>';
    }
    echo '</select>';
} else {
    echo "Currently do not exist topics!";
}




