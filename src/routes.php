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
    // Pass the query string to the "controler variable"
   return $this->renderer->render($response, 'chatfuel-test.php', $args);
});

$app->get('/image-process/{text}', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
    // Pass the query string to the "controler variable"
   return $this->renderer->render($response, 'image-process.php', $args);
});