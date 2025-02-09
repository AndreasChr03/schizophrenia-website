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
      $sqlAppointments = "
      SELECT DATE_FORMAT(appointmentDate, '%Y-%m') AS month, COUNT(*) AS count
      FROM appointment_details
      WHERE appointmentDate >= DATE_FORMAT(NOW() - INTERVAL 13 MONTH, '%Y-%m-01') 
      AND appointmentDate < DATE_FORMAT(NOW(), '%Y-%m-01')
      GROUP BY DATE_FORMAT(appointmentDate, '%Y-%m')
      ORDER BY month";
      
      $stmtAppointments = $conn->prepare($sqlAppointments);
      $stmtAppointments->execute();
      $resultAppointments = $stmtAppointments->get_result();
      
      $months = [];
      $appointmentCounts = [];
      
      // Δημιουργία λίστας με τους προηγούμενους 12 μήνες
      $allMonths = [];
      $currentMonth = date("Y-m");
      
      for ($i = 12; $i > 0; $i--) {
          $allMonths[] = date("Y-m", strtotime("-$i month", strtotime($currentMonth)));
      }
      
      while ($row = $resultAppointments->fetch_assoc()) {
          $months[] = $row['month'];
          $appointmentCounts[] = $row['count'];
      }
      
      // Αντιστοίχιση μηνών με μηδενικές τιμές όπου δεν υπάρχουν δεδομένα
      foreach ($allMonths as $month) {
          if (!in_array($month, $months)) {
              $months[] = $month;
              $appointmentCounts[] = 0;
          }
      }
      
      // Ταξινόμηση των μηνών για να είναι στη σωστή σειρά
      array_multisort($months, SORT_ASC, $appointmentCounts);
    
    
    //_______________________________pinakas 2________________________________________________
    
    // Ερώτημα για τα ραντεβού
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
        <h2 style="text-align: center; padding-bottom: 40px;">Αποτελέσματα Χρηστών Ανά Έτος</h2>
        <select id="yearFilter" onchange="updateChart()">
            <option value="2024" selected>2024</option>
            <option value="2023">2023</option>
            <option value="2022">2022</option>
        </select>
   
    <canvas id="comparisonChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        // Περνάμε τα PHP δεδομένα απευθείας στη JavaScript
        const months = <?php echo json_encode($months); ?>;
        const noSchizophrenia = <?php echo json_encode($no_schizophrenia); ?>;
        const possibleStage = <?php echo json_encode($possible_stage); ?>;
        const schizophreniaStage = <?php echo json_encode($schizophrenia_stage); ?>;

        const ctx = document.getElementById('comparisonChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'No Signs of Schizophrenia',
                        data: noSchizophrenia,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderWidth: 1
                    },
                    {
                        label: 'Possibility of Early Schizophrenia',
                        data: possibleStage,
                        backgroundColor: 'rgba(255, 206, 86, 0.6)',
                        borderWidth: 1
                    },
                    {
                        label: 'Early Schizophrenia',
                        data: schizophreniaStage,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

<script>
    // Δεδομένα για το Pie Chart
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
                    'rgba(255, 99, 132, 0.7)', 
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });

    // Δεδομένα για το Bar Chart
    const months = <?php echo json_encode($months); ?>;
    const appointmentCounts = <?php echo json_encode($appointmentCounts); ?>;

    const ctx2 = document.getElementById('comparisonChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Αριθμός Ραντεβού',
                data: appointmentCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function updateChart() {
        const selectedYear = document.getElementById('yearFilter').value;
        console.log("Selected Year:", selectedYear);
        // TODO: Εδώ μπορείς να προσθέσεις δυναμικό φιλτράρισμα δεδομένων ανά έτος
    }
    
    new PureCounter();
</script>
        </div>
    </div>