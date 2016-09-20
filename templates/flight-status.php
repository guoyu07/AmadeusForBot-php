<?php 
// Register classes
require_once __DIR__ . '/../src/classes.php';


$flight = new FlightStatus; 
$chatfuel = new ChatfuelMessage;
$helper = new ValidationHelper;
$flightStatusImage = new FlightImage;



$flightData = array();

// Get data

$flight_number = $helper->ExtractNumbers($flight_number);
$flight = $flight->SearchFlight($flight_number,$type);
// create image
$FlightImage = $flightStatusImage->GenerateFlightStatusImage($flight);
$flightData["ImageUrl"] = $FlightImage["url"];
//prepare message
$card = $chatfuel->CardElement("Departing in 20 minutes",$flightData["ImageUrl"],"","");
$message = $chatfuel->GalleryMessage($card);
// $message = $chatfuel->ImageAttachment($flightData["ImageUrl"]);




header("Content-Type: application/json");
echo json_encode($message,JSON_UNESCAPED_UNICODE);

