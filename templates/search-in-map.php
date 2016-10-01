<?php 
require_once __DIR__ . '/../src/classes.php';

$map = new Map;
$message = new ChatfuelMessage;
$cards= array();

$search = $map->SearchInAirport($query); 
foreach ($search as $key =>$venue) {
	$venues[$key]= $map->GetVenueRelevantData($venue);
	$cards[$key] = $message->VenueCard($venues[$key]);
}
$response =  $message->GalleryMessage($cards);

header("Content-Type: application/json");
echo json_encode($response,JSON_UNESCAPED_UNICODE);