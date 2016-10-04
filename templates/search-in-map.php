<?php 
require_once __DIR__ . '/../src/classes.php';

$map = new Map;
$message = new ChatfuelMessage;
$cards= array();

$search = $map->UnderstandQuery($query);

if (!isset($search["error"])) {
	$results = $map->SearchInAirport($search["poi"]); 
	$index = 0;

	foreach ($results as $key =>$venue) {
		if ($index <= 8 ) {
		 $venues[$key]= $map->GetVenueRelevantData($venue);
		 if (isset($search['gate'])|| isset($search['building'])) { // filter data if theres any filter
		 	
		 	//filter card if it does not contain any value with the filter
		 	if (isset($search['gate'])) {
		 		if ($map->FilterPoi($venues[$key],$search['gate'],"gate")) {
		 		 	$cards[$key] = $message->VenueCard($venues[$key]);
		            $index ++;
		 		 }  
		 	} elseif (isset($search['building'])) {
		 		if ($map->FilterPoi($venues[$key],$search['building'],"building")) {
		 		 	$cards[$key] = $message->VenueCard($venues[$key]);
		            $index ++;
		 		 } 
		 	}
		 } else {
		 	$cards[$key] = $message->VenueCard($venues[$key]);
		    $index ++;
		 }
		} else {
			break; 
		}
	}
 	if 	(count($cards) > 0) { 
	$response =  $message->GalleryMessage($cards);
	} else {
		$response = $message->TextMessage("Sorry I couldnt find a place with your criteria");
	}
} else {
   $response = $message->TextMessage($search["error"]);
}

header("Content-Type: application/json");
echo json_encode($response,JSON_UNESCAPED_UNICODE);