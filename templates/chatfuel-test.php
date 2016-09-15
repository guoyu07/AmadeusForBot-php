<?php
// Register classes
require_once __DIR__ . '/../src/classes.php';


$chatfuel = new ChatfuelMessage;
$message = $chatfuel->TextCardMessage($text); 
header("Content-Type: application/json");
echo json_encode($message,JSON_UNESCAPED_UNICODE);


?>