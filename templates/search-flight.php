<?php

// Register classes
require_once __DIR__ . '/../src/classes.php';


// echo "<pre>";
// print_r($search);
// echo "</pre>";

// message to json to bot
$message;

// Validate search query
	$FieldsToValidate = array(
            "0" => "origin",
            "1" => "destination",
            "2" => "departure_date"
 	); 
 	$CardTitleOptions = array(
            "0" => "Option 1 : Best Value",
            "1" => "Option 2 : Cheapest",
            "2" => "Option 3 : Shortest"
 	); 

	$helper = new ValidationHelper; 
	$errors = $helper->ValidateArrayFields($FieldsToValidate, $search); 
	if ($errors) {
		// Next: Send Message there was an error
		$message ['error'] = $errors;
		header("Content-Type: application/json");
		echo json_encode($message,JSON_UNESCAPED_UNICODE);
	}
	else {
		// Search Departure and Destination IATA CODE
		$airport = new Airport;
		$airportDataOrigin = $airport->FirstAirport($search["origin"]);
		
		// Validate Departure
		if (isset($airportDataOrigin["error"])){
			$message ['error'] = $airportDataOrigin["error"];
			header("Content-Type: application/json");
			echo json_encode($message,JSON_UNESCAPED_UNICODE);
		} else {
			$search["origin"] = $airportDataOrigin["cityIATA"];
		}
		//Validate Destination
		$airportDataDestination = $airport->FirstAirport($search["destination"]);
		if (isset($airportDataDestination["errror"])){
			$message ['error'] = $airportDataDestination["error"];
			header("Content-Type: application/json");
			echo json_encode($message,JSON_UNESCAPED_UNICODE);
		} else {
			$search["destination"] = $airportDataDestination["cityIATA"];
		}
		
		//Validate date query (if exist) 

		$DepartureDate = $helper->DateExtract($search["departure_date"]);
		
		if (isset($DepartureDate['error']))
		{
			// Next: Send Message there was an error
			$message ['error'] = $DepartureDate['error'];
			header("Content-Type: application/json");
			echo json_encode($message,JSON_UNESCAPED_UNICODE);
		} 

		//if One Way test departure is in the future
		else {

		 $DateIsCorrect = $helper->ValidateFutureDate($DepartureDate["date"]); 

		 if (!$DateIsCorrect) {
		 	echo "entra-por-aqui";
		 	// Next: Send Message there was an error
		 	$message ['error'] = "Departure date must be later than actual date";
			header("Content-Type: application/json");
			echo json_encode($message ,JSON_UNESCAPED_UNICODE);

		} elseif (isset($search["return_date"])) { //if it is a return flight validate dates.
		 	
		 	$ReturnDate = $helper->DateExtract($search["return_date"]);
		 	
		 	// If return date was wrong 
		 	
		 	if (isset($ReturnDate['error'])){
				// Next: Send Message there was an error
				$message ['error'] = $ReturnDate['error'];
				header("Content-Type: application/json");
				echo json_encode($message,JSON_UNESCAPED_UNICODE);
			
			} else {
				$DateIsCorrect = $helper->ValidateReturnDate($DepartureDate["date"],$ReturnDate["date"]);
				 if (!$DateIsCorrect){
				 	// Next: Send Message there was an error
				 	$message ['error'] = "Departure date must be later than actual date";
					header("Content-Type: application/json");
					echo json_encode($message,JSON_UNESCAPED_UNICODE);
				 } else {
				 	//All Ok, Do search in Amadeus
				 	$search['departure_date'] = $DepartureDate["date"];
				 	$search['return_date'] = $ReturnDate["date"];
				 	// Return Results
				 	$flightSearch = new FlightSearch; 
					$response = $flightSearch->BestMatch($search);

					//
					$chatfuel = new ChatfuelMessage;
					$Cardimage = new FlightImage;
				   	$index = 0;


				   	// Prepare response for message 
				   	// Some times a single fare can apply to 2 itineraries. like this:
				   	// ||Results 
				   	// 	->fare
				   	//  	->option 1
				   	//  	->option 2
				   	//	->fare
				   	//	  	->option 3
				   	//
				   	// message should contain the fare + the flight data.

				   	foreach ($response->results as $key => $value) {
		   		        
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
				   		
				   		
				   	}
				    
					//create gallery
					$cardsArray = array_merge_recursive($card);			   	
				    $message = $chatfuel->GalleryMessage($cardsArray);

				   	//send Message
				   	
					header("Content-Type: application/json");
				   	echo json_encode($message,JSON_UNESCAPED_UNICODE);

				 }
			} 	
		} else {
			$DateIsCorrect = $helper->ValidateFutureDate($DepartureDate["date"]);
				 if (!$DateIsCorrect){
				 	// Next: Send Message there was an error
				 	$message ['error'] = "Departure date must be later than actual date";
					header("Content-Type: application/json");
					echo json_encode($message,JSON_UNESCAPED_UNICODE);
				 } else {
			
					$search['departure_date'] = $DepartureDate["date"];
			
		 			//All Ok, Do search in Amadeus

			 		// Return Results
			 		$flightSearch = new FlightSearch; 
					$response = $flightSearch->BestMatch($search);


					//Send Message
					$chatfuel = new ChatfuelMessage;
					$Cardimage = new FlightImage;
				   	$index = 0;
				   		// Prepare response for message 
				   	// Some times a single fare can apply to 2 itineraries. like this:
				   	// ||Results 
				   	// 	->fare
				   	//  	->option 1
				   	//  	->option 2
				   	//	->fare
				   	//	  	->option 3
				   	//
				   	// message should contain the fare + the flight data.

				   	foreach ($response->results as $key => $value) {
		   		        
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
				   		
				   		
				   	}
				    
					//create gallery
					$cardsArray = array_merge_recursive($card);			   	
				    $message = $chatfuel->GalleryMessage($cardsArray);

				   	//send Message
				   	
					header("Content-Type: application/json");
				   	echo json_encode($message,JSON_UNESCAPED_UNICODE);
				}


			}
		}
	}
