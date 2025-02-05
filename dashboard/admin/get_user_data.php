<?php
include "../../config/config.php";
// get_user_data.php

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Προετοιμασία του SQL query για ανάκτηση των δεδομένων του χρήστη
    $sql = "SELECT * FROM users WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $userId); // Δεσμεύουμε την παράμετρο user_id
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Επιστροφή των δεδομένων ως JSON
            echo json_encode($row);
        } else {
            echo json_encode(['error' => 'Δεν βρέθηκε χρήστης']);
        }
    } else {
        echo json_encode(['error' => 'Σφάλμα SQL']);
    }
} else {
    echo json_encode(['error' => 'Δεν δόθηκε user_id']);
}

$conn->close();
?>
