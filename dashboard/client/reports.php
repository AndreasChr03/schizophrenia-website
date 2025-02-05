<?php 
    include "../../config/config.php";
    
    if ($_SESSION['user']['role_id'] != 2) { 
        header("Location: ../../index.php"); //πρέπει να είναι ο admin
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
    <link rel="stylesheet" href="../../dashboard/admin/reports.css">
</head>
<body>
    <?php include "header.php"; ?>
    
    <div class="content">
        <div class="allTables" style="display: flex;">
            <!-- Table 1: Αποτελέσματα Ερωτηματολογίου -->
            <div class="container_table" style="flex: 1; margin-right: 20px;">
                <h2 style="text-align: center; padding-bottom: 40px;">Αποτελέσματα Ερωτηματολογίου</h2>
                <table id="questionsTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Percentage</th>
                            <th>Αποτέλεσμα</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $email_user = $_SESSION['user']['email'];
                        $sql1 = "SELECT * FROM rating WHERE client_email = ?";
                        $stmt = $conn->prepare($sql1);   
                        $stmt->bind_param("s", $email_user);  
                        $stmt->execute();
                        
                        // Ανάκτηση αποτελεσμάτων
                        $result = $stmt->get_result();
                        $data = ["Possibility of early schizophrenia" => 0, "No Signs of Schizophrenia" => 0, "Early schizophrenia" => 0];

                        if ($result->num_rows > 0) {
                            $ctr = 0;
                            while ($row = $result->fetch_assoc()) {
                                $ctr++;
                                echo "<tr>";
                                echo "<td>" . $ctr  . "</td>";
                                echo "<td>" . htmlspecialchars($row['percentage']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['result']) . "</td>";
                                echo "</tr>";

                                // Μετράμε τις κατηγορίες για το Pie Chart
                                if (isset($data[$row['result']])) {
                                    $data[$row['result']]++;
                                }
                            }
                        } else {
                            echo "<tr><td colspan='3'>Δεν βρέθηκαν κάποια αποτελέσματα.</td></tr>";
                        }
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Table 2: Γραφική Παρουσίαση Αποτελεσμάτων -->
            <div class="container_table" style="flex: 1; margin-left: 20px;">
                <h2 style="text-align: center; padding-bottom: 40px;">Γραφική Παρουσίαση Αποτελεσμάτων</h2>
                <canvas id="resultsChart"></canvas>
                <script>
                    var ctx = document.getElementById('resultsChart').getContext('2d');
                    var resultsChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ['Possibility of early schizophrenia', 'No Signs of Schizophrenia', 'Early schizophrenia'],
                            datasets: [{
                                label: 'Αποτελέσματα Ερωτηματολογίου',
                                data: [<?php echo $data['Possibility of early schizophrenia']; ?>, <?php echo $data['No Signs of Schizophrenia']; ?>, <?php echo $data['Early schizophrenia']; ?>],
                                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                                hoverOffset: 4
                            }]
                        }
                    });
                </script>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#questionsTable').DataTable({
            "paging": true,
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50, 100],
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
