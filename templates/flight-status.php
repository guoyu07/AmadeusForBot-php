<?php 
// Register classes
require_once __DIR__ . '/../src/classes.php';


$flight = new FlightStatus; 
$chatfuel = new ChatfuelMessage;
$helper = new ValidationHelper;
$flightStatusImage = new FlightImage;


//------
$flightData = array();
$FlightImage = $flightStatusImage->GenerateFlightStatusImage($flightData);
$flightData["ImageUrl"] = $FlightImage["url"];







//-------

$message = $chatfuel->ImageAttachment($flightData["ImageUrl"]);

$flight_number = $helper->ExtractNumbers($flight_number);

$flight = $flight->SearchFlight($flight_number,$type);



header("Content-Type: application/json");
echo json_encode($message,JSON_UNESCAPED_UNICODE);

