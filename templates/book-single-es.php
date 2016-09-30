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
	$CardTitleOptions = array(
        "0" => "Opción 1:  Mejor Resultado",
        "1" => "Opción 2 : Más Barata",
        "2" => "Opción 3 : Más corta"
 	); 
 	$search = array(
 		'origin' => $origin,
 		'destination' => $destination,
 		'departure_date' => $departure_date,
 		'adults' => $adults, 
 		'currency' => $currency,
 		'include_airlines' => $airline,
 		'number_of_results' => $limit,
 		'name' => $name,
 		'last_name' => $last_name
 	);

 	$error = array(
        "Departure" => "Lo siento, no pude encontrar la ciudad : ".$search["origin"],
        "Destination" => "Lo siento no pude encontrar la ciudad : ".$search["destination"],
        "InvalidDepartureDate" => "Lo siento no pude entender  la fecha de salida : ".$search["departure_date"],
        "InvalidReturnDate" => "Lo siento no pude entender  la fecha de salida : ".$search["return_date"],
        "FutureDepartureDate" => "Lo siento la fecha de salida debe ser en posterior a la fecha actual: ".$search["departure_date"],
        "FutureReturnDate" => "Lo siento la fecha de llegada:".$search["departure_date"]." debe ser posterior a la fecha de salida: ".$search["return_date"],
        "FlightNotFound" => "Lo siento, no pude encontrar un vuelo con tus criterios de busqueda"
 	); 

// 1. Validate Departure and Destination IATA CODE
// 2. Obtain Iata Code.


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

$DepartureDate = $helper->DateInSpanish($search["departure_date"]);

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



$response = $flightSearch->BestMatch($search);
if ($response== "No result found.") {
	// Next: Send Message there was an error
	$message = $chatfuel->TextMessage($error["FlightNotFound"]);
	header("Content-Type: application/json");
	echo json_encode($message,JSON_UNESCAPED_UNICODE);
	return;
}else{
	$index = 0;
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
	   	 	$card[$index] = $chatfuel->FlightDetailsMessage($flightData,$CardTitleOptions[$index]);	
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
		 	$card[$index] = $chatfuel->FlightDetailsMessage($flightData,$CardTitleOptions[$index]);	
		 	$index++;
		}	
	} // end for each 

	//create gallery with the cards created
	$cardsArray = array_merge_recursive($card);			   	
	$message = $chatfuel->GalleryMessage($cardsArray);

	//send Message

	header("Content-Type: application/json");
	echo json_encode($message,JSON_UNESCAPED_UNICODE);
	return;

}




		
			


			
			
	 