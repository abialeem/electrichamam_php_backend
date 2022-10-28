<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');


include_once('../../core/initialize.php');
include_once '../../models/seller.php';

$seller = new Seller($db);

$result = $seller->getAllSellers();
$row_count = $result->rowCount();



if ($row_count > 0) {
    $seller_arr = array();
    $seller_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $seller = array(
            'id' => $id,
            'name' => $name,
            'image' => $image,
            'description' => $description,
            'products_count' => $products_count
            
        );
        array_push($seller_arr['data'], $seller);
    }
    echo json_encode($seller_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array('message' => 'No sellers to display')
    );
}

?>