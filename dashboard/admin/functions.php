<?php
session_start();

if ($_SESSION['user']['role_id'] != 1) { 
    
    header("Location: ../../index.php");//prepei na einai o admin
    exit();
}

if (!isset($_SESSION['user'])) {
	header("Location: ../../index.php"); 
	exit();
  }

$userRole = $_SESSION['user']['role_id'];
include"../../config/config.php";

if (!isset($_SESSION['user']['user_id'])) {
	$user_id = $_SESSION['user'];
	
    // Ανακατεύθυνση στη σελίδα login
    header("Location: landing_page/login.php");
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
</head>

<body>

<?php include "header.php"?>
    
<main class="container">
<!--  Section Title -->
<!-- End Section Title -->

<!-- Leitourgies of Dashboard Section - Home Page -->
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
}
#services {
	padding-top:0px;
}

</style>

					<section id="services" class="services section">

						
						<div class="container" style="padding-top: 40px;">
						
						  <div class="row gy-4">
						
						    <div class="col-lg-4 col-md-5" style="padding-right:30px;" data-aos="fade-up" data-aos-delay="100">
						      <div class="service-item  position-relative">
							  <div class="icon flex-shrink-0"><i class="bi bi-person-gear"></i></div>
						        <a href="modify_questionnaire.php" class="stretched-link">
						          <h3>Τροποποίηση ερωτηματολογίου</h3>
						        </a>
						        <p>Διαγραφή, ενημέρωση και προσθήκη ερωτήσεων</p>
						      </div>
						    </div><!-- End Service Item -->
						
						    
						
						    <div class="col-lg-4 col-md-5 fade-right-to-left" style="padding-right:30px;" data-aos="fade-up" data-aos-delay="200">
							  <div class="service-item position-relative">
							    <div class="icon">
							      <i class="fas fa-hospital-user"></i>
							    </div>
							    <a href="user_management.php" class="stretched-link">
							      <h3>Διαχείριση Χρηστών</h3>
							    </a>
							    <p>Προβολή, Μπλοκάρισμα και Προώθηση χρηστών</p>
							  </div>
							</div>
							
							<div class="col-lg-4 col-md-5 fade-right-to-left" data-aos="fade-up" data-aos-delay="300">
							  <div class="service-item position-relative">
							    <div class="icon">
							      <i class="fas fa-calendar-alt"></i> <!-- Εικονίδιο ημερολογίου -->
							    </div>
							    <a href="events.php" class="stretched-link">
							      <h3>Εκδηλώσεις</h3>
							    </a>
							    <p>Προσθέση, Διαγραφεή και Ενημέρωση Εκδηλώσεων</p>
							  </div>
							</div>
							<!-- End Service Item -->
						    
							<div class="row gy-4">
							  <div class="col-lg-4 col-md-5" style="padding-right:30px;" data-aos="fade-up" data-aos-delay="400">
							    <div class="service-item position-relative">
							      <div class="icon">
							        <i class="fas fa-chart-bar"></i> <!-- Εικονίδιο για ραβδόγραμμα -->
							      </div>
							      <a href="#" class="stretched-link">
							        <h3>Στατιστικά Στοιχεία</h3>
							      </a>
							      <p>Προβολή Διαφόρων Στατιστικών Στοιχείων</p>
							      <a href="reports.php" class="stretched-link"></a>
							    </div>
							  </div><!-- End Service Item -->
						    
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
							
							<div class="col-lg-4 col-md-5"  data-aos="fade-up" data-aos-delay="600">
							  <div class="service-item position-relative">
							    <div class="icon">
							      <i class="fas fa-file-alt"></i> <!-- Εικονίδιο που αντιπροσωπεύει αρχεία/καταγραφές -->
							    </div>
							    <a href="logs.php" class="stretched-link">
							      <h3>Ιστορικό Συστήματος (Logs)</h3>
							    </a>
							    <p>Προβολή Ιστορικού Συστήματος</p>
							  </div>
							</div>
						</div>
							
							

			<!-- Modal -->
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
			    <form id="userForm" onsubmit="submitUser(event)">
			        <div class="form-group">
			            <label for="selectedUser">Επιλέξτε Registration Number</label>
			            <!-- Χρήση του datalist για την επιλογή -->
			            <input list="registrationNumbers" id="selectedUser" name="selectedUser" class="form-control" placeholder="Επιλέξτε ή γράψτε">
			            <datalist id="registrationNumbers">
						<?php
		                    if ($result->num_rows > 0) {
		                        while ($row = $result->fetch_assoc()) {
		                            echo '<option value="' . htmlspecialchars($row['registration_number']) . '">';
		                        }
		                    } else {
		                        echo '<option value="">No Registration Numbers Found</option>';
		                    }
		                    ?>
			            </datalist>
			        </div>
			    </form>
			</div>

            <!-- Footer Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλείσιμο</button>
                <button type="submit" class="btn btn-primary" onclick="submitUser()">Επιλογή Χρήστη</button>
            </div>
        </div>
    </div>
