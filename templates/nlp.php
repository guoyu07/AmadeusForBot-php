<?php 
require_once __DIR__ . '/../src/classes.php';

$nlp = new NLP ; 

$result=($nlp->NLPProcess($message));

header("Content-Type: application/json");
return(json_encode($nlp->RedirectToFlow($result)));