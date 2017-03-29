<?php

require_once("sparqlConnection.php");


$first_link=$_POST['first_link'];
//echo $first_link;
//$list_of_natiative_prefixes='PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#> ';

#test values
$first_link='SELECT ?Target WHERE{SERVICE SILENT <http://147.91.205.66:3030/mydataset/sparql>{?Target <http://147.91.205.66:2020/Tests/TestOntology#hasCompound> ?compund.?compound <http://147.91.205.66:2020/Tests/TestOntology#hasInChiKey> "AAAAKTROWFNLEP-UHFFFAOYSA-N".}}LIMIT 1';
//$first_link=$list_of_natiative_prefixes.'PREFIX bindingdb1:<http://chem2bio2rdf.org/bindingdb/resource/>SELECT ?Target WHERE{ SERVICE SILENT<http://cheminfov.informatics.indiana.edu:8080/bindingdb/sparql>{ ?bindingdb bindingdb1:inchikey "AAAAKTROWFNLEP-UHFFFAOYSA-N" .?Target bindingdb1:Monomerid ?bindingdb }} Limit 1';


//$first_link1=$list_of_natiative_prefixes.$first_link;



$result = $db->query($first_link);

if (!$result) {
    echo("<b>Endpoint problem! Please try leter!</b> \n\n");
    exit;
} else {
    $i = 0;
    $json = '{"Type": "first_link", "children": [';
    $fields = sparql_field_array($result);
    while ($row = $result->fetch_array()) {
        
        foreach ($fields as $field) {

            $json .='{"Data": "link"';

            $json = $json . ', "FirstLink": "' . $row[$field] . '"}';
        }
        $json.= ']}';

        
    }
   
}
    
echo $json;
       
    