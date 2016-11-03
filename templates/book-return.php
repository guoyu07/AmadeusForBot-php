<?php

// Register classes
require_once __DIR__ . '/../src/classes.php';

/* Search a return flight
/ This code search for a return flight in amadeus 
/ and return a fb card with a link to the booking site.
*/

	$helper = new ValidationHelper;  // helper Object
	$chatfuel = new ChatfuelMessage; // message object
	$airport = new Airport; // Airport Object
	$flightSearch = new FlightSearch;  // Flight search object
	$Cardimage = new FlightImage; // Image processing object
	

	$message; // var to store results
	$lang = "en";
	$CardTitleOptions = array(
        "0" => "Option 1 : Best Value",
        "1" => "Option 2 : Cheapest",
        "2" => "Option 3 : Shortest"
 	); 
 	$search = array(
 		'origin' => $origin,
 		'destination' => $destination,
 		'departure_date' => $departure_date,
 		'return_date' => $return_date,
 		'adults' => $adults, 
 		'currency' => $currency,
 		'include_airlines' => $airline,
 		'number_of_results' => $limit,
 		'name' => $name,
 		'last_name' => $last_name
 	);

 		$error = array(
        "Departure" => "Sorry I couldnt find your city of origin: ".$search["origin"],
        "Destination" => "Sorry I couldnt find your city of destination: ".$search["destination"],
        "InvalidDepartureDate" => "I couldnt understand your date of your departure : ".$search["departure_date"],
        "InvalidReturnDate" => "I couldnt understand your return date : ".$search["return_date"],
        "FutureDepartureDate" => "Ups, departure date must be after today: ".$search["departure_date"],
        "FutureReturnDate" => "Ups, it seems that return date :".$search["return_date"]." is before departure date: ".$search["departure_date"],
        "FlightNotFound" => "Sorry, I couldnt find a flight with your search criteria "
 	); 

// Validate Departure

$airportDataOrigin = $airport->FirstAirport($search["origin"]);


if (isset($airportDataOrigin["error"])){
	$message = $chatfuel->TextMessage($error["Departure"]);
	header("Content-Type: application/json");
	echo json_encode($message,JSON_UNESCAPED_UNICODE);
	return;
} else {
	$search["origin"] = $airportDataOrigin["cityIATA"];
}

//Validate Destination

$airportDataDestination = $airport->FirstAirport($search["destination"]);

if (isset($airportDataDestination["error"])){
	$message = $chatfuel->TextMessage($error["Destination"]);
	header("Content-Type: application/json");
	echo json_encode($message,JSON_UNESCAPED_UNICODE);
	return;
} else {
	$search["destination"] = $airportDataDestination["cityIATA"];
}


// Validate Departure Date

$DepartureDate = $helper->DateInEnglish($search["departure_date"]);

if (isset($DepartureDate['error']))
{
	// Send Message there was an error
	$message = $chatfuel->TextMessage($error["InvalidDepartureDate"]);
	header("Content-Type: application/json");
	echo json_encode($message,JSON_UNESCAPED_UNICODE);
	return;
} else {

	$DateIsCorrect = $helper->ValidateFutureDate($DepartureDate);

	if (!$DateIsCorrect) {
	 	// Pending : fix the problem with  dates the next year.
	 	$message = $chatfuel->TextMessage($error["FutureDepartureDate"]);
		header("Content-Type: application/json");
		echo json_encode($message ,JSON_UNESCAPED_UNICODE);
		return;
	} else {
		$search['departure_date'] = $DepartureDate;
	}
}



// Validate Return Date

$ReturnDate = $helper->DateInEnglish($search["return_date"]);
 	
// If return date was wrong 

if (isset($ReturnDate['error'])){
	// Next: Send Message there was an error
	$message = $chatfuel->TextMessage($error["InvalidReturnDate"]);
	header("Content-Type: application/json");
	echo json_encode($message,JSON_UNESCAPED_UNICODE);
	return;

} else {
	$DateIsCorrect = $helper->ValidateReturnDate($DepartureDate,$ReturnDate);
	if (!$DateIsCorrect){
	// Next: Send Message there was an error
	$message = $chatfuel->TextMessage($error["FutureReturnDate"]);
	header("Content-Type: application/json");
	echo json_encode($message,JSON_UNESCAPED_UNICODE);
	return;
	} else {
      $search['return_date'] = $ReturnDate;
	}
}

// Search for a flight
$response = $flightSearch->BestMatch($search);


//fix this validation to isset or exists
if ($response == "No result found.") {
	// Next: Send Message there was an error
	$message = $chatfuel->TextMessage($error["FlightNotFound"]);
	header("Content-Type: application/json");
	echo json_encode($message,JSON_UNESCAPED_UNICODE);
	return;
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
	   	    $FlightImage = $Cardimage->GenerateImage($flightData,$index);
	   	    $flightData["ImageUrl"] = $FlightImage["url"];
	   	 	// Card to chatfuel  commented replaced to  Flow XO
	   	    $card[$index] = $flightData;
	   	 	//$card[$index] = $chatfuel->FlightDetailsMessage($flightData,$CardTitleOptions[$index]);
	   	 	$index++;
			} 
		// case single fare single flight
		}else {
			//extract data

			$flightData = $flightSearch->ExtractOutboundData($value->itineraries[0]->outbound, $fare);
		    // generate image
		    $FlightImage = $Cardimage->GenerateImage($flightData,$index);
		    $flightData["ImageUrl"] = $FlightImage["url"];
		 	// Create the message
		 	$card[$index] = $flightData;
		 	// Card to chatfuel  commented replaced to  Flow XO
		 	//$card[$index] = $chatfuel->FlightDetailsMessage($flightData,$CardTitleOptions[$index]);	
		 	$index++;
		}	
	} // end for each 
	
	//create gallery with the cards created
	$cardsArray = array_merge_recursive($card);			   	
	print_r($cardsArray);

	//$message = $chatfuel->GalleryMessage($cardsArray);

	//send Message
	// return $message;

}




		
			


			
			
	 