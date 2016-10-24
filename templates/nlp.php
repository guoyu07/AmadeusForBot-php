<?php 
require_once __DIR__ . '/../src/classes.php';

$nlp = new NLP ; 

$result=($nlp->NLPProcess($message));

header("Content-Type: application/json");
print_r(json_encode($nlp->RedirectToFlow($result)));