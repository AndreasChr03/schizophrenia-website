<?php

include "../../config/config.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['userId']);
    $roleId = intval($_POST['role_id']);

    // Έλεγχος για έγκυρα δεδομένα
    if ($roleId !== 1 && $roleId !== 2 && $roleId !== 3 && $roleId !== 4) {
        echo json_encode(['success' => false, 'message' => 'Μη έγκυρος ρόλος']);
        exit;
    }

    // Ενημέρωση του role_id στη βάση δεδομένων
    $stmt = $conn->prepare("UPDATE users SET role_id = ? WHERE user_id = ?");
    $stmt->bind_param("ii", $roleId, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Αποτυχία ενημέρωσης', 'error' => $stmt->error]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Μη έγκυρο αίτημα']);
}
?>