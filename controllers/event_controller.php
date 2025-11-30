<?php

//Event Controller Functions

require_once dirname(__FILE__) . '/../classes/event_class.php';


//Get event by ID

function get_event_by_id_ctr($event_id) {
    $event = new Event();
    return $event->get_event_by_id($event_id);
}


//Create a new event

function create_event_ctr($data) {
    $event = new Event();
    return $event->create_event($data);
}


//Get all events for a user

function get_user_events_ctr($user_id) {
    $event = new Event();
    return $event->get_user_events($user_id);
}


//Update event details

function update_event_ctr($event_id, $data) {
    $event = new Event();
    return $event->update_event($event_id, $data);
}


//Delete an event

function delete_event_ctr($event_id) {
    $event = new Event();
    return $event->delete_event($event_id);
}


//Update event status

function update_event_status_ctr($event_id, $status) {
    $event = new Event();
    return $event->update_event_status($event_id, $status);
}
?>