<?php 
require_once __DIR__ . '/../src/classes.php';

$map = new Map;
$message = new ChatfuelMessage;
$cards= array();

$search = $map->UnderstandQuery($query); 
if (!isset($search["errror"])) {
	$results = $map->SearchInAirport($search); 
	$index = 0;
	foreach ($results as $key =>$venue) {
		if ($index <= 8 ) {
		 $venues[$key]= $map->GetVenueRelevantData($venue);
		 $cards[$key] = $message->VenueCard($venues[$key]);
		 $index ++;
		} else {
			break; 
		}
	}

	$response =  $message->GalleryMessage($cards);
} else {
   $response = $message->TextMessage($search["errror"]);
}

header("Content-Type: application/json");
echo json_encode($response,JSON_UNESCAPED_UNICODE);