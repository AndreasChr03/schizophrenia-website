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
    
    //_______________________________pinakas 2________________________________________________
    
    
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
            <h2 style="text-align: center; padding-bottom: 40px;">Αριθμός Ραντεβού Ανά Μήνα</h2>
            <canvas id="secondChart"></canvas>
        </div>
        <div class="chart-container">
        <h2 style="text-align: center;">
          Αποτελέσματα Χρηστών Ανά Έτος 
          <select id="yearFilter" onchange="updateChart()" style="font-size: 22px; height: 40px; line-height: 40px;">
          </select>
        </h2>            
            <canvas id="comparisonChart"></canvas>
        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let myChart; // Διατηρούμε αναφορά στο chart

function fetchChartData(year) {
    fetch(`fetch_data.php?year=${year}`)
        .then(response => response.json())
        .then(data => {
            updateChartData(data.selected_year, data.year_2025);
        })
        .catch(error => console.error("Error fetching data:", error));
}

function updateChartData(data, data2025) {
    if (myChart) {
        myChart.destroy(); // Καταστρέφουμε το προηγούμενο γράφημα
    }

    const ctx = document.getElementById('comparisonChart').getContext('2d');
    myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Χωρίς Ενδείξεις Σχιζοφρένειας - ' + data.year, 'Πιθανότητα πρώιμης σχιζοφρένειας - ' + data.year, 'Πρώιμη σχιζοφρένεια - ' + data.year, 
                'Χωρίς Ενδείξεις Σχιζοφρένειας - 2025', 'Πιθανότητα πρώιμης σχιζοφρένειας - 2025', 'Πρώιμη σχιζοφρένεια - 2025'
            ],
            datasets: [
                {
                    label: `Στοιχεία ${data.year}`,
                    data: [
                        data.no_schizophrenia, data.possible_stage, data.schizophrenia_stage,
                        data2025.no_schizophrenia, data2025.possible_stage ,data2025.schizophrenia_stage
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)', 'rgba(75, 192, 192, 0.3)', 
                        'rgba(255, 206, 86, 0.6)', 'rgba(255, 206, 86, 0.3)', 
                        'rgba(255, 99, 132, 0.6)', 'rgba(255, 99, 132, 0.3)'
                    ],
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
}

    function updateChart() {
        const selectedYear = document.getElementById('yearFilter').value;
        fetchChartData(selectedYear);
    }

    // Φόρτωση δεδομένων με την πρώτη εμφάνιση της σελίδας
    fetchChartData(2025);
</script>
<script>
    // Δημιουργία των τελευταίων 10 ετών δυναμικά
    function populateYearDropdown() {
        const yearFilter = document.getElementById("yearFilter");
        const currentYear = new Date().getFullYear();

        for (let i = 0; i < 10; i++) {
            let year = currentYear - i;
            let option = document.createElement("option");
            option.value = year;
            option.textContent = year;
            if (i === 0) option.selected = true; // Επιλεγμένο το πιο πρόσφατο έτος
            yearFilter.appendChild(option);
        }
    }

    populateYearDropdown(); // Κλήση της συνάρτησης κατά τη φόρτωση της σελίδας
</script>

<script>
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
</script>
        </div>
    </div>
</body>
</html>