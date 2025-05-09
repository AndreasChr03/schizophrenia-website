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
    
    
    // _____________________________________proto kouti_______________________________
    
    $sql = "SELECT COUNT(*) AS total_users FROM users WHERE role_id = ?";
    $stmt = $conn->prepare($sql);

    // Έλεγχος για λάθη κατά την προετοιμασία του statement
    if ($stmt === false) {
        die("Σφάλμα κατά την προετοιμασία του statement: " . $conn->error);
    }

    // Δέσμευση παραμέτρων και εκτέλεση
    $role_id = 2; // Η τιμή για το role_id
    $stmt->bind_param("i", $role_id); // "i" σημαίνει integer
    $stmt->execute();

    // Λήψη αποτελεσμάτων
    $result = $stmt->get_result();
    $totalUsers = 0;
    if ($result && $row = $result->fetch_assoc()) {
        $totalUsers = $row['total_users'];
    }

    // ___________________________________________deutero kouti____________________________________

    $sql1 = "SELECT COUNT(*) AS new_users FROM users WHERE creation_date > DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH)";
    $stmt1 = $conn->prepare($sql1);

    // Έλεγχος για λάθη κατά την προετοιμασία του statement
    if ($stmt1 === false) {
        die("Σφάλμα κατά την προετοιμασία του statement: " . $conn->error);
    }

    // Εκτέλεση του statement
    $stmt1->execute();

    // Λήψη αποτελεσμάτων
    $result = $stmt1->get_result();
    $newUsers = 0;
    if ($result && $row = $result->fetch_assoc()) {
        $newUsers = $row['new_users'];
    }

    // __________________________________________3 tou pinaka__________________________________________
    
    $sql3 = "SELECT COUNT(*) AS total_females FROM users WHERE gender = ? ";
    $stmt = $conn->prepare($sql3);

    // Έλεγχος για λάθη κατά την προετοιμασία του statement
    if ($stmt === false) {
        die("Σφάλμα κατά την προετοιμασία του statement: " . $conn->error);
    }

    // Δέσμευση παραμέτρων και εκτέλεση
    $gender = "female"; 
    $role_id = 2;
    $stmt->bind_param("s",$gender); // "i" σημαίνει integer
    $stmt->execute();

    // Λήψη αποτελεσμάτων
    $result = $stmt->get_result();
    $totalUsers = 0;
    if ($result && $row = $result->fetch_assoc()) {
        $totalWomen = $row['total_females'];
    }
    
    
    //______________________________________4 tou pinaka________________________________________
    
    $sql3 = "SELECT COUNT(*) AS total_men FROM users WHERE gender = ?";
    $stmt = $conn->prepare($sql3);

    // Έλεγχος για λάθη κατά την προετοιμασία του statement
    if ($stmt === false) {
        die("Σφάλμα κατά την προετοιμασία του statement: " . $conn->error);
    }

    // Δέσμευση παραμέτρων και εκτέλεση
    $gender = "male"; 
    $role_id = 2;
    $stmt->bind_param("s",$gender, ); // "i" σημαίνει integer
    $stmt->execute();

    // Λήψη αποτελεσμάτων
    $result = $stmt->get_result();
    $totalUsers = 0;
    if ($result && $row = $result->fetch_assoc()) {
        $totalMen = $row['total_men'];
    }
    
    // _____________________________________proto kouti_______________________________
    $sql = "SELECT COUNT(*) AS total_users FROM users";
    $stmt = $conn->prepare($sql);
    
    // Έλεγχος για λάθη κατά την προετοιμασία του statement
    if ($stmt === false) {
        die("Σφάλμα κατά την προετοιμασία του statement: " . $conn->error);
    }
    
    // Δέσμευση παραμέτρων και εκτέλεση
    $role_id = 2;
    $stmt->execute();
    $result = $stmt->get_result();
    $totalUsers = $result->fetch_assoc()['total_users'] ?? 0;
    
    
    // ___________________________________________deutero kouti____________________________________
    $sql1 = "SELECT COUNT(*) AS new_users FROM users WHERE creation_date > DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH)";
    $stmt1 = $conn->prepare($sql1);
    if ($stmt1 === false) {
        die("Σφάλμα κατά την προετοιμασία του statement: " . $conn->error);
    }
    $stmt1->execute();
    $result = $stmt1->get_result();
    $newUsers = $result->fetch_assoc()['new_users'] ?? 0;
    
    
    // __________________________________________pinakas 1__________________________________________
    $sqlYears = "SELECT DISTINCT YEAR(date) as year FROM rating ORDER BY year DESC";
$resultYears = $conn->query($sqlYears);

$years = [];
while ($row = $resultYears->fetch_assoc()) {
    $years[] = $row['year'];
}

// Τώρα πάρε το έτος που επιλέχθηκε ή το πιο πρόσφατο
$selectedYear = $_GET['year'] ?? $years[0];

// Πάρε αποτελέσματα μόνο για το συγκεκριμένο έτος
$sqlGraph = "SELECT result, COUNT(*) AS count FROM rating WHERE YEAR(date) = ? GROUP BY result";
$stmtGraph = $conn->prepare($sqlGraph);
$stmtGraph->bind_param("i", $selectedYear);
$stmtGraph->execute();
$result = $stmtGraph->get_result();

