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

    // ============================================================
    // NEW METHODS FOR VENDOR APPROVAL SYSTEM - ADD BELOW THIS LINE
    // ============================================================

    /**
     * Get vendors by verification status
     * @param string $status - 'pending', 'approved', or 'rejected'
     * @return array - Array of vendor records
     */
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
    
    /**
     * Approve a vendor application
     * @param int $vendor_id - The vendor ID to approve
     * @param int $admin_id - The admin user ID approving
     * @return bool - Success or failure
     */
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
            // Optional: Send email notification to vendor
            $this->send_approval_email($vendor_id);
            return true;
        }
        
        return false;
    }
    
    /**
     * Reject a vendor application
     * @param int $vendor_id - The vendor ID to reject
     * @param int $admin_id - The admin user ID rejecting
     * @param string $reason - Reason for rejection
     * @return bool - Success or failure
     */
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
            // Optional: Send email notification to vendor
            $this->send_rejection_email($vendor_id, $reason);
            return true;
        }
        
        return false;
    }
    
    /**
     * Send approval email to vendor
     * @param int $vendor_id
     */
    private function send_approval_email($vendor_id) {
        $conn = $this->db_conn();
        
        // Get vendor details
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
            
            // Uncomment the line below when you're ready to send emails
            // mail($to, $subject, $message, $headers);
        }
    }
    
    /**
     * Send rejection email to vendor
     * @param int $vendor_id
     * @param string $reason
     */
    private function send_rejection_email($vendor_id, $reason) {
        $conn = $this->db_conn();
        
        // Get vendor details
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
            
            // Uncomment the line below when you're ready to send emails
            // mail($to, $subject, $message, $headers);
        }
    }
}
?>