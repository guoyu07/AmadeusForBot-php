<?php


require_once __DIR__ . '/../src/SimpleImage.php';
$ImagePath = __DIR__ .'/../src/flight-itinerary.png';
$FontPathRegular =  __DIR__ .'/fonts/Lato-Regular.ttf';
$FontPathBold =  __DIR__ .'/fonts/Lato-Bold.ttf';




try {

 
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

    return $response;

 
} catch(Exception $e) {
    echo '<span style="color: red;">' . $e->getMessage() . '</span>';
}
