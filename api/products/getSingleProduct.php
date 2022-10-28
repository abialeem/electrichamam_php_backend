<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../../core/initialize.php';
include_once '../../models/product.php';

$product = new Product($db);

$product_id = isset($_GET['id']) ?  intval($_GET['id']) : die();

// $data = json_decode(file_get_contents("php://input"));

if (isset($product_id)) {
    if(empty($product_id)) {
        http_response_code(422);
        echo json_encode(
            array('message' => 'Please select a product')
        );
    } else {
        if(is_int($product_id) != 1) {
            http_response_code(422);
            echo json_encode(
                array('message' => 'Please provide an integer value for product id')
            );
        } else {
            $product->id = $product_id;
            $result = $product->getSingleProduct();
            $row_count = $result->rowCount();
            if ($row_count > 0) {
                $product_arr = array();
                $product_arr['data'] = array();
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $product = array(
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
                    array_push($product_arr['data'], $product);
                }
                echo json_encode($product_arr);
            } else {
                http_response_code(404);
                echo json_encode(
                    array('message' => 'No product to display.')
                );
            }
        }
    }
} else {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Please select a product')
    );
}
?>