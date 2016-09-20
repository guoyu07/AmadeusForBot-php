<?php 
// Register classes
require_once __DIR__ . '/../src/classes.php';


$flight = new FlightStatus; 
$chatfuel = new ChatfuelMessage;

$message = $chatfuel->ImageAttachment("https://hd.unsplash.com/photo-1470229722913-7c0e2dbbafd3");

$flight = $flight->SearchFlight($flight_number,$type);



header("Content-Type: application/json");
echo json_encode($message,JSON_UNESCAPED_UNICODE);

