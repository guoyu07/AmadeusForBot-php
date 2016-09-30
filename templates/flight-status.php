<?php 
// Register classes
require_once __DIR__ . '/../src/classes.php';


$flight = new FlightStatus; 
$chatfuel = new ChatfuelMessage;
$helper = new ValidationHelper;
$flightStatusImage = new FlightImage;
$lang = "en";
$label = array(
	"button_1" => "Go to Gate",
	"button_2" => "Suscribe",
	"Subtitle" => " Flight Details"
	);
$label_es = array(
	"button_1" => "Ir a puerta de embarque",
	"button_2" => "Suscribirse",
	"Subtitle" => "Dettalles del Vuelo"
);
$flightData = array();

// Get data
$type = strtolower($type);
// Translate spanish strings
if ($type == "llegada") {$type = "arrival";  $lang = "es"; }
if ($type == "salida") {$type = "departure"; $lang = "es"; }
if ($lang = "es") {$label = $label_es;}

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


