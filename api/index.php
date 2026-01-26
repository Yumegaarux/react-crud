<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");

    include 'connection.php';
    $dbObj = new Connection;
    $conn = $dbObj->connect();

    $user = file_get_contents('php://input');
    $method = $_SERVER['REQUEST_METHOD'];

    switch($method){
        case 'GET':
            $sql = "SELECT * FROM users";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($users);
            break;
        case "POST":
            $user = json_decode(file_get_contents('php://input')); 

            $sql = "INSERT INTO users(fname, lname, mobilenum) VALUES(:fname, :lname, :mobilenum)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fname', $user->fname);
            $stmt->bindParam(':lname', $user->lname);
            $stmt->bindParam(':mobilenum', $user->mobilenum);

            if($stmt->execute()) {
                $response = ['status' => 1, 'message' => 'Record Created Successfully'];
            } else {
                $response = ['status' => 0, 'message' => 'Record Created Successfully'];
            }
            break;
    }
