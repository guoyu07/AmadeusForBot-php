<?php

// These code snippets use an open-source library. http://unirest.io/php
$headers = array('Accept' => 'application/json');
$query = array('apikey' => 'EpDbA3yQmsIAKcF5wA5F9DiIOGoExqhc', 'term' => $airport); 
$response = Unirest\Request::get("https://api.sandbox.amadeus.com/v1.2/airports/autocomplete",$headers, $query);
print_r($response->body);
$response2 = Unirest\Request\Body::json($response->body);


// header("Content-Type: application/json; charset=utf-8");
// echo json_encode($response->body);