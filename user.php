<?php

class User
{
    private $db;
    private $table = 'users'; // Define the table property here


    public function __construct($db)
    {
        //store the database connection instance
        $this->db = $db->getconnection();
    }

    public function createUser($username, $email, $password) {
        // Prepare the SQL query
        $query = "INSERT INTO " . $this->table . " (username, email, password) VALUES (:username, :email, :password)";
        
        // Prepare the statement
        $stmt = $this->db->prepare($query);
    
        // Bind parameters to the SQL query
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
    
        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            echo "User registered successfully!";
            $_SESSION['username']=$username;
            return true; // Return true if the query was successful
        } else {
            // If there's an error, display it
            echo "Error registering user: " . implode(", ", $stmt->errorInfo());
            return false; // Return false if there was an error
        }
    }
     // Function to generate a random 2FA code
     public function generate2FACode()
     {
         return rand(100000, 999999); // Generate a random 6-digit code
     }
 
     // Function to send the 2FA code via email
     public function send2FACode($email, $code)
     {
         $subject = "Your 2FA Code";
         $message = "Your two-factor authentication code is: $code";
         mail($email, $subject, $message);
     }
 
     // Function to start the 2FA process: Generate the code, save it, and send it to the user
     public function start2FA($userId, $email)
     {
         $code = $this->generate2FACode();
         $expiryTime = date('Y-m-d H:i:s', strtotime('+10 minutes'));
 
         // Save the 2FA code and expiry time to the database
         $stmt = $this->db->prepare("UPDATE " . $this->table . " SET two_factor_code = :code, two_factor_code_expiry = :expiry WHERE id = :userId");
         $stmt->bindParam(':code', $code);
         $stmt->bindParam(':expiry', $expiryTime);
         $stmt->bindParam(':userId', $userId);
         $stmt->execute();
 
         // Send the 2FA code to the user's email
         $this->send2FACode($email, $code);
     }
    
 
     // Function to verify the 2FA code entered by the user
     public function verify2FACode($userId, $enteredCode)
     {
         // Get the stored 2FA code and its expiry time
         $stmt = $this->db->prepare("SELECT two_factor_code, two_factor_code_expiry FROM " . $this->table . " WHERE id = :userId");
         $stmt->bindParam(':userId', $userId);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
         // Check if the entered code matches and if it has not expired
         if ($row && $row['two_factor_code'] == $enteredCode) {
             $expiry = new DateTime($row['two_factor_code_expiry']);
             $currentTime = new DateTime();
 
             if ($currentTime < $expiry) {
                 // Code is correct and not expired
                 return ['status'=> true,'message'=>'code verified successfully'];

             } else {
                 // Code has expired
                 return ['status' =>false, 'message' =>'Invalid 2FA code'];
             }
             } else {
                  return['status' => false, 'message' => 'User not found or 2FA data missing.'];
             }
         }
    

        public function store2FACode($email, $code)
    {
        // Update the 2FA code for the user with the specified email
        $query = "UPDATE " . $this->table . " SET two_factor_code = :code WHERE email = :email";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error storing 2FA code: " . implode(", ", $stmt->errorInfo());
            return false;
        }
    } 
    public function authenticateUser($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->db->getConnection()->prepare($query);
    
        // Bind parameters
        $stmt->bindParam(':email', $email);
    
        // Execute the query
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Check if user exists and verify the password
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Return user data if authenticated
        }
    
        return false; // Return false if authentication fails
    }

    // Check if the email exists in the database
    public function emailExists($email) {
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    // Store the reset code and expiry time for the user
    public function storeResetCode($email, $resetCode, $expiryTime) {
        $query = "UPDATE users SET reset_code = :resetCode, reset_code_expiry = :expiryTime WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':resetCode', $resetCode);
        $stmt->bindParam(':expiryTime', $expiryTime);
        return $stmt->execute();
    }
    // Verify if the reset code is valid and not expired
    public function verifyResetCode($code) {
        $query = "SELECT email, reset_code_expiry FROM users WHERE reset_code = :code";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $expiryTime = $result['reset_code_expiry'];
            if (strtotime($expiryTime) > time()) {
                return true;
            }
        }
        return false;
    }
    // Reset the user's password
    public function resetPassword($resetCode, $newPassword) {
        $query = "SELECT email FROM users WHERE reset_code = :resetCode";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':resetCode', $resetCode);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $email = $stmt->fetchColumn();
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateQuery = "UPDATE users SET password = :password, reset_code = NULL, reset_code_expiry = NULL WHERE email = :email";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(':password', $hashedPassword);
            $updateStmt->bindParam(':email', $email);
            return $updateStmt->execute();
        }
        return false;
    }
}
