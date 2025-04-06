<?php
    include "../../config/config.php";
    
    

    if ($_SESSION['user']['role_id'] != 3) { 
    
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
    
            // Διαγραφή από τη βάση δεδομένων
            $sql = "DELETE FROM news WHERE id = ?";
            $stmt = $conn->prepare($sql);
            
            // Καταγραφή της ενέργειας στο logs πίνακα
            $email = $_SESSION['user']['email'];
            $action1 = "Διαγραφή ερώτησης";
            $stmt1 = $conn->prepare("INSERT INTO logs (email, action) VALUES (?, ?)");
            $stmt1->bind_param("ss", $email, $action1);
            $stmt1->execute();
        
            // Έλεγχος για σφάλμα στην προετοιμασία του query
            if ($stmt === false) {
                die("Σφάλμα στην προετοιμασία του ερωτήματος: " . $conn->error);
            }
        
            // Δέσιμο του ID και εκτέλεση της διαγραφής
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['title'], $_POST['description'])) {
            $id = $_POST['id']; // Λήψη ID από POST
            $title = $_POST['title']; // Λήψη τίτλου από POST
            $description = $_POST['description']; // Λήψη ερώτησης από POST
        
            $sql = "UPDATE news SET title = ?, description = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
        
            if ($stmt === false) {
                die("Error in preparing the query: " . $conn->error);
            }
        
            $stmt->bind_param("ssi", $title, $description, $id);
        
            if ($stmt->execute()) {
                $successMessage = "Η ερώτηση ενημερώθηκε επιτυχώς!";
            } else {
                $errorMessage = "Σφάλμα κατά την εκτέλεση του ερωτήματος: " . $stmt->error;
            }
        
            // Καταγραφή στο log
            $email = $_SESSION['user']['email'];
            $action1 = "Ενημέρωση είδησης";
            $stmt1 = $conn->prepare("INSERT INTO logs (email, action) VALUES (?, ?)");
            $stmt1->bind_param("ss", $email, $action1);
            $stmt1->execute();
        
            $stmt->close();
        }
        
        break;

        
        
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['details'])) {
            $question = $_POST['title'];
            $details = $_POST['details'];
            $user_id = $_SESSION['user']['user_id'];
        
            // Προετοιμασία του SQL ερωτήματος για την εισαγωγή της είδησης
            $sql = "INSERT INTO news (author_id, title, description) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
        
            // Εισαγωγή καταγραφής του χρήστη που έκανε την ενέργεια (log)
            $email = $_SESSION['user']['email'];
            $action1 = "Προσθήκη νέας ιατρικής είδησης";
            $stmt1 = $conn->prepare("INSERT INTO logs (email, action) VALUES (?, ?)");
            $stmt1->bind_param("ss", $email, $action1);
            $stmt1->execute();
        
            // Ελέγχουμε αν υπάρχει σφάλμα στην προετοιμασία του query
            if ($stmt === false) {
                die("Error in preparing the query: " . $conn->error);
            }
        
            // Δέσιμο των παραμέτρων και εκτέλεση του query
            $stmt->bind_param("iss",$user_id, $question, $details);
        
            if ($stmt->execute()) {
                $successMessage = "Η νέα ιατρική είδηση αποθηκεύτηκε επιτυχώς!";
            } else {
                $errorMessage = "Σφάλμα κατά την εκτέλεση του ερωτήματος: " . $stmt->error;
            }
        
            $stmt->close();
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
    <h2 style="text-align: center; padding-bottom: 40px;">Ιατρικά Νέα</h2>
    <div class="add">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Προσθήκη Ιατρικής Είδησης</button>
    </div>


    <table id="newsTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Τίτλος</th>
          <th>Ιατρικά Νέα</th>
          <th>Ακύρωση</th>
          <th>Ενημέρωση</th>
        </tr>
      </thead>
      <tbody>
        <?php
         $user_id = $_SESSION['user']['user_id'];
        $sql2 = "
            SELECT n.id, n.title, n.description 
            FROM news n
            JOIN users u ON n.author_id = u.user_id
            WHERE u.user_id = ?
            ORDER BY n.id DESC
        ";

        if ($stmt2 = $conn->prepare($sql2)) {
            $stmt2->bind_param("i", $user_id);  // Φιλτράρουμε μόνο τις ειδήσεις του συγκεκριμένου χρήστη
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($result2->num_rows > 0) {
                $ctr = 0;
                while ($row = $result2->fetch_assoc()) {
                    $ctr++;
                    echo "<tr>";
                    echo "<td>" . $ctr  . "</td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";

                    // Κουμπί ακύρωσης
                    echo "<td>
                        <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal' 
                            data-id='" . $row['id'] . "' 
                            data-title='" . htmlspecialchars($row['title']) . "' 
                            data-action='delete'>
                            <i class='bi bi-trash'></i> Ακύρωση
                        </button>
                      </td>";

                    // Κουμπί ενημέρωσης
                    echo "<td>
                        <button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#updateModal' 
                            data-id='" . $row['id'] . "' 
                            data-title='" . htmlspecialchars($row['title']) . "' 
                            data-content='" . htmlspecialchars($row['description']) . "' 
                            data-action='update'>
                            <i class='bi bi-pencil'></i> Ενημέρωση
                        </button>
                      </td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Δεν βρέθηκαν νέα.</td></tr>";
            }
           
        } else {
            echo "<tr><td colspan='5'>Σφάλμα στη βάση δεδομένων.</td></tr>";
        }
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
                <h5 class="modal-title" id="exampleModalLabel">Προσθήκη Ιατρικής Είδησης</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body Modal -->
            <div class="modal-body">
                <form action="news.php?action=add" method="POST">
                    <div class="mb-3">
                        <label for="question" class="form-label">Τίτλος Είδησης:</label>
                        <input type="text" class="form-control" id="newQuestion" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="details" class="form-label">Περιγραφή Είδησης:</label>
                        <textarea class="form-control" id="details" name="details" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Προσθήκη Είδησης</button>
                </form>
            </div>
        </div>
    </div>
</div>

        
        
        
        <!-- update modal -->

        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Ενημέρωση είδησης</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body Modal -->
            <div class="modal-body">
                <form id="updateForm" method="POST" action="news.php?action=update">
                    <input type="hidden" id="questionId" name="id" value=""> <!-- Κρυφό πεδίο για το ID -->

                    <div class="mb-3">
                        <label for="titleText" class="form-label">Τίτλος:</label>
                        <input type="text" class="form-control" id="titleText" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="questionText" class="form-label">Είδηση:</label>
                        <textarea class="form-control" id="questionText" name="description" rows="3" required></textarea>
                    </div>

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
        Είστε σίγουροι ότι θέλετε να διαγράψετε αυτή την ερώτηση;
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ακύρωση</button>
        <form id="deleteForm" method="POST" action="news.php?action=delete">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" id="deleteId" value="">
          <button type="submit" class="btn btn-danger">Διαγραφή</button>
        </form>
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


    // Ενεργοποιούμε το modal και προσθέτουμε τα δεδομένα του ID
$('#deleteModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Παίρνουμε το κουμπί που ενεργοποίησε το modal
  var id = button.data('id');  // Παίρνουμε το ID από το κουμπί
  var title = button.data('title');  // Παίρνουμε τον τίτλο από το κουμπί

  // Εισάγουμε τα δεδομένα στο modal
  var modal = $(this);
  modal.find('.modal-body').text('Είστε σίγουροι ότι θέλετε να διαγράψετε την ερώτηση: ' + title + '?');
  modal.find('#deleteId').val(id);  // Βάζουμε το ID στο hidden input
});



    document.addEventListener("DOMContentLoaded", function () {
    var updateModal = document.getElementById("updateModal");

    updateModal.addEventListener("show.bs.modal", function (event) {
        var button = event.relatedTarget; 
        var id = button.getAttribute("data-id");
        var title = button.getAttribute("data-title"); // Νέο για τον τίτλο
        var question = button.getAttribute("data-content");

        document.getElementById("questionId").value = id;
        document.getElementById("titleText").value = title; 
        document.getElementById("questionText").value = question;
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
