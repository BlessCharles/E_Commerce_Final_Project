<?php
require_once "../settings/db_class.php";

class Budget extends db_connection {

    // create event
    function create_event($user_id, $event_type, $event_name, $event_date, $guest_count, $budget)
    {
        $event_type = mysqli_real_escape_string($this->db_conn(), $event_type);
        $event_name = mysqli_real_escape_string($this->db_conn(), $event_name);

        $sql = "INSERT INTO events (user_id, event_type, event_name, event_date, guest_count, total_budget)
                VALUES ('$user_id', '$event_type', '$event_name', '$event_date', '$guest_count', '$budget')";

        if ($this->db_write_query($sql)) {
            return $this->last_insert_id();
        }
        return false;
    }

    // create budget allocation row
    function create_allocation($event_id, $category, $amount)
    {
        $conn = $this->db_conn();
        $category = mysqli_real_escape_string($conn, $category);

        $sql = "INSERT INTO budget_allocations (event_id, category, allocated_amount)
                VALUES ('$event_id', '$category', '$amount')";

        return $this->db_write_query($sql);
    }

    // Example auto allocation percentages
    function auto_allocate_category($service, $total_budget)
    {
        $percentages = [
            "catering"       => 0.36,
            "venue"          => 0.18,
            "tent"           => 0.14,
            "photography"    => 0.10,
            "decoration"     => 0.08,
            "sound"          => 0.06,
            "transportation" => 0.06,
            "miscellaneous"  => 0.02
        ];

        if (!isset($percentages[$service])) {
            return 0;
        }

        return $total_budget * $percentages[$service];
    }

    
    //Get event by ID
    
    function get_event_by_id($event_id)
    {
        $sql = "SELECT * FROM events WHERE event_id = $event_id LIMIT 1";
        return $this->db_fetch_one($sql);
    }

    
    //Get all budget allocations for an event
    
    function get_event_allocations($event_id)
    {
        $sql = "SELECT * FROM budget_allocations WHERE event_id = $event_id ORDER BY allocated_amount DESC";
        return $this->db_fetch_all($sql);
    }

    
    //Get specific allocation by event and category
    
    function get_allocation_by_category($event_id, $category)
    {
        $conn = $this->db_conn();
        $category = mysqli_real_escape_string($conn, $category);
        
        $sql = "SELECT * FROM budget_allocations 
                WHERE event_id = $event_id 
                AND category = '$category' 
                LIMIT 1";
        
        return $this->db_fetch_one($sql);
    }

    
    //Update spent amount for a category
    
    function update_spent_amount($event_id, $category, $amount)
    {
        $conn = $this->db_conn();
        $category = mysqli_real_escape_string($conn, $category);
        
        $sql = "UPDATE budget_allocations 
                SET spent_amount = spent_amount + $amount 
                WHERE event_id = $event_id 
                AND category = '$category'";
        
        return $this->db_write_query($sql);
    }

    
    //Get total spent for an event
    
    function get_total_spent($event_id)
    {
        $sql = "SELECT SUM(spent_amount) as total_spent 
                FROM budget_allocations 
                WHERE event_id = $event_id";
        
        $result = $this->db_fetch_one($sql);
        return $result ? (float)$result['total_spent'] : 0;
    }
}
?>