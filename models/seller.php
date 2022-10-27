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

    public function getSellers()
    {
        $query = 'SELECT *
                    FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}