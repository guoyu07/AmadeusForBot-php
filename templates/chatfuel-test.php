<?php
// Register classes
require_once __DIR__ . '/../src/classes.php';


$chatfuel = new ChatfuelMessage;

      $button1 = array(
            "type"=>"web_url",
            "url" => "http://www.google.com",
            "title" => "Book Now!"
            );
      $button2 =array (
            "type"=>"web_url",
            "url" => "http://www.google.com",
            "title" => "Book Now 2!"
        ); 
     $buttons = array();
     array_push($buttons, $button1);
     array_push($buttons, $button2); 
     $message = $chatfuel->TextCardMessage($text,$buttons); 
header("Content-Type: application/json");
print_r(json_encode($message));


?>