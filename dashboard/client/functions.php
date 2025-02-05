<?php
include"../../config/config.php";

if ($_SESSION['user']['role_id'] != 2) { 
    
  header("Location: ../../index.php");//prepei na einai o admin
  exit();
}

if (!isset($_SESSION['user'])) {
  header("Location: ../../index.php"); 
  exit();
}

	$user_name = $_SESSION['user']['name'] . ' ' . $_SESSION['user']['surname'];
// $loginError = '';
// if ($_SESSION["loggedin"] == false) {
//     header("Location: index.php");
//     exit();
// }
// $role_id = $_SESSION["role_id"];
// mysqli_close($mysqli);
 
$sql = "SELECT registration_number FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
 
 ?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Tab Icon -->
    
    
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="../../assets/img/favicon.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../../assets/css/main.css" rel="stylesheet">
  <style>
  
  

    .row-fluid {
        margin-left: 60px !important; /* Αλλάζεις την τιμή όπως χρειάζεται */
    }
        .row {
	padding-top: 10px;
	padding-bottom:10px;
}
	.dashboard-item:hover {
    background-color: rgba(1, 65, 185, 0.1);
    border-radius: 10px;
  }
  .service-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  text-align: center;
  padding-top: 30px !important; /* Εφαρμογή αλλαγής */
  padding-bottom: 30px !important;
  border-radius: 10px;
  border: 1px solid #ddd;
  padding: 15px 20px;
  margin-bottom: 20px;
  border-radius: 8px;
  transition: all 0.3s ease-in-out;
}
.dashboard-title {
    text-align: center;
    padding-bottom: 0px!important;;
  }


/* Προσαρμογή της διάταξης για τις υπηρεσίες (αν χρειάζεται) */
.row.gy-4 {
  display: flex;
  flex-wrap: wrap;
   /* Απόσταση μεταξύ των service items */
  justify-content: center; /* Κεντράρισμα των service items */
  margin-top:0px;
  padding-top:0px;
  padding-bottom:0px;
}
#Home {
	padding-top:0px;
}
  </style>
 </head>
 <body class="index-page">
 <?php 
    include "header.php";
    
    $user_id = $_SESSION['user'];
    if (!isset($_SESSION['user']['user_id'])) {
        // Ανακατεύθυνση στη σελίδα login
        header("Location: ../../landing_page/login.php");
        exit();
    }
 ?>
 
 <section id="Home" class="services section">

						
						<div class="container">
						
						  <div class="row gy-4">
						
						    <div class="col-lg-4 col-md-5 fade-right-to-left" style="padding-right: 25px;" data-aos="fade-up" data-aos-delay="100">
						      <div class="service-item  position-relative">
							  <div class="icon flex-shrink-0"><i class="bi bi-person-gear"></i></div>
						        <a href="../../myProfile.php" class="stretched-link">
						          <h3>Το Προφίλ μου</h3>
						        </a>
						        <p>Προβολή και ενημέρωση του Προφίλ</p>
						      </div>
						    </div><!-- End Service Item -->
						
						    
						
						    <div class="col-lg-4 col-md-5 fade-right-to-left" data-aos="fade-up" data-aos-delay="200">
							  <div class="service-item position-relative">
							    <div class="icon">
							      <i class="fas fa-hospital-user"></i>
							    </div>
							    <a href="client_medicines.php" class="stretched-link">
							      <h3>Ιστορικό Ραντεβού Χρηστη</h3>
							    </a>
							    <p>Προβολή του ιστορικού (Μελλοντικά ραντεβού)</p>
							  </div>
							</div>
						</div>
						
						<div class="row gy-4">
							  <div class="col-lg-4 col-md-5 fade-right-to-left" style="padding-right: 25px;" data-aos="fade-up" data-aos-delay="300">
							    <div class="service-item position-relative">
							      <div class="icon">
							        <i class="fas fa-chart-bar"></i> <!-- Εικονίδιο για ραβδόγραμμα -->
							      </div>
							      <a href="#" class="stretched-link">
							        <h3>Στατιστικά Στοιχεία</h3>
							      </a>
							      <p>Προβολή Στατιστικών Στοιχείων</p>
							      <a href="reports.php" class="stretched-link"></a>
							    </div>
							  </div>
							  
							<div class="col-lg-4 col-md-5 fade-right-to-left" data-aos="fade-up" data-aos-delay="400">
							  <div class="service-item position-relative">
							    <div class="icon">
							      <i class="fas fa-calendar-alt"></i> <!-- Εικονίδιο ημερολογίου -->
							    </div>
							    <a href="events.php" class="stretched-link">
							      <h3>Εκδηλώσεις</h3>
							    </a>
							    <p>Προσθέστε, Διαγράψτε και Ενημερώστε Εκδηλώσεις σας</p>
							  </div>
							</div>
						</div>
					</div>
							
							
	</section>
 

<!-- Vendor JS Files -->

<script src="../../assets/vendor/php-email-form/validate.js"></script>
<script src="../../assets/vendor/aos/aos.js"></script>
<script src="../../assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="../../assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="../../assets/vendor/swiper/swiper-bundle.min.js"></script>

<!-- Main JS File -->
<script src="../../assets/js/main.js"></script>
 </body>
 </html>