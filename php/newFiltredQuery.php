<?php

set_time_limit(18000000000000000); 
require_once("sparqlConnection.php");


//pass value
$templateID = $_POST['template_id'];
$selectvalue = $_POST['select_value'];
$predicates = $_POST['predicates'];
$topicname = $_POST['topicname'];
$for_new_filter = trim($_POST['for_new_filter']);


//test values
//$templateID = 2;
//$selectvalue = 'AAAAKTROWFNLEP-UHFFFAOYSA-N';
//$predicates = "Chembl/EMBL-EBI:http://purl.org/dc/terms/title,http://www.w3.org/2000/01/rdf-schema#label;BindingDB/Chem2Bio2RDF:http://chem2bio2rdf.org/bindingdb/resource/TARGET;TestDataset/TestInitiative:http://147.91.205.66:2020/Tests/TestOntology#hasSynonym,http://www.w3.org/1999/02/22-rdf-syntax-ns#type;";
//$topicname = "Target";
//$for_new_filter = "";

//$templateID = 2;
//$selectvalue = 'AAAAKTROWFNLEP-UHFFFAOYSA-N';
//$topicname="Target";
//$for_new_filter = 'TestDataset/TestInitiative,http://147.91.205.66:3030/mydataset/sparql,?Target <http://147.91.205.66:2020/Tests/TestOntology#hasCompound> ?compund.?compound <http://147.91.205.66:2020/Tests/TestOntology#hasInChiKey> "AAAAKTROWFNLEP-UHFFFAOYSA-N"';


if ($for_new_filter != "") {
    $i = 0;
    $for_dataset = array();
    $for_dataset_array = explode(',', $for_new_filter);
    $new_datasets = array();
    array_push($new_datasets, $for_dataset_array[0]);
} else {
    $new_datasets = array();
}

//test values
//$for_dataset=array("BindingDB1"=>array("bindingdb1: <http://chem2bio2rdf.org/bindingdb/resource/>","http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql","?bindingdb bindingdb1:inchikey '%s' .?Target bindingdb1:Monomerid ?bindingdb.","BindingDB1/Chem2Bio2RDF"));


$get_predicates_value = explode(";", rtrim($predicates, ";"));

//print_r($get_predicates_value);
$predicates_arange = array();
for ($t = 0; $t < count($get_predicates_value); $t++) {
    $key_for_predicates_arange = substr($get_predicates_value[$t], 0, strpos($get_predicates_value[$t], ':'));


    $value_for_predicates_arange = substr($get_predicates_value[$t], strpos($get_predicates_value[$t], ':'));
    //echo $value_for_predicates_arange;
    if (array_key_exists($key_for_predicates_arange, $predicates_arange)) {
        $predicates_arange[$key_for_predicates_arange] .= ' ' . substr($get_predicates_value[$t], strpos($get_predicates_value[$t], ':') + 1);
    } else {
        $predicates_arange[$key_for_predicates_arange] = substr($get_predicates_value[$t], strpos($get_predicates_value[$t], ':') + 1);
    }
}



foreach ($predicates_arange as $k => $id) {
    if (strpos($id, ' ') !== false) {
        $array_for_predicate_value = array();
        $single_predicates = explode(' ', $id);


        for ($m = 0; $m < count($single_predicates); $m++) {
            array_push($array_for_predicate_value, $single_predicates[$m]);
        }
        $predicates_arange[$k] = $array_for_predicate_value;
    } else {

        $predicates_arange[$k] = (array) $id;
    }
}



//print_r($predicates_arange);


$array_for_new_predicates = array();

$predicates_variable = array();
$triples_for_query = array();
foreach ($predicates_arange as $key => $value) {
    #print_r($value);
    $array_for_new_predicates[$key . "_subjects"] = array();
    $array_for_new_predicates[$key . "_triples"] = array();
    for ($x = 0; $x < count($value); $x++) {
        //echo $value[$x];

        $new_value = explode(",", $value[$x]);
        //print_r($new_value);

        $varibale_for_select = '';
        $triple = '';
        for ($y = 0; $y < count($new_value); $y++) {
            if (strpos($new_value[$y], '#') > 0) {
                //echo "ima".' '.$new_value[$y]."<br/>";
                $fragment = parse_url(htmlspecialchars(urldecode($new_value[$y])), PHP_URL_FRAGMENT);
                //echo $fragment.'<br/>';
                $varibale_for_select = '?' . $fragment . ' ';
                array_push($array_for_new_predicates[$key . "_subjects"], $varibale_for_select);

                $triple = '<' . htmlspecialchars(urldecode($new_value[$y])) . '> ' . '?' . $fragment . '; ';
                $triple = substr($triple, 0, -1);
                array_push($array_for_new_predicates[$key . "_triples"], $triple);
            } else {
                //echo "nema" . ' ' . $new_value[$y] . "<br/>";
                $after_last_backslash = explode('/', parse_url($new_value[$y], PHP_URL_PATH));
                //print_r($after_last_backslash);
                //echo count($after_last_backslash);
                $after_last_backslash_last = $after_last_backslash[count($after_last_backslash) - 1];
                //echo $after_last_backslash_last.'<br/>';
                $varibale_for_select = '?' . $after_last_backslash_last . ' ';
                array_push($array_for_new_predicates[$key . "_subjects"], $varibale_for_select);

                $triple = '<' . htmlspecialchars(urldecode($new_value[$y])) . '> ' . '?' . $after_last_backslash_last . '; ';
                $triple = substr($triple, 0, -1);
                array_push($array_for_new_predicates[$key . "_triples"], $triple);
            }
        }
    }
}

//print_r($array_for_new_predicates);

