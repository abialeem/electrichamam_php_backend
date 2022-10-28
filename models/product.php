<?php
class Product
{
    private $conn;
    private $table = 'products';

    //product properties
    public $id;
    public $title;
    public $image;
    public $images;
    public $description;
    public $price;
    public $quantity;
    public $short_desc;
    public $cat_id;
    public $seller_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllProducts()
    {
        $query = 'SELECT 
                   *
                    FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getSingleProduct()
    {


        $queryProductOnly = 'SELECT 
                    *
                    FROM ' . $this->table . ' 
                        WHERE 
                            id = :id';

        $queryWithSellerName = 'SELECT
         products.*,sellers.name 
         FROM
          products
           INNER JOIN
            sellers ON ( products.seller_id = sellers.id ) 
            WHERE products.id = :id';
        
        $stmt = $this->conn->prepare($queryProductOnly);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function getAvailableQuantity()
    {
        $query = 'SELECT 
                    quantity 
                    FROM ' . $this->table . ' 
                        WHERE 
                            id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function changeAvailableQuantity() 
    {
        $query = 'UPDATE ' . $this->table . ' 
                        SET
                            quantity = :quantity
                            WHERE 
                                id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->execute();
        return $stmt;
    }

    public function getProductPrice() {
        $query = 'SELECT 
                    price 
                    FROM ' . $this->table . ' 
                        WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }
}
?>