<?php
// Routes

$app->get('/airport-search/[{airport}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
   return $this->renderer->render($response, 'hello-world.php', $args);
});

