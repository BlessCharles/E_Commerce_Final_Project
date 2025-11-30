<?php
session_start();

// For header redirection
ob_start();


//Check if user is logged in

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}


//Check if user is logged in (alias for consistency)

function is_logged_in() {
    return isLoggedIn();
}


//Check if user is admin

function isAdmin() {
    if (isLoggedIn()) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1;
    }
    return false;
}


//Get current user ID

function get_user_id() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}


//Get current user name

function get_user_name() {
    if (isset($_SESSION['user_name'])) {
        return $_SESSION['user_name'];
    }
    
    // Construct from first_name and last_name if available
    if (isset($_SESSION['first_name']) && isset($_SESSION['last_name'])) {
        return $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
    }
    
    // Fallback to first_name only
    if (isset($_SESSION['first_name'])) {
        return $_SESSION['first_name'];
    }
    
    return 'User';
}


//Get current user email

function get_user_email() {
    if (isset($_SESSION['user_email'])) {
        return $_SESSION['user_email'];
    }
    
    // If not in session, fetch from database
    if (isset($_SESSION['user_id'])) {
        require_once dirname(__FILE__) . '/../classes/user_class.php';
        $user = new User();
        $user_data = $user->get_user_by_id($_SESSION['user_id']);
        
        if ($user_data && isset($user_data['email'])) {
            $_SESSION['user_email'] = $user_data['email'];
            return $user_data['email'];
        }
    }
    
    return null;
}


//Get user type (customer or vendor)

function get_user_type() {
    return isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'customer';
}


//Check if user is vendor

function is_vendor() {
    return get_user_type() === 'vendor';
}


//Check if user is customer

function is_customer() {
    return get_user_type() === 'customer';
}


//Require login - redirect if not logged in

function require_login($redirect_to = '../view/login.php') {
    if (!is_logged_in()) {
        header("Location: $redirect_to");
        exit();
    }
}


//Logout user

function logout() {
    session_unset();
    session_destroy();
}


//Set flash message

function set_message($message, $type = 'info') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}


//Get and clear flash message

function get_message() {
    if (isset($_SESSION['flash_message'])) {
        $message = [
            'text' => $_SESSION['flash_message'],
            'type' => $_SESSION['flash_type'] ?? 'info'
        ];
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return $message;
    }
    return null;
}
?>