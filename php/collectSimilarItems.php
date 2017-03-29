
<?php

set_time_limit(18000000000000000); 
session_start();
require_once("sparqlConnection.php");

$template_id=$_POST['template_id'];
$select_value=$_POST['select_value'];
$subject_uris = $_POST['subject_uris'];
$datasets_initiatives = $_POST['dataset_inistiatives'];

$new_endpoints=$_REQUEST['new_endpoints'];
$new_initiatives=$_REQUEST['new_initiatives'];
$for_similar=$_REQUEST['for_similar'];



//#test values
#$template_id=2;
#$select_value="AAAAKTROWFNLEP-UHFFFAOYSA-N";
#$subject_uris=['http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL2208','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL3587','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL4040','http://rdf.ebi.ac.uk/resource/chembl/target/CHEMBL614245','http://chem2bio2rdf.org/bindingdb/resource/bindingdb_interaction/55299'];
#$datasets_initiatives=['Chembl/EMBL-EBI','Chembl/EMBL-EBI','Chembl/EMBL-EBI','Chembl/EMBL-EBI','BindingDB/Chem2Bio2RDF'];
#$new_endpoints="";
#$new_initiatives="";
#$for_similar="";
//$for_similar=('"TestDataset/TestInitiative"=>"http://147.91.205.66:2020/Tests/TestOntology#hasSynonym,http://147.91.205.66:2020/Tests/TestOntology#hasName"');        
//


$new_subject_uris = "";
for ($x = 0; $x < count($subject_uris); $x++) {
    $new_subject_uris .= $subject_uris[$x]."," ;
}


$endpoints = "";
$my_connections = array();

for ($x = 0; $x < count($datasets_initiatives); $x++) {
    //echo $datasets_initiatives[$x];
   
    $topics = "
     PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
     PREFIX owl: <http://www.w3.org/2002/07/owl#>
     PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
     PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
     PREFIX pibas:<http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
     SELECT ?endpoint
     FROM <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS/DataSources.owl>
     WHERE { 

         <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#" . $datasets_initiatives[$x] . "Instance> pibas:endpoint ?endpoint.
         }
     ";


    if (!$db) {
        echo "Endpoint connection error! \n\n" . $db->errno() . ": " . $db->error() . "\n";
        exit;
    }
    $result = $db->query($topics);
    if (!$result) {
        echo "Error in SPARQL call! \n\n" . $db->errno() . ": " . $db->error() . "\n";
        exit;
    }

    while ($row = $result->fetch_array()) {

        $endpoints .= $row['endpoint'] . ",";
        $my_connections[$row['endpoint']] = $datasets_initiatives[$x];
    }
    
    
   
}

$connections = "";
foreach ($my_connections as $x => $x_value) {
    $connections .= $x . "=" . $x_value . ",";
}



if ($new_endpoints != "") {
    $new_endpoints_exp=explode(',', $new_endpoints);
    for($x=0;$x<count($new_endpoints_exp);$x++){
        $endpoints .=$new_endpoints_exp[$x].',';
        
    }
    $un_new_endpoints_exp=array_unique($new_endpoints_exp);
    for($x=0;$x<count($un_new_endpoints_exp);$x++){
        $connections .=$new_endpoints_exp[$x]."=".$new_initiatives.",";
        
    }
    
    
}





if ($for_similar == "") {
    $for_similar = "no";
}

//echo $new_subject_uris.'<br/>'.$connections.'<br/>'.$endpoints;

//echo $template_id,'<br/>',trim($new_subject_uris, ","),'<br/>',trim($endpoints, ","),'<br/>',trim($connections, ','),'<br/>',$for_similar,'<br/>';
$myfile = fopen("../txt/testfile_".$template_id."_".$select_value.".txt", "w");
        
$txt = "$template_id\n";

$txt .= trim($new_subject_uris,',')."\n";
$txt .= trim($endpoints,',')."\n";
$txt .=trim($connections,',')."\n";
$txt .=trim($for_similar);
fwrite($myfile, $txt);
fclose($myfile);

$_SESSION['similar']="";

$pyscript = "../py//collectSimilarItem.py";
$python = "C://Python27//python.exe";

$command = escapeshellcmd("$python $pyscript ". "../txt/testfile_".$template_id.'_'.$select_value.".txt");
$output = shell_exec($command);
echo $output;
$_SESSION['similar']=$output;


?>

