<?php

// import composer autoload
require __DIR__ . '../../vendor/autoload.php';
$requestUri = $_SERVER['REQUEST_URI'];

$urlChunks = explode('/', $requestUri);

// clean out blank chunks
foreach ($urlChunks as $k => $chunk)
{
    if (!$chunk) {
        unset($urlChunks[$k]);
    }
}

// if this is a call to the api that isn't the root (just to display the homepage)
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
$homeControllerName = '\App\Controllers\HomeController';
$controllerName = (!empty($urlChunks)) ? '\App\Controllers\\'.ucwords(array_shift($urlChunks)).'Controller' : $homeControllerName;

if (!empty($urlChunks)) {
    $resourceId = array_shift($urlChunks);
}

try {
    $controller = new $controllerName();

    $isApiRequest = false;
    if ($controller instanceof App\ResourceControllerInterface) {
        $isApiRequest = true;
        switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
            case 'GET':
                if ($resourceId) {
                    $method = 'show';
                } else {
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
    } elseif ($controllerName == $homeControllerName) {
        $method = 'index';
    } else {
        throw new Exception('Any controller used must implement the ResourceControllerInterface');
    }

    if ($method == 'show' || $method == 'update') {
        if (!is_numeric($resourceId)) {
            throw new Exception('Must provide a valid resource id');
        }
        $viewData = $controller->$method($resourceId);
    } else {
        $viewData = $controller->$method();
    }

    if ($isApiRequest) {
        echo json_encode($viewData);
    }
    
} catch (Exception $e) {
    var_dump($e); exit;
}

