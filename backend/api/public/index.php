<?php
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit(0);
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once __DIR__ . '/../../../vendor/autoload.php';

    use myProject\backend\models\User;

    $user = file_get_contents('php://input');

    function getEndpoint(){
        // 1. GET the passed URL from FRONTEND (sometimes passed onto an AXIOS function), 
        // PHP_URL_PATH tells parse_url to only get the PATH, removing query strings (Data).

        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        echo "requestURI: ", $requestUri;

        // dirname removes filename and retains only the directories.

        $scriptName = dirname($_SERVER['SCRIPT_NAME']);;
        echo "</br>scriptName: ", $scriptName;
        // Checks if $requestUri and $scriptName are the same,
        // Checks starting position 0, it returns 0 meaning it found $scriptName inside the first sentence of,
        // $requestUri
        
        if (strpos($requestUri, $scriptName) === 0){
            $requestUri = substr($requestUri, strlen($scriptName));
            echo "</br>requestUri: ", $requestUri;
        }

        // Separates $requestUri by /, ex. react-crud/subsystem -> react-crud subsystem,
        // then placed inside pathParts.
        
        $pathParts = explode('/', trim($requestUri,  '/'));
        
        if (!empty($pathParts) && $pathParts[0] === 'api') {
            array_shift($pathParts);
        }
        print_r($pathParts);
        return [
            'endpoint' => $pathParts[0] ?? null,
            'id' => $pathParts[1] ?? null,
            'action' => $pathParts[2] ?? null,
        ];
        
    }

    $endpoint = getEndpoint();
    $method = $_SERVER['REQUEST_METHOD'];

    echo $endpoint['endpoint'];
    echo $endpoint['id'];
    echo $endpoint['action'];

    try{
        switch($endpoint['endpoint']){
            case 'user':
                handleUsers($method);
                break;
            case "POST":

        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Internal Server Error!',
            'message' => $e->getMessage()
        ]);
    }

    function handleUsers($method) {
        $userModel = new User();
 
        switch ($method){
            case 'GET':        
                $user = $userModel->findAll();
                echo json_encode($user);
                break;
        }            

    }


