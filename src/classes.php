<?php

require __DIR__ . '/../src/SimpleImage.php';





/**
*  Amadeus Airport Autocomplete API integration,
*  Use: Amadeus API ,  UniRest
*  https://sandbox.amadeus.com/travel-innovation-sandbox/apis/get/airports/autocomplete
*  Methods: 
*   -FirstAirport : Returns (array) the first Airport match.
*   -AllAirports : Returns (array) list of the airports. 
*   -GetCityIATA: Returns the IATA code for a city for a given Airport IATA code.
*/


class Airport{
    
    private $FirstAirportResult;
    private $AllAirportsResult;
    private $AmadeusApiKey = 'EpDbA3yQmsIAKcF5wA5F9DiIOGoExqhc';
    private $CityIATACode; 



    public function FirstAirport($query) {
        // check parameter
        if (!empty($query)) {
            $headers = array('Accept' => 'application/json');
            // set up uri
            $uri = array('apikey' => 'EpDbA3yQmsIAKcF5wA5F9DiIOGoExqhc', 'term' => $query);
            // Do get request
            $response = Unirest\Request::get("https://api.sandbox.amadeus.com/v1.2/airports/autocomplete",$headers, $uri);
            // check  and parse response
           
            if (!empty($response->body)){
               $this->FirstAirportResult = (array)$response->body[0]; 
               $cityIATA["cityIATA"] = $this->GetCityIATA($response->body[0]->value);
               $this->FirstAirportResult=array_merge($this->FirstAirportResult,$cityIATA); 
             }
            else
            {
             $this->FirstAirportResult["error"] = "The Airport: '".$query."' was not found";
            } 
        }
        else {
           $this->FirstAirportResult = array("error"=> "Airport Origin/Destination field is empty");
        }
        return $this->FirstAirportResult;
    }

    public function AllAirports($query) {
        // check parameter
        if (!empty($query)) {
            $headers = array('Accept' => 'application/json');
            // set up uri

            $uri = array('apikey' => 'EpDbA3yQmsIAKcF5wA5F9DiIOGoExqhc', 'term' => $query);
            // Do get request
            $response = Unirest\Request::get("https://api.sandbox.amadeus.com/v1.2/airports/autocomplete",$headers, $uri);
            // check  and parse response
           
            if (!empty($response->body)){
               // parse response
               $this->AllAirportsResult = $response->body;
               //add city IATA code to array
               $cityIATA["cityIATA"] = $this->GetCityIATA($response->body[0]->value);
               array_push($this->AllAirportsResult,$cityIATA); 
               
             }
            else
            {
             $this->AllAirportsResult["error"] = "No results";
            } 
        }
        else {
           $this->AllAirportsResult = array("error"=> "query empty");
        }
        return $this->AllAirportsResult;
    }
    public function GetCityIATA($code){

            if (!empty($code)) {
            $headers = array('Accept' => 'application/json');
            // set up uri
            
            $uri = array('apikey' => 'EpDbA3yQmsIAKcF5wA5F9DiIOGoExqhc');
            
            // Do GET request
            $response = Unirest\Request::get("https://api.sandbox.amadeus.com/v1.2/location/".$code,$headers, $uri);
            // check  and parse response
            if (!empty($response->raw_body)){
              // get rid of the headers and leave the data
              $response = json_decode($response->raw_body);
              $this->CityIATACode = $response->city->code;
             }
            else
            {
             $this->CityIATACode = array("error" => "No results");
            } 

            return $this->CityIATACode;
        }
    }
}

/**
*  Amadeus  Flight Search.
*  Use: Amadeus API ,  UniRest
*  https://sandbox.amadeus.com/travel-innovation-sandbox/apis/get/flights/low-fare-search
*  https://sandbox.amadeus.com/travel-innovation-sandbox/apis/get/flights/affiliate-search
*  Methods: 
*   -BestMatch : Returns (array) of the lowest fare.
*   -AffiliateSearchBestMatch : Returns (array) the best match of the search.
*   -ExtractOutboundData : Returns (array) of the texts required for the card.
*/

class FlightSearch {
    // Variables 
    private $AmadeusApiKey = "EpDbA3yQmsIAKcF5wA5F9DiIOGoExqhc";
    private $FlightSearchResult;
    private $FlightData; 

