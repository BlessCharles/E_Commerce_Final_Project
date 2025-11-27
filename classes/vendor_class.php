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
}
?>
