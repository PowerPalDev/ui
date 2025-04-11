<?php

function DB(){
    static $db;
    if ($db === null) {
        // Database connection
        $db = new mysqli('127.0.0.1', 'roy', 'roy', 'dsTest1');

        if ($db->connect_error) {
            die(json_encode(['error' => 'Database connection failed: ' . $db->connect_error]));
        }
    }
    return $db;
}



