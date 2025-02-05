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


if (isset($_SESSION['user'])) {
    if (isset($_GET['user_id'])) {
        $user = $_SESSION['user']; 
    	$userName = $user['name'];
        $user_id = $_GET['user_id'];
        $user_role = $user['role_id'];
    
    
    
    	// Prepare and execute the SQL query
    	$sql = "SELECT * FROM users WHERE registration_number = ?";
    	$stmt = $conn->prepare($sql);
    	$stmt->bind_param("i", $user_id);
    	$stmt->execute();
    	$result = $stmt->get_result();
    	
    	if ($result->num_rows > 0) {
    		while ($row = $result->fetch_assoc()) {
    			$email = $row['email'];
    			$name = $row['name'];
    			$surname = $row['surname'];
    		}
    	}
    	
    	
    	// pairno ta dedomena apo to modal pou thelo
    }
    
        
}
// PHP Form for Psychiatrist to Fill Appointment Details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Συλλογή δεδομένων από τη φόρμα
    $appointmentDate = $_POST['appointmentDate'];
    $name = $_POST['name'];
    $clientEmail = $_POST['clientEmail'];
    $medications = $_POST['medications'];
    $therapyCompliance = $_POST['therapyCompliance'];
    $symptoms = $_POST['symptoms'];
    $symptomSeverity = $_POST['symptomSeverity'];
    $psychiatristNotes = $_POST['psychiatristNotes'];

    // SQL ερώτημα για εισαγωγή δεδομένων
    $sql = "INSERT INTO appointment_details (
                appointmentDate, name, clientEmail, medications, 
                therapy_compliance, symptoms, symptom_severity, 
                psychiatrist_notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                
            
                $action = "Φόρμα αξιολόγησης Πελάτη";
                $stmt1 = $conn->prepare("INSERT INTO logs (email, action) VALUES (?, ?)");
                $stmt1->bind_param("ss", $clientEmail, $action);
                $stmt1->execute();

    // Προετοιμασία και εκτέλεση ερωτήματος
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss",$appointmentDate, $name, $clientEmail, $medications,$therapyCompliance, $symptoms, $symptomSeverity, $psychiatristNotes);

    if ($stmt->execute()) {
        $successMessage = "Τα δεδομένα αποθηκεύτηκαν επιτυχώς!";
        echo "<script>
        setTimeout(() => {
                    window.location.href = 'functions.php'; // Αλλάξτε τη σελίδα με τη δική σας
                }, 500);
              </script>";
    } else {
        $errorMessage = "Σφάλμα κατά την εκτέλεση του ερωτήματος: " . $stmt->error;
    }

    $stmt->close();
    } else {
    
    }   


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - Medilab Bootstrap Template</title>
  

  <!-- =======================================================
  * Template Name: Medilab
  * Template URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<style>
.form-section {
    margin-bottom: 20px;
}
input.form-control {
    height: 40px; /* Ύψος */
    font-size: 1.1rem; /* Μέγεθος γραμματοσειράς */
    border-radius: 5px;
}
#all_form {
    margin-bottom: 20px !important;
}
#successMessage {
    position: fixed;
    top: 20%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: auto;
    max-width: 400px; /* Ρύθμιση πλάτους, μπορείς να το προσαρμόσεις */
    padding: 15px;
    z-index: 9999;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
}
</style>

<body class="index-page">

  <?php include "header.php"?>
  
  <!-- minimata epitixias kai apotixias  -->
  <?php if (isset($successMessage)): ?>
    <div id="successMessage" class="alert alert-success" role="alert">
        <?php echo $successMessage; ?>
    </div>
<?php endif; ?>


<?php if (isset($errorMessage)): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $errorMessage; ?>
    </div>