$json = '[';

foreach ($predicates_arange as $key => $value) {
    if (!(in_array($key, $new_datasets))) {
        //echo $key;
        for ($i = 0; $i < count($array_for_new_predicates[$key . "_subjects"]); $i++) {

            $pattern_query = "
PREFIX  cco: <http://rdf.ebi.ac.uk/terms/chembl#> 
PREFIX pubchem:<http://chem2bio2rdf.org/pubchem/resource/> 
PREFIX  chembl: <http://rdf.farmbio.uu.se/chembl/onto/#> 
PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#> 
SELECT DISTINCT ?patternQuery 
FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl> 
WHERE 
{ 
  ?template pibas:id ?templateID.
  ?template <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#" . $key . "patternQuery> ?patternQuery .
  FILTER(?templateID=$templateID).
}
";

//echo $pattern_query;
//echo $key." ".$array_for_new_predicates[$key."_subjects"][$i]," ---------- ".htmlspecialchars(urldecode($array_for_new_predicates[$key."_triples"][$i]))."<br/>";

            $result = $db->query($pattern_query);
            if (!$result) {
                echo "Error: " . $db->errno();
                exit;
            }
            $row = $result->fetch_array();
            $filter_query = $row['patternQuery'];

            $r = sprintf($filter_query, $array_for_new_predicates[$key . "_subjects"][$i], $selectvalue, $array_for_new_predicates[$key . "_triples"][$i]);
            //echo htmlspecialchars(urldecode($r))."<br/><br/>";


            $result1 = $db->query($r);

            if (!$result1) {
                echo "Error: " . $db->errno();
                exit;
            }


            if (sparql_num_rows($result1) > 0) {
                $j = 0;
                $json .= '{';
                $fields = sparql_field_array($result1);
                foreach ($fields as $field) {
                    $j++;
                    $json .= '"Variable' . $j . '": "' . $field . '", ';
                }
                $json .= '"children": [';


                while ($row = $result1->fetch_array()) {

                    $json .= '{';

                    foreach ($fields as $field) {

                        if (!empty($row[$field]) && $row[$field] != "") {
                            //$json = $json . ' "Variable": "' . $field. '",';
                            $json = $json . ' "' . $field . '": "' . $row[$field] . '",';


                            //echo $row[$field]."<br/>";
                        } else {
                            $json = $json . ' "' . $field . '": "N/A",';
                        }
                    }

                    $json = substr_replace($json, "", -1);
                    $json .= '},';
                }
                $json = substr($json, 0, -1);
                $json .= ']},';
            }
        }
    } 
    else 
        {

        for ($i = 0; $i < count($array_for_new_predicates[$key . "_subjects"]); $i++) {

            //echo $array_for_new_predicates[$key . "_subjects"][$i];
            $dataset = $for_dataset_array[0];
            $endpoint = $for_dataset_array[1];
            $pattern = sprintf($for_dataset_array[2]);

            if(substr($pattern, -1)!="."){
                $pattern .=".";
            }

            //echo $endpoint,$pattern,$dataset;
            $add_query = " 
                SELECT DISTINCT ?" . $topicname . " ?Dataset %s 
                WHERE{SERVICE SILENT <" . $endpoint . ">
                {OPTIONAL{" . $pattern . "?" . $topicname . " %s.
                BIND('$dataset' AS ?Dataset).}}}";


            //echo $add_query;
            //echo sprintf($add_query,$array_for_new_predicates[$key . "_subjects"][$i],$array_for_new_predicates[$key . "_triples"][$i]);

            $result2 = $db->query(sprintf($add_query, $array_for_new_predicates[$key . "_subjects"][$i], $array_for_new_predicates[$key . "_triples"][$i]));
            if (!$result2) {
                echo "Error: " . $db->errno();
                exit;
            }

            if (sparql_num_rows($result2) > 0) {
                $j = 0;
                $json .= '{';
                $fields = sparql_field_array($result2);
                foreach ($fields as $field) {
                    $j++;
                    $json .= '"Variable' . $j . '": "' . $field . '", ';
                }
                $json .= '"children": [';

                $j = 0;
                while ($row = $result2->fetch_array()) {
                    $j++;
                    if ($j > 1)
                        $json .= ',';
                    $json .= '{';
                    foreach ($fields as $field) {
                        if (!empty($row[$field]) && $row[$field] != "") {
                            //$json = $json . ' "Variable": "' . $field. '",';
                            $json = $json . ' "' . $field . '": "' . $row[$field] . '",';


                            //echo $row[$field]."<br/>";
                        } else {
                            $json = $json . ' "' . $field . '": "N/A",';
                        }
                    }
                    $json = substr_replace($json, "", -1);
                    $json .= '}';
                }
                $json .= ']},';
            }
        }
    }
}

$json = substr($json, 0, -1);
$json .= ']';

//$json='[{"Variable1": "Target", "Variable2": "Dataset", "Variable3": "hasTargetName", "children": [{ "Target": "http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#TaregtTest1", "Dataset": "PIBAS/CPCTAS","hasTargetName": "MAPK-activated protein kinase 2"}]},{"Variable1": "Target", "Variable2": "Dataset", "Variable3": "CID_GENE", "Variable4": "uniprot", "children": [{ "Target": "http://chem2bio2rdf.org/bindingdb/resource/bindingdb_interaction/55299", "Dataset": "BindingDB/Chem2Bio2RDF","CID_GENE": "http://chem2bio2rdf.org/chemogenomics/resource/chemogenomics/44143514:MAPKAPK2","uniprot": "http://chem2bio2rdf.org/uniprot/resource/uniprot/P49137"}]}]';
echo $json;




