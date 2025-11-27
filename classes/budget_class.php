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
            "catering"    => 0.25,
            "venue"       => 0.30,
            "decoration"  => 0.15,
            "photography" => 0.10,
            "tent"        => 0.10,
            "sound"       => 0.05,
            "transport"   => 0.05
        ];

        if (!isset($percentages[$service])) {
            return 0;
        }

        return $total_budget * $percentages[$service];
    }
}
