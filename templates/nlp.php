<?php 
require_once __DIR__ . '/../src/classes.php';

$nlp = new NLP ; 
echo "<pre>";
$result=($nlp->NLPProcess("Cuando Llega el vuelo AV 12 de Miami?"));
print_r($nlp->RedirectToFlow($result));
echo "</pre>";