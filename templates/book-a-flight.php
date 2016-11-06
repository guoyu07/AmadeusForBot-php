<?php 

// Register classes
require_once __DIR__ . '/../src/classes.php';

/* Search a return flight
/ This code search for a return flight in amadeus 
/ and return a fb card with a link to the booking site.
*/

	
	

	
 	/**
 	* 
 	*/
 	class Book
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

 	public function bookAflight($params)
 	{

		$helper = new ValidationHelper;  // helper Object
 	 	$chatfuel = new ChatfuelMessage; // message object
 	 	$airport = new Airport; // Airport Object
 	 	$flightSearch = new FlightSearch;  // Flight search object
 	 	$Cardimage = new FlightImage; // Image processing object

 		$this->params = $params;
 		$search = array(
 		 		'origin' => $this->params['origin'],
 		 		'destination' => $this->params['destination'],
 		 		'departure_date' => $this->params['departure_date'],
 		 		'adults' => $this->params['adults'], 
 		 		'currency' => $this->params['currency'],
 		 		// 'include_airlines' => $this->params['airline'],
 		 		'number_of_results' => $this->params['limit'],
 		 		'name' => $this->params['name'],
 		 		'last_name' => $this->params['last_name'],
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
 		

 		// Search for a flight
 		$response = $flightSearch->BestMatch($search);


 		//fix this validation to isset or exists
 		if ($response == "No result found.") {
 			// Next: Send Message there was an error
 			return array("error" => $error["FlightNotFound"]);
 		}else{
 			$index = 1;
 			foreach ($response->results as $key => $value) {

 				// Note : Some times a single fare can apply to 2 itineraries. like this:
 				// ||Results 
 				// 	->fare
 				//  	->option 1
 				//  	->option 2
 				//	->fare
 				//	  	->option 3
 				//
 				// message should contain the fare + the flight data.
 			    
 				//get the fare 
 				$fare  = $value->fare->total_price;
 				
 				// extract the outbound data ocording to the case
 				// case single fare for multiple flights
 				if (sizeof($value->itineraries) > 1 ) {
 					foreach ($value->itineraries as $key => $value) {

 					$flightData = $flightSearch->ExtractOutboundData($value->outbound, $fare);
 			   	    $FlightImage = $Cardimage->GenerateImage($flightData,$index-1);
 			   	    $flightData["ImageUrl"] = $FlightImage["url"];
 			   	    $cardTitle = $this->cardTitle($index,$flightData["fare"]);

 					} 
 				// case single fare single flight
 				}else {
 					//extract data

 					$flightData = $flightSearch->ExtractOutboundData($value->itineraries[0]->outbound, $fare);
 				    // generate image
 				    $FlightImage = $Cardimage->GenerateImage($flightData,$index-1);
 				    $flightData["ImageUrl"] = $FlightImage["url"];
 				    $cardTitle = $this->cardTitle($index,$flightData["fare"]);
 				 	
 				}	
 				
 				$card = $chatfuel->FlightDetailsMessage($flightData,$cardTitle);
 				$cardsArray [$index] = $card;
 				$index++;
 			} // end for each 
 		
 			return $cardsArray;
 		}
 	}
 	}