</div>

<script>
    function submitUser(event) {
        // Αποφυγή της φυσικής υποβολής της φόρμας
        event.preventDefault();

        // Παίρνουμε το επιλεγμένο registration number
        const selectedUser = document.getElementById("selectedUser").value;

        // Ανακατεύθυνση στη σελίδα form_appointment.php με το επιλεγμένο registration number
        window.location.href = "form_appointment.php?registration_number=" + selectedUser;
    }
</script>
<!-- End Service Item -->
						
						</div>
						
					</div>
				</div>

</section>
					<!-- End dashboard Item -->



<!-- Bootstrap JS, Popper.js, jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // JavaScript για την υποβολή της επιλογής του χρήστη
    function submitUser() {
        var selectedUser = document.getElementById("selectedUser").value;
        alert("Επιλέξατε τον χρήστη με ID: " + selectedUser);
        $('#exampleModal').modal('hide');  // Κλείσιμο του modal
    }
</script>

<script>
    // Συνάρτηση για την αποστολή της επιλογής του χρήστη
    function submitUser() {
        var selectedUserId = document.getElementById('selectedUser').value;

        if (selectedUserId) {
            // Κλείσιμο του modal
            $('#selectUserModal').modal('hide');
            
            // Κατεύθυνση στη σελίδα form_appointment.php με το επιλεγμένο user_id
            window.location.href = 'form_appointment.php?user_id=' + selectedUserId;
        } else {
            alert('Παρακαλώ επιλέξτε έναν χρήστη!');
        }
    }
</script>
<script>
        // Φιλτράρισμα επιλογών dropdown
        function filterDropdown() {
            const input = document.getElementById('selectedUser');
            const filter = input.value.toLowerCase();
            const dropdown = document.getElementById('dropdownMenu');
            const options = dropdown.getElementsByTagName('div');

            let matches = 0;
            for (let i = 0; i < options.length; i++) {
                const txtValue = options[i].textContent || options[i].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    options[i].style.display = "";
                    matches++;
                } else {
                    options[i].style.display = "none";
                }
            }

            dropdown.style.display = matches > 0 ? "block" : "none";
        }

        // Απόκρυψη dropdown όταν γίνεται κλικ έξω από το input
        document.addEventListener('click', function (e) {
            const dropdown = document.getElementById('dropdownMenu');
            const input = document.getElementById('selectedUser');
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = "none";
            }
        });

        // Επιλογή τιμής από το dropdown
        document.getElementById('dropdownMenu').addEventListener('click', function (e) {
            const input = document.getElementById('selectedUser');
            if (e.target.tagName === 'DIV') {
                input.value = e.target.getAttribute('data-value');
                this.style.display = "none";
            }
        });
    </script>

	</div>
</section><!-- End Leitourgies Section -->
</main>


<script src="../../assets/vendor/php-email-form/validate.js"></script>
  <script src="../../assets/vendor/aos/aos.js"></script>
  <script src="../../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../../assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="../../assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="../../assets/js/main.js"></script>

</body>
</html>
