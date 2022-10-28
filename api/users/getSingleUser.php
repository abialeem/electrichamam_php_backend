<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/user.php';

$user = new User($db);

$user_id = isset($_GET['id']) ?  intval($_GET['id']) : die();

if (isset($user_id)) {
    if(empty($user_id)) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please select a user')
        );
    } else {
                    //check if user id is int

                    if(is_int($user_id) != 1) {
                        http_response_code(422);
                        echo json_encode(
                            array('message' => 'Please provide an integer value for user id')
                        );
                    } else {
                        $user->id = $user_id;
                        $result = $user-> getUserDetails();
                        $row_count = $result->rowCount();
                        if ($row_count > 0) {
                            $user_arr = array();
                            $user_arr['data'] = array();
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                $user = array(
                                    'username' => $username,
                                    'email' => $email,
                                    'fname' => $fname,
                                    'lname' => $lname,
                                    'id' => $id
                                   
                                );
                                array_push($user_arr['data'], $user);
                            }
                            echo json_encode($user_arr);
                        } else {
                            http_response_code(404);
                            echo json_encode(
                                array('message' => 'No user to display.')
                            );
                        }


                    }
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please select a user')
    );
}




?>