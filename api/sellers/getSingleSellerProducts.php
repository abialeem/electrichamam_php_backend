<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/seller.php';
include_once '../../models/product.php';

$product = new Product($db);
$seller = new Seller($db);

$seller_id = isset($_GET['id']) ?  intval($_GET['id']) : die();

if (isset($seller_id)) {
    if(empty($seller_id)) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please select a seller')
        );
    } else {
                    //check if seller id is int

                    if(is_int($seller_id) != 1) {
                        http_response_code(422);
                        echo json_encode(
                            array('message' => 'Please provide an integer value for seller id')
                        );
                    } else {
                        $seller->id = $seller_id;
                        $result = $seller-> getSingleSellerProducts();
                        $row_count = $result->rowCount();
                        if ($row_count > 0) {
                            $seller_arr = array();
                            $seller_arr['data'] = array();
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                $seller = array(
                                    'id' => $id,
                        'title' => $title,
                        'image' => $image,
                        'images' => $images,
                        'description' => $description,
                        'price' => $price,
                        'quantity' => $quantity,
                        'short_desc' => $short_desc,
                        'cat_id' => $cat_id,
                        'seller_id' => $seller_id
                                );
                                array_push($seller_arr['data'], $seller);
                            }
                            echo json_encode($seller_arr);
                        } else {
                            http_response_code(404);
                            echo json_encode(
                                array('message' => 'No seller to display.')
                            );
                        }


                    }
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please select a seller')
    );
}




?>