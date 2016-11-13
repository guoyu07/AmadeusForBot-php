<?php 

// Register classes
require_once __DIR__ . '/../src/classes.php';


/* Search a fight on aero berlin api.
/ This code search for a return flight in amadeus 
/ and return a fb card with a link to the booking site.
*/

class BookAb
{

	public $message; // var to store results
	public $lang = "en";
	public $CardTitleOptions = array(
		"0" => "Option 1 : Best Value",
		"1" => "Option 2 : Cheapest",
		"2" => "Option 3 : Shortest"
		); 
	private $params;
	private $fare;
	private $title;


	public function cardTitle($index, $fare)
	{
		$this->fare = $fare;

		switch ($index) {
			case 1:
			$this->title = "Option ".$index." : Best Value (USD ".$this->fare .")";
			return $this->title;

			case 2:
			$this->title = "Option".$index." : Cheapest (USD ".$this->fare .")";
			return $this->title;

			case 1:
			$this->title = "Option".$index." : Shortest (USD ".$this->fare.")";
			return $this->title;

			default:
			$this->title = "Option".$index." : Best Value (USD ".$this->fare .")";
			return $this->title;
		}
	}
	public function searchAvailabilities($params)
	{
		$configs = include('./../src/config.php');

		$headers = array('Accept' =>  'application/json', 'Authorization' => $configs['aero_berlin'] );
	// set up uri    
		$uri = array(
			"filter%5Bdeparture%5D" => $params['origin'],
			"filter%5Bdestination%5D" => $params['destination'],
			"fields%5Bavailabilities%5D" => "next_outbound_flight_date%2Cprevious_outbound_flight_date%2Crandom_id%2Cdeparture%2Cdestination",
			"include" => "combinations",
			"sort" => "random_id",
			"page%5Bnumber%5D" => "1",
			"page%5Bsize%5D" => "100"
			);

		if (!empty($params)) {    
	// Do GET request
			$this->results = Unirest\Request::get(
				"https://xap.ix-io.net/api/v1/airberlin_lab_2016/availabilities",$headers, $uri
				);
			if ($this->results->code == 200) {
				return $this->results->body->combinations[0];
			} else {
				return $this->message['error'] = "Server Error";
			}

		} else {
			return $this->message['error'] = "Error: Empty query";
		}
	}
	public function searchFlight($params)
	{

	$helper = new ValidationHelper;  // helper : date mostly 
	$airport = new Airport; // Airport Object
	$Cardimage = new FlightImage; // Image processing object

	$this->params = $params;


	$search = array(
		'origin' => $this->params['origin'],
		'destination' => $this->params['destination'],
		'departure_date' => $this->params['departure_date'],
		'return_date' => $this->params['return_date'],
		'adults' => "1", 
		'currency' => "EUR",
	    // 'include_airlines' => $this->params['airline'],
		'isReturn' => $this->params['isReturn']
		);

	$error = array(
		"Departure" => "Sorry I couldnt find your city of origin: ".$search["origin"],
		"Destination" => "Sorry I couldnt find your city of destination: ".$search["destination"],
		"InvalidDepartureDate" => "I couldnt understand your date of your departure : ".$search["departure_date"],
		"FutureDepartureDate" => "Ups, departure date must be after today: ".$search["departure_date"],
		"FlightNotFound" => "Sorry, I couldnt find a flight with your search criteria "
		); 	 	


	$airportDataOrigin = $airport->FirstAirport($search["origin"]);


	if (isset($airportDataOrigin["error"])){
		return array("error" => $error["Departure"]);
	} else {
		$search["origin"] = $airportDataOrigin["cityIATA"];
	}

	//Validate Destination

	$airportDataDestination = $airport->FirstAirport($search["destination"]);

	if (isset($airportDataDestination["error"])){
		return array("error" => $error["Destination"]);
	} else {
		$search["destination"] = $airportDataDestination["cityIATA"];
	}


	// Validate Departure Date

	$DepartureDate = $helper->DateInEnglish($search["departure_date"]);

	if (isset($DepartureDate['error']))
	{
	// Send Message there was an error
		return array("error" => $error["InvalidDepartureDate"]);
	} else {

		$DateIsCorrect = $helper->ValidateFutureDate($DepartureDate);

		if (!$DateIsCorrect) {
	// Pending : fix the problem with  dates the next year.
			return array("error" => $error["FutureDepartureDate"]);
		} else {
			$search['departure_date'] = $DepartureDate;
		}
	}



	// Validate Return Date
	if ($search["isReturn"] == "true" || !empty($this->params["return_date"])) {

	//setup errors and variables
		$search['return_date'] = $this->params['return_date'];
		$ReturnDate = $helper->DateInEnglish($search["return_date"]);
		$error["InvalidReturnDate"] = "I couldnt understand your return date : ".$search["return_date"];
		$error["FutureReturnDate"] = "Ups, it seems that return date :".$search["return_date"]." is before departure date: ".$search["departure_date"];

	// If return date was wrong 

		if (isset($ReturnDate['error'])){
	// Next: Send Message there was an error
			return array("error" => $error["InvalidReturnDate"]);

		} else {
			$DateIsCorrect = $helper->ValidateReturnDate($DepartureDate,$ReturnDate);
			if (!$DateIsCorrect){
	// Next: Send Message there was an error
				return array("error" => $error["FutureReturnDate"]);
			} else {
				$search['return_date'] = $ReturnDate;
			}
		}
	} 


// Search for a flight using AeroBerlinApi. 


		$response =  $this->searchAvailabilities($search);
		var_dump($response);
			die();

		
	//fix this validation to isset or exists
		if ($response == "No result found.") {
			// Next: Send Message there was an error
			return array("error" => $error["FlightNotFound"]);
		} else {
			$data = json_decode(json_encode($response),true);
			
			$flightData = array (
				"stops" => "1", 
				"DepartureTime" => "2:00P", 
				"ArrivalTime" => "2:00P", 
				"OriginAirport" => "TXL", 
				"DestinationAirport" => "DUS", 
				"ArrivalDate" => "Nov, 22 2016"
			);


			foreach ($data["onward_flight_info"]["passenger_pricing"] as  $key => $value) {
				print_r( $key."\n");
		    	if ($key == "pricing") {
		    		$flightData ["fare"] = $value["@total"];
		    	}
		    	if ($key == "segment_infos"){
		    		$date = new DateTime($value["@flight_date"]);
					echo $date->format('Y-m-d');
					die();
		    		$flightData ["InternalFlightNo"] = $value["@internal_flight_no"];
		    		$flightData ["FareBaseCode"] = $value["@fare_base_code"];
		    		$flightData ["DepartureDate"] = strtotime($value["@flight_date"]);
		    	}

			}
			
			return $flightData;



			}
	}
} // end of class.