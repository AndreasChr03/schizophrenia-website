<?php
include "config/config.php";
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']; 
	$userEmail = $user['email'];



    // take data from AJAX call
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $percentage = $_POST['percentage'];
        $result = $_POST['result'];
    
        // insert db
        $sql = "INSERT INTO rating (percentage, result,client_email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dss", $percentage, $result, $userEmail);
    
        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error: " . $stmt->error;
        }
    
        $stmt->close();
    }
}

$conn->close();
?>