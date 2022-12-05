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
    'path' => __DIR__ . DIRECTORY_SEPARATOR . $_ENV['DB_NAME']
);

$entityManager = Doctrine\ORM\EntityManager::create($conn, $config);

/* 
* Routing
*/
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use (&$entityManager) {

	$r->addRoute('GET', '/', function () {
        header('Content-Type: text/html');
		include __DIR__ . DIRECTORY_SEPARATOR . "html" . DIRECTORY_SEPARATOR . 'index.html';
        exit();
	});

    $r->addRoute('GET', '/app.js', function () {
        header('Content-Type: text/javascript');
        include __DIR__ . DIRECTORY_SEPARATOR . "html" . DIRECTORY_SEPARATOR . 'app.js';
        exit();
    });

    $entities = ['user', 'genre', 'book', 'record'];

    foreach ($entities as &$entity) {

        $entityClass = 'App\\Model\\' . ucfirst($entity);
        $serializerClass = 'App\\Serializers\\' . ucfirst($entity) . 'Serializer';
        
        $r->addRoute('GET', '/' . $entity, function () use ($entityManager, $entityClass, $serializerClass) {
            
            //TODO: Pagination

            $objects = $entityManager->getRepository($entityClass)->findAll();

            $collection = new Tobscure\JsonApi\Collection($objects, new $serializerClass);

            $document = new Tobscure\JsonApi\Document($collection);

            $url = "http" . ( !empty($_SERVER['HTTPS'] ) ? "s":"") . "://" //protocol
                    . $_SERVER['SERVER_NAME'] . explode('?', $_SERVER['REQUEST_URI'])[0];

            $document->addMeta('total', count($objects));
            $document->addLink('self', $url);

            //TODO: Document pagination links

            return json_encode($document, JSON_PRETTY_PRINT);
        });

        $r->addRoute('GET', '/' . $entity . '/{id:\d+}', function ($id) use ($entityManager, $entityClass, $serializerClass) {
            $entity = ucfirst($entity);
            $entityClass = 'App\\Model\\' . $entity;

            $object = $entityManager->getRepository($entityClass)->findOneBy(['id' => $id]);
            
            $serializerClass = 'App\\Serializers\\' . $entity . 'Serializer';
            $collection = new Tobscure\JsonApi\Collection([$object], new $serializerClass);

            $document = new Tobscure\JsonApi\Document($collection);

            $url = "http" . ( !empty($_SERVER['HTTPS'] ) ? "s":"") . "://" //protocol
                    . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

            $document->addLink('self', $url);

            return json_encode($document, JSON_PRETTY_PRINT);
        });
        
        $r->addRoute('DELETE', '/' . $entity .  '/{id:\d+}', function ($id) use ($entityManager, $entityClass, $serializerClass) {

            $object = $entityManager->getRepository($entityClass)->findOneBy(['id' => $id]);

            if ($object === null) {
                http_response_code(404);
                echo '404 Not Found';
                exit;
            }

            $entityManager->remove($object);

            return 'OK';
        });
        
        $r->addRoute('PUT', '/' . $entity, function () use ($entityManager, $entityClass, $serializerClass) {

            $object = new $entityClass();

            //Setting values
            foreach ($_GET as $param=>$value) {
                $array = array_map('ucfirst', explode('_', $param));
                $param = 'set' . implode('', $array);
                $object->$param($value);
            }

            $entityManager->persist($object);
            $entityManager->flush();

            $collection = new Tobscure\JsonApi\Collection([$object], new $serializerClass);

            $document = new Tobscure\JsonApi\Document($collection);

            $url = "http" . ( !empty($_SERVER['HTTPS'] ) ? "s":"") . "://" //protocol
                    . $_SERVER['SERVER_NAME'] . explode('?', $_SERVER['REQUEST_URI'])[0] . '/' . $object->getId();

            $document->addLink('self', $url);

            return json_encode($document, JSON_PRETTY_PRINT);
            
        });

        $r->addRoute('PATCH', '/' . $entity . '/{id:\d+}', function ($id) use ($entityManager, $entityClass, $serializerClass) {

            $object = $entityManager->getRepository($entityClass)->findOneBy(['id' => $id]);

            if ($object === null) {
                http_response_code(404);
                echo '404 Not Found';
                exit;
            }

            //Setting values
            foreach ($_GET as $param=>$value) {
                $array = array_map('ucfirst', explode('_', $param));
                $param = 'set' . implode('', $array);
                $object->$param($value);
            }

            $entityManager->persist($object);

            $collection = new Tobscure\JsonApi\Collection([$object], new $serializerClass);

            $document = new Tobscure\JsonApi\Document($collection);

            $url = "http" . ( !empty($_SERVER['HTTPS'] ) ? "s":"") . "://" //protocol
                    . $_SERVER['SERVER_NAME'] . explode('?', $_SERVER['REQUEST_URI'])[0] . '/' . $object->getId();

            $document->addLink('self', $url);

            return json_encode($document, JSON_PRETTY_PRINT);
        });
    }
});


/*
* Dispatch
*/
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = explode('?', $_SERVER['REQUEST_URI'])[0];

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

//Flushing changes
$entityManager->flush();