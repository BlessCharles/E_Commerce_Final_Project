<?php
require_once __DIR__ . '/../settings/db_class.php';

class Vendor extends db_connection {

    // Insert or update vendor profile
    public function save_vendor_profile($user_id, $data, $image_path = null) {
        $conn = $this->db_conn();

        // Check if vendor already exists
        $sql_check = "SELECT vendor_id FROM vendors WHERE user_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql_check);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Prepare fields
        $business_name = $data['business_name'];
        $business_description = $data['business_description'];
        $category = $data['category'];
        $years_experience = $data['years_experience'];
        $location = $data['location'];
        $address = $data['address'];
        $starting_price = $data['starting_price'];
        $price_range = $data['price_range'];

        if ($result->num_rows > 0) {
            // Update vendor
            $vendor_id = $result->fetch_assoc()['vendor_id'];

            $sql_update = "UPDATE vendors 
                SET business_name = ?, business_description = ?, image = ?, category = ?, 
                    years_experience = ?, location = ?, address = ?, starting_price = ?, 
                    price_range = ?, updated_at = NOW()
                WHERE vendor_id = ?";

            $stmt2 = $conn->prepare($sql_update);
            $stmt2->bind_param(
                "ssssissdsi",
                $business_name,
                $business_description,
                $image_path,
                $category,
                $years_experience,
                $location,
                $address,
                $starting_price,
                $price_range,
                $vendor_id
            );
            $stmt2->execute();

            return $vendor_id;

        } else {
            // Insert new vendor
            $sql_insert = "INSERT INTO vendors 
                (user_id, business_name, business_description, image, category, years_experience, 
                 location, address, starting_price, price_range)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

            $stmt3 = $conn->prepare($sql_insert);
            $stmt3->bind_param(
                "issssissds",
                $user_id,
                $business_name,
                $business_description,
                $image_path,
                $category,
                $years_experience,
                $location,
                $address,
                $starting_price,
                $price_range
            );
            $stmt3->execute();

            return $this->last_insert_id();
        }
    }

