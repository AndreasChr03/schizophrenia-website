<?php 

include "../../config/config.php";

$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");

// SQL Query για το επιλεγμένο έτος (αποτελέσματα χρηστών)
$sql = "
    SELECT 
        SUM(CASE WHEN result = 'Χωρίς Ενδείξεις Σχιζοφρένειας' THEN 1 ELSE 0 END) AS no_schizophrenia,
        SUM(CASE WHEN result = 'Πιθανότητα πρώιμης σχιζοφρένειας' THEN 1 ELSE 0 END) AS possible_stage,
        SUM(CASE WHEN result = 'Πρώιμη σχιζοφρένεια' THEN 1 ELSE 0 END) AS schizophrenia_stage
    FROM rating
    WHERE YEAR(date) = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$data['year'] = $year;

// SQL Query για τα δεδομένα του 2025
$year_2025 = 2025;
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $year_2025);
$stmt->execute();
$result = $stmt->get_result();
$data2025 = $result->fetch_assoc();
$data2025['year'] = 2025;

// SQL Query για τον αριθμό των χρηστών ανά έτος
$sql_users = "SELECT YEAR(creation_date) as year, COUNT(*) as total_users FROM users GROUP BY year";
$result = $conn->query($sql_users);

$usersByYear = [];
while ($row = $result->fetch_assoc()) {
    $usersByYear[$row['year']] = $row['total_users'];
}

// Επιστροφή JSON με τα δεδομένα
echo json_encode([
    'selected_year' => $data,
    'year_2025' => $data2025,
    'users_by_year' => $usersByYear
]);

?>
