<?php 

// Register classes
require_once __DIR__ . '/../src/classes.php';

/* Get a flight status
/ This code search for a flight in El Dorado flight information system 
/ return flight data to be parsed on a image card
*/
 


class FlightStatusController 
{
	
	public function searchFlight($flight_number)
	{
		$flight = new FlightStatus; 
		$chatfuel = new ChatfuelMessage;
		$helper = new ValidationHelper;
		$flightStatusImage = new FlightImage;
		$flightData = array();

		//------- Language Labels and settings ---------------//
		$lang = "en";

		$label = array(
			"button_1" => "Navigate to Gate",
			"button_2" => "Subscribe to alerts",
			"Subtitle" => "Departing from Gate C11"
		);
		$url = array(
			"button_1" => "http://bot.airportdigital.com/AirlineBotService/public/map?poi=92"
		);

		$error = array(
			"Number" => "I am sorry, The flight yu provide is incorrect: ".$flight_number,
			"Flight" => "I am sorry, I couldnt find your flight: ".$flight_number
			);

		//------- Data Validation ---------------//


		$flight_number = rtrim($flight_number);
		$flight_number = $helper->ExtractFlightNumbers($flight_number);


		if (is_array($flight_number)) {
			//if it is an array it contains an error
			return $message["error"] = $error["Number"];
		} else {
			//search flight
			$flight = $flight->SearchFlight($flight_number);

			if (is_array($flight)) {
				//if it is an array it contains an error
				return $message["error"] = $error["Flight"];
			} else {
			  // create image
			  $FlightImage = $flightStatusImage->GenerateFlightStatusImage($flight,$lang);
			  
			  
			  //prepare message
			  $button_1 = array( "url"=>"http://bot.airportdigital.com/AirlineBotService/public/map?poi=92", "text" => "Go to Gate");
			  $button_2 = array("text" => "Suscribe to Alerts");

			  $buttons = array($button_1,$button_2); 
			  $message = array (
			  	"image" => $FlightImage["url"],
			  	"labels" => $label,
			  	"flight_data" => $flight,
			  	"url" => $url

			  	); 
			}
		}
			// send Message
			  
			  return $message;
		   
	}
}