    // Low fare best match 
    public function BestMatch ($query) {
        $headers = array('Accept' => 'application/json');
        // set up uri    
        $uri = array('apikey' => $this->AmadeusApiKey);
            $uri = array_merge($uri,$query); 

        if (!empty($query)) {    
            // Do GET request
            $results = Unirest\Request::get("https://api.sandbox.amadeus.com/v1.2/flights/low-fare-search",$headers, $uri);
            if ($results->code == 200) {
                //return Object
                
                return $this->FlightSearchResult = $results->body;
                
            }
             else{
                 
                 return $this->FlightSearchResult["error"] = $results->body->message;
            } 
        }
        else {
           return $this->FlightSearchResult = array("error"=> "empty query");
        }   
    }

    // Affilate Search Best Match
    public function AffiliateSearchBestMatch ($query) {
        //Future Dev
    }

    
    // Note: optimize class create a helpers for flight number , date and time.
    public function ExtractOutboundData ($result, $fare) {

        $this->FlightData = array(); 
        
        if ($result) {


           //count number of stops 
            $stops = sizeof($result->flights) -1;

           
            // Get Departure Date and Time
            $DepartureDate = $result->flights[0]->departs_at; 
            //format time 
            $date = new DateTime($DepartureDate);
            $DepartureDate = $date->format('M d, Y');
            $DepartureTime = $date->format('g:i a');
            
            // Get Arrival Date and time 
            // get last item
            
            $ArrivalDate = $result->flights[$stops]->arrives_at; 
            
            //format time 
            $date = new DateTime($ArrivalDate);
            $ArrivalDate = $date->format('M d, Y');
            $ArrivalTime = $date->format('g:i a');
           

           //get flight number of first flight
            $flightNumber = $result->flights[0]->operating_airline." ".$result->flights[0]->flight_number;

           //Get origin Airport 
           $OriginAirport = $result->flights[0]->origin->airport;

           // Get Destination Airport
           $DestinationAirport =  $result->flights[$stops]->destination->airport;
           

           // Get Class 
            $TravelClass = $result->flights[0]->booking_info->travel_class;


         
            $this->FlightData = array(
            'DepartureDate' => $DepartureDate, 
            'DepartureTime' => $DepartureTime,
            'ArrivalDate'  => $ArrivalDate,
            'ArrivalTime' => $ArrivalTime,
            'flightNumber' => $flightNumber,
            'DestinationAirport' =>  $DestinationAirport,
            'OriginAirport' => $OriginAirport,
            'TravelClass' => $TravelClass,
            'fare' => $fare,
            'stops' => $stops
            );
         
         return $this->FlightData;

        } else {
           return $this->FlightData['error'] = 'Results Empty';
        }
    }

} 
/**
*  El dorado  Flight Status.
*  Use: El dorado json flight status,  UniRest
*  http://eldorado.aero/wp-content/themes/hostmev2-child/js/flight_status.json
*  
*  Methods: 
*   -GetFlightStatus : Returns (array) the data and status of a flight  .
*   -SearchFlight : Search in the json array the flight data.
*   -ExtractflightData: Returns (array) the data associated with the card.
*/

class FlightStatus {

public function SearchFlight($FlightNumber, $flightType)
{
    
    $headers = array('Accept' => 'application/json');
    // set up uri    
    $results = Unirest\Request::get("http://eldorado.aero/wp-content/themes/hostmev2-child/js/flight_status.json",$headers);
        if ($results->code == 200 && isset($flightType)) {
            $flightsAvailable = $results->body;
            switch ($flightType) {
                case 'departure':
                    foreach ($flightsAvailable->departures as $key => $value) {
                        if ($value->flight_number == $FlightNumber) {
                            return $value;
                        } 
                    }
                    //flight not found
                    return  $error = array("error"=>"Sorry, flight not found");
                    break;
                
                case 'arrival':
                    $flightsAvailable = $results->body;
                  
                    $list = $flightsAvailable->arrivals;
   
                    foreach($list as $value) {
                        
                        if ($value->flight_number == $FlightNumber) { 
                            return $value;
                        }   
                    }
                    // Flight not found
                    return  $error = array("error"=>"Sorry, flight not found");
                    break;
            }
        }
}

}


/**
*  Validation Helper / Processing
*  Use: Mashape API Date and Time Assistant , UniRest
*  https://montanaflynn-timekeeper---format-dates-and-times.p.mashape.com/format/date
*  Methods: 
*   -DateExtract : Extract and process a text and returns a date International ISO formated.
*   -ValidateFutureDate  : Validate (bool) if a date is in the future or the same day. 
*   -ValidateReturnDate : Validate (bool) depart and return date with the rule depart date must be before the return date 
*   -ValidateArrayFields  : Validate (array) if the required fields for amadeus API are present.
*/
class ValidationHelper {
    private $ProcessedDate;
    private $DateIsCorrect;

    public function DateExtract($textInput) {
        $headers = array('X-Mashape-Key' => '1NpHAmnoBqmshAlkwkvxxaejUTlmp1GfscejsnWFWeb5e7LcX5','Accept' => 'application/json');
        $uri = array('date' => $textInput);
        $response = Unirest\Request::get("https://montanaflynn-timekeeper---format-dates-and-times.p.mashape.com/format/date",$headers,$uri);
        if ($response->code=="200") {
          $this->ProcessedDate["date"] = $response->body->international; 
        }
        else {

            $this->ProcessedDate["error"] = "We could not process the date given";
        }
        
        return $this->ProcessedDate;       
    }

    public function ValidateFutureDate ($UserDate) {
        //defaults to UTC it would be a nice feature to have the user Time zone to make the comparison.

        date_default_timezone_set('UTC');
        $today = date("Y-m-d");
         // your date from  user
        $date = new DateTime($UserDate);
        $today = new DateTime($today);
        // calculate difference
        if ($date > $today ) {
            return true;
        } else {
            return false;
        }
        
 
    }
    public function ValidateReturnDate ($DepartureDate,$ReturnDate) {
        // validate depart date is a future date and then compare the two dates
        if ($this->ValidateFutureDate($DepartureDate)) {
            if ($DepartureDate > $ReturnDate) {
                return false; 
            }
            else {
                return true; 
            }
        }
        else {
            return false;
        }

    }
    public function ValidateArrayFields($FieldsToValidate, $ArrayToValidate)
    {
        $response=  array();
        $errorCount = 0;
        foreach ($FieldsToValidate as $key => $value) {
            if (!array_key_exists($value, $ArrayToValidate)) {
                $errorMessage[$value] = "The parameter ".$value." is empty please verify data";
                $errorCount++;
            } 
        }
        if (isset($errorMessage)) {
            $response['errror'] = $errorMessage;
            return $response;
        } 
        else {
            return $response;
        }
        
    }
}

/**
*  Chatfuel Message Class
*  Use: UniRest
*  https://montanaflynn-timekeeper---format-dates-and-times.p.mashape.com/format/date
*  Methods: 
*   -TextMessage : Parse a text message with an error showing the user input.
*   -GalleryMessage : Parse Multiple Text Cards.
*   -TextCardMessage : Parse a text card with an image and two buttons(optional).
*/

class ChatfuelMessage {

    private $TextMessage = array();
    private $CardMessage;
    private $Message;
    private $attachment; 
    private $PayloadArray;
    private $ButtonsArray;
    private $CardArray;
    private $Card;
    private $ButtonElement;
    private $SubtitleMessage; 
    private $imageAttachment;


    public function TextMessage($message)
    {
       if ($message) {
        $this->TextMessage['text'] = $message; 
        return $this->TextMessage;
       } else {
        return $message = "empty parameter";
       }

    }

    public function ImageAttachment($imageUrl)
    {
    
       if ($imageUrl) {
        $this->PayloadArray = array("url" => $imageUrl); 
        $this->Attachment = array("type" => "image" , "payload" => $this->PayloadArray); 
        $this->imageAttachment = array("attachment" => $this->Attachment);
        return $this->imageAttachment;
       } else {
        return $message = array("error" => "empty image url parameter");
       }

    }
    public function TextCardMessage($text,$buttons)
    {
        $this->ButtonsArray = $buttons;

        $this->PayloadArray = array(
               "template_type" => "button",
                "text"=> $text,
                "buttons" => $this->ChatfuelButtonsArray
                );


        if ($text){
            $this->TextCardMessage["attachment"] = array(
            'type' => "template", 
            "payload" => $this->PayloadArray
            ); 
        return  $this->TextCardMessage;
        } else {
          return $message = "empty message";
        }

    }
    public function GalleryMessage($cards_array)
    {
        /* The Gallery follows this structre
        *    Attachment
        *    ->Payload
        *      ->Elements 
        */

        $this->PayloadArray = array(
        "template_type" => "generic",
        "elements"=> $cards_array,
        );

        $this->attachment = array(
            "type" => "template" , 
            "payload" => $this->PayloadArray
        );

        return $this->Message = array('attachment' => $this->attachment);
    }
    public function FlightDetailsMessage ($flightDetails, $Cardtitle) {
        
        //create buttons
        $button = $this->ButtonElement("web_url", "http://www.avianca.com", "Select");
        $buttons = array ($button);

        //create cards
        $this->SubtitleMessage = "Flight: ".$flightDetails['flightNumber']." -- ".$flightDetails['TravelClass'];
        // Add Fare to the title 
        $Cardtitle = $Cardtitle." (USD $".round($flightDetails['fare']).")";
        $this->Card = $this->CardElement($Cardtitle,$flightDetails['ImageUrl'],$this->SubtitleMessage,$buttons); 
        
        return $this->Card;
    }
   
    // Return an array of a Card element
    public function CardElement($title,$imageUrl,$subtitle,$buttons) {
        $this->Card = array (
        "title" => $title,
        "image_url" => $imageUrl, 
        "subtitle" => $subtitle, 
        "buttons" => $buttons
        );
        return $this->Card;
    }
    
    public function ButtonElement($type, $url, $title)
    {
        $this->ButtonElement = array(
            "type" => $type,
            "url" => $url,
            "title" => $title
        );   
        return $this->ButtonElement;
    }


    public function AssambleElements($cards)
    {
        $this->CardArray = array(); 
        foreach ($cards as $card) {
           array_push($this->CardArray,$card); 
        }
        return $this->CardArray;
    }
}

/**
*  Flight Image
*  Use: Simple Image
*  
*   -GenerateImage : Create the Flight Itinerary Image and return the url.
*/
class FlightImage {


// $FontPathRegular =  __DIR__ . '/../template/fonts/Lato-Regular.ttf';
// $FontPathBold = __DIR__ . '/../template/fonts/Lato-Bold.ttf';



 public function GenerateImage($FlightData,$Option) {

    $ImagePath = './../src/flight-itinerary-template.png';
    $FontPathRegular = './../templates/fonts/Lato-Regular.ttf';
    $FontPathBold =  './../templates/fonts/Lato-Bold.ttf';


    try {
        
        //Create image
        $img = new SimpleImage($ImagePath);
        //STOPS 
        if ($FlightData["stops"] > 1) {
        $img->text($FlightData["stops"]." stops", $FontPathRegular, 24, '#EC1F27', 'top', -6, 228);
        }else{
         $img->text("Direct", $FontPathRegular, 24, '#EC1F27', 'top', -6, 228);   
        }
        
        // DEPARTURE TIME
        $img->text($FlightData["DepartureTime"], $FontPathBold, 40, '#000000', 'left', 38, 10);
        // ARRIVAL TIME
        $img->text($FlightData["ArrivalTime"], $FontPathBold, 40, '#000000', 'right', -40, 10);
        // DEPART CITY
        $img->text($FlightData["OriginAirport"], $FontPathRegular, 31, '#B7B7B7', 'top', -274, 263);
        //ARRIVAL CITY
        $img->text($FlightData["DestinationAirport"], $FontPathRegular, 31, '#B7B7B7', 'top', 278, 263);
        //DEPARTURE DATE
        $img->text($FlightData["DepartureDate"], $FontPathRegular, 23.5, '#7a7a7a', 'left', 45, 134);
        //ARRIVAL DATE
        $img->text($FlightData["ArrivalDate"], $FontPathRegular, 23.5, '#7a7a7a', 'right', -45, 136);
        // OPTION
        $img->text($Option+1, $FontPathRegular, 24, '#FFFFFF', 'top', 310, 65);
        
        //Once Created set path to be saved 

        $ImageResultPath = "./images/result-image-".$Option."-".time().".png" ;
        

        $img->save($ImageResultPath);

        $result["url"] = "http://".$_SERVER['SERVER_NAME']."/AirlineBotService/public/".$ImageResultPath;   

        return  $result;

    } catch(Exception $e) {
        return $result["error"] = $e->getMessage() ;
    }

}
}

