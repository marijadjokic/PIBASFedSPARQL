<?php

set_time_limit(18000000000000000); 
require_once("sparqlConnection.php");


/* @var $_POST type */
$templateID = $_POST["template_id"];
$dataset_name = $_POST["dataset_name"];
$dataset_endpoint = $_POST["dataset_endpoint"];
$dataset_initiative = $_POST["dataset_initiative"];
$dataset_topicname = $_POST["topicname"];
$query_for_new_predicates = $_POST['query_for_new_predicates'];
$dataset_instances = json_decode($_POST['dataset_instances']);
$query_for_new_predicates = "";

if ($query_for_new_predicates == "") {
    $i = 0;
    $json = '{"Type": "' . $dataset_name . '", "children": [';
    $list_of_predicates=array();
    $dataset_instances_new=$dataset_instances;
    $union_predicate="{";
    for ($x = 0; $x < count($dataset_instances_new); $x++) {
         if($x<=35){
         $union_predicate.=" <".$dataset_instances_new[$x]."> ?predicate ?object.
               OPTIONAL{ ?predicate rdfs:label ?description.}} UNION{ ?subject ?predicate <".$dataset_instances_new[$x]."> .OPTIONAL{?predicate rdfs:label ?description.}} UNION{";
   
         }
    }
    $union_predicate_new=substr($union_predicate,0,-6);
        if($dataset_name=="PIBAS"){
            $predicate_description = "
            PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
            PREFIX xsd:<http://www.w3.org/2001/XMLSchema#>
            PREFIX rdfs:<http://www.w3.org/2000/01/rdf-schema#>
            PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
            PREFIX owl:<http://www.w3.org/2002/07/owl#>
            PREFIX dc: <http://purl.org/dc/terms/>

            SELECT DISTINCT ?predicate ?description
            WHERE {".$union_predicate_new."}";
        }
        else{
          $predicate_description = "
            PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
            PREFIX xsd:<http://www.w3.org/2001/XMLSchema#>
            PREFIX rdfs:<http://www.w3.org/2000/01/rdf-schema#>
            PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
            PREFIX owl:<http://www.w3.org/2002/07/owl#>
            PREFIX dc: <http://purl.org/dc/terms/>

            SELECT DISTINCT ?predicate ?description
            WHERE { 
            SERVICE SILENT<$dataset_endpoint>{".$union_predicate_new."}}";
        }
        $result = $db->query($predicate_description);
        while ($row = $result->fetch_array()) {
            if (!empty($row['predicate']) and (!in_array($row['predicate'],$list_of_predicates))){
            $json .= '{"Data": "' . $dataset_name. '",';
            if (!empty($row['description'])) {
                $json = $json . ' "Predicate": "' . $row['predicate'] . '","Description": "' . $row['description'] . '"},';
            } else {
                $json = $json . ' "Predicate": "' . $row['predicate'] . '","Description": "' . $row['predicate'] . '"},';
            }
            array_push($list_of_predicates,$row['predicate']);
        }
        
           
        }
        
    
    $final_predicates = substr($json, 0, -1);
    
    echo $final_predicates.']}';
    
} else {
    $get_predictates = "
                 PREFIX rdfs:<http://www.w3.org/2000/01/rdf-schema#>
                 SELECT DISTINCT ?predicate
                 WHERE{
                 SERVICE SILENT<$dataset_endpoint>{
<$query_for_new_predicates> ?predicate ?haspredicate.
OPTIONAL{?predicate rdfs:label ?label.
}}
}";

    $result2 = $db->query($get_predictates);
    if (!$result2) {
        echo("<b>Endpoint problem! Please try leter!</b> \n\n");
        exit;
    } else {
        $i = 0;
        $json = '{"Type": "' . $dataset_name . '", "children": [';

        while ($row = $result2->fetch_array()) {
            $i++;
            if ($i > 1)
                $json .= ',';


            if (!empty($row['predicate'])) {
                $json .= '{"Data": "' . $dataset_name . '"';
                $json = $json . ', "Predicate": "' . $row['predicate'] . '","Description": "' . $row['predicate'] . '"}';
            }
        }

        $json .= ']}';

        echo $json;
    }
}