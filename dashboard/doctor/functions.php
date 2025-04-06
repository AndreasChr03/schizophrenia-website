<?php
include"../../config/config.php";

if ($_SESSION['user']['role_id'] != 3) { 
    
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

$registrationNumbers = [];
while ($row = $result->fetch_assoc()) {
    $registrationNumbers[] = $row['registration_number'];
}
 
 ?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../assets/img/favicon1.png" rel="icon">

    <!-- Tab Icon -->
    
    
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
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
  
  #dropdownMenu {
            max-height: 175px; /* Περιορισμός ύψους για 7 εγγραφές */
            overflow-y: auto;  /* Ενεργοποίηση scroll bar */
            position: absolute; /* Για να εμφανίζεται κάτω από το input */
            width: 100%; /* Ίδιο πλάτος με το input */
            background-color: white;
            border: 1px solid #ccc;
            z-index: 1055; /* Πάνω από το modal (1050 είναι το modal default) */
            display: none; /* Κρύβεται αρχικά */
        }

        #dropdownMenu div {
            padding: 8px 10px;
            cursor: pointer;
        }

        #dropdownMenu div:hover {
            background-color: #f0f0f0;
        }
.modal-backdrop {
    z-index: 1040 !important; /* Χαμηλότερο z-index για το backdrop */
    background-color: rgba(0, 0, 0, 0.5) !important; /* Ρυθμίζει το γκρίζο φόντο */
}

/* Ρύθμιση του modal */
.modal-dialog {
    z-index: 1050 !important; /* Υψηλότερο z-index για το modal */
    margin-top: 10%;
}

/* Ρύθμιση του modal-content για καλύτερη εμφάνιση */
.modal-content {
    background-color: #fff;
    border-radius: 8px;
}

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
						
						    
						
						    <div class="col-lg-4 col-md-5" style="padding-right:30px;" data-aos="fade-up" data-aos-delay="200">
							    <div class="service-item position-relative">
							        <div class="icon">
							            <i class="fas fa-notes-medical"></i>
							        </div>
							        <!-- Το κουμπί παραμένει ως έχει αλλά ανοίγει το modal αντί να πηγαίνει στη σελίδα -->
							        <a href="events.php" class="stretched-link">
							            <h3>Εκδηλώσεις</h3>
							        </a>
							        <p>Προσθέστε, Διαγράψτε και Ενημερώστε Εκδηλώσεις</p>
							    </div>
							</div>
							
							
							<div class="col-lg-4 col-md-5" style="padding-right:30px;" data-aos="fade-up" data-aos-delay="300">
							    <div class="service-item position-relative">
							        <div class="icon">
							            <i class="fas fa-notes-medical"></i>
							        </div>
							        <!-- Το κουμπί παραμένει ως έχει αλλά ανοίγει το modal αντί να πηγαίνει στη σελίδα -->
							        <a href="reports.php" class="stretched-link">
							            <h3>Στατιστικά Στοιχεία</h3>
							        </a>
							        <p>Προβολή Στατιστικών Στοιχείων</p>
							    </div>
							</div>
							
						</div>
						
						<div class="row gy-4">
							  <div class="col-lg-4 col-md-5 fade-right-to-left" style="padding-right: 25px;" data-aos="fade-up" data-aos-delay="400">
							    <div class="service-item position-relative">
							      <div class="icon">
							        <i class="fas fa-chart-bar"></i> <!-- Εικονίδιο για ραβδόγραμμα -->
							      </div>
							      <a href="#" class="stretched-link">
							        <h3>Ιατρικά Νέα</h3>
							      </a>
							      <p>Προσθήκη Ιατρικών Νέων</p>
							      <a href="news.php" class="stretched-link"></a>
							    </div>
							  </div>
							  
                <div class="col-lg-4 col-md-5" style="padding-right:30px;" data-aos="fade-up" data-aos-delay="500">
							    <div class="service-item position-relative">
							        <div class="icon">
							            <i class="fas fa-notes-medical"></i>
							        </div>
							        <!-- Το κουμπί παραμένει ως έχει αλλά ανοίγει το modal αντί να πηγαίνει στη σελίδα -->
							        <a href="#" class="stretched-link" data-toggle="modal" data-target="#exampleModal">
							            <h3>Φόρμα Αξιολόγησης Πελατών</h3>
							        </a>
							        <p>Αξιολόγηση και Προβολή Προηγούμενων Ραντεβού  </p>
							    </div>
							</div>
					</div>
				</div>
							
							
	</section>
	
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Επιλέξτε Χρήστη</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body Modal -->
            <div class="modal-body">
                <div id="error-message" class="alert alert-danger d-none" role="alert">
                    Το Registration Number δεν υπάρχει στο σύστημα!
                </div>
                <form id="userForm">
                    <div class="form-group">
                        <label for="selectedUser">Επιλέξτε Registration Number</label>
                        <input list="registrationNumbers" id="selectedUser" name="selectedUser" class="form-control" placeholder="Επιλέξτε ή γράψτε">
                        <datalist id="registrationNumbers">
                            <?php foreach ($registrationNumbers as $regNum): ?>
                                <option value="<?= htmlspecialchars($regNum) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <!-- Alert Message για το error -->
                    
                </form>
            </div>

            <!-- Footer Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλείσιμο</button>
                <button type="button" class="btn btn-primary" onclick="submitUser()">Επιλογή Χρήστη</button>
            </div>
        </div>
    </div>
</div>

<script>
    function submitUser(event) {
        // Ελέγχουμε αν το event υπάρχει πριν το χρησιμοποιήσουμε
        if (event) {
            event.preventDefault();
        }

        // Παίρνουμε το επιλεγμένο registration number
        const selectedUser = document.getElementById("selectedUser").value;
        const errorMessage = document.getElementById("error-message");

        // Μετατρέπουμε τα registration_numbers σε JavaScript array
        const registrationNumbers = <?php echo json_encode($registrationNumbers); ?>;

        if (!registrationNumbers.includes(selectedUser)) {
            // Αν δεν υπάρχει το ID, εμφανίζουμε το μήνυμα λάθους
            errorMessage.classList.remove('d-none');
        } else {
            // Αν υπάρχει, κρύβουμε το μήνυμα και προχωράμε στην ανακατεύθυνση
            errorMessage.classList.add('d-none');
            window.location.href = "form_appointment.php?registration_number=" + selectedUser;
        }
    }
</script>
 



<!-- Vendor JS Files -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script src="../../assets/vendor/php-email-form/validate.js"></script>
<script src="../../assets/vendor/aos/aos.js"></script>
<script src="../../assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="../../assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="../../assets/vendor/swiper/swiper-bundle.min.js"></script>

<!-- Main JS File -->
<script src="../../assets/js/main.js"></script>
 </body>
 </html>