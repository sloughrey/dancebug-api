<?php

// import composer autoload
require __DIR__ . '../../vendor/autoload.php';
$requestUri = $_SERVER['REQUEST_URI'];
$urlChunks = explode('/', $requestUri);

foreach($urlChunks as $k => $chunk)
{
    if(!$chunk) {
        unset($urlChunks[$k]);
    }
}

if (empty($urlChunks) && $requestUri != '/') {
    throw new Exception('Must provide resource controller and / or resource id');    
}

// grab our query string if it exists
$queryString = '';
foreach ($urlChunks as $k => $chunk) {
    if (strpos($chunk, '?')) {
        $urlParts = explode('?', $chunk);
        $urlChunks[$k] = $urlParts[0];
        $queryString = $urlParts[1];
    }
}

$controllerName = '';
$resourceId = '';
if(!empty($urlChunks)) {
    $controllerName = '\App\Controllers\\' . ucwords(array_shift($urlChunks)) . 'Controller';
}
if(!empty($urlChunks)) {
    $resourceId = array_shift($urlChunks);
}

try {
    $controller = new $controllerName();
    switch(strtoupper($_SERVER['REQUEST_METHOD']))
    {
        case 'GET':
            if ($resourceId) {
                $method = 'show';
            }
            else { 
                $method = 'all';
            }
            break;
        case 'POST':
            // create user
            $method = 'create';
            break;
        case 'PUT':
            // update the user
            $method = 'update';
            break;
        case 'DELETE':
            // delete a user
            $method = 'delete';
            break;
        default:
            throw new Exception('HTTP method not accounted for');
    }

    if ($method == 'show') {
        if (!is_numeric($resourceId)) {
            throw new Exception('Must provide a valid resource id');
        }
        $viewData = $controller->$method($resourceId);
    } else {
        $viewData = $controller->$method();
    }
    

    echo json_encode($viewData);
} catch (Exception $e) {
    var_dump($e); exit;
}

