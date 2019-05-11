<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once("sparqlConnection.php");
$pibas_fed_sparql_query = "
PREFIX  pibas: <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
PREFIX  rdf:  <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX  rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX  drugbank: <http://bio2rdf.org/drugbank_vocabulary:>
PREFIX  bindingdb: <http://chem2bio2rdf.org/bindingdb/resource/>
PREFIX  kegg: <http://bio2rdf.org/kegg_vocabulary:>
PREFIX  sio:  <http://semanticscience.org/resource/>
PREFIX  cco:  <http://rdf.ebi.ac.uk/terms/chembl#>

SELECT DISTINCT  ?Target ?Dataset
WHERE
  { 
     ?activeSubstance pibas:hasInChiKey 'GHASVSINZRGABV-UHFFFAOYSA-N'.
		?Experiment pibas:activeSubstance ?activeSubstance.
		?Experiment pibas:hasTarget ?Target;
                pibas:IC50 ?ic50value.
		FILTER(?ic50value<300000.0).   
        BIND('PIBAS/CPCTAS' AS ?Dataset).
   }";


$result = $db->query($pibas_fed_sparql_query);
if (!$result) {
    echo "Error: " . $db->errno();
    exit;
}


if (sparql_num_rows($result) > 0) {
   
    while ($row = $result->fetch_array()) {
        $instance_data=$pibas_fed_sparql_query = "
            PREFIX  pibas: <http://cpctas-lcmb.pmf.kg.ac.rs/2012/3/PIBAS#>
            PREFIX  rdf:  <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
            PREFIX  rdfs: <http://www.w3.org/2000/01/rdf-schema#>
            PREFIX  drugbank: <http://bio2rdf.org/drugbank_vocabulary:>
            PREFIX  bindingdb: <http://chem2bio2rdf.org/bindingdb/resource/>
            PREFIX  kegg: <http://bio2rdf.org/kegg_vocabulary:>
            PREFIX  sio:  <http://semanticscience.org/resource/>
            PREFIX  cco:  <http://rdf.ebi.ac.uk/terms/chembl#>

            SELECT DISTINCT  ?predicate ?object
            WHERE
              { 
                 <".$row['Target']."> ?predicate ?object
               }"; 
            $result1 = $db->query($instance_data);
            while ($row1 = $result1->fetch_array()) {
                echo $row1['predicate']." ".$row1['object']."<br/>";
            }
    }

} else {
    echo "Currently do not exist result!";
}
