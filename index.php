<?php

//TODO: phpDocumentor

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

//Loading environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

/* 
* Doctrine ORM
*/
$config = Doctrine\ORM\ORMSetup::createAttributeMetadataConfiguration(
    paths: array(__DIR__."/src/Entities"),
    isDevMode: $_ENV['TYPE'] == 'dev' ? true : false,
);

$conn = array(
    'driver' => 'pdo_mysqli',
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'host' => 'localhost'
);

$entityManager = Doctrine\ORM\EntityManager::create($conn, $config);

/* 
* Routing
*/
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
	$r->addRoute('GET', '/', function () {
		echo 'Hello, World!';
	});

    //Users
    $r->addRoute('GET', '/users', function ()  { echo 'Users'; });
    
    $r->addRoute('GET', '/user/{id:\d+}', function () {});
    
    $r->addRoute('DELETE', '/user/{id:\d+}', function () {});
    
    $r->addRoute('PUT', '/user/{id:\d+}', function () {});

    $r->addRoute('PATCH', '/user/{id:\d+}', function () {});

    //Books
    $r->addRoute('GET', '/books', function ()  {});
    
    $r->addRoute('GET', '/book/{id:\d+}', function () {});
    
    $r->addRoute('DELETE', '/book/{id:\d+}', function () {});
    
    $r->addRoute('PUT', '/book/{id:\d+}', function () {});

    $r->addRoute('PATCH', '/book/{id:\d+}', function () {});

    //Genres
    $r->addRoute('GET', '/genres', function ()  {});
    
    $r->addRoute('GET', '/genre/{id:\d+}', function () {});
    
    $r->addRoute('DELETE', '/genre/{id:\d+}', function () {});
    
    $r->addRoute('PUT', '/genre/{id:\d+}', function () {});

    $r->addRoute('PATCH', '/genre/{id:\d+}', function () {});

    //Journal
    $r->addRoute('GET', '/journal', function ()  {});

    //$r->addRoute('GET', '/journal/{id:\d+}', function ()  {});
    
    $r->addRoute('PUT', '/journal/{id:\d+}', function () {});

    $r->addRoute('PATCH', '/journal/{id:\d+}', function () {});
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];


$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
    	http_response_code(404);
    	echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $handler(...$vars);
        break;
}