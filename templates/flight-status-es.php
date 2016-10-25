<?php 
// Register classes
require_once __DIR__ . '/../src/classes.php';


$flight = new FlightStatus; 
$chatfuel = new ChatfuelMessage;
$helper = new ValidationHelper;
$flightStatusImage = new FlightImage;
$flightData = array();

//------- Language Labels and settings ---------------//
$lang = "es";

$label = array(
	"button_1" => "Navigate to Gate",
	"button_2" => "Subscribe to Alerts",
	"Subtitle" => "Departing from Gate C11"
);

$error = array(
	"Number" => "Lo siento el número de vuelo al parecer es incorrecto: ".$flight_number,
	"Flight" => "Lo siento, no pude encontrar el: ".$flight_number." en el sistema de información de vuelos"
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
	  // Flow XO Message 
	  $message = array ("image" => $flightData["ImageUrl"]);
	  //Chat Fuel Message
	  // $button_1 = $chatfuel->ButtonElement("web_url", "http://bot.airportdigital.com/AirlineBotService/public/map?poi=92", $label["button_1"]);
	  // $button_2 = $chatfuel->ButtonElement("show_block", "Subscribed", $label["button_2"]);

	  // $buttons = array($button_1,$button_2); 
	  // $card = $chatfuel->CardElement($label["Subtitle"],$flightData["ImageUrl"],"",$buttons);
	  // $message = $chatfuel->GalleryMessage(array($card));
	}
}
	// send Message
	  
	  header("Content-Type: application/json");
      echo json_encode($message,JSON_UNESCAPED_UNICODE);
   


