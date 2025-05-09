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
    
    $sql3 = "SELECT COUNT(*) AS total_females FROM users WHERE gender = ?";
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
    $stmt->bind_param("s",$gender); // "i" σημαίνει integer
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

    $stmt->execute();
    $result = $stmt->get_result();
    $totalUsers = $result->fetch_assoc()['total_users'] ?? 0;
    
    
    // ___________________________________________deutero kouti____________________________________
    
    $sql = "SELECT result, COUNT(*) AS total FROM rating GROUP BY result";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Αρχικοποίηση των counters
$early_schizophrenia = 0;
$no_signs = 0;
$possibility_early = 0;

while ($row = $result->fetch_assoc()) {
  $category = trim(strtolower($row['result'])); // Καθαρίζουμε τυχόν κενά και μετατρέπουμε σε πεζά

  switch ($category) {
      case 'Πιθανότητα πρώιμης σχιζοφρένειας':
          $possibility_early = $row['total'];
          break;
      case 'Χωρίς Ενδείξεις Σχιζοφρένειας':
          $no_signs = $row['total'];
          break;
      case 'Πρώιμη σχιζοφρένεια':
          $early_schizophrenia = $row['total'];
          break;
      default:
          echo "Άγνωστη κατηγορία: " . $row['result'] . "<br>"; // Για αποσφαλμάτωση
  }
}
    
    
    //___________________________________________deutero kouti____________________________________________
    
    //____________________________________________3 kouti_________________________________________________
    $sql = "SELECT nationality AS city, COUNT(*) AS total_users 
        FROM users 
        GROUP BY nationality 
        ORDER BY total_users DESC 
        LIMIT 4";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$top_cities = [];

while ($row = $result->fetch_assoc()) {
    $top_cities[] = $row; // Αποθηκεύουμε τις πόλεις στο array
}
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="reports.css">
    <link rel="icon" href="../../assets/img/favicon_32x32.png" sizes="32x32" type="image/png">
    <title>Dashboard</title>
    
    
</head>
<body>
    <?php include "header.php"; ?>
        <div class="container-fluid">
            <!-- Sidebar section -->
            <div class="sidebar" style="position: fixed;">
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
    
    <div class="content" style="padding-left:200px;">
        <section id="stats" class="stats section light-background">

            <div class="container" data-aos="fade-up" data-aos-delay="100">
              <h2 style="text-align: center; padding-bottom: 40px;">Στατιστικά Χρηστών</h2>
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
        </section>
        
        <section id="stats" class="stats section light-background">

            <div class="container" data-aos="fade-up" data-aos-delay="200">
            <h2 style="text-align: center; padding-bottom: 40px;">Ερωτήσεις Ερωτηματολογίου</h2>
            <div class="row gy-4">
              <strong id="totalUsers" style="display: block; text-align: center;">
                  Ο συνολικός αριθμός Ερωτηματολογίων είναι 
                  <?= $no_signs + $possibility_early + $early_schizophrenia;?>
              </strong>
            </div>
              <div class="row gy-4">
            
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center">
                  <i class="fa-solid fa-user-doctor"></i>
                  <div class="stats-item">
                    <span data-purecounter-start="0" data-purecounter-end="<?= $no_signs ?>" data-purecounter-duration="1" class="purecounter"></span>
                    <p>Χωρίς Ενδείξεις Σχιζοφρένειας</p>
                  </div>
                </div><!-- End Stats Item -->
            
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center">
                  <i class="fa-regular fa-hospital"></i>
                  <div class="stats-item">
                    <span data-purecounter-start="0" data-purecounter-end="<?= $possibility_early ?>" data-purecounter-duration="1" class="purecounter"></span>
                    <p>Πιθανότητα πρώιμης σχιζοφρένειας</p>
                  </div>
                </div><!-- End Stats Item -->
            
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center">
                  <i class="fas fa-flask"></i>
                  <div class="stats-item">
                    <span data-purecounter-start="0" data-purecounter-end="<?= $early_schizophrenia  ?>" data-purecounter-duration="1" class="purecounter"></span>
                    <p> Πρώιμη σχιζοφρένεια</p>
                  </div>
                </div><!-- End Stats Item -->
            
              </div>
            
            </div>
        </section>
        
        <section id="stats" class="stats section light-background">

            <div class="container" data-aos="fade-up" data-aos-delay="100">
            <h2 style="text-align: center; padding-bottom: 40px;">Οι 4 Πόλεις με τη Μεγαλύτερη Συμμετοχή</h2>
            <div class="row gy-4">
              <strong id="totalUsers" style="display: block; text-align: center;">
                  Ο συνολικός αριθμός χρηστών είναι 
                  <?= $totalUsers?>
              </strong>
            </div>
              <div class="row gy-4">
            
                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                  <i class="fa-regular fa-hospital"></i>
                  <div class="stats-item">
                    <span data-purecounter-start="0" data-purecounter-end="<?= $top_cities[0]['total_users'] ?? 0 ?>" data-purecounter-duration="1" class="purecounter"></span>
                    <p><?= $top_cities[0]['city'] ?? '-' ?></p>
                  </div>
                </div><!-- End Stats Item -->
            
                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                  <i class="fa-regular fa-hospital"></i>
                  <div class="stats-item">
                    <span data-purecounter-start="0" data-purecounter-end="<?= $top_cities[1]['total_users'] ?? 0 ?>" data-purecounter-duration="1" class="purecounter"></span>
                    <p><?= $top_cities[1]['city'] ?? '-' ?></p>
                  </div>
                </div><!-- End Stats Item -->
            
                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                  <i class="fa-regular fa-hospital"></i>
                  <div class="stats-item">
                    <span data-purecounter-start="0" data-purecounter-end="<?= $top_cities[2]['total_users'] ?? 0 ?>" data-purecounter-duration="1" class="purecounter"></span>
                    <p><?= $top_cities[2]['city'] ?? '-' ?></p>
                  </div>
                </div><!-- End Stats Item -->
            
                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                  <i class="fa-regular fa-hospital"></i>
                  <div class="stats-item">
                    <span data-purecounter-start="0" data-purecounter-end="<?= $top_cities[3]['total_users'] ?? 0 ?>" data-purecounter-duration="1" class="purecounter"></span>
                    <p><?= $top_cities[3]['city'] ?? '-' ?></p>
                  </div>
                </div><!-- End Stats Item -->
            
              </div>
            
            </div>
        </section>
        </div>
    </div>
    <script>
        new PureCounter();
    </script>
</body>
</html>