<?php

//TODO: phpDocumentor
//TODO: phpUnit

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

//Loading environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

/* 
* Doctrine ORM
*/
$config = Doctrine\ORM\ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . DIRECTORY_SEPARATOR . "src/Model"],
    isDevMode: $_ENV['TYPE'] == 'dev' ? true : false,
);


$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . DIRECTORY_SEPARATOR . 'db.sqlite'
);

$entityManager = Doctrine\ORM\EntityManager::create($conn, $config);

/* 
* Routing
*/
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use (&$entityManager) {
	$r->addRoute('GET', '/', function () {
		include __DIR__ . DIRECTORY_SEPARATOR . "html" . DIRECTORY_SEPARATOR . 'index.html';
        exit();
	});

    $entities = ['user', 'genre', 'book', 'record'];

    foreach ($entities as &$entity) {
        $r->addRoute('GET', '/' . $entity, function () use ($entityManager, $entity) {
            $offset = intval( $_GET['offset'] ?? 0 );
            $limit = intval( $_GET['limit'] ?? 10 );

            $entity = ucfirst($entity);
            $entityClass = 'App\\Model\\' . $entity;

            $users = $entityManager->getRepository($entityClass)->all($offset, $limit);
            
            $serializerClass = 'App\\Serializers\\' . $entity . 'Serializer';
            $collection = new Tobscure\JsonApi\Collection($users, new $serializerClass);

            $document = new Tobscure\JsonApi\Document($collection);

            $url = "http" . ( !empty($_SERVER['HTTPS'] ) ? "s":"") . "://" //protocol
                    . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

            $document->addMeta('total', count($users));
            $document->addLink('self', $url);

            $document->addPaginationLinks( //Pagination links
                $url, // The base URL for the links
                $_GET,    // The query params provided in the request
                $offset,    // The current offset
                $limit,    // The current limit
                count($users) // The total number of results
            );

            // Output the document as JSON.
            return json_encode($document, JSON_PRETTY_PRINT);
        });

        $r->addRoute('GET', '/' . $entity . '/{id:\d+}', function () {

        });
        
        $r->addRoute('DELETE', '/' . $entity .  '/{id:\d+}', function () {

        });
        
        $r->addRoute('PUT', '/' . $entity, function () {

        });

        $r->addRoute('PATCH', '/' . $entity . '/{id:\d+}', function () {

        });
    }
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
        header('Content-Type: application/json');
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        echo $handler(...$vars);
        break;
}