    // Save vendor services
    public function save_vendor_services($vendor_id, $services) {
        $conn = $this->db_conn();

        // Remove old services
        $conn->query("DELETE FROM vendor_services WHERE vendor_id = $vendor_id");

        // Insert new services
        $sql = "INSERT INTO vendor_services (vendor_id, event_type) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        foreach ($services as $service) {
            $stmt->bind_param("is", $vendor_id, $service);
            $stmt->execute();
        }
    }

    public function get_vendor_by_user($user_id) {
        $conn = $this->db_conn();

        $sql = "SELECT v.*, 
                COALESCE(v.verification_status, 'pending') as verification_status
                FROM vendors v 
                WHERE v.user_id = ? 
                LIMIT 1";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    //smart recommendations methods
    
    //Get verified vendors by category within budget range
    
    public function get_vendors_by_category_and_budget($category, $budget_min, $budget_max, $limit = 3) {
        $conn = $this->db_conn();
        if (!$conn) {
            return false;
        }

        $category = mysqli_real_escape_string($conn, $category);
        
        $sql = "SELECT 
                    v.vendor_id,
                    v.business_name,
                    v.business_description,
                    v.category,
                    v.starting_price,
                    v.rating,
                    v.total_reviews,
                    v.location,
                    v.image,
                    u.email,
                    u.phone
                FROM vendors v
                INNER JOIN users u ON v.user_id = u.user_id
                WHERE v.category = '$category'
                AND v.verification_status = 'approved'
                AND v.is_active = 1
                AND v.starting_price BETWEEN $budget_min AND $budget_max
                ORDER BY v.rating DESC, v.total_reviews DESC
                LIMIT $limit";
        
        return $this->db_fetch_all($sql);
    }

    
    //Get vendor details by ID
    
    public function get_vendor_details($vendor_id) {
        $conn = $this->db_conn();
        if (!$conn) {
            return false;
        }

        $sql = "SELECT 
                    v.*,
                    u.email,
                    u.phone,
                    u.first_name,
                    u.last_name
                FROM vendors v
                INNER JOIN users u ON v.user_id = u.user_id
                WHERE v.vendor_id = $vendor_id
                AND v.is_active = 1";
        
        return $this->db_fetch_one($sql);
    }

    
    //Get all verified vendors by category
    
    public function get_all_vendors_by_category($category) {
        $conn = $this->db_conn();
        if (!$conn) {
            return false;
        }

        $category = mysqli_real_escape_string($conn, $category);
        
        $sql = "SELECT 
                    v.vendor_id,
                    v.business_name,
                    v.business_description,
                    v.category,
                    v.starting_price,
                    v.rating,
                    v.total_reviews,
                    v.location,
                    v.image
                FROM vendors v
                WHERE v.category = '$category'
                AND v.verification_status = 'approved'
                AND v.is_active = 1
                ORDER BY v.rating DESC, v.total_reviews DESC";
        
        return $this->db_fetch_all($sql);
    }

    
    //Check if vendor exists and is verified
    
    public function is_vendor_verified($vendor_id) {
        $sql = "SELECT vendor_id FROM vendors 
                WHERE vendor_id = $vendor_id 
                AND verification_status = 'approved' 
                AND is_active = 1 
                LIMIT 1";
        
        $result = $this->db_fetch_one($sql);
        return $result !== false;
    }

    //vendor dashboard methods
    
    //Get vendor statistics for dashboard
    
    public function get_vendor_stats($vendor_id) {
        $conn = $this->db_conn();
        
        $stats = [];
        
        // Pending bookings count
        $sql = "SELECT COUNT(*) as count FROM bookings WHERE vendor_id = ? AND status = 'pending'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $vendor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['pending_requests'] = $result->fetch_assoc()['count'];
        
        // Completed bookings count
        $sql = "SELECT COUNT(*) as count FROM bookings WHERE vendor_id = ? AND status = 'completed'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $vendor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['completed_bookings'] = $result->fetch_assoc()['count'];
        
        // Average rating and total earnings
        $sql = "SELECT rating, total_reviews FROM vendors WHERE vendor_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $vendor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $vendor_data = $result->fetch_assoc();
        $stats['average_rating'] = $vendor_data['rating'] ?? 0;
        $stats['total_reviews'] = $vendor_data['total_reviews'] ?? 0;
        
        // Total earnings
        $sql = "SELECT SUM(amount) as total FROM bookings WHERE vendor_id = ? AND status = 'completed'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $vendor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats['total_earnings'] = $result->fetch_assoc()['total'] ?? 0;
        
        return $stats;
    }

    
    //Get vendor bookings
    
    public function get_vendor_bookings($vendor_id, $status = null) {
        $conn = $this->db_conn();
        
        $sql = "SELECT b.*, e.event_type, e.event_date, e.location as event_location, 
                e.guest_count, u.first_name, u.last_name, u.email, u.phone
                FROM bookings b
                INNER JOIN events e ON b.event_id = e.event_id
                INNER JOIN users u ON e.user_id = u.user_id
                WHERE b.vendor_id = ?";
        
        if ($status) {
            $sql .= " AND b.status = ?";
        }
        
        $sql .= " ORDER BY b.created_at DESC";
        
        $stmt = $conn->prepare($sql);
        
        if ($status) {
            $stmt->bind_param("is", $vendor_id, $status);
        } else {
            $stmt->bind_param("i", $vendor_id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    
    //Get vendor reviews (updated to avoid confusion with get_vendor_reviews in smart recommendations)
    
    public function get_vendor_reviews($vendor_id, $limit = null) {
        $conn = $this->db_conn();
        
        $sql = "SELECT r.*, u.first_name, u.last_name, b.amount,
                r.rating, r.comment, r.created_at
                FROM reviews r
                INNER JOIN bookings b ON r.booking_id = b.booking_id
                INNER JOIN users u ON r.user_id = u.user_id
                WHERE r.vendor_id = ?
                ORDER BY r.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT $limit";
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $vendor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    
    //Update booking status
    
    public function update_booking_status($booking_id, $status) {
        $conn = $this->db_conn();
        
        $sql = "UPDATE bookings SET status = ?, updated_at = NOW() WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $booking_id);
        
        return $stmt->execute();
    }

    //vendor approval methods
    
    //Get vendors by verification status
    
    public function get_vendors_by_status($status) {
        $conn = $this->db_conn();
        
        $sql = "SELECT v.*, u.email, u.first_name, u.last_name 
                FROM vendors v
                INNER JOIN users u ON v.user_id = u.user_id
                WHERE v.verification_status = ?
                ORDER BY v.created_at DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    
    //Approve a vendor application
    
    public function approve_vendor($vendor_id, $admin_id) {
        $conn = $this->db_conn();
        
        $sql = "UPDATE vendors 
                SET verification_status = 'approved',
                    verified_at = NOW(),
                    verified_by = ?
                WHERE vendor_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $admin_id, $vendor_id);
        
        if ($stmt->execute()) {
            $this->send_approval_email($vendor_id);
            return true;
        }
        
        return false;
    }
    
    
    //Reject a vendor application
    
    public function reject_vendor($vendor_id, $admin_id, $reason = '') {
        $conn = $this->db_conn();
        
        $sql = "UPDATE vendors 
                SET verification_status = 'rejected',
                    verified_by = ?,
                    rejection_reason = ?
                WHERE vendor_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $admin_id, $reason, $vendor_id);
        
        if ($stmt->execute()) {
            $this->send_rejection_email($vendor_id, $reason);
            return true;
        }
        
        return false;
    }
    
    
    //Send approval email to vendor
    
    private function send_approval_email($vendor_id) {
        $conn = $this->db_conn();
        
        $sql = "SELECT v.business_name, u.email, u.first_name 
                FROM vendors v
                INNER JOIN users u ON v.user_id = u.user_id
                WHERE v.vendor_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $vendor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $vendor = $result->fetch_assoc();
        
        if ($vendor) {
            $to = $vendor['email'];
            $subject = "Congratulations! Your Vendor Application is Approved";
            $message = "
                <html>
                <body>
                    <h2>Welcome to PlanSmart Ghana, {$vendor['first_name']}!</h2>
                    <p>Great news! Your vendor application for <strong>{$vendor['business_name']}</strong> has been approved.</p>
                    <p>You can now:</p>
                    <ul>
                        <li>Receive booking requests from customers</li>
                        <li>Display your verified badge</li>
                        <li>Build your reputation with reviews</li>
                    </ul>
                    <p>Log in to your dashboard to get started!</p>
                    <p>Best regards,<br>The PlanSmart Ghana Team</p>
                </body>
                </html>
            ";
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: no-reply@plansmartghana.com" . "\r\n";
            
            
        }
    }
    
    
    //Send rejection email to vendor
    
    private function send_rejection_email($vendor_id, $reason) {
        $conn = $this->db_conn();
        
        $sql = "SELECT v.business_name, u.email, u.first_name 
                FROM vendors v
                INNER JOIN users u ON v.user_id = u.user_id
                WHERE v.vendor_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $vendor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $vendor = $result->fetch_assoc();
        
        if ($vendor) {
            $to = $vendor['email'];
            $subject = "Update on Your Vendor Application";
            $message = "
                <html>
                <body>
                    <h2>Hello {$vendor['first_name']},</h2>
                    <p>Thank you for your interest in joining PlanSmart Ghana.</p>
                    <p>Unfortunately, we are unable to approve your application for <strong>{$vendor['business_name']}</strong> at this time.</p>
                    " . ($reason ? "<p><strong>Reason:</strong> {$reason}</p>" : "") . "
                    <p>You may update your application and resubmit for review.</p>
                    <p>If you have questions, please contact our support team.</p>
                    <p>Best regards,<br>The PlanSmart Ghana Team</p>
                </body>
                </html>
            ";
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: no-reply@plansmartghana.com" . "\r\n";
            
            
        }
    }


    //Get filtered vendors based on multiple criteria
    
    public function get_filtered_vendors($category = 'all', $search = '', $min_price = 0, $max_price = 999999, $min_rating = 0, $location = '', $verified_only = false) {
        $conn = $this->db_conn();
        if (!$conn) {
            return [];
        }
        
        $sql = "SELECT 
                    v.vendor_id,
                    v.business_name,
                    v.business_description,
                    v.category,
                    v.starting_price,
                    v.rating,
                    v.total_reviews,
                    v.location,
                    v.image,
                    v.verification_status,
                    u.email,
                    u.phone
                FROM vendors v
                INNER JOIN users u ON v.user_id = u.user_id
                WHERE v.is_active = 1";
        
        // Category filter
        if ($category !== 'all' && !empty($category)) {
            $category_escaped = mysqli_real_escape_string($conn, $category);
            $sql .= " AND v.category = '$category_escaped'";
        }
        
        // Search filter - search in business name and description
        if (!empty($search)) {
            $search_escaped = mysqli_real_escape_string($conn, $search);
            $sql .= " AND (v.business_name LIKE '%$search_escaped%' 
                    OR v.business_description LIKE '%$search_escaped%'
                    OR v.category LIKE '%$search_escaped%')";
        }
        
        // Price range filter
        if ($min_price > 0 || $max_price < 999999) {
            $sql .= " AND v.starting_price BETWEEN $min_price AND $max_price";
        }
        
        // Rating filter
        if ($min_rating > 0) {
            $sql .= " AND v.rating >= $min_rating";
        }
        
        // Location filter
        if (!empty($location)) {
            $location_escaped = mysqli_real_escape_string($conn, $location);
            $sql .= " AND v.location LIKE '%$location_escaped%'";
        }
        
        // Verified only filter
        if ($verified_only) {
            $sql .= " AND v.verification_status = 'approved'";
        }
        
        // Order by rating and reviews
        $sql .= " ORDER BY v.rating DESC, v.total_reviews DESC";
        
        $result = $this->db_fetch_all($sql);
        
        return $result ? $result : [];
    }

    
    //Get all unique categories from vendors table
    
    public function get_all_categories() {
        $conn = $this->db_conn();
        if (!$conn) {
            return [];
        }
        
        $sql = "SELECT DISTINCT category 
                FROM vendors 
                WHERE is_active = 1
                ORDER BY category ASC";
        
        $result = $this->db_fetch_all($sql);
        
        $categories = [];
        if ($result) {
            foreach ($result as $row) {
                if (!empty($row['category'])) {
                    $categories[] = $row['category'];
                }
            }
        }
        
        return $categories;
    }
}
?>