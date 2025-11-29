<?php
require_once __DIR__ . '/../settings/db_class.php';

class Event extends db_connection {

    /**
     * Get event by ID
     * @param int $event_id
     * @return array|false
     */
    public function get_event_by_id($event_id) {
        $sql = "SELECT * FROM events WHERE event_id = $event_id LIMIT 1";
        return $this->db_fetch_one($sql);
    }

    /**
     * Get events by user
     * @param int $user_id
     * @return array|false
     */
    public function get_user_events($user_id) {
        $sql = "SELECT * FROM events 
                WHERE user_id = $user_id 
                ORDER BY event_date DESC, created_at DESC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Update event status
     * @param int $event_id
     * @param string $status
     * @return bool
     */
    public function update_event_status($event_id, $status) {
        $conn = $this->db_conn();
        $status = mysqli_real_escape_string($conn, $status);
        
        $sql = "UPDATE events 
                SET status = '$status', updated_at = NOW() 
                WHERE event_id = $event_id";
        
        return $this->db_write_query($sql);
    }
}
?>