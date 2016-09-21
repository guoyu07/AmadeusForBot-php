<?php 
$CssPath = 'http://107.20.75.175/AirlineBotService/public/css/';
$imagesPath = 'http://107.20.75.175/AirlineBotService/public/check-out-images/';
$localHost = 'http://localhost/bot/public/';
$AwsHost = 'http://bot.airportdigital.com/AirlineBotService/public/';

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

    <title>Booking Confirmation</title>

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
            <h1>Booking confirmed!</h1>
        </div>
      <div class="row">
        <div class="col-md-12 inner-content padding40 text-center">
          <p>Your booking with number<br>
          <b>#2342353456</b>is confirmed!</p>
          <p>An email receipt has been emailed to<br>
          <span class="red">juanrt@gmail.com.</span></p>
       </div>
      </div>
      <div class="row">
            <a type="button" class="btn btn-default bt-lg btn-blue" href="https://www.messenger.com/t/280583632340336"><img src="<?php echo $imagesPath ?>messenger.png">Back to Messenger</a>
      </div>


      <footer>
        <p>&copy; 2016 AirportDigital</p>
      </footer>
    </div> <!-- /container -->

    <script>
    function goBack() {
    window.history.go(-4);
    }
    </script>

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
