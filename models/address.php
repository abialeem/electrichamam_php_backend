<?php
class Address
{
    private $conn;
    private $table = 'addresses';

    public $id;
    public $full_name;
    public $email;
    public $address;
    public $city;
    public $zip;
    public $mobile;
    public $user_id;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAddressDetails()
    {
        $query = "SELECT * FROM " . $this->table . " 
                    WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function addressExists()
    {
        $query = "SELECT * FROM " . $this->table . " 
                    WHERE 
                        LOWER(full_name) = :full_name 
                        AND LOWER(email) = :email 
                        AND LOWER(address) = :address 
                        AND LOWER(city) = :city 
                        AND LOWER(zip) = :zip 
                        AND LOWER(mobile) = :mobile
                        AND user_id = :user_id
                        ";
        $stmt = $this->conn->prepare($query);
        $this->full_name = strtolower(htmlspecialchars(strip_tags($this->full_name)));
        $this->email = strtolower(htmlspecialchars(strip_tags($this->email)));
        $this->address = strtolower(htmlspecialchars(strip_tags($this->address)));
        $this->city = strtolower(htmlspecialchars(strip_tags($this->city)));
        $this->zip = strtolower(htmlspecialchars(strip_tags($this->zip)));
        $this->mobile = strtolower(htmlspecialchars(strip_tags($this->mobile)));
        $this->user_id = strtolower(htmlspecialchars(strip_tags($this->user_id)));
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":zip", $this->zip);
        $stmt->bindParam(":mobile", $this->mobile);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();
        return $stmt;
    }

    public function addAddressToDatabase()
    {
        $query = "INSERT INTO " . $this->table . " 
                    SET 
                        full_name = :full_name, 
                        email = :email, 
                        address = :address, 
                        city = :city, 
                        zip = :zip, 
                        mobile = :mobile,
                        user_id = :user_id
                        ";
        $stmt = $this->conn->prepare($query);
        $this->full_name = strtoupper(htmlspecialchars(strip_tags($this->full_name)));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->address = ucwords(htmlspecialchars(strip_tags($this->address)));
        $this->city = ucfirst(htmlspecialchars(strip_tags($this->city)));
        $this->zip = ucfirst(htmlspecialchars(strip_tags($this->zip)));
        $this->mobile = strtoupper(htmlspecialchars(strip_tags($this->mobile)));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":zip", $this->zip);
        $stmt->bindParam(":mobile", $this->mobile);
        $stmt->bindParam(":user_id", $this->user_id);
        try {
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
        } catch (Exception $e) {
            printf('Exception: %s.\n', $e);
            return 0;
        }
        printf('Error: %s.\n', $stmt->error);
        return 0;
    }

    public function checkAddressById()
    {
        $query = "SELECT * FROM " . $this->table . " 
                    WHERE 
                        id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }
}
?>