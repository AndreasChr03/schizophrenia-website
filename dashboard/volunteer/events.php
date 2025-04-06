<?php
    include "../../config/config.php";
    $user_email = $_SESSION['user']['email'];
    $user_id = $_SESSION['user']['user_id'];
    
    if ($_SESSION['user']['role_id'] != 4) { 
    
        header("Location: ../../index.php");//prepei na einai o admin
        exit();
    }
    
    if (!isset($_SESSION['user'])) {
        header("Location: ../../index.php"); 
        exit();
      }
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id']; // Λήψη του ID από τη φόρμα
    
            $sql = "DELETE FROM events WHERE id = ?";
            $stmt = $conn->prepare($sql);
            
            $email = $_SESSION['user']['email'];
            
            $action = "Ακύρωση εκδήλωσης";
            $stmt1 = $conn->prepare("INSERT INTO logs (email, action) VALUES (?, ?)");
            $stmt1->bind_param("ss", $email, $action);
            $stmt1->execute();
    
            if ($stmt === false) {
                die("Σφάλμα στην προετοιμασία του ερωτήματος: " . $conn->error);
            }
    
            $stmt->bind_param("i", $id);
    
            if ($stmt->execute()) {
                $successMessage = "Η ερώτηση διαγράφηκε επιτυχώς!";
            } else {
                $errorMessage = "Σφάλμα κατά την εκτέλεση του ερωτήματος: " . $stmt->error;
            }
    
            $stmt->close();
        } else {
            $errorMessage = "Δεν παρέχεται έγκυρο ID για διαγραφή.";
        }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['title'], $_POST['date'], $_POST['time'], $_POST['description'], $_POST['organiser'])) {
            // Λήψη δεδομένων από το POST
            $id = $_POST['id']; // Λήψη ID από POST
            $title = $_POST['title']; // Λήψη τίτλου από POST
            $date = $_POST['date']; // Λήψη ημερομηνίας από POST
            $time = $_POST['time']; // Λήψη ώρας από POST
            $description = $_POST['description']; // Λήψη περιγραφής από POST
            $organiser = $_POST['organiser']; // Λήψη διοργανωτή από POST
        
            // Ενημέρωση της βάσης δεδομένων
            $sql = "UPDATE events SET title = ?, date = ?, time = ?, description = ?, organiser = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            
            $email = $_SESSION['user']['email'];
            
            $action = "Ενημέρωση εκδήλωσης";
            $stmt1 = $conn->prepare("INSERT INTO logs (email, action) VALUES (?, ?)");
            $stmt1->bind_param("ss", $email, $action);
            $stmt1->execute();
        
            if ($stmt === false) {
                die("Error in preparing the query: " . $conn->error);
            }
        
            // Δέσιμο παραμέτρων
            $stmt->bind_param("sssssi", $title, $date, $time, $description, $organiser, $id);
        
            // Εκτέλεση του ερωτήματος
            if ($stmt->execute()) {
                $successMessage = "Η εκδήλωση ενημερώθηκε επιτυχώς!";
            } else {
                $errorMessage = "Σφάλμα κατά την εκτέλεση του ερωτήματος: " . $stmt->error;
            }
        
            // Κλείσιμο του statement
            $stmt->close();
        }
        break;

        
        
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['date'], $_POST['time'], $_POST['description'], $_POST['organiser'])) {
            // Λήψη των δεδομένων από το POST
            $title = $_POST['title']; // Τίτλος από τη φόρμα
            $date = $_POST['date'];   // Ημερομηνία από τη φόρμα
            $time = $_POST['time'];   // Ώρα από τη φόρμα
            $description = $_POST['description']; // Περιγραφή από τη φόρμα
            $organiser = $_POST['organiser']; // Οργανωτής από τη φόρμα
        
            
        
            // Εισαγωγή δεδομένων στη βάση
            $sql = "INSERT INTO events (title, date, time, description, organiser,creator_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            $email = $_SESSION['user']['email'];
            
            $action = "Δημιουργία νέας εκδήλωσης";
            $stmt1 = $conn->prepare("INSERT INTO logs (email, action) VALUES (?, ?)");
            $stmt1->bind_param("ss", $email, $action);
            $stmt1->execute();
        
            if ($stmt === false) {
                die("Σφάλμα στην προετοιμασία του ερωτήματος: " . $conn->error);
            }
        
            // Δέσμευση των παραμέτρων (μετά από έλεγχο για αποφυγή SQL injection)
            $stmt->bind_param("sssssi", $title, $date, $time, $description, $organiser, $user_id);
        
            if ($stmt->execute()) {
                $successMessage =  "Η εκδήλωση προστέθηκε επιτυχώς!";
            } else {
                $errorMessage = "Σφάλμα κατά την εκτέλεση του ερωτήματος: ";
            }
        
            // Κλείσιμο του statement και της σύνδεσης
            $stmt->close();
            $conn->close();
        }
        break;
        
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
    <link rel="icon" href="../../assets/img/favicon_32x32.png" sizes="32x32" type="image/png">

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
      max-width: 1400px;
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
      /* Ορίζει ένα ελάχιστο ύψος */
    max-height: none !important;   /* Αφαιρεί τον περιορισμό ύψους */
    overflow-y: auto !important;   /* Προσθέτει κάθετη κύλιση αν χρειάζεται */
    word-wrap: break-word !important;
    white-space: normal !important;
    padding: 20px;  /* Αυξάνει το padding για μεγαλύτερη απόσταση */
    line-height: 2; /* Αυξάνει το ύψος των γραμμών για πιο ευανάγνωστο κείμενο */
    display: block; /* Επιτρέπει στη στήλη να επεκτείνεται ανάλογα με το περιεχόμενο */
}

/* Αυξάνει το πλάτος της στήλης περιγραφής */
#questionsTable th:nth-child(6), #questionsTable td:nth-child(6) {
    width: 300px;  /* Μεγαλύτερο πλάτος για να χωράνε μεγάλα κείμενα */
    /* Ορίζει μέγιστο πλάτος */
}

  </style>

</head>
<body>
<?php
    include "header.php";
    
?>
<?php if (isset($successMessage)): ?>
    <div id="successMessage" class="alert alert-success" role="alert">
        <?php echo $successMessage; ?>
    </div>
<?php endif; ?>
<div class="container_table">
    <h2 style="text-align: center; padding-bottom: 40px;">Εκδηλώσεις</h2>
    <div class="add">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class="bi bi-plus-circle"></i> Νέα Εκδήλωση
    </button>
</div>

    
    
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
          <th>Συμμετέχοντες</th>
          <th>Συμμετοχή</th>
          <th>Διαγραφή</th>
          <th>Ενημέρωση</th>
        </tr>
      </thead>
      <tbody>
        <?php
        
        $sql1 = "
    SELECT e.*, 
           COUNT(p1.email_user) AS participant_count, 
           CASE 
               WHEN p2.email_user IS NOT NULL THEN 1
               ELSE 0
           END AS is_participating
    FROM events e
    LEFT JOIN participants p1 
           ON e.id = p1.id_event  
    LEFT JOIN participants p2 
           ON e.id = p2.id_event AND p2.email_user = ?  
    WHERE e.date > CURDATE() AND creator_id = ?
    GROUP BY e.id, p2.email_user
";

$stmt = $conn->prepare($sql1);
if ($stmt === false) {
    die("Error in preparing the query: " . $conn->error);
}

$stmt->bind_param('si', $user_email, $user_id);
$stmt->execute();

// Ανάκτηση αποτελεσμάτων
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $ctr = 0;
    while ($row = $result->fetch_assoc()) {
        $isParticipating = $row['is_participating'];
        $participantCount = $row['participant_count']; // Αριθμός συμμετεχόντων
        $ctr++;
        
        echo "<tr>";
        echo "<td>" . $ctr . "</td>";
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['organiser']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['time']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . $participantCount . "</td>"; 

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
            // Προσθήκη κουμπιών για διαγραφή
            echo "<td>
            <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal' 
                data-id='" . $row['id'] . "' 
                data-action='delete'>
                <i class='bi bi-trash'></i> Διαγραφή
            </button>
          </td>";
    
    // Προσθήκη κουμπιού για modal ενημέρωσης
    echo "<td>
        <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#updateModal' 
            data-id='" . $row['id'] . "' 
            data-title='" . htmlspecialchars($row['title']) . "' 
            data-date='" . htmlspecialchars($row['date']) . "' 
            data-time='" . htmlspecialchars($row['time']) . "' 
            data-description='" . htmlspecialchars($row['description']) . "' 
            data-organiser='" . htmlspecialchars($row['organiser']) . "' 
            data-action='update'>
            <i class='bi bi-pencil'></i> Ενημέρωση
        </button>
      </td>";

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

<!-- modal -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Προσθήκη Νέας Εκδήλωσης</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body Modal -->
            <div class="modal-body">
    <form action="events.php?action=add" method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Τίτλος:</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Ημερομηνία:</label>
            <input type="date" class="form-control" id="date" name="date" min="" required>
        </div>
        <div class="mb-3">
            <label for="time" class="form-label">Ώρα:</label>
            <input type="time" class="form-control" id="time" name="time" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Περιγραφή:</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="organiser" class="form-label">Διοργανωτής:</label>
            <input type="text" class="form-control" id="organiser" name="organiser" required>
        </div>
        <button type="submit" class="btn btn-primary">Προσθήκη Εκδήλωσης</button>
    </form>
</div>

<script>
// Ρυθμίζουμε την ελάχιστη ημερομηνία στη σημερινή
document.getElementById('date').min = new Date().toISOString().split('T')[0];
</script>
        </div>
    </div>
</div>
        
        
        
        <!-- update modal -->

        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Ενημέρωση Εκδήλωσης</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body Modal -->
            <div class="modal-body">
                <form id="updateForm" method="POST" action="events.php?action=update">
                    <!-- Κρυφό πεδίο για το ID -->
                    <input type="hidden" id="eventId" name="id" value="">

                    <!-- Πεδίο για τον Τίτλο -->
                    <div class="mb-3">
                        <label for="eventTitle" class="form-label">Τίτλος:</label>
                        <input type="text" class="form-control" id="eventTitle" name="title" required>
                    </div>

                    <!-- Πεδίο για την Ημερομηνία -->
                    <div class="mb-3">
                        <label for="eventDate" class="form-label">Ημερομηνία:</label>
                        <input type="date" class="form-control" id="eventDate" name="date" required>
                    </div>

                    <!-- Πεδίο για την Ώρα -->
                    <div class="mb-3">
                        <label for="eventTime" class="form-label">Ώρα:</label>
                        <input type="time" class="form-control" id="eventTime" name="time" required>
                    </div>

                    <!-- Πεδίο για την Περιγραφή -->
                    <div class="mb-3">
                        <label for="eventDescription" class="form-label">Περιγραφή:</label>
                        <textarea class="form-control" id="eventDescription" name="description" rows="3"></textarea>
                    </div>

                    <!-- Πεδίο για τον Διοργανωτή -->
                    <div class="mb-3">
                        <label for="eventOrganiser" class="form-label">Διοργανωτής:</label>
                        <input type="text" class="form-control" id="eventOrganiser" name="organiser" required>
                    </div>

                    <!-- Κουμπί Αποθήκευσης -->
                    <button type="submit" class="btn btn-primary">Αποθήκευση</button>
                </form>
            </div>
        </div>
    </div>
</div>





<!-- Modal Διαγραφής -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Επιβεβαίωση Διαγραφής</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Είστε σίγουροι ότι θέλετε να διαγράψετε αυτό το event;
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ακύρωση</button>
        <form id="deleteForm" method="POST" action="events.php?action=delete">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" id="deleteId" value="">
          <button type="submit" class="btn btn-danger">Διαγραφή</button>
        </form>
      </div>
    </div>
  </div>
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


    var updateModal = document.getElementById('deleteModal');
    updateModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Κουμπί που άνοιξε το modal
        var id = button.getAttribute('data-id');
        var question = button.getAttribute('data-question');
        
        // Ενημέρωση των input πεδίων του modal
        var modalIdInput = updateModal.querySelector('#deleteId');
        var modalQuestionInput = updateModal.querySelector('#updateQuestion');

        modalIdInput.value = id;
        modalQuestionInput.value = question;
    });


    document.addEventListener('DOMContentLoaded', function () {
    const updateModal = document.getElementById('updateModal');
    updateModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Η μεταβλητή που παραλαμβάνει το κουμπί που άνοιξε το modal
        const id = button.getAttribute('data-id');
        const title = button.getAttribute('data-title');
        const date = button.getAttribute('data-date');
        const time = button.getAttribute('data-time');
        const description = button.getAttribute('data-description');
        const organiser = button.getAttribute('data-organiser');

        // Γέμισμα των input του modal
        document.getElementById('eventId').value = id;
        document.getElementById('eventTitle').value = title;
        document.getElementById('eventDate').value = date;
        document.getElementById('eventTime').value = time;
        document.getElementById('eventDescription').value = description;
        document.getElementById('eventOrganiser').value = organiser;
    });
});

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
