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

$user = file_get_contents('php://input');

function getEndpoint(){
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    
    if (strpos($requestUri, $scriptName) === 0){
        $requestUri = substr($requestUri, strlen($scriptName));
    }
    
    $pathParts = explode('/', trim($requestUri,  '/'));
    
    if (!empty($pathParts) && $pathParts[0] === 'api') {
        array_shift($pathParts);
    }
    
    return [
        'endpoint' => $pathParts[0] ?? null,
        'id' => $pathParts[1] ?? null,
        'action' => $pathParts[2] ?? null,
    ];
}

$endpoint = getEndpoint();
$method = $_SERVER['REQUEST_METHOD'];

use myProject\backend\models\User;

try{
    switch($endpoint['endpoint']){
        case 'user':
            handleUsers($method, $endpoint['id']);
            break;
        default:
            http_response_code(404);
            echo json_encode([
                'error' => 'Endpoint not found',
                'requested' => $endpoint['endpoint']
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error!',
        'message' => $e->getMessage()
    ]);
}

function handleUsers($method, $id = null) {
    $userModel = new User();
    
    switch ($method){
        case 'GET':
            if ($id) {
                $user = $userModel->find($id);
                if ($user) {
                    echo json_encode($user);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'User not found']);
                }
            } else {
                $users = $userModel->findAll();
                echo json_encode($users);
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if ($data && !empty($data)) {
                $newId = $userModel->create($data);
                http_response_code(201);
                echo json_encode([
                    'message' => 'User created successfully',
                    'id' => $newId
                ]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid data provided']);
            }
            break;
        case 'PUT':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'User ID is required']);
                return;
            }

            try {
                $affected = $userModel->update($id, $data);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'User updated successfully',
                    'affected_rows' => $affected
                ]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
}
