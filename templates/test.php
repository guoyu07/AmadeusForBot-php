<?php 
require_once __DIR__ . '/../src/classes.php';

$date = new ValidationHelper;

// $response = $date->DateInSpanish("octubre 50"); 
$response = $date->DateInEnglish("december the 20th"); 
echo "<pre>";
print_r($response);
echo "<pre>";

// header("Content-Type: application/json");
// echo json_encode($response,JSON_UNESCAPED_UNICODE);