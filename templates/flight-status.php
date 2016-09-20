<?php 
// Register classes
require_once __DIR__ . '/../src/classes.php';


$flight = new FlightStatus; 

$flight = $flight->SearchFlight($flight_number,$type);



header("Content-Type: application/json");
echo json_encode($flight,JSON_UNESCAPED_UNICODE);

