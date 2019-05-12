<?php

set_time_limit(18000000000000000);
require_once("sparqlConnection.php");


//pass value

$templateID = $_POST['template_id'];
$selectvalue = $_POST['select_value'];
$predicates = $_POST['predicates'];
$topicname = $_POST['topicname'];
$for_new_filter = trim($_POST['for_new_filter']);

if ($for_new_filter != "") {
    $i = 0;
    $for_dataset = array();
    $for_dataset_array = explode(',', $for_new_filter);
    $new_datasets = array();
    array_push($new_datasets, $for_dataset_array[0]);
} else {
    $new_datasets = array();
}

$get_predicates_value = explode(";", rtrim($predicates, ";"));

$predicates_arange = array();
for ($t = 0; $t < count($get_predicates_value); $t++) {
    $key_for_predicates_arange = substr($get_predicates_value[$t], 0, strpos($get_predicates_value[$t], ':'));


    $value_for_predicates_arange = substr($get_predicates_value[$t], strpos($get_predicates_value[$t], ':'));
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

$array_for_new_predicates = array();

$predicates_variable = array();
$triples_for_query = array();
foreach ($predicates_arange as $key => $value) {
    $array_for_new_predicates[$key . "_subjects"] = array();
    $array_for_new_predicates[$key . "_triples"] = array();
    for ($x = 0; $x < count($value); $x++) {
        $new_value = explode(",", $value[$x]);

        $varibale_for_select = '';
        $triple = '';
        $for_inverse_predicate='';
        for ($y = 0; $y < count($new_value); $y++) {
            if (strpos($new_value[$y], '#') > 0) {
                $fragment = parse_url(htmlspecialchars(urldecode($new_value[$y])), PHP_URL_FRAGMENT);
                $varibale_for_select = '?' . $fragment . ' ';
                array_push($array_for_new_predicates[$key . "_subjects"], $varibale_for_select);
                $after_last_backslash = explode('#', parse_url($new_value[$y], PHP_URL_PATH));
                $after_last_backslash_last = str_replace("-","_",str_replace(":","_",$after_last_backslash[count($after_last_backslash) - 1]));
                $triple = '<' . htmlspecialchars(urldecode($new_value[$y])) . '> ' . '?' . $fragment . '; ';
                $triple = substr($triple, 0, -1);
                array_push($array_for_new_predicates[$key . "_triples"], $triple);
                $for_inverse_predicate.="?".$fragment." <".htmlspecialchars(urldecode($new_value[$y]))."> ?".$topicname.". "; 
            } else {
                $after_last_backslash = explode('/', parse_url($new_value[$y], PHP_URL_PATH));
                $after_last_backslash_last = str_replace("-","_",str_replace(":","_",$after_last_backslash[count($after_last_backslash) - 1]));
                $varibale_for_select = '?' . $after_last_backslash_last . ' ';
                array_push($array_for_new_predicates[$key . "_subjects"], $varibale_for_select);

                $triple = '<' . htmlspecialchars(urldecode($new_value[$y])) . '> ' . '?' . $after_last_backslash_last . '; ';
                $triple = substr($triple, 0, -1);
                array_push($array_for_new_predicates[$key . "_triples"], $triple);
                $for_inverse_predicate.="?".$after_last_backslash_last." <".htmlspecialchars(urldecode($new_value[$y]))."> ?".$topicname.". "; 
            }
        }
    }
}

$json = '[';

foreach ($predicates_arange as $key => $value) {
    if (!(in_array($key, $new_datasets))) {
        for ($i = 0; $i < count($array_for_new_predicates[$key . "_subjects"]); $i++) {
            $key_for_pattern = explode('/', $key);
            $pattern_query = "
                PREFIX  cco: <http://rdf.ebi.ac.uk/terms/chembl#> 
                PREFIX pubchem:<http://chem2bio2rdf.org/pubchem/resource/> 
                PREFIX  chembl: <http://rdf.farmbio.uu.se/chembl/onto/#> 
                PREFIX xsd:<http://www.w3.org/2001/XMLSchema#>
                PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#> 
                SELECT DISTINCT ?patternQuery 

                WHERE 
                { 
                  ?template pibas:id '" . $templateID . "'^^xsd:int.
                  ?template <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#" . $key_for_pattern[0] ."/". $key_for_pattern[1]."patternQuery> ?patternQuery .

                }
                ";

            $result = $db->query($pattern_query);t);
            if (!$result) {
                echo "Error: " . $db->errno();
                exit;
            }
            $row = $result->fetch_array();
            $filter_query = $row['patternQuery'];
            
            
            if ($key_for_pattern[0] ."/". $key_for_pattern[1]=="Chembl/EMBL-EBI" || $key_for_pattern[0] ."/". $key_for_pattern[1]=="PIBAS/CPCTAS" || $key_for_pattern[0] ."/". $key_for_pattern[1]=="BindingDB/Chem2Bio2RDF" || $key_for_pattern[0] ."/". $key_for_pattern[1]=="Pubmed/Bio2RDF" || $key_for_pattern[0] ."/". $key_for_pattern[1]=="Reference/CPCTAS"){
                 $r = sprintf($filter_query, $array_for_new_predicates[$key . "_subjects"][$i], $selectvalue, $array_for_new_predicates[$key . "_triples"][$i],$for_inverse_predicate);
            
            }
            else{
                $r = sprintf($filter_query, $array_for_new_predicates[$key . "_subjects"][$i], $array_for_new_predicates[$key . "_triples"][$i],$for_inverse_predicate,$selectvalue);
            
            }
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
                            $json = $json . ' "' . $field . '": "' . str_replace('"', "'",$row[$field]) . '",';

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
    } else {

        for ($i = 0; $i < count($array_for_new_predicates[$key . "_subjects"]); $i++) {
            $dataset = $for_dataset_array[0];
            $endpoint = $for_dataset_array[1];
            $pattern = sprintf($for_dataset_array[2]);

            if (substr($pattern, -1) != ".") {
                $pattern .= ".";
            }
            $add_query = " 
                SELECT DISTINCT ?" . $topicname . " ?Dataset %s 
                WHERE{SERVICE SILENT <" . $endpoint . ">
                {OPTIONAL{" . $pattern . "?" . $topicname . " %s.
                BIND('$dataset' AS ?Dataset).}}}";

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
                            $json = $json . ' "' . $field . '": "' . str_replace('"', "'",$row[$field]) . '",';
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
echo $json;




