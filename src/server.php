<?php
spl_autoload_extensions(".php");
spl_autoload_register(function ($class) {
    $class = str_replace("\\", "/", $class);
    $file = __DIR__ . "/" . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use Logging\Logger;
use Http\HttpRequest;
use Http\HttpResponse;
use Exceptions\interface\UserVisibleException;
use Render\HTMLRenderer;
use Settings\Settings;

try {
    date_default_timezone_set(Settings::env("TIMEZONE"));
    $logger = Logger::getInstance();
    $logger->logRequest();
    $httpRequest = new HttpRequest();
    $routes = include('Routing/routes.php');
    $renderer = null;
    foreach ($routes as $uriPattern => $controller) {
        if (preg_match($uriPattern, $httpRequest->getURI())) {
            $renderer = $controller->assignProcess();
        }
    }
    if (is_null($renderer)) {
        $htmlElems = [
            'title' => '404 Not Found',
            'headline' => '404 Not Found',
            'message' => 'There is no content associated with the specified URL.'
        ];
        $httpResponse = new HttpResponse(new HTMLRenderer(404, 'error', $htmlElems));
    } else {
        $httpResponse = new HttpResponse($renderer);
    }
} catch (UserVisibleException $e) {
    $htmlElems = [
        'title' => '400 Bad Request',
        'headline' => '400 Bad Request',
        'message' => $e->displayErrorMessage()
    ];
    $httpResponse = new HttpResponse(new HTMLRenderer(400, 'error', $htmlElems));
    $logger->logError($e);
} catch (Throwable $e) {
    $htmlElems = [
        'title' => '500 Internal Server Error',
        'headline' => '500 Internal Server Error',
        'message' => 'Internal error, please contact the admin.'
    ];
    $httpResponse = new HttpResponse(new HTMLRenderer(500, 'error', $htmlElems));
    $logger->logError($e);
} finally {
    $httpResponse->send();
    $logger->logResponse($httpResponse);
}
