<?php 
// Register classes
require_once __DIR__ . '/../src/classes.php';


$flight = new FlightStatus; 
$chatfuel = new ChatfuelMessage;
$helper = new ValidationHelper;

$flightData = array();

class AbFlightStatus 
{
	public function generateImage($flight)
	{
		$flightStatusImage = new FlightImage;
	     $flight_data = array (
	      "airline_code" => "AB",
	      "status_en" => $flight["status_en"],
	      "status_es" => $flight["status_es"],
	      "flight_number" => $flight["flight_number"],
	      "schedule_time" => $flight["arrival_time"],
	      "estimated_time" => $flight["estimated_time"],
	      "origin" => $flight["origin"],
	      "origin_iata" => $flight["origin_iata"],
	      "destination" => $flight["destination"],
	      "destination_iata" => $flight["destination_iata"],
	     );	

		 $FlightImage = $flightStatusImage->AbFlightStatusImage($flight_data,"en");
		 return $FlightImage;
	}
	
}

