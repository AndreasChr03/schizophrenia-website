<?php
    $hostName = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "schizophrenia";
    
    $conn = new mysqli($hostName, $username, $password, $databaseName);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Έλεγχος αν το session είναι ήδη ενεργό
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>