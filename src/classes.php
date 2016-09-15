<?php
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
*   -AllFlightMatches  : Returns (array) all of the results of the query. 
*   -AffiliateSearchBestMatch : Returns (array) the best match of the search.
*   -AllAffilliateSearch: Returns (array) all of the results of the affiliate flight search.
*/

class FlightSearch {
    // Variables 
    private $AmadeusApiKey = "EpDbA3yQmsIAKcF5wA5F9DiIOGoExqhc";
    private $FlightSearchResult;

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
    // Low fare all results
    public function AllFlightMatches ($query) {
         //Future Dev
    }
    // Affilate Search Best Match
    public function AffiliateSearchBestMatch ($query) {
        //Future Dev
    }

    //Affilliate search all results
    public function AllAffilliateSearch ($query) {
             //Future Dev
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

    private $ChatfuelTextMessage = array();  
    private $ChatfuelTextCardMessage; 
    private $ChatfuelPayloadArray;
    private $ChatfuelButtonsArray;

    public function TextMessage($message)
    {
       if ($message) {
        $this->ChatfuelTextMessage['text'] = $message; 
        return $this->ChatfuelTextMessage;
       } else {
        return $message = "empty parameter";
       }

    }
    public function TextCardMessage($text,$buttons)
    {
        $this->ChatfuelButtonsArray = $buttons;

        $this->ChatfuelPayloadArray = array(
               "template_type" => "button",
                "text"=> $text,
                "buttons" => $this->ChatfuelButtonsArray
                );


        if ($text){
            $this->ChatfuelTextCardMessage["attachment"] = array(
            'type' => "template", 
            "payload" => $this->ChatfuelPayloadArray
            ); 
        return  $this->ChatfuelTextCardMessage;
        } else {
          return $message = "empty message";
        }

    }
    public function GalleryMessage($cards)
    {
        # code...
    }
}


