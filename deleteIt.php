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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="reports.css">
    <title>Dashboard</title>
    
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
    
    <div class="content">
            <section id="stats" class="stats section light-background">
        
        <div class="container" data-aos="fade-up" data-aos-delay="100">
        
          <div class="row gy-4">
        
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
              <i class="fa-solid fa-user-doctor"></i>
              <div class="stats-item">
                <span data-purecounter-start="0" data-purecounter-end="85" data-purecounter-duration="1" class="purecounter"></span>
                <p>Doctors</p>
              </div>
            </div><!-- End Stats Item -->
        
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
              <i class="fa-regular fa-hospital"></i>
              <div class="stats-item">
                <span data-purecounter-start="0" data-purecounter-end="18" data-purecounter-duration="1" class="purecounter"></span>
                <p>Departments</p>
              </div>
            </div><!-- End Stats Item -->
        
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
              <i class="fas fa-flask"></i>
              <div class="stats-item">
                <span data-purecounter-start="0" data-purecounter-end="12" data-purecounter-duration="1" class="purecounter"></span>
                <p>Research Labs</p>
              </div>
            </div><!-- End Stats Item -->
        
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
              <i class="fas fa-award"></i>
              <div class="stats-item">
                <span data-purecounter-start="0" data-purecounter-end="150" data-purecounter-duration="1" class="purecounter"></span>
                <p>Awards</p>
              </div>
            </div><!-- End Stats Item -->
        
          </div>
        
        </div>
        
        </section>


        </div>
    </div>
</body>
</html>