<?php 
require_once __DIR__ . '/../src/classes.php';

$map = new Map;
$message = new ChatfuelMessage;
$cards= array();

$search = $map->UnderstandQuery($query); 
if (!isset($search["errror"])) {
	$results = $map->SearchInAirport($search); 
	foreach ($results as $key =>$venue) {
		$venues[$key]= $map->GetVenueRelevantData($venue);
		$cards[$key] = $message->VenueCard($venues[$key]);
	}

	$response =  $message->GalleryMessage($cards);
} else {
   $response = $message->TextMessage($search["errror"]);
}

header("Content-Type: application/json");
echo json_encode($response,JSON_UNESCAPED_UNICODE);