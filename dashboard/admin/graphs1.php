<?php 
    include "../../config/config.php";
    
    
    if ($_SESSION['user']['role_id'] != 1) { 
    
        header("Location: ../../index.php");//prepei na einai o admin
        exit();
    }
    
    if (!isset($_SESSION['user'])) {
        header("Location: ../../index.php"); 
        exit();
      }
      
      $sqlGraph = "SELECT result, COUNT(*) AS count FROM rating GROUP BY result";
    $stmtGraph = $conn->prepare($sqlGraph);
    if ($stmtGraph === false) {
        die("Σφάλμα κατά την προετοιμασία του statement: " . $conn->error);
    }
    $stmtGraph->execute();
    $result = $stmtGraph->get_result();
    
    // Δημιουργία πίνακα για τα δεδομένα του γραφήματος
    $resultsData = [];
    $counts = [];
    while ($row = $result->fetch_assoc()) {
        $resultsData[] = $row['result'];
        $counts[] = $row['count'];
    }
    
    
    //_______________________________pinakas 2________________________________________________
    
    // Ερώτημα για τα ραντεβού
$sqlAppointments = "
SELECT DATE_FORMAT(appointmentDate, '%Y-%m') AS month, COUNT(*) AS count
FROM appointment_details
WHERE appointmentDate >= DATE_FORMAT(NOW() - INTERVAL 13 MONTH, '%Y-%m-01') AND appointmentDate < DATE_FORMAT(NOW(), '%Y-%m-01')
GROUP BY DATE_FORMAT(appointmentDate, '%Y-%m')
ORDER BY month
";
$stmtAppointments = $conn->prepare($sqlAppointments);
$stmtAppointments->execute();
$resultAppointments = $stmtAppointments->get_result();

$months = [];
$appointmentCounts = [];

// Δημιουργία λίστας με τους προηγούμενους 12 μήνες
$allMonths = [];
$currentMonth = date("Y-m");  // Τρέχων μήνας

// Δημιουργία των 12 προηγούμενων μηνών (χωρίς τον τρέχοντα μήνα)
for ($i = 12; $i > 0; $i--) {
    $allMonths[] = date("Y-m", strtotime("-$i month", strtotime($currentMonth)));
}

// Επεξεργασία των δεδομένων που επιστρέφει το query
while ($row = $resultAppointments->fetch_assoc()) {
    $months[] = $row['month'];
    $appointmentCounts[] = $row['count'];
}

// Προσθήκη τιμών 0 για μήνες που δεν έχουν δεδομένα
foreach ($allMonths as $month) {
    // Αν ο μήνας δεν υπάρχει στη λίστα, προσθέτουμε τον μήνα με τιμή 0
    if (!in_array($month, $months)) {
        $months[] = $month;
        $appointmentCounts[] = 0;
    }
}

// Ταξινόμηση των μηνών και των μετρήσεων για να βεβαιωθούμε ότι είναι σωστά ευθυγραμμισμένα
array_multisort($months, SORT_ASC, $appointmentCounts);
      
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard</title>
    <link rel="stylesheet" href="reports.css">
</head>
<body style="overflow-y: hidden;" >
    <?php include "header.php"; ?>
    <div class="container-fluid">
            <!-- Sidebar section -->
            <div class="sidebar">
            <a href="reports.php">
                <div class="sidebar-item">
                    <i class="fas fa-file-alt"></i> <!-- Icon for Reports -->
                <p>Βασικά Στατιστικά Στοιχεία</p>
        </div>
        </a>
        
        <a href="statistics.php">
            <div class="sidebar-item">
                <i class="fas fa-chart-bar"></i><!-- Icon for Statistics -->
                <p>Στατιστικά</p>
            </div>
        </a> 
        
        <a href="graphs.php">
            <div class="sidebar-item">
                <i class="fas fa-chart-line"></i> <!-- Icon for Graphs -->
                <p>Γραφήματα</p>
            </div>
        </a>
        <a href="data.php">
            <div class="sidebar-item">
                <i class="fas fa-database"></i><!-- Icon for Data -->
                <p>Δεδομένα</p>
            </div>
        </a> 
        
        </div>
        <div class="content">
        
        <section id="stats" class="stats section light-background" style="padding-top: 10px;">
          <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
                <!-- Stats content can go here -->
            </div>
          </div>
        </section>

    <div class="charts-row">
        <div class="chart-container">
        <h2 style="text-align: center; padding-bottom: 40px;">Αποτελέσματα Ερωτηματολογίου</h2>
            <canvas id="resultsChart"></canvas>
        </div>
        <div class="chart-container">
        <h2 style="text-align: center; padding-bottom: 40px;">Αριθμός Ραντεβού Ανά Μήνα</h2>
            <canvas id="secondChart"></canvas>
        </div>
    </div>

    <script>
    const labels = <?php echo json_encode($resultsData); ?>;
    const dataCounts = <?php echo json_encode($counts); ?>;

    const ctx1 = document.getElementById('resultsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Αριθμός Αξιολογήσεων',
                data: dataCounts,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)', // Κόκκινο
                    'rgba(75, 192, 192, 0.7)', // Πράσινο
                    'rgba(54, 162, 235, 0.7)', // Μπλε
                    'rgba(153, 102, 255, 0.7)', // Μωβ
                    'rgba(255, 159, 64, 0.7)'  // Πορτοκαλί
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    
    

    const appointmentMonths = <?php echo json_encode($months); ?>;
const appointmentCounts = <?php echo json_encode($appointmentCounts); ?>;

const ctx2 = document.getElementById('secondChart').getContext('2d');
new Chart(ctx2, {
    type: 'line',
    data: {
        labels: appointmentMonths,
        datasets: [{
            label: 'Ραντεβού ανά Μήνα (12 Προηγούμενοι Μήνες)',
            data: appointmentCounts,
            borderColor: 'rgba(54, 162, 235, 1)', // Πράσινο
            backgroundColor: 'rgba(54, 162, 235, 0.7)', // Διαφανές Πράσινο
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});

    new PureCounter();
</script>
        </div>
    </div>