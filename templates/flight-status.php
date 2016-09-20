<?php 
// Register classes
require_once __DIR__ . '/../src/classes.php';

echo $flight; 
echo $type; 
$flight = new FlightStatus; 
$flight = $flight->SearchFlight("1400","arrival");



header("Content-Type: application/json");
echo json_encode($flight,JSON_UNESCAPED_UNICODE);

