<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");


    $user = file_get_contents('php://input');

    function getEndpoint(){
        // 1. GET the passed URL from FRONTEND (sometimes passed onto an AXIOS function), 
        // PHP_URL_PATH tells parse_url to only get the PATH, removing query strings (Data).

        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        echo "requestURI: ", $requestUri;

        // dirname removes filename and retains only the directories.

        $scriptName = '/reactCrud/react-crud/api';
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

    try{
        switch($endpoint){
            case 'user':
                handleUsers($method);
            case "POST":

        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Internal Server Error!',
            'message' => $e->getMessage()
        ]);
    }

    use myProject\backend\models\baseModel;
                

    function handleUsers($method) {   
        switch ($method){
            case 'GET':
        }            
    }


    
    // $sql = "SELECT * FROM users";
    // $stmt = $conn->prepare($sql);
    // $stmt->execute();
    // $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo json_encode($users);
    // break;


    // $user = json_decode(file_get_contents('php://input')); 

                // $sql = "INSERT INTO users(fname, lname, mobilenum) VALUES(:fname, :lname, :mobilenum)";
                // $stmt = $conn->prepare($sql);
                // $stmt->bindParam(':fname', $user->fname);
                // $stmt->bindParam(':lname', $user->lname);
                // $stmt->bindParam(':mobilenum', $user->mobilenum);

                // if($stmt->execute()) {
                //     $response = ['status' => 1, 'message' => 'Record Created Successfully'];
                // } else {
                //     $response = ['status' => 0, 'message' => 'Record Created Successfully'];
                // }
                // break;