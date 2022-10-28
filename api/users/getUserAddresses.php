<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/user.php';
include_once '../../models/address.php';

$user = new User($db);
$address = new Address($db);
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
                                   
                                    'id' => $id
                                   
                                );
                                $address->user_id = $id;
                                $user_address_result = $address->checkAddressByUserId();
                                $user_address_row_count = $user_address_result->rowCount();
                                if ($user_address_row_count > 0) {
                                    
                                    while ($address_row = $user_address_result->fetch(PDO::FETCH_ASSOC)){
                                        extract($address_row);
                                        $address_arr = array(
                                            'id' => $id,
                                            'full_name' => $full_name,
                                            'email' => $email,
                                            'address' => $address,
                                            'city' => $city,
                                            'zip' => $zip,
                                            'mobile' => $mobile,
                                            'user_id' => $user_id
                                        );

                                        array_push($user_arr['data'], $address_arr);
                                    }
                                    

                                } else {
                                    $user_arr['message'] = array(
                                        "message" => "No addresses found",
                                    );
                                }
                                
                               
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