


<?php
    include "../../config/config.php";
    
    if ($_SESSION['user']['role_id'] != 2) { 
    
      header("Location: ../../index.php");//prepei na einai o admin
      exit();
  }
  
  if (!isset($_SESSION['user'])) {
      header("Location: ../../index.php"); 
      exit();
    }
    
    
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
      
        case 'participate':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['id_event']) && isset($_POST['email'])) {
                    $eventId = $_POST['id_event'];
                    $email = $_POST['email'];
            
                    // Συνδέσου με την βάση δεδομένων και αποθήκευσε τη συμμετοχή
                    $sql = "INSERT INTO participants (email_user, id_event) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('si',  $email, $eventId);
                    
                    $action = "Συμμετοχή σε εκδήλωση";
                    $stmt1 = $conn->prepare("INSERT INTO logs (email, action) VALUES (?, ?)");
                    $stmt1->bind_param("ss", $email, $action);
                    $stmt1->execute();
                    
                    if ($stmt->execute()) {
                        $successMessage =  "Επιτυχής συμμετοχή.";
                    } else {
                        $errorMessage = "Σφάλμα κατά την αποθήκευση της συμμετοχής.";
                    }
                }
            }
        break;
        
        case 'cancel':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'cancel') {
                $email = $_POST['email'];
                $eventId = $_POST['id_event'];
            
                $sql = "DELETE FROM participants WHERE email_user = ? AND id_event = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('si', $email, $eventId);
                
                $action = "Ακύρωση συμμετοχής σε εκδήλωση";
                $stmt1 = $conn->prepare("INSERT INTO logs (email, action) VALUES (?, ?)");
                $stmt1->bind_param("ss", $email, $action);
                $stmt1->execute();
                
                if ($stmt->execute()) {
                    $successMessage =  "Η συμμετοχή διαγράφηκε επιτυχώς.";
                } else {
                    $errorMessage = "Σφάλμα στη διαγραφή συμμετοχής: ";
                }
            }
        break;

    default:
        // Αν δεν υπάρχει καμία δράση, μπορούμε να επιστρέψουμε ένα προεπιλεγμένο μήνυμα ή να μην κάνουμε τίποτα.
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../../assets/img/favicon1.png" rel="icon">

    <style>
   
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
    /* Εφαρμογή χρωμάτων κοντά στο μπλε */
    .table {
      border: 1px solid #007BFF;
    }

    .table th, .table td {
      background-color: #f0f8ff; /* Πολύ ανοιχτό μπλε */
      color: #003366; /* Σκούρο μπλε */
    }

    .table thead {
      background-color: #007BFF;
      color: white;
    }

    /* Κεντρικοποίηση του πίνακα στον οριζόντιο άξονα */
    .container_table {
      max-width: 900px;
      margin: 50px auto;  /* Κεντράρει το container στην σελίδα */
    }
    .add {
        padding-bottom: 20px;
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
#questionsTable td:nth-child(6) {
    max-height: 200px;      /* Ρυθμίζει το μέγιστο ύψος του κελιού */
    overflow-y: auto;       /* Ενεργοποιεί την κύλιση αν το περιεχόμενο είναι μεγάλο */
    word-wrap: break-word;  /* Σπάσιμο της λέξης για να μην βγαίνει από το πλαίσιο */
    white-space: normal;    /* Επιτρέπει την πολλαπλή γραμμή */
    padding: 10px;          /* Προσθήκη padding για να είναι πιο ευανάγνωστο το περιεχόμενο */
}

/* Ρύθμιση του πλάτους της στήλης περιγραφής */
#questionsTable th:nth-child(6), #questionsTable td:nth-child(6) {
    width: 350px;           /* Ρυθμίζει το πλάτος της στήλης για να δείχνει πιο ευανάγνωστη */
}
  </style>

</head>
<body>
<?php
    include "header.php";
    $user_email = $_SESSION['user']['email']
?>
<?php if (isset($successMessage)): ?>
    <div id="successMessage" class="alert alert-success" role="alert">
        <?php echo $successMessage; ?>
    </div>
