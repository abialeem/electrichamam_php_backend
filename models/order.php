<?php
class Order
{
    private $conn;
    private $table = 'orders';

    public $id;
    public $user_id;
    public $placed_on;
    public $total_amount;
    public $user_address_id;
    public $status;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addOrder() {
        $query = 'INSERT INTO ' . $this->table . ' 
                    SET
                        user_address_id = :user_address_id,
                        status = :status,
                        user_id = :user_id,
                        total_amount = :total_amount,
                        placed_on = CURRENT_TIMESTAMP()';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_address_id", $this->user_address_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":total_amount", $this->total_amount);
        $stmt->bindParam(":quantity", $this->quantity);
        try {
            if($stmt->execute()) {
                return true;
            }
        } catch (Exception $e) {
            printf('Exception: %s.\n', $e);
            return false;
        }
        printf('Error: %s.\n', $stmt->error);
        return false;
    }

    public function viewPurchaseHistory()
    {
        $query = 'SELECT * FROM ' . $this->table . ' 
                    WHERE 
                        user_id = :user_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();
        return $stmt;
    }

    public function viewParticularPurchaseHistory()
    {
        $query = 'SELECT * FROM ' . $this->table . ' 
                    WHERE 
                        id = :id 
                        AND user_id = :user_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt;
    }
}
?>