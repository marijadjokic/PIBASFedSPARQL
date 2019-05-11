<?php

require_once( "sparqllib.php" );
$sparqlEndPoint = "http://localhost:3030/PIBAS/query";
$sparqlEndPointInsert = "http://localhost:3030/PIBAS/update";
$db = sparql_connect($sparqlEndPoint);

