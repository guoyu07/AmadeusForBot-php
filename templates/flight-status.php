<?php 
// Register classes
require_once __DIR__ . '/../src/classes.php';


$flight = new FlightStatus; 
$chatfuel = new ChatfuelMessage;
$helper = new ValidationHelper;
$flightStatusImage = new FlightImage;



$flightData = array();

// Get data
$type = strtolower($type);
$flight_number = rtrim($flight_number);
$flight_number = $helper->ExtractFlightNumbers($flight_number);


if (is_array($flight_number)) {
	//if it is an array it contains an error
	$message = $chatfuel->TextMessage($flight_number['error']);
} else {
	//search flight
	$flight = $flight->SearchFlight($flight_number,$type);

	if (is_array($flight)) {
		//if it is an array it contains an error
		$message = $chatfuel->TextMessage($flight['error']);
	} else {
	  // create image
	  $FlightImage = $flightStatusImage->GenerateFlightStatusImage($flight);
	  $flightData["ImageUrl"] = $FlightImage["url"];
	  //prepare message
	  $buttons = $chatfuel->ButtonElement("web_url", "http://eldorado2016.wpengine.com/en/about/maps/", "Go to Gate");
	  $card = $chatfuel->CardElement("Departing in 20 minutes",$flightData["ImageUrl"],"",array($buttons));
	  $message = $chatfuel->GalleryMessage(array($card));
	}
}
	// send Message
	  header("Content-Type: application/json");
      echo json_encode($message,JSON_UNESCAPED_UNICODE);