<?php endif; ?>

  <div class="container mt-5">
    <h1 class="text-center mb-4">Φόρμα Αξιολόγησης Ψυχιάτρου</h1>
    <form action="form_appointment.php" method="POST" class="needs-validation" novalidate>

        <!-- Appointment and User Details -->
        <div class="row g-4 mb-4">
            <!-- Appointment Details -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">Πληροφορίες Ραντεβού</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="appointmentDate" class="form-label">Ημερομηνία Ραντεβού</label>
                            <input type="date" class="form-control" id="appointmentDate" name="appointmentDate" required>
                            <div class="invalid-feedback">Παρακαλώ εισάγετε ημερομηνία ραντεβού.</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                window.onload = function() {
                    var today = new Date();
                    today.setDate(today.getDate());  // Προσθέτει 1 μέρα για να γίνει αυριανή
                
                    var dd = String(today.getDate()).padStart(2, '0');  // Μορφοποίηση της ημερομηνίας
                    var mm = String(today.getMonth() + 1).padStart(2, '0');  // Μήνας (προσθέτουμε 1 γιατί οι μήνες είναι 0-based)
                    var yyyy = today.getFullYear();
                
                    var today = yyyy + '-' + mm + '-' + dd;  // Δημιουργία της ημερομηνίας στην κατάλληλη μορφή (YYYY-MM-DD)
                
                    document.getElementById("appointmentDate").value = today;  // Ρύθμιση της αυριανής ημερομηνίας στο input
                };
            </script>

            <!-- User Details -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="card-title mb-0">Στοιχεία Χρήστη</h4>
                    </div>
                    <div class="card-body">
                    <div class="mb-3">
                        <label for="userName" class="form-label">Όνομα Χρήστη</label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Όνομα Χρήστη" value= <?php echo $name ?> required>
                        <div class="invalid-feedback">Παρακαλώ εισάγετε το όνομά σας.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="userName" class="form-label">Επίθετο Χρήστη</label>
                        <input type="text" class="form-control form-control-lg" id="surname" name="surname" placeholder="Επίθετο Χρήστη" value= <?php echo $surname ?> required>
                        <div class="invalid-feedback">Παρακαλώ εισάγετε το επίθετό σας.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control form-control-lg" id="clientEmail" name="clientEmail" placeholder="you@example.com" value= <?php echo $email?> required>
                        <div class="invalid-feedback">Παρακαλώ εισάγετε ένα έγκυρο email.</div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medication and Side Effects -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h4 class="card-title mb-0">Ιστορικό Φαρμακευτικής Αγωγής</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="medications" class="form-label">
                                <i class="fas fa-pills"></i> Φάρμακα και Παρενέργειες
                            </label>
                            <textarea class="form-control" id="medications" name="medications" rows="5" style="min-height: 150px;" placeholder="Γράψτε τα φάρμακα και τυχόν παρενέργειες..." required></textarea>
                            <div class="invalid-feedback">Παρακαλώ εισάγετε λεπτομέρειες για τα φάρμακα.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Therapy Compliance -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h4 class="card-title mb-0">Συμμόρφωση Θεραπειών</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="therapyCompliance" class="form-label">Κατάσταση Θεραπειών</label>
                            <textarea class="form-control" id="therapyCompliance" name="therapyCompliance" rows="5" style="min-height: 150px;" placeholder="Έκανε τις θεραπείες του; Έλαβε τα φάρμακά του; Προβλήματα που προέκυψαν..." required></textarea>
                            <div class="invalid-feedback">Παρακαλώ εισάγετε πληροφορίες για τη συμμόρφωση θεραπειών.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Symptoms and Psychiatrist Notes -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="card-title mb-0">Παρακολούθηση Συμπτωμάτων</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="symptoms" class="form-label">Περιγραφή Συμπτωμάτων</label>
                            <textarea class="form-control" id="symptoms" name="symptoms" rows="5" style="min-height: 150px;" placeholder="Περιγράψτε τα συμπτώματα..." required></textarea>
                            <div class="invalid-feedback">Παρακαλώ περιγράψτε τα συμπτώματα.</div>
                        </div>
                        <div class="mb-3">
                            <label for="symptomSeverity" class="form-label">Βαθμός Σοβαρότητας</label>
                            <select class="form-select" id="symptomSeverity" name="symptomSeverity" required>
                                <option value="">Επιλέξτε...</option>
                                <option value="mild">Ήπια</option>
                                <option value="moderate">Μέτρια</option>
                                <option value="severe">Σοβαρή</option>
                            </select>
                            <div class="invalid-feedback">Παρακαλώ επιλέξτε βαθμό σοβαρότητας.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-danger text-white">
                        <h4 class="card-title mb-0">Συμπεράσματα Ψυχιάτρου</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="psychiatristNotes" class="form-label">Παρατηρήσεις</label>
                            <textarea class="form-control" id="psychiatristNotes" name="psychiatristNotes" rows="5" style="min-height: 150px;" placeholder="Γράψτε τις παρατηρήσεις..." required></textarea>
                            <div class="invalid-feedback">Παρακαλώ εισάγετε παρατηρήσεις.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="d-flex justify-content-between mb-5">
            <button type="button" class="btn btn-secondary" onclick="history.back()">Προηγούμενη Συνάντηση</button>
            <button type="submit" class="btn btn-primary">Υποβολή</button>
        </div>
    </form>
</div>




    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Επαλήθευση φόρμας Bootstrap
        (function () {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
     <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


<script>
    // Αν υπάρχει το μήνυμα επιτυχίας στην οθόνη, κλείσιμο του μετά από 3 δευτερόλεπτα
    setTimeout(function() {
        var successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 3000); // 3000 ms = 3 δευτερόλεπτα
</script>
</body>
</html>

