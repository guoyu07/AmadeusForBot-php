<?php 
$CssPath = 'http://107.20.75.175/AirlineBotService/public/css/';
$imagesPath = 'http://107.20.75.175/AirlineBotService/public/check-out-images/';
$localHost = 'http://localhost/bot/public/';
$AwsHost = 'http://107.20.75.175/AirlineBotService/public/';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Review and Purchase</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,300,700,600" rel="stylesheet" type='text/css'>
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <!--button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button-->
          <a class="navbar-brand" href="#"><img src="<?php echo $imagesPath ?>logo-avianca.png"/></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        </div><!--/.navbar-collapse -->
      </div>
    </nav>
    <div class="container">
      <div class="row header">
            <h1>Review & Purchase</h1>
            <p>Please review your information before purchasing</p>
        </div>
      <div class="row">
        <div class="col-md-12 inner-content">
            <h3>Flights</h3>
            <div class="row  divider">
              <div class="col-xs-4 bold">Outbound:</div>
              <div class="col-xs-8 text-right">BOG > MAD<br>
                <a role="button" data-toggle="collapse" href="#collapseOut" aria-expanded="false" aria-controls="collapseOut" class="stops">Non Stop <img src="<?php echo $imagesPath ?>arrow.png" style="width: 15px;"></a>

              </div>
              <div class="collapse col-xs-12" id="collapseOut">
                  <div class="well">
                    <p>...</p>
                  </div>
              </div>
              <div class="col-xs-4 bold">Inbound:</div>
              <div class="col-xs-8 text-right">MAD > JFK > BOG<br>
                <a role="button" data-toggle="collapse" href="#collapseIn" aria-expanded="false" aria-controls="collapseIn"  class="stops">1 Stop <img src="<?php echo $imagesPath ?>arrow.png" style="width: 15px;"></a>

              </div>
              <div class="collapse col-xs-12" id="collapseIn">
                  <div class="well">
                    <p>...</p>
                  </div>
              </div>
            </div>
            <div class="row  divider">
                <div class="col-xs-4">Subtotal</div>
                <div class="col-xs-8 text-right bold">$800.00 USD</div>
            </div>
            <div class="row divider">
                <div class="col-xs-6">Taxes, Fees and Charges</div>
                <div class="col-xs-6 text-right bold">$52.00 USD</div>
            </div>
            <div class="row total">
                <div class="col-xs-4 bold">TOTAL</div>
                <div class="col-xs-8 text-right red bold">$852.00 USD</div>
            </div>
       </div>
        <div class="col-md-12 inner-content">
          <h3>Extra</h3>
            <div class="row  divider">
                <div class="col-xs-6 red">Seat Upgrade & Trip Extras</div>
                <div class="col-xs-6 text-right bold">$0.00 USD</div>
            </div>
            <div class="row divider">
                <div class="col-xs-6 red">Trip Protection</div>
                <div class="col-xs-6 text-right bold">$0.00 USD</div>
            </div>
            <div class="row total">
                <div class="col-xs-6 bold">TOTAL WITH EXTRAS</div>
                <div class="col-xs-6 text-right red bold">$852.00 USD</div>
            </div>        
        </div>
      </div>
      <div class="row">
            <p class="spacer10" "><a class="red bold" role="button" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
              Change & Cancellation Policies >
            </a>
            <div class="collapse" id="collapse1">
              <div class="well">
                <p class="small-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed eros sodales lorem porttitor dignissim vitae vitae libero. Fusce semper dictum iaculis. Mauris fermentum, tortor quis dictum blandit, leo lacus vestibulum nisl, id dignissim ligula augue at eros. Pellentesque vitae elementum tortor. Curabitur tincidunt nunc sit amet quam blandit, in auctor nunc pellentesque. Aliquam pulvinar, leo fringilla faucibus pellentesque, neque metus fermentum quam, vel efficitur nisi libero non est. Donec nibh sapien, varius id nunc sed, accumsan convallis quam. Proin eu interdum dui, a laoreet arcu. Nam a tempus tellus, nec iaculis ante. Duis sagittis venenatis libero at pharetra. Nulla ac pulvinar nisi.</p>
              </div>
            </div></p>
            <p><a class="red bold" role="button" data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
              Tickets are Nonrefundable >
            </a>
            <div class="collapse" id="collapse2">
              <div class="well">
                <p class="small-text">In ac justo eget justo mattis bibendum nec ut elit. Sed convallis mi sed fringilla commodo. Fusce commodo mauris non risus malesuada tincidunt. Vivamus mattis ut ipsum non malesuada. In eros risus, dictum vitae lorem sit amet, gravida rhoncus magna. Mauris pellentesque facilisis eros, sit amet dignissim elit consequat sit amet. Sed ornare faucibus ultricies. Cras eget aliquam dui, vel tincidunt justo. Nunc condimentum cursus sem, at aliquam est tempor at. Morbi facilisis placerat velit, pharetra rutrum lectus gravida in. Phasellus egestas nulla justo, vel placerat est convallis id. Nunc ut purus ipsum. Integer dictum volutpat lectus in commodo. Nulla metus enim, dignissim at efficitur quis, luctus quis tellus.</p>
              </div>
            </div>
            </p>
            <p class="small-text">Protect your travel investment with valuable Allianz Travel Insurance. Trip protection includes coverage if you cancel or interrupt your trip for reasons like covered illness, injury, layoff, and more.</p><br>
            <a type="button" class="btn btn-default bt-lg" href="<?php echo $localHost ?>confirmation">Enter Payment Info</a>
      </div>

      <footer>
        <p>&copy; 2016 AirportDigital</p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
