<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'C:\Apache24\htdocs\project\PHPMailer\vendor\autoload.php';

class User
{
    private $db;
    private $table = 'users';

    public function __construct($db)
    {
        $this->db = $db->getConnection();
    }

    public function createUser($username, $email, $password) {
        $query = "INSERT INTO " . $this->table . " (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        return $stmt->execute();
    }

    public function store2FACode($email, $code)
    {
        $query = "UPDATE " . $this->table . " 
                  SET two_factor_code = :code
                  WHERE email = :email";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':email', $email);

        return $stmt->execute();
    }

    public function verify2FACode($email, $enteredCode) {
        $stmt = $this->db->prepare( "SELECT two_factor_code FROM users WHERE email = '$email'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$row || is_null($row['two_factor_code'])) {
            echo "❌ No code found in database or NULL value.\n";
            return false;
        }
        
         $storedCode = (int) $row['two_factor_code']; // Ensure integer comparison
        $enteredCode = (int) $enteredCode; // Convert user input to integer
        // $storedCode =  $row['two_factor_code']; // Ensure integer comparison
        //  $enteredcode =  $enteredCode; // Convert user input to integer
    
        echo "🔍 Debugging:\n";
        echo "Stored Code: " . $storedCode . "\n";
        echo "Entered Code: " . $enteredCode . "\n";
    
        if ($enteredCode=$storedCode) {
            // Clear the code after successful verification
            $stmt = $this->db->prepare("UPDATE " . $this->table . " SET two_factor_code = NULL WHERE email = ?");
            $stmt->execute([$email]);
    
            echo "✅ 2FA Verified!\n";
         return true;
        } else {
            echo "❌ Codes do not match.\n";
            
        
        }
   
        return false;
    
        
        
    
       
    }
    
    

    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Fetch the two_factor_code for a given email
    public function get2FACode($email) {
        $stmt = $this->db->prepare("SELECT two_factor_code FROM " . $this->table . " WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['two_factor_code'] : null; // Return the code or null if not found
    }
}
?>
