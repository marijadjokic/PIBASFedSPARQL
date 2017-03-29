<?php

set_time_limit(18000000000000000); 
require_once("sparqlConnection.php");


/* @var $_POST type */
$templateID = $_POST["template_id"];
$dataset_name = $_POST["dataset_name"];
$dataset_endpoint = $_POST["dataset_endpoint"];
$dataset_prefix = $_POST["dataset_prefix"];
$dataset_initiative = $_POST["dataset_initiative"];
$dataset_topicname = $_POST["topicname"];
$query_for_new_predicates = $_POST['query_for_new_predicates'];


//test values 1
//$templateID = 1;
//$dataset_name = "Chembl";
//$dataset_endpoint = "https://www.ebi.ac.uk/rdf/services/chembl/sparql";
//$dataset_initiative = "EMBL-EBI";
//$dataset_prefix = "chembl:<http://rdf.ebi.ac.uk/terms/chembl#>";
//$dataset_topicname = "Assay";
//$query_for_new_predicates = "http://chem2bio2rdf.org/bindingdb/resource/bindingdb_interaction/55299";
//$query_for_new_predicates = "";

////$templateID = 2;
//$dataset_name = "TestDataset";
//$dataset_endpoint = "http://cpctas-lcmb.pmf.kg.ac.rs:2020/sparql";
//$dataset_initiative = "TestInitiative";
//$dataset_prefix = "drugbank:<http://bio2rdf.org/drugbank_vocabulary:>";
//$dataset_topicname = "Target";
//$query_for_new_predicates="http://147.91.205.66:2020/Tests/TestOntology#TestTarget1";




if ($query_for_new_predicates == "") {

    $explode_prefix = explode(":", $dataset_prefix);


    $predicate_description = "
        PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
        PREFIX xsd:<http://www.w3.org/2001/XMLSchema#>
        PREFIX rdfs:<http://www.w3.org/2000/01/rdf-schema#>
        PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
        PREFIX owl:<http://www.w3.org/2002/07/owl#>

        select ?getPredicateQuery
        FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
        WHERE { 
        ?template pibas:id $templateID;
                  pibas:" . strtolower($dataset_name) . "" . strtolower($dataset_initiative) . "" . $dataset_topicname . "getPredicate ?getPredicateQuery.
         }";

       #echo $predicate_description;


    $result1 = $db->query($predicate_description);
    if (!$result1) {
        echo "Error: " . $db->errno();
        exit;
    }

    $row = $result1->fetch_array();

    $get_predictates = $row['getPredicateQuery'];
    #echo htmlspecialchars($get_predictates);
    $result2 = $db->query($get_predictates);
    if (!$result2) {
        echo "Error: " . $db->errno();
        exit;
    } 
        //$returnValue = "<table>";

        $i = 0;
        $json = '{"Type": "' . $explode_prefix[0] . '", "children": [';

        while ($row = $result2->fetch_array()) {
            $i++;
            if ($i > 1)
                $json .= ',';
            $json .= '{"Data": "' . $explode_prefix[0] . '"';
            if (!empty($row['description'])) {
                $json = $json . ', "Predicate": "' . $row['predicate'] . '","Description": "' . $row['description'] . '"}';
            } else {
                $json = $json . ', "Predicate": "' . $row['predicate'] . '","Description": "' . $row['predicate'] . '"}';
            }
        }
        $json .= ']}';

        echo $json;
    
} else {

    // $explode_prefix = explode(":", $dataset_prefix);
    $get_predictates = "
                 PREFIX rdfs:<http://www.w3.org/2000/01/rdf-schema#>
                 SELECT DISTINCT ?predicate
                 WHERE{
                 SERVICE SILENT<$dataset_endpoint>{
<$query_for_new_predicates> ?predicate ?haspredicate.
OPTIONAL{?predicate rdfs:label ?label.
}}
}";

//echo htmlspecialchars($get_predictates);
    $result2 = $db->query($get_predictates);
    if (!$result2) {
        echo("<b>Endpoint problem! Please try leter!</b> \n\n");
        exit;
    } else {
        //$returnValue = "<table>";

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