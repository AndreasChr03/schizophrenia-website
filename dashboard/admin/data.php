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
    <title>Dashboard</title>
    <link rel="stylesheet" href="reports.css">
</head>
<body style="overflow-y: hidden;">
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
        <div class="allTables">
                <div class="container_table">
            <h2 style="text-align: center; padding-bottom: 40px;">Αποτελέσματα Ερωτηματολογίου</h2>
                <table id="questionsTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>email</th>
                  <th>Percentage</th>
                  <th>Αποτέλεσμα</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql1 = "SELECT * FROM rating";
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
                    echo "<td>" . htmlspecialchars($row['client_email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['percentage']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['result']) . "</td>";
                    
                   
        
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
        <div class="container_table">
    <h2 style="text-align: center; padding-bottom: 40px;">Λίστα Εκδηλώσεων</h2>
    <table id="questionsTable2" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Τίτλος Εκδήλωσης</th>
                <th>Ημερομηνία</th>
                <th>Ώρα</th>
                <th>Αριθμός Συμμετεχόντων</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT e.id, e.title, e.date, e.time, COUNT(p.id) AS participant_count
                    FROM events e
                    LEFT JOIN participants p ON e.id = p.id_event
                    GROUP BY e.id, e.title, e.date, e.time
                    ORDER BY e.date DESC";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $ctr = 0;
                while ($row = $result->fetch_assoc()) {
                    $ctr++;
                    echo "<tr>";
                    echo "<td>" . $ctr . "</td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . date('H:i', strtotime($row['time'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['participant_count']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Δεν βρέθηκαν εκδηλώσεις.</td></tr>";
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
    $(document).ready(function() {
        // Αρχικοποίηση του DataTable για το πίνακα
        $('#questionsTable2').DataTable({
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
        </div>
    </div>
    
</body>
</html>