$resultsData = [];
$counts = [];
while ($row = $result->fetch_assoc()) {
    $resultsData[] = $row['result'];
    $counts[] = $row['count'];
    $totalResultsForYear = array_sum($counts);
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
    <link rel="icon" href="../../assets/img/favicon_32x32.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" href="reports.css">
</head>
<body>
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



        <!-- Main content section -->
        <div class="content">

        
    <section id="stats" class="stats section light-background" style="padding-bottom: 20;">

        <div class="container" data-aos="fade-up" data-aos-delay="100">
        
          <div class="row gy-4">
        
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
              <i class="fa-solid fa-user-doctor"></i>
              <div class="stats-item">
                <span data-purecounter-start="0" data-purecounter-end="<?= $totalUsers ?>" data-purecounter-duration="1" class="purecounter"></span>
                <p>Χρήστες</p>
              </div>
            </div><!-- End Stats Item -->
        
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
              <i class="fa-regular fa-hospital"></i>
              <div class="stats-item">
                <span data-purecounter-start="0" data-purecounter-end="<?= $newUsers ?>" data-purecounter-duration="1" class="purecounter"></span>
                <p>Χρήστες τους Τελευταίους 3 Μήνες</p>
              </div>
            </div><!-- End Stats Item -->
        
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
              <i class="fas fa-flask"></i>
              <div class="stats-item">
                <span data-purecounter-start="0" data-purecounter-end="<?= $totalWomen ?>" data-purecounter-duration="1" class="purecounter"></span>
                <p>Γυναίκες</p>
              </div>
            </div><!-- End Stats Item -->
        
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
              <i class="fas fa-award"></i>
              <div class="stats-item">
                <span data-purecounter-start="0" data-purecounter-end="<?= $totalMen ?>" data-purecounter-duration="1" class="purecounter"></span>
                <p>Άντρες</p>
              </div>
            </div><!-- End Stats Item -->
        
          </div>
        
        </div>
        
        </section><!-- /Stats Section -->
        <section id="stats" class="stats section light-background">
          <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
                <!-- Stats content can go here -->
            </div>
          </div>
        </section>

        <div class="charts-row" style="display: flex; justify-content: center;">
    <div class="chart-container" style="max-width: 800px; width: 100%;">
        <h2 style="text-align: center;">
            Αποτελέσματα Χρηστών Ανά Έτος
            <select id="yearFilter" onchange="updateChart()" style="font-size: 22px; height: 40px;">
                <?php foreach ($years as $year): ?>
                    <option value="<?= $year ?>" <?= ($year == $selectedYear ? 'selected' : '') ?>><?= $year ?></option>
                <?php endforeach; ?>
            </select>
        </h2>
        
        <p style="text-align: center; font-size: 20px;">
            Σύνολο χρηστών για το έτος 
            <span id="selectedYear"><?= $selectedYear ?></span>: 
            <strong id="totalUsers"><?= $totalResultsForYear ?? "0" ?></strong>
        </p>
        
        <canvas id="resultsChart"></canvas>
    </div>

        <!-- <div class="chart-container">
            <h2 style="text-align: center; padding-bottom: 40px;">Αριθμός Ραντεβού Ανά Μήνα</h2>
            <canvas id="secondChart"></canvas>
        </div> -->
    </div>

    
    <script>
    const chartLabels = <?= json_encode($resultsData) ?>;
    const chartData = <?= json_encode($counts) ?>;
    const ctx = document.getElementById('resultsChart').getContext('2d');
    let chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'Αριθμός Αξιολογήσεων',
            data: chartData,
            backgroundColor: [
                'rgba(75, 192, 192, 0.7)',   // Τιρκουάζ
                'rgba(255, 99, 132, 0.7)',   // Κόκκινο
                'rgba(255, 206, 86, 0.7)',   // Κίτρινο
                // Μπορείς να επαναλαμβάνεις τα χρώματα αν έχεις περισσότερα δεδομένα
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 206, 86, 1)',
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

    function updateChart() {
        const year = document.getElementById("yearFilter").value;
        window.location.href = "?year=" + year; // Φορτώνει ξανά τη σελίδα με το νέο έτος
    }

    




//     const appointmentMonths = <?php echo json_encode($months); ?>;
// const appointmentCounts = <?php echo json_encode($appointmentCounts); ?>;

// const ctx2 = document.getElementById('secondChart').getContext('2d');
// new Chart(ctx2, {
//     type: 'line',
//     data: {
//         labels: appointmentMonths,
//         datasets: [{
//             label: 'Ραντεβού ανά Μήνα (12 Προηγούμενοι Μήνες)',
//             data: appointmentCounts,
//             borderColor: 'rgba(54, 162, 235, 1)', // Πράσινο
//             backgroundColor: 'rgba(54, 162, 235, 0.7)', // Διαφανές Πράσινο
//             fill: true,
//             tension: 0.4
//         }]
//     },
//     options: {
//         responsive: true,
//         scales: {
//             y: { beginAtZero: true }
//         }
//     }
// });

    new PureCounter();
</script>

        </div>
    </div>
</body>
</html>
