<?php

use Logging\Logger;
use Logging\LogLevel;
use Request\RequestURI;

spl_autoload_extensions(".php");
// autoloadはこのファイルを実行するプロセスの作業ディレクトリを基準にする
spl_autoload_register(function ($class) {
    $class = str_replace("\\", "/", $class);
    $file = $class . '.php';
    // file_put_contents(__DIR__ . "/../test/debug.txt", $file);
    // echo $file;
    if (file_exists($file)) {
        require_once $file;
    }
});

try {
    $logger = Logger::getInstance();
    $logger->logRequest();

    $requestURI = new RequestURI();
    $uriTopDir = $requestURI->getTopDirectory();

    $routes = include('Routing/routes.php');
    if (!isset($routes[$uriTopDir])) {
        http_response_code(404);
        echo "404 Not Found: The requested route was not found on this server.";
    }

    $renderer = $routes[$uriTopDir]($requestURI);

    foreach ($renderer->getFields() as $name => $value) {
        $sanitized_value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        if ($sanitized_value && $sanitized_value === $value) {
            header("{$name}: {$sanitized_value}");
            header("Access-Control-Allow-Origin: *");
        } else {
            http_response_code(500);
            print("Failed setting header - original: '$value', sanitized: '$sanitized_value'");
            exit;
        }
        print($renderer->getContent());
    }
} catch (Throwable $e) {
    $logger->log(LogLevel::ERROR, $e->getMessage() . PHP_EOL . $e->getTraceAsString());
    http_response_code(500);
    print("Internal error, please contact the admin.<br>");
}
