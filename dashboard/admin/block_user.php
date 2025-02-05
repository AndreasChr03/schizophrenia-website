<?php
// Συνδεόμαστε με τη βάση δεδομένων
include "../../config/config.php";


// Παίρνουμε το email του συνδεδεμένου χρήστη
$myEmail = $_SESSION['user']['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $banned = $_POST['banned'];

    // Έλεγχος αν ο χρήστης προσπαθεί να μπλοκάρει τον εαυτό του
    if ($email == $myEmail) {
        echo 'error_self_ban'; // Δεν επιτρέπεται να μπλοκάρει τον εαυτό του
        exit();
    }

    if ($banned == 1) {
        // Διαγραφή του χρήστη από τον πίνακα blocked (ξεμπλοκάρισμα)
        $sql = "DELETE FROM blocked_users WHERE user_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            echo 'success'; // Αν η διαγραφή ήταν επιτυχής
        } else {
            echo 'error_deleting'; // Αν υπήρξε πρόβλημα κατά τη διαγραφή
        }
    } else {
        // Μπλοκάρισμα του χρήστη (εισαγωγή στο blocked_users)
        $banned = 1;
        $sql = "INSERT INTO blocked_users (user_email, banned) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $banned);

        if ($stmt->execute()) {
            echo 'success'; // Αν η καταχώρηση ήταν επιτυχής
        } else {
            echo 'error_inserting'; // Αν υπήρξε πρόβλημα κατά την καταχώρηση
        }
    }
} else {
    echo 'error_missing_email'; // Αν το email δεν παραλήφθηκε
}
?>
