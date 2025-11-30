<?php
require_once __DIR__ . "/../settings/db_class.php";

class Event extends db_connection
{
    /**
     * Get event by ID
     * @param int $event_id
     * @return array|false
     */
    public function get_event_by_id($event_id) {
        $event_id = intval($event_id);
        $sql = "SELECT * FROM events WHERE event_id = $event_id LIMIT 1";
        return $this->db_fetch_one($sql);
    }

    /**
     * Create a new event
     * @param array $data
     * @return int|false - event_id or false
     */
    public function create_event($data) {
        $conn = $this->db_conn();
        
        $user_id = intval($data['user_id']);
        $event_type = mysqli_real_escape_string($conn, $data['event_type']);
        $event_name = mysqli_real_escape_string($conn, $data['event_name'] ?? '');
        $event_date = mysqli_real_escape_string($conn, $data['event_date']);
        $location = mysqli_real_escape_string($conn, $data['location'] ?? '');
        $guest_count = intval($data['guest_count'] ?? 0);
        $total_budget = floatval($data['total_budget'] ?? 0);
        $status = mysqli_real_escape_string($conn, $data['status'] ?? 'planning');
        
        $sql = "INSERT INTO events (user_id, event_type, event_name, event_date, location, guest_count, total_budget, status, created_at)
                VALUES ($user_id, '$event_type', '$event_name', '$event_date', '$location', $guest_count, $total_budget, '$status', NOW())";
        
        if ($this->db_write_query($sql)) {
            return mysqli_insert_id($conn);
        }
        return false;
    }

    /**
     * Get all events for a user
     * @param int $user_id
     * @return array|false
     */
    public function get_user_events($user_id) {
        $user_id = intval($user_id);
        $sql = "SELECT * FROM events WHERE user_id = $user_id ORDER BY created_at DESC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Update event details
     * @param int $event_id
     * @param array $data
     * @return bool
     */
    public function update_event($event_id, $data) {
        $event_id = intval($event_id);
        $conn = $this->db_conn();
        
        $updates = [];
        
        if (isset($data['event_type'])) {
            $event_type = mysqli_real_escape_string($conn, $data['event_type']);
            $updates[] = "event_type = '$event_type'";
        }
        
        if (isset($data['event_name'])) {
            $event_name = mysqli_real_escape_string($conn, $data['event_name']);
            $updates[] = "event_name = '$event_name'";
        }
        
        if (isset($data['event_date'])) {
            $event_date = mysqli_real_escape_string($conn, $data['event_date']);
            $updates[] = "event_date = '$event_date'";
        }
        
        if (isset($data['location'])) {
            $location = mysqli_real_escape_string($conn, $data['location']);
            $updates[] = "location = '$location'";
        }
        
        if (isset($data['guest_count'])) {
            $guest_count = intval($data['guest_count']);
            $updates[] = "guest_count = $guest_count";
        }
        
        if (isset($data['total_budget'])) {
            $total_budget = floatval($data['total_budget']);
            $updates[] = "total_budget = $total_budget";
        }
        
        if (isset($data['status'])) {
            $status = mysqli_real_escape_string($conn, $data['status']);
            $updates[] = "status = '$status'";
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $updates[] = "updated_at = NOW()";
        $update_string = implode(', ', $updates);
        
        $sql = "UPDATE events SET $update_string WHERE event_id = $event_id";
        return $this->db_write_query($sql);
    }

    /**
     * Update event status
     * @param int $event_id
     * @param string $status
     * @return bool
     */
    public function update_event_status($event_id, $status) {
        $event_id = intval($event_id);
        $conn = $this->db_conn();
        $status = mysqli_real_escape_string($conn, $status);
        
        $sql = "UPDATE events SET status = '$status', updated_at = NOW() WHERE event_id = $event_id";
        return $this->db_write_query($sql);
    }

    /**
     * Delete an event
     * @param int $event_id
     * @return bool
     */
    public function delete_event($event_id) {
        $event_id = intval($event_id);
        $sql = "DELETE FROM events WHERE event_id = $event_id";
        return $this->db_write_query($sql);
    }

    /**
     * Get events by status
     * @param int $user_id
     * @param string $status
     * @return array|false
     */
    public function get_events_by_status($user_id, $status) {
        $user_id = intval($user_id);
        $conn = $this->db_conn();
        $status = mysqli_real_escape_string($conn, $status);
        
        $sql = "SELECT * FROM events WHERE user_id = $user_id AND status = '$status' ORDER BY event_date ASC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Get upcoming events
     * @param int $user_id
     * @return array|false
     */
    public function get_upcoming_events($user_id) {
        $user_id = intval($user_id);
        $sql = "SELECT * FROM events 
                WHERE user_id = $user_id 
                AND event_date >= CURDATE() 
                AND status != 'cancelled'
                ORDER BY event_date ASC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Get past events
     * @param int $user_id
     * @return array|false
     */
    public function get_past_events($user_id) {
        $user_id = intval($user_id);
        $sql = "SELECT * FROM events 
                WHERE user_id = $user_id 
                AND event_date < CURDATE()
                ORDER BY event_date DESC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Get event count by status
     * @param int $user_id
     * @param string $status
     * @return int
     */
    public function count_events_by_status($user_id, $status) {
        $user_id = intval($user_id);
        $conn = $this->db_conn();
        $status = mysqli_real_escape_string($conn, $status);
        
        $sql = "SELECT COUNT(*) as count FROM events WHERE user_id = $user_id AND status = '$status'";
        $result = $this->db_fetch_one($sql);
        return $result ? intval($result['count']) : 0;
    }

    /**
     * Get total budget for all events
     * @param int $user_id
     * @return float
     */
    public function get_total_budget($user_id) {
        $user_id = intval($user_id);
        $sql = "SELECT SUM(total_budget) as total FROM events WHERE user_id = $user_id";
        $result = $this->db_fetch_one($sql);
        return $result ? floatval($result['total']) : 0;
    }
}
?>