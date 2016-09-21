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

    <title>Traveller Info</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo $AwsHost ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $AwsHost ?>css/style.css" rel="stylesheet">
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
            <h1>Traveller Info</h1>
            <a class="red passport"><img src="<?php echo $imagesPath ?>icon-passport.png"> Scan Passport</a>
        </div>
      <div class="row">
        <div class="col-md-12 inner-content traveller">
            <div class="row marginnormal">
              <div class="col-md-3 col-xs-6 boxed"><?php echo ucfirst($name)?></div>
              <div class="col-md-3 col-xs-6 boxed borderless-resp">Andrés</div>
              <div class="col-md-3 col-xs-6 boxed"><?php echo ucfirst($last_name) ?></div>
              <div class="col-md-3 col-xs-6 boxed borderless">June 27, 1990</div>
              <div class="col-md-3 col-xs-6 boxed">PE087571</div>
              <div class="col-md-3 col-xs-6 boxed borderless-resp">Colombia</div>
              <div class="col-md-3 col-xs-6 boxed">September 8, 2013</div>
              <div class="col-md-3 col-xs-6 boxed borderless">September 8, 2023</div>
            </div>
            <div class="row marginnormal">
              <div class="col-md-6 col-xs-12 boxed borderless-resp gray">Email Address</div>
              <div class="col-md-6 col-xs-12 boxed borderless  gray">Frequent Flyer Number</div>
            </div>
            <div class="row marginnormal">
              <div class="col-md-2 col-xs-4 boxed borderless gender">Gender</div>
              <div class="col-md-2 col-xs-4 boxed borderless gender">
                <span class="radio-text"><input type="radio" name="gender" value="male"> Male</span>
              </div>
              <div class="col-md-2 col-xs-4 boxed borderless-resp gender">
                <span class="radio-text"><input type="radio" name="gender" value="female"> Female</span>
              </div>
              <div class="col-md-4 col-xs-4 boxed borderless pets">Will you be traveling with pets?</div>
              <div class="col-md-1 col-xs-4 boxed borderless pets">
                <span class="radio-text"><input type="radio" name="pets" value="yes"> Yes</span>
              </div>
              <div class="col-md-1 col-xs-4 boxed borderless pets">
                <span class="radio-text"><input type="radio" name="pets" value="no"> No</span>
              </div>
            </div>
       </div>
       <div class="col-md-12">
       <h2>Trip Insurance (Optional)</h2>
       </div>
       <div class="col-md-12 inner-content">
            <p class="small-text">Protect your travel investment with valuable Allianz Travel Insurance. Trip protection includes coverage if you cancel or interrupt your trip for reasons like covered illness, injury, layoff, and more</p>
            <div class="border-updown">
              <span class="radio-text" style="margin-bottom:10px;"><input type="radio" name="insurance" value="yes"> Yes, add trip protection for $35.55</span>
              <span class="radio-text"><input type="radio" name="insurance" value="no"> No, thanks.</span>
            </div>
            <p class="small-text">Insurance benefits are underwritten by either BCS Insurance Company or Jefferson Insurance Company, depending on insured's state of residence. AGA Service Company is the licensed producer and administrator of this plan. Important terms, conditions and exclusions apply.</p>
            <a class="red bold" role="button" href="#">
              Learn More
            </a>       
        </div>
      </div>
      <div class="row">
            <a type="button" class="btn btn-default bt-lg" href="<?php echo $localHost ?>review-purchase"> Book Now</a>
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
