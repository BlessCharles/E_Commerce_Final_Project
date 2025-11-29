<?php

require_once __DIR__ . "/../settings/db_class.php";

class VendorModel extends db_connection
{
    public function getVendorByUserId($user_id)
    {
        $sql = "SELECT * FROM vendors WHERE user_id = '$user_id' LIMIT 1";
        return $this->db_fetch_one($sql);
    }
}
