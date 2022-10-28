<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json;charset=UTF-8');

include_once('../../core/initialize.php');
include_once '../../models/user.php';

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if (isset($data->email) && isset($data->password)) {
    if (empty($data->email) || empty($data->password)) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please enter valid  required fields')
        );
    } else {
        $user->email = $data->email;
        $user->password = $data->password;
        if ($user->isLoggedIn()) {
            http_response_code(400);
            echo json_encode(
                array('message' => 'You are currently logged in.')
            );
        } else {
            if (!$user->isEmailValid()) {
                http_response_code(422);
                echo json_encode(
                    array('message' => 'Please enter a valid email address')
                );
            } else {
                $result = $user->loginUser();
                $row_count = $result->rowCount();
                if ($row_count > 0) {
                    $user_arr = array();
                    $user_arr['data'] = array();
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    extract($row);
                    // $_SESSION['id'] = $id;

                    //after logging in user stuff to send starts here
                    $user = array(
                        'username' => $username,
                        'email' => $email,
                        'fname' => $fname,
                        'lname' => $lname,
                        'id' => $id
                       
                    );
                    array_push($user_arr['data'], $user);


                    http_response_code(202);
                    echo json_encode(
                        $user_arr
                    );


                     //after logging in user stuff to send ends here

                 

                } else {
                    echo json_encode(
                        array('message' => 'Could not find that email address. Please register first.')
                    );
                }
            }
        }
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please enter all the required fields')
    );
}
?>