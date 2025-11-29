<?php
require_once __DIR__ . '/../classes/user_class.php';

class UserController {

    public function register() {

        // Save old input for reloading view on error
        $_SESSION['old'] = $_POST;

        //Basic validation
        $required = ['first_name','last_name','email','phone','password','user_type'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = ucfirst($field) . " is required.";
                header("Location: ../view/register.php");
                exit;
            }
        }

        //Sanitize input
        $first = trim($_POST['first_name']);
        $last  = trim($_POST['last_name']);
        $email = strtolower(trim($_POST['email']));
        $phone = trim($_POST['phone']);
        $pass  = $_POST['password'];
        $type  = $_POST['user_type'];

        //Initialize model
        $userModel = new User();

        // 4. Check if email already exists
        if ($userModel->email_exists($email)) {
            $_SESSION['error'] = "Email already registered.";
            header("Location: ../view/register.php");
            exit;
        }

        //Prepare data for model
        $insertData = [
            'first_name' => $first,
            'last_name'  => $last,
            'email'      => $email,
            'phone'      => $phone,
            'password'   => password_hash($pass, PASSWORD_DEFAULT),
            'user_type'  => $type,
            'created_at' => date("Y-m-d H:i:s")
        ];

        //Insert user into database
        $user_id = $userModel->create_user($insertData);

        if ($user_id) {
            unset($_SESSION['old']);
            header("Location: ../view/login.php");
            exit;
        } else {
            $_SESSION['error'] = "Registration failed. Try again.";
            header("Location: ../view/register.php");
            exit;
        }
    }

    public function register_ajax() {

    $required = ['first_name','last_name','email','phone','password','user_type'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            return [
                "status" => "error",
                "message" => ucfirst($field) . " is required."
            ];
        }
    }

    $first = trim($_POST['first_name']);
    $last  = trim($_POST['last_name']);
    $email = strtolower(trim($_POST['email']));
    $phone = trim($_POST['phone']);
    $pass  = $_POST['password'];
    $type  = $_POST['user_type'];

    $user = new User();

    if ($user->email_exists($email)) {
        return [
            "status" => "error",
            "message" => "Email already exists."
        ];
    }

    $data = [
        'first_name' => $first,
        'last_name' => $last,
        'email'     => $email,
        'phone'     => $phone,
        'password'  => password_hash($pass, PASSWORD_DEFAULT),
        'user_type' => $type,
        'created_at'=> date("Y-m-d H:i:s")
    ];

    $user_id = $user->create_user($data);

    if ($user_id) {
        return [
            "status" => "success",
            "message" => "Registration successful!",
            "user_id" => $user_id
        ];
    }

    return [
        "status" => "error",
        "message" => "Registration failed. Try again."
    ];
    }


    //Login method - validates credentials and returns user data

    public function login($email, $password) {
        $userModel = new User();
    
        // Get user by email
        $user = $userModel->get_user_by_email($email);
    
        // Check if user exists and password is correct
        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $userModel->update_last_login($user['user_id']);
            return $user;
        }
    
        return false;
    }


    //AJAX Login handler

    public function login_ajax() {
        // Validate required fields
        if (empty($_POST['email']) || empty($_POST['password'])) {
            return [
                "status" => "error",
                "message" => "Email and password are required."
            ];
        }

        $email = strtolower(trim($_POST['email']));
        $password = $_POST['password'];
        $remember = isset($_POST['remember']) && $_POST['remember'];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                "status" => "error",
                "message" => "Invalid email format."
            ];
        }

        $userModel = new User();
    
        // Get user by email
        $user = $userModel->get_user_by_email($email);
    
        // Verify password
        if (!$user || !password_verify($password, $user['password'])) {
            return [
                "status" => "error",
                "message" => "Invalid email or password."
            ];
        }

        // Start session and store user data
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['email'] = $user['email'];

        // Handle remember me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $hashed_token = password_hash($token, PASSWORD_DEFAULT);
        
            // Save to database
            $userModel->save_remember_token($user['user_id'], $hashed_token);
        
            // Set cookie (30 days)
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
        }

        // Update last login
        $userModel->update_last_login($user['user_id']);

        // Determine redirect URL based on user type
        $redirect_url = $this->get_redirect_url($user);

        return [
            "status" => "success",
            "message" => "Login successful!",
            "redirect_url" => $redirect_url,
            "user_type" => $user['user_type']
        ];
    }


    //Get redirect URL based on user type and profile completion

    private function get_redirect_url($user) {

        // Admin redirect
        if ($user['user_type'] === 'admin') {
            return "../admin/admin_dash.php";
        }

        // Vendor redirect
        if ($user['user_type'] === 'vendor') {
            $userModel = new User();

            if ($userModel->vendor_profile_complete($user['user_id'])) {
                return "../view/vendor_dash.php";
            } else {
                return "../view/vendor_onboarding.php";
            }
        }

        // Default -> normal customer
        return "../view/user_landing.php";
    }



}
