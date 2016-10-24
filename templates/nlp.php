<?php 
require_once __DIR__ . '/../src/classes.php';

$nlp = new NLP ; 

$result=($nlp->NLPProcess("Cuando Llega el vuelo AV 12 de Miami?"));

header("Content-Type: application/json");
print_r(json_encode($nlp->RedirectToFlow($result)));