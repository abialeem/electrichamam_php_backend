<?php
class Seller
{
    private $conn;
    private $table = 'sellers';

    public $id;
    public $name;
    public $image;
    public $description;
    public $products_count;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }
// GET ALL Sellers
    public function getAllSellers()
    {
        $query = 'SELECT *
                    FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
// GET SINGLE Seller BY ID
    public function getSingleSeller()
    {


        $query = 'SELECT 
                    *
                    FROM ' . $this->table . ' 
                        WHERE 
                            id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }


    //GET Seller Name
    public function getSellerName(){
        $query = 'SELECT name FROM sellers WHERE id = :id  ';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

// GET SINGLE Seller Products BY ID
    public function getSingleSellerProducts(){
        $query = 'SELECT * FROM products WHERE seller_id = :id  ';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }




}