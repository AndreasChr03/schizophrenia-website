<?php 
    include "../../config/config.php";
    $email_user = $_SESSION['user']['email'];
    if ($_SESSION['user']['role_id'] != 3) { 
        header("Location: ../../index.php");
        exit();
    }

    if (!isset($_SESSION['user'])) {
        header("Location: ../../index.php"); 
        exit();
    }
    $doctor_id = $_SESSION['user']['user_id'];
    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

    $sqlGraph = "SELECT result, COUNT(*) AS count FROM rating WHERE client_email = ? AND YEAR(date) = ? GROUP BY result";
    $stmtGraph = $conn->prepare($sqlGraph);
    
    $stmtGraph->bind_param("si", $email_user, $year);
    $stmtGraph->execute();
    $result = $stmtGraph->get_result();

    $resultsData = [];
    $resultsCounts = []; // Changed variable name to avoid conflict with events data
    while ($row = $result->fetch_assoc()) {
        $resultsData[] = $row['result'];
        $resultsCounts[] = $row['count'];
    }

    $user_id = $_SESSION['user']['user_id'];

    // Using different variable for event data
    $eventLabels = [];
    $eventCounts = [];

    // Ερώτημα για τα δεδομένα των εκδηλώσεων
    $sqlEvents = "
    SELECT e.title, COUNT(p.id_event) AS participant_count
    FROM events e
    LEFT JOIN participants p ON e.id = p.id_event
    WHERE submit = 1
    GROUP BY e.title
    ORDER BY participant_count DESC
    LIMIT 5";

    $stmtEvents = $conn->prepare($sqlEvents);
    $stmtEvents->execute();
    $resultEvents = $stmtEvents->get_result();

    while ($row = $resultEvents->fetch_assoc()) {
        $eventLabels[] = $row['title']; // Τα ονόματα των εκδηλώσεων
        $eventCounts[] = $row['participant_count']; // Ο αριθμός των συμμετεχόντων
    }
    
    
    $personalLabels = [];
    $personalCounts = [];

    // Ερώτημα για τα δεδομένα των εκδηλώσεων
    $perEvents = "
    SELECT e.title, COUNT(p.id_event) AS participant_count
    FROM events e
    LEFT JOIN participants p ON e.id = p.id_event
    WHERE submit = 1 AND user_id = ?
    GROUP BY e.title
    ORDER BY participant_count DESC
    LIMIT 5";

$stmtEvent = $conn->prepare($perEvents);

$stmtEvent->bind_param("i", $user_id); 
$stmtEvent->execute();
$resultEvent = $stmtEvent->get_result();

    while ($row = $resultEvent->fetch_assoc()) {
        $personalLabels[] = $row['title']; // Τα ονόματα των εκδηλώσεων
        $personalCounts[] = $row['participant_count']; // Ο αριθμός των συμμετεχόντων
    }
    $years = [];
    $currentYear = date('Y');
    for ($i = 4; $i >= 0; $i--) {
        $years[] = $currentYear - $i;
    }
    
    $maleCounts = [];
    $femaleCounts = [];
    
    foreach ($years as $year) {
        $startDate = "$year-01-01";
        $endDate = "$year-12-31";
    
        $stmt = $conn->prepare("
            SELECT u.gender, COUNT(*) as count
            FROM appointment_details a
            JOIN users u ON a.clientEmail = u.email
            WHERE a.doctor_id = ? AND a.appointmentDate BETWEEN ? AND ?
            GROUP BY u.gender
        ");
        $stmt->bind_param("sss", $doctor_id, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $male = 0;
        $female = 0;
    
        while ($row = $result->fetch_assoc()) {
            if (strtolower($row['gender']) === 'male') {
                $male = (int)$row['count'];
            } elseif (strtolower($row['gender']) === 'female') {
                $female = (int)$row['count'];
            }
        }
    
        $maleCounts[] = $male;
        $femaleCounts[] = $female;
    
        
    }
    
    
    
    
    $sql = "SELECT 
    e.id AS event_id, 
    e.title, 
    e.`date`, 
    e.`time`, 
    e.user_id,
    u.name, 
    u.surname, 
    u.email,
    COUNT(p.id) AS participant_count
FROM events e
INNER JOIN users u ON e.user_id = u.user_id
LEFT JOIN participants p ON e.id = p.id_event
GROUP BY e.id, e.title, e.`date`, e.`time`, e.user_id, u.name, u.surname, u.email
ORDER BY participant_count DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->execute();

// Παίρνουμε το αποτέλεσμα
$result4 = $stmt->get_result();
    
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="../../assets/img/favicon1.png" rel="icon">
    <link rel="stylesheet" href="../../dashboard/admin/reports.css">
</head>
<body>
    <?php include "header.php"; ?>

    <div class="content">
    <div class="container_table" style="margin: 0 auto; max-width: 800px;">
        <h2 style="text-align: center; padding-bottom: 40px;">
            Ραντεβού Ανδρών και Γυναικών ανά Έτος
        </h2>
        <canvas id="appointmentsChart"></canvas>
    </div>
</div>

    <script>
        const ctx3 = document.getElementById('appointmentsChart').getContext('2d');
        const appointmentsChart = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($years); ?>,
                datasets: [
                    {
                        label: 'Άνδρες',
                        data: <?php echo json_encode($maleCounts); ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    },
                    {
                        label: 'Γυναίκες',
                        data: <?php echo json_encode($femaleCounts); ?>,
                        backgroundColor: 'rgba(255, 99, 132, 0.7)'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Ραντεβού Ανδρών και Γυναικών ανά Έτος'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Αριθμός Ραντεβού'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Έτος'
                        }
                    }
                }
            }
        });
    </script>



    <div class="content">
    
    <div class="allTables">
        <div class="container_table" style="flex: 1; margin-left: 20px;">
        <h2 style="text-align: center; padding-bottom: 40px; display: flex; justify-content: center; align-items: center; gap: 10px;">
            Καλύτερες 5 Εκδηλώσεις σε επισκεψιμότητα
        </h2>
        <p style="text-align: center;">Τα τελευταία 3 χρόνια</p>
        <canvas id="resultsChart1"></canvas>
    </div>
    <div class="container_table" style="flex: 1; margin-left: 20px;">
                <h2 style="text-align: center; padding-bottom: 40px; display: flex; justify-content: center; align-items: center; gap: 10px;">
                    Προσωπικές εκδηλώσεις σε επισκεψιμότητα
                </h2>
                <p style="text-align: center;">Τα τελευταία 3 χρόνια</p>
                <canvas id="resultsChart2"></canvas>
            </div>
    
        </div>
    </div>
    
    <div class="d-flex justify-content-center mt-5">
        <div class="container_table shadow p-4 rounded" style="max-width: 95%; background-color: #f8f9fa;">
        <h2 style="text-align: center; padding-bottom: 40px;">Πληροφορίες Εκδηλώσεων</h2>
        <table id="questionsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Τίτλος</th>
                    <th>Ημερομηνία</th>
                    <th>Ώρα</th>
                    <th>Διοργανωτής</th>
                    <th>Συμμετέχοντες</th>
                    <th>Επικοινωνία</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $counter = 1;
                while ($row = $result4->fetch_assoc()): ?>
                    <tr>
                        <td><?= $counter++ ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['time']) ?></td>
                        <td><?= htmlspecialchars($row['name'] . ' ' . $row['surname']) ?></td>
                        <td><?= htmlspecialchars($row['participant_count']) ?></td>
                        <td>
                            <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="btn btn-primary">
                                Επικοινωνία
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!--  jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!--  DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!--  DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!--  Ενεργοποίηση του DataTable αφού έχουν φορτωθεί όλα -->
<script>
    $(document).ready(function () {
        $('#questionsTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/el.json"
            },
            "pageLength": 10,
            "ordering": true
        });
    });
</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('resultsChart1').getContext('2d');
    var labels = <?php echo json_encode($eventLabels); ?>; // Τα δεδομένα των τίτλων
    var data = <?php echo json_encode($eventCounts); ?>; // Τα δεδομένα των αριθμών συμμετοχών

    var resultsChart1 = new Chart(ctx, {
        type: 'bar', // Τύπος γραφήματος (bar chart)
        data: {
            labels: labels, // Τίτλοι των γεγονότων
            datasets: [{
                label: 'Αριθμός Συμμετεχόντων',
                data: data, // Αριθμός συμμετοχών
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'], // Χρώματα των μπαρ
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true // Αρχή του άξονα y από το 0
                }
            }
        }
    });
</script>

<script>
    var ctx = document.getElementById('resultsChart2').getContext('2d');
    var labels = <?php echo json_encode($personalLabels); ?>; // Τα δεδομένα των τίτλων
    var data = <?php echo json_encode($personalCounts); ?>; // Τα δεδομένα των αριθμών συμμετοχών

    var resultsChart2 = new Chart(ctx, {
        type: 'bar', // Τύπος γραφήματος (bar chart)
        data: {
            labels: labels, // Τίτλοι των γεγονότων
            datasets: [{
                label: 'Αριθμός Συμμετεχόντων',
                data: data, // Αριθμός συμμετοχών
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'], // Χρώματα των μπαρ
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true // Αρχή του άξονα y από το 0
                }
            }
        }
    });
</script>


</body>
</html>
