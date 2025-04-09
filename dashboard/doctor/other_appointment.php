<?php
include '../../config/config.php'; // Σύνδεση στη βάση δεδομένων
if (isset($_SESSION['user'])) {
    if (isset($_GET['registration_number'])) {
        $user = $_SESSION['user']; 
    	$userName = $user['name'];
        $user_id = $_GET['registration_number'];
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
    }
}
if (isset($_GET['registration_number']) && isset($_GET['date'])) {
    $registration_number = $_GET['registration_number']; 
    $date = $_GET['date']; // Μετατρέπουμε σε ακέραιο για ασφάλεια
    
    // Παίρνουμε το N-οστό τελευταίο ραντεβού πριν τη σημερινή ημερομηνία
    $query = "
        SELECT ad.*, u.*
        FROM appointment_details ad
        JOIN users u ON ad.clientEmail = u.email
        WHERE u.registration_number = ? AND ad.appointmentDate = ?
        ORDER BY ad.appointmentDate DESC
    ";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("is", $registration_number, $date); // Χρησιμοποιούμε OFFSET για να επιλέξουμε το σωστό ραντεβού
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link rel="icon" href="../../assets/img/favicon_32x32.png" sizes="32x32" type="image/png">

  <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- jQuery (απαραίτητο για DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


  
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
    <h1 class="text-center mb-4">Φόρμα Αξιολόγησης Ψυχιάτρου<?php $user_id?></h1>
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
                        <input type="date" class="form-control" id="appointmentDate" name="appointmentDate" value=<?php echo $date?> required>
                        <div class="invalid-feedback">Παρακαλώ εισάγετε ημερομηνία ραντεβού.</div>
                    </div>

                    <table id="questionsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Ημερομηνία</th>
                                <th>Προβολή</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql1 = "
                                SELECT 
                                    u.registration_number,
                                    u.email,
                                    a.clientEmail,
                                    a.appointmentDate
                                FROM 
                                    users u
                                JOIN 
                                    appointment_details a
                                ON 
                                    u.email = a.clientEmail
                                WHERE u.email = ?
                                ORDER BY a.appointmentDate DESC;
                            ";

                            $stmt = $conn->prepare($sql1);
                            if ($stmt === false) {
                                die("Error in preparing the query: " . $conn->error);
                            }

                            $stmt->bind_param('s', $email);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['appointmentDate']) . "</td>";
                                    echo "<td>
                                        <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#viewModal' 
                                            data-id='" . htmlspecialchars($row['registration_number']) . "'  
                                            data-date='" . htmlspecialchars($row['appointmentDate']) . "'>
                                            <i class='bi bi-eye'></i> Προβολή
                                        </button>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>Δεν βρέθηκαν τα προηγούμενα ραντεβού.</td></tr>";
                            }

                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    

</form>

<!-- DataTable Script -->
<script>
    $(document).ready(function () {
        $('#questionsTable').DataTable({
            pageLength: 5,
            lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
            language: {
                "lengthMenu": "Εμφάνιση _MENU_ εγγραφών ανά σελίδα",
                "zeroRecords": "Δεν βρέθηκαν εγγραφές",
                "info": "Εμφάνιση _START_ έως _END_ από _TOTAL_ εγγραφές",
                "infoEmpty": "Δεν υπάρχουν διαθέσιμες εγγραφές",
                "infoFiltered": "(Φιλτραρισμένο από _MAX_ συνολικές εγγραφές)",
                "search": "Αναζήτηση:",
                "paginate": {
                    "first": "Πρώτη",
                    "last": "Τελευταία",
                    "next": "Επόμενη",
                    "previous": "Προηγούμενη"
                }
            }
        });
    });
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
                        <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Όνομα Χρήστη" value="<?php echo $appointment['name']?>" readonly>
                        <div class="invalid-feedback">Παρακαλώ εισάγετε το όνομά σας.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="userName" class="form-label">Επίθετο Χρήστη</label>
                        <input type="text" class="form-control form-control-lg" id="surname" name="surname" placeholder="Επίθετο Χρήστη" value="<?php echo $appointment['surname']?>" readonly>
                        <div class="invalid-feedback">Παρακαλώ εισάγετε το επίθετό σας.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control form-control-lg" id="clientEmail" name="clientEmail" value="<?php echo $appointment['email']?>" readonly>
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
                            <textarea class='form-control' id='medications' readonly><?php echo  $appointment['medications']?></textarea>                            
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
                            <textarea class="form-control" id="therapyCompliance" name="therapyCompliance" rows="5" style="min-height: 150px;" readonly><?php echo  $appointment['therapy_compliance']?></textarea>                            <div class="invalid-feedback">Παρακαλώ εισάγετε πληροφορίες για τη συμμόρφωση θεραπειών.</div>
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
                            <textarea class="form-control" id="symptoms" name="symptoms" rows="5" style="min-height: 150px;" readonly><?php echo  $appointment['symptoms']?></textarea>
                            <div class="invalid-feedback">Παρακαλώ περιγράψτε τα συμπτώματα.</div>
                        </div>
                        <div class="mb-3">
                            <label for="symptomSeverity" class="form-label">Βαθμός Σοβαρότητας</label>
                            <input class="form-select" id="symptomSeverity" name="symptomSeverity" value="<?php echo  $appointment['symptom_severity']?>" readonly>
                            
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
                            <textarea class="form-control" id="psychiatristNotes" name="psychiatristNotes" rows="5" style="min-height: 150px;" readonly><?php echo  $appointment['psychiatrist_notes']?></textarea>
                            <div class="invalid-feedback">Παρακαλώ εισάγετε παρατηρήσεις.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        

        <?php
$disabled = ''; // Ενεργοποίηση κουμπιού εξ ορισμού

if (isset($conn, $user_id)) {
    $check_query = "
        SELECT COUNT(*) as total 
        FROM appointment_details ad
        JOIN users u ON ad.clientEmail = u.email
        WHERE u.registration_number = ? AND ad.appointmentDate < CURDATE()
    ";

    if ($stmt = $conn->prepare($check_query)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        // Αν δεν υπάρχουν προηγούμενα ραντεβού, το κουμπί γίνεται ανενεργό
        
        
    }
}
?>

<!-- Navigation Buttons -->
<div class="d-flex justify-content-between mb-5">
    <!-- Κουμπί για Προηγούμενη Συνάντηση -->

    <!-- Κουμπί για Σημερινή Συνάντηση -->
    <button type="button" class="btn btn-primary" 
            onclick="redirectToCurrentAppointment(<?php echo $user_id; ?>)">
        Σημερινή Συνάντηση
    </button>
</div>

<script>
    

    // Συνάρτηση για Σημερινή Συνάντηση
    function redirectToCurrentAppointment(user_id) {
        const currentDate = new Date().toISOString().slice(0, 10); // YYYY-MM-DD
        window.location.href = `form_appointment.php?registration_number=${user_id}`;
    }
</script>

<script>
    function redirectToAppointmentPage(userId,value) {
        value += 1; 
        window.location.href = 'previous_appointment.php?user_id=' + userId + '&value=' + value;
    }
</script>