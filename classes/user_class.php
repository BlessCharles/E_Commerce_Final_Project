<?php
require_once __DIR__ . '/../settings/db_class.php';

class User extends db_connection {
    
    
    
    
    //Create a new user

    public function create_user($data) {
        $sql = "INSERT INTO users (first_name, last_name, email, phone, password, user_type, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        // Get connection
        $conn = $this->db_conn();
        if (!$conn) {
            return false;
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssss",
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['password'],
            $data['user_type'],
            $data['created_at']
        );
        
        if ($stmt->execute()) {
            return $this->last_insert_id();
        }
        
        return false;
    }
    

    //Check if email exists

    public function email_exists($email) {
        $sql = "SELECT user_id FROM users WHERE email = ? LIMIT 1";
        
        $conn = $this->db_conn();
        if (!$conn) {
            return false;
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }
    

    //Get user by email

    public function get_user_by_email($email) {
        $conn = $this->db_conn();
        if (!$conn) {
            return false;
        }
    
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
    
        return false;
    }
    

    //Get user by ID

    public function get_user_by_id($user_id) {
        $conn = $this->db_conn();
        if (!$conn) {
            return false;
        }
    
        $sql = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
    
        return false;
    }
    

    //Update last login time

    public function update_last_login($user_id) {
        $conn = $this->db_conn();
        if (!$conn) {
            return false;
        }
    
        $sql = "UPDATE users SET last_login = NOW() WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
    

    //Save remember me token

    public function save_remember_token($user_id, $hashed_token) {
        $conn = $this->db_conn();
        if (!$conn) {
            return false;
        }
        
        $sql = "UPDATE users SET remember_token = ?, token_expiry = DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_token, $user_id);
        return $stmt->execute();
    }
    

    //Verify remember token

    public function verify_remember_token($token) {
        $sql = "SELECT user_id, remember_token FROM users WHERE token_expiry > NOW()";
        $users = $this->db_fetch_all($sql);
        
        if ($users) {
            foreach ($users as $user) {
                if (password_verify($token, $user['remember_token'])) {
                    return $this->get_user_by_id($user['user_id']);
                }
            }
        }
        
        return false;
    }
    

    //Check if vendor profile is complete

    public function vendor_profile_complete($user_id) {
        $conn = $this->db_conn();
        if (!$conn) {
            return false;
        }

        // Get vendor record
        $sql = "SELECT * FROM vendors WHERE user_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $vendor = $result->fetch_assoc();

        // If vendor record does NOT exist → new vendor
        if (!$vendor) {
            return false;
        }

        // Required fields that MUST be filled before going to dashboard
        $required_fields = [
            'business_name',
            'business_description',
            'category',
            'location',
            'address',
            'starting_price'
        ];

        foreach ($required_fields as $field) {
            if (empty($vendor[$field])) {
                return false; // Incomplete onboarding
            }
        }

        return true; // Fully completed onboarding
    }
    

    //Update user profile

    public function update_user($user_id, $data) {
        $conn = $this->db_conn();
        if (!$conn) {
            return false;
        }
        
        $sql = "UPDATE users SET ";
        $params = [];
        $types = "";
        
        foreach ($data as $key => $value) {
            if ($key !== 'user_id' && $key !== 'password') {
                $sql .= "$key = ?, ";
                $params[] = $value;
                $types .= "s";
            }
        }
        
        $sql = rtrim($sql, ", ");
        $sql .= " WHERE user_id = ?";
        $params[] = $user_id;
        $types .= "i";
        
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        return $stmt->execute();
    }
    

    //Update password

    public function update_password($user_id, $hashed_password) {
        $conn = $this->db_conn();
        if (!$conn) {
            return false;
        }
        
        $sql = "UPDATE users SET password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $user_id);
        return $stmt->execute();
    }



}
?>