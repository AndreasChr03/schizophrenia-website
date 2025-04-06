<?php 
    include "../../config/config.php";
    $email_user = $_SESSION['user']['email'];
    if ($_SESSION['user']['role_id'] != 3) { 
        header("Location: ../../index.php");
        exit();
    }

    if (!isset($_SESSION['user'])) {
        header("Location: ../../index.php"); 
        exit();
    }

    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

    $sqlGraph = "SELECT result, COUNT(*) AS count FROM rating WHERE client_email = ? AND YEAR(date) = ? GROUP BY result";
    $stmtGraph = $conn->prepare($sqlGraph);
    
    $stmtGraph->bind_param("si", $email_user, $year);
    $stmtGraph->execute();
    $result = $stmtGraph->get_result();

    $resultsData = [];
    $resultsCounts = []; // Changed variable name to avoid conflict with events data
    while ($row = $result->fetch_assoc()) {
        $resultsData[] = $row['result'];
        $resultsCounts[] = $row['count'];
    }

    $user_id = $_SESSION['user']['user_id'];

    // Using different variable for event data
    $eventLabels = [];
    $eventCounts = [];

    // Ερώτημα για τα δεδομένα των εκδηλώσεων
    $sqlEvents = "
    SELECT e.title, COUNT(p.id_event) AS participant_count
    FROM events e
    LEFT JOIN participants p ON e.id = p.id_event
    WHERE submit = 1
    GROUP BY e.title
    ORDER BY participant_count DESC
    LIMIT 5";

    $stmtEvents = $conn->prepare($sqlEvents);
    $stmtEvents->execute();
    $resultEvents = $stmtEvents->get_result();

    while ($row = $resultEvents->fetch_assoc()) {
        $eventLabels[] = $row['title']; // Τα ονόματα των εκδηλώσεων
        $eventCounts[] = $row['participant_count']; // Ο αριθμός των συμμετεχόντων
    }
    
    
    $personalLabels = [];
    $personalCounts = [];

    // Ερώτημα για τα δεδομένα των εκδηλώσεων
    $perEvents = "
    SELECT e.title, COUNT(p.id_event) AS participant_count
    FROM events e
    LEFT JOIN participants p ON e.id = p.id_event
    WHERE submit = 1 AND user_id = ?
    GROUP BY e.title
    ORDER BY participant_count DESC
    LIMIT 5";

$stmtEvent = $conn->prepare($perEvents);

$stmtEvent->bind_param("i", $user_id); 
$stmtEvent->execute();
$resultEvent = $stmtEvent->get_result();

    while ($row = $resultEvent->fetch_assoc()) {
        $personalLabels[] = $row['title']; // Τα ονόματα των εκδηλώσεων
        $personalCounts[] = $row['participant_count']; // Ο αριθμός των συμμετεχόντων
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="../../assets/img/favicon1.png" rel="icon">
    <link rel="stylesheet" href="../../dashboard/admin/reports.css">
</head>
<body>
    <?php include "header.php"; ?>

    <div class="content">
    
    <div class="allTables">
        <div class="container_table" style="flex: 1; margin-left: 20px;">
        <h2 style="text-align: center; padding-bottom: 40px; display: flex; justify-content: center; align-items: center; gap: 10px;">
            Αποτελέσματα Εκδηλώσεων
        </h2>
        <canvas id="resultsChart1"></canvas>
    </div>
    <div class="container_table" style="flex: 1; margin-left: 20px;">
                <h2 style="text-align: center; padding-bottom: 40px; display: flex; justify-content: center; align-items: center; gap: 10px;">
                    Προσωπικές εκδηλώσεις
                </h2>
                <canvas id="resultsChart2"></canvas>
            </div>
    
        </div>
    </div>


    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('resultsChart1').getContext('2d');
    var labels = <?php echo json_encode($eventLabels); ?>; // Τα δεδομένα των τίτλων
    var data = <?php echo json_encode($eventCounts); ?>; // Τα δεδομένα των αριθμών συμμετοχών

    var resultsChart1 = new Chart(ctx, {
        type: 'bar', // Τύπος γραφήματος (bar chart)
        data: {
            labels: labels, // Τίτλοι των γεγονότων
            datasets: [{
                label: 'Αριθμός Συμμετεχόντων',
                data: data, // Αριθμός συμμετοχών
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'], // Χρώματα των μπαρ
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true // Αρχή του άξονα y από το 0
                }
            }
        }
    });
</script>

<script>
    var ctx = document.getElementById('resultsChart2').getContext('2d');
    var labels = <?php echo json_encode($personalLabels); ?>; // Τα δεδομένα των τίτλων
    var data = <?php echo json_encode($personalCounts); ?>; // Τα δεδομένα των αριθμών συμμετοχών

    var resultsChart2 = new Chart(ctx, {
        type: 'bar', // Τύπος γραφήματος (bar chart)
        data: {
            labels: labels, // Τίτλοι των γεγονότων
            datasets: [{
                label: 'Αριθμός Συμμετεχόντων',
                data: data, // Αριθμός συμμετοχών
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'], // Χρώματα των μπαρ
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true // Αρχή του άξονα y από το 0
                }
            }
        }
    });
</script>


</body>
</html>
