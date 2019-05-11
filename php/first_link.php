<?php

require_once("sparqlConnection.php");


$first_link=$_POST['first_link'];
$result = $db->query($first_link);

if (!$result) {
    echo("Endpoint problem! Please try leter!\n\n");
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
       
    