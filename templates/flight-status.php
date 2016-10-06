<?php 
// Register classes
require_once __DIR__ . '/../src/classes.php';


$flight = new FlightStatus; 
$chatfuel = new ChatfuelMessage;
$helper = new ValidationHelper;
$flightStatusImage = new FlightImage;
$flightData = array();

//------- Language Labels and settings ---------------//
$lang = "en";

$label = array(
	"button_1" => "Navigate to Gate",
	"button_2" => "Subscribe to Alerts",
	"Subtitle" => "Flight Details"
);

$error = array(
	"Number" => "I am sorry, The flight yu provide is incorrect: ".$flight_number,
	"Flight" => "I am sorry, I couldnt find your flight: ".$flight_number." in ".$type."s"
	);

//------- Data Validation ---------------//

// Get data
$type = strtolower($type);
// Translate spanish strings
if ($type == "llegada" || $type == "llegadas") {$type = "arrival";  }
if ($type == "salida" || $type == "salidas")  {$type = "departure"; }


$flight_number = rtrim($flight_number);
$flight_number = $helper->ExtractFlightNumbers($flight_number);


if (is_array($flight_number)) {
	//if it is an array it contains an error
	$message = $chatfuel->TextMessage($error["Number"]);
} else {
	//search flight
	$flight = $flight->SearchFlight($flight_number,$type);

	if (is_array($flight)) {
		//if it is an array it contains an error
		$message = $chatfuel->TextMessage($error["Flight"]);
	} else {
	  // create image
	  $FlightImage = $flightStatusImage->GenerateFlightStatusImage($flight,$lang);
	  $flightData["ImageUrl"] = $FlightImage["url"];
	  //prepare message
	  $button_1 = $chatfuel->ButtonElement("web_url", "http://eldorado2016.wpengine.com/en/about/maps/", $label["button_1"]);
	  $button_2 = $chatfuel->ButtonElement("web_url", "http://eldorado2016.wpengine.com/en/", $label["button_2"]);

	  $buttons = array($button_1,$button_2); 
	  $card = $chatfuel->CardElement($label["Subtitle"],$flightData["ImageUrl"],"",$buttons);
	  $message = $chatfuel->GalleryMessage(array($card));
	}
}
	// send Message
	  
	  header("Content-Type: application/json");
      echo json_encode($message,JSON_UNESCAPED_UNICODE);
   