<?php endif; ?>
<div class="container_table">
    <h2 style="text-align: center; padding-bottom: 40px;">Εκδηλώσεις</h2>
    

    
    
    <!-- Πίνακας με τις 5 ερωτήσεις -->
    <table id="questionsTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Τίτλος</th>
          <th>Διοργανωτής</th>
          <th>Ημερομηνία</th>
          <th>Ώρα</th>
          <th>Περιγραφή</th>
          <th>Συμμετοχή</th>
        </tr>
      </thead>
      <tbody>
        <?php
        
                $sql1 = "
            SELECT e.*, 
                   CASE 
                       WHEN p.email_user IS NOT NULL THEN 1
                       ELSE 0
                   END AS is_participating
            FROM events e
            LEFT JOIN participants p 
                   ON e.id = p.id_event AND p.email_user = ?
        ";
        $stmt = $conn->prepare($sql1);
        
        if ($stmt === false) {
            die("Error in preparing the query: " . $conn->error);
        }
        
        $stmt->bind_param('s', $user_email);
        
        // Εκτέλεση του query
        $stmt->execute();
        
        // Ανάκτηση αποτελεσμάτων
        $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $ctr = 0;
        while ($row = $result->fetch_assoc()) {
        $isParticipating = $row['is_participating'];
            $ctr++;
            echo "<tr>";
            // Εμφάνιση του αριθμού σειράς
            echo "<td>" . $ctr  . "</td>";
            // Εμφάνιση της ερώτησης
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['organiser']) . "</td>";
            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['time']) . "</td>";
            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
            if ($isParticipating) {
                // Ο χρήστης συμμετέχει, εμφάνιση κουμπιού για ακύρωση συμμετοχής
                echo "<td><button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#cancelModal' 
                        data-email='" . $user_email . "' 
                        data-id='" . $row['id'] . "' 
                        data-action='cancel'>
                        <i class='bi bi-x-circle'></i> Ακύρωση Συμμετοχής
                    </button></td>";
            } else {
                // Ο χρήστης δεν συμμετέχει, εμφάνιση κουμπιού για συμμετοχή
                echo "<td><button class='btn btn-success btn-sm' data-bs-toggle='modal' data-bs-target='#participationModal' 
                        data-email='" . $user_email . "' 
                        data-id='" . $row['id'] . "' 
                        data-action='participate'>
                        <i class='bi bi-check-circle'></i> Συμμετοχή
                    </button></td>";
            }
           

echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Δεν βρέθηκαν κάποια events.</td></tr>";
    }

    $stmt->close();
    ?>
      </tbody>
    </table>
</div>


<!-- modal participants -->
<div class="modal fade" id="participationModal" tabindex="-1" aria-labelledby="participationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="participationModalLabel">Επιβεβαίωση Συμμετοχής</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Θέλετε να συμμετέχετε σε αυτή την εκδήλωση;

        <!-- Φόρμα για την αποστολή συμμετοχής -->
        <form id="participationForm" method="POST" action="events.php?action=participate">
          <!-- Κρυφό πεδίο για το email του χρήστη -->
          <input type="hidden" name="email" id="userEmail">
          <!-- Κρυφό πεδίο για το eventId -->
          <input type="hidden" name="id_event" id="id_event">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Άκυρο</button>
        <button type="submit" form="participationForm" class="btn btn-primary">Συμμετοχή</button>
      </div>
    </div>
  </div>
</div>



<!-- modal akirosis simmetoxis -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cancelModalLabel">Επιβεβαίωση Ακύρωσης</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Είστε σίγουροι ότι θέλετε να ακυρώσετε τη συμμετοχή σας σε αυτή την εκδήλωση;

        <!-- Φόρμα για την αποστολή ακύρωσης -->
        <form id="cancelForm" method="POST" action="events.php?action=cancel">
          <!-- Κρυφό πεδίο για το email του χρήστη -->
          <input type="hidden" name="email" id="cancelUserEmail">
          <!-- Κρυφό πεδίο για το eventId -->
          <input type="hidden" name="id_event" id="cancelIdEvent">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Όχι</button>
        <button type="submit" form="cancelForm" class="btn btn-danger">Ακύρωση Συμμετοχής</button>
      </div>
    </div>
  </div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Αν υπάρχει το μήνυμα επιτυχίας στην οθόνη, κλείσιμο του μετά από 3 δευτερόλεπτα
    setTimeout(function() {
        var successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 3000); // 3000 ms = 3 δευτερόλεπτα
</script>
<script>

document.addEventListener('DOMContentLoaded', function () {
    var participationModal = document.getElementById('participationModal');
    
    participationModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Κουμπί που ενεργοποίησε το modal
        const email = button.getAttribute('data-email');
        const id = button.getAttribute('data-id');
        // Λαμβάνουμε το email από τη session του PHP
        document.getElementById('userEmail').value = email;
        document.getElementById('id_event').value = id;
        // Εμφανίζουμε το email στο modal
        
    });
});
document.addEventListener('DOMContentLoaded', function () {
    var cancelModal = document.getElementById('cancelModal');

    cancelModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Κουμπί που ενεργοποίησε το modal
        const email = button.getAttribute('data-email');
        const id = button.getAttribute('data-id');
        // Λαμβάνουμε το email και το eventId
        document.getElementById('cancelUserEmail').value = email;
        document.getElementById('cancelIdEvent').value = id;
    });
});




    $(document).ready(function() {
        // Αρχικοποίηση του DataTable για το πίνακα
        $('#questionsTable').DataTable({
            "paging": true,      // Ενεργοποιεί την πλοήγηση (pagination)
            "pageLength": 5,     // Εμφανίζει 5 στοιχεία ανά σελίδα
            "lengthMenu": [5, 10, 25, 50, 100], // Προσθήκη 5 στο dropdown των επιλογών
            "language": {
                "sSearch": "Αναζήτηση:",
                "lengthMenu": "Εμφάνιση _MENU_ ερωτήσεων ανά σελίδα",
                "info": "Εμφανίζονται _START_ έως _END_ από _TOTAL_ ερωτήσεις"
            }
        });
    });
    
</script>
</body>
</html>
