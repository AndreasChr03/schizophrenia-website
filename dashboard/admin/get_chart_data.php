<?php
include 'config/config.php'; // Σύνδεση με τη βάση δεδομένων

$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");

$sql = "
   SELECT 
    MONTH(date) AS month,
    SUM(CASE WHEN result = 'No Signs of Schizophrenia' THEN 1 ELSE 0 END) AS no_schizophrenia,
    SUM(CASE WHEN result = 'Possibility of early schizophrenia' THEN 1 ELSE 0 END) AS possible_stage,
    SUM(CASE WHEN result = 'Early schizophrenia' THEN 1 ELSE 0 END) AS schizophrenia_stage
FROM rating
WHERE YEAR(date) = ?
GROUP BY month
ORDER BY month ASC;

";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();

$months = [];
$no_schizophrenia = [];
$possible_stage = [];
$schizophrenia_stage = [];

while ($row = $result->fetch_assoc()) {
    $months[] = date("F", mktime(0, 0, 0, $row['month'], 1)); // Μετατροπή αριθμού μήνα σε όνομα
    $no_schizophrenia[] = $row['no_schizophrenia'];
    $possible_stage[] = $row['possible_stage'];
    $schizophrenia_stage[] = $row['schizophrenia_stage'];
}

echo json_encode([
    "months" => $months,
    "no_schizophrenia" => $no_schizophrenia,
    "possible_stage" => $possible_stage,
    "schizophrenia_stage" => $schizophrenia_stage
]);
?>
