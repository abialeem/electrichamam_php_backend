<?php
include_once '../core/initialize.php';
class User
{
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $password;
    public $email;
    public $fname;
    public $lname;
    public $age;
    public $role;
    public $photoUrl;
    public $type;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function isEmailValid()
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    public function isRegistered()
    {
        $query = "SELECT id FROM " . $this->table . " 
                    WHERE 
                        email = :email 
                        AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt;
    }

    public function checkPassword($old_password)
    {
        $query = "SELECT id FROM " . $this->table . " 
                    WHERE 
                        password = :password 
                        AND email = :email";
        $stmt = $this->conn->prepare($query);
        $old_password = md5(htmlspecialchars(strip_tags($old_password)));
        $stmt->bindParam(':password', $old_password);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        return $stmt;
    }

    public function registerUser()
    {
        $query = 'INSERT INTO ' . $this->table . '
                    SET
                        email = :email,
                        username= :email,
                        password = :password,
                        fname = :fname,
                        lname = :lname,
                        age = :age,
                        role = :role,
                        photoUrl = :photoUrl,
                        type= :type,
                        updated_at = CURRENT_TIMESTAMP()';
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = md5(htmlspecialchars(strip_tags($this->password)));
        $this->fname = htmlspecialchars(strip_tags($this->fname));
        $this->lname = htmlspecialchars(strip_tags($this->lname));
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':fname', $this->fname);
        $stmt->bindParam(':lname', $this->lname);
        $stmt->bindParam(':age', '18');
        $stmt->bindParam(':role', 'customer');
        $stmt->bindParam(':photoUrl', 'defaultPhotoUrl');
        $stmt->bindParam(':type', 'customer');
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch(Exception $e) {
            printf('Error: %s.\n', $e);
            return false;
        }
        printf('Error: %s.\n', $stmt->error);
        return false;
    }

    public function isLoggedIn()
    {
        return (isset($_SESSION['id'])) ? true : false;
    }

    public function loginUser()
    {
        $query = "SELECT * FROM " . $this->table . " 
                    WHERE email = :email 
                    AND password = :password 
                    AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = md5(htmlspecialchars(strip_tags($this->password)));
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->execute();
        return $stmt;
    }

    public function getUserDetails()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function updateUserDetails()
    {
        // $id = $_SESSION['id'];
        $id =  $this->id;
        $query = "UPDATE " . $this->table . " 
                    SET 
                        fname = :fname, 
                        lname = :lname, 
                        updated_at = CURRENT_TIMESTAMP() 
                            WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->fname = htmlspecialchars(strip_tags($this->fname));
        $this->lname = htmlspecialchars(strip_tags($this->lname));
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":fname", $this->fname);
        $stmt->bindParam(":lname", $this->lname);
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch(Exception $e) {
            printf('Exception: %s.\n', $e);
            return false;
        }
        printf('Error: %s.\n', $stmt->error);
        return false;
    }

    public function updateUserPassword()
    {
        // $id = $_SESSION['id'];
        $id =  $this->id;
        $query = "UPDATE " . $this->table . " 
                    SET 
                        password = :password, 
                        updated_at = CURRENT_TIMESTAMP() 
                            WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->password = md5(htmlspecialchars(strip_tags($this->password)));
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":password", $this->password);
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch(Exception $e) {
            printf('Exception: %s.\n', $e);
            return false;
        }
        printf('Error: %s.\n', $stmt->error);
        return false;
    }

    public function deleteUser()
    {
       // $id = $_SESSION['id'];
       $id =  $this->id;
        $query = "UPDATE " . $this->table . " 
                    SET 
                        deleted_at = CURRENT_TIMESTAMP() 
                            WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch(Exception $e) {
            printf('Exception: %s.\n', $e);    
        }
        printf('Error: %s.\n', $stmt->error);
        return false;
    }
}
?>