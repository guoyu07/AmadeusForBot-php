<?php
// Routes

$app->get('/flight/{search}', function ($request, $response, $args) {
    // Log Query
    $args["search"] = $request->getQueryParams();
    $query_log = array_map('strval', $args["search"]);
    $this->logger->info("Slim-Skeleton '/flight/{search}'route");
    // Pass the query string to the "controler variable"
   $args["search"] = $request->getQueryParams();

   return $this->renderer->render($response, 'search-flight.php', $args);
});


$app->get('/test/{text}', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
   phpinfo();
   // Pass the query string to the "controler variable"
   //return $this->renderer->render($response, 'chatfuel-test.php', $args);
});

// Flight status end point 
$app->get('/flight-status/{type}/{flight_number}', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    return $this->renderer->render($response, 'flight-status.php', $args);
});




$app->get('/image-process/{text}', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    $ImagePath = __DIR__ .'/../src/flight-itinerary-template.png';
    $FontPathRegular =  __DIR__ .'/../templates/fonts/Lato-Regular.ttf';
    $FontPathBold =  __DIR__ .'/../templates/fonts/Lato-Bold.ttf';




    try {

        // Flip the image and output it directly to the browser
        $img = new SimpleImage($ImagePath);
        //STOPS 
        $img->text('2 stop', $FontPathRegular, 24, '#EC1F27', 'top', -6, 228);
        // DEPARTURE TIME
        $img->text('2:40pm', $FontPathBold, 40, '#000000', 'left', 40, 10);
        // ARRIVAL TIME
        $img->text('11:40pm', $FontPathBold, 40, '#000000', 'top', 200, 186);
        // DEPART CITY
        $img->text('DEN', $FontPathRegular, 31, '#B7B7B7', 'top', -268, 263);
        //ARRIVAL CITY
        $img->text('BOG', $FontPathRegular, 31, '#B7B7B7', 'top', 268, 263);
        //DEPARTURE DATE
        $img->text('Nov 10, 2016', $FontPathRegular, 23.5, '#7a7a7a', 'left', 45, 134);
        //ARRIVAL DATE
        $img->text('Nov 20, 2016', $FontPathRegular, 23.5, '#7a7a7a', 'right', -45, 134);
        // OPTION
        $img->text('2', $FontPathRegular, 24, '#FFFFFF', 'top', 303, 63);
        $response->withHeader('Content-Type','image/png');
        $img->output();
    } catch(Exception $e) {
        echo '<span style="color: red;">' . $e->getMessage() . '</span>';
    }





      // Pass the query string to the "controler variable"
    //
});