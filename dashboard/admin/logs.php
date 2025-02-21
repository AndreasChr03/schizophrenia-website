

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
    <h2 style="text-align: center; padding-bottom: 40px;">Ερωτήσεις Ερωτηματολογίου</h2>

    
    
    <!-- Πίνακας με τις 5 ερωτήσεις -->
    <table id="questionsTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Όνομα</th>
          <th>Επίθετο</th>
          <th>Email</th>
          <th>Ημερομηνία-Ώρα</th>
          <th>Ενέργεια</th>
        </tr>
      </thead>
      <tbody>
        <?php
        
                $sql1 = "SELECT * FROM logs INNER JOIN users ON logs.email = users.email
        ";
        $stmt = $conn->prepare($sql1);      
        // Εκτέλεση του query
        $stmt->execute();
        
        // Ανάκτηση αποτελεσμάτων
        $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $ctr = 0;
        while ($row = $result->fetch_assoc()) {
            $ctr++;
            echo "<tr>";
            // Εμφάνιση του αριθμού σειράς
            echo "<td>" . $ctr  . "</td>";
            // Εμφάνιση της ερώτησης
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['surname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['date_time']) . "</td>";
            echo "<td>" . htmlspecialchars($row['action']) . "</td>";
            
           

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


<script>



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
