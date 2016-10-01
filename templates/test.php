<?php 
require_once __DIR__ . '/../src/classes.php';

$map = new Map;
$message = new ChatfuelMessage;
$cards= array();
// $response = $date->DateInSpanish("octubre 50"); 
$search = $map->SearchInAirport("starbucks"); 
foreach ($search as $key =>$venue) {
	$venues[$key]= $map->GetVenueRelevantData($venue);
	$cards[$key] = $message->VenueCard($venues[$key]);
}

// echo "<pre>";
// print_r($cards);
// echo "<pre>";
$response =  $message->GalleryMessage($cards);
header("Content-Type: application/json");
echo json_encode($response,JSON_UNESCAPED_UNICODE);