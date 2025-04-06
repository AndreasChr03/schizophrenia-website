<?php
include "config/config.php";  // Σύνδεση με τη βάση δεδομένων
$role = $_SESSION['user']['role_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['user_id']; // Από session
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_SESSION['user']['email'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];

    // Έλεγχος εγκυρότητας ημερομηνίας
    if (DateTime::createFromFormat('Y-m-d', $dateOfBirth) === false) {
        echo json_encode(['success' => false, 'message' => 'Μη έγκυρη ημερομηνία.']);
        exit();
    }

    // Ενημέρωση βασικών στοιχείων
    $sql = "UPDATE users SET name = ?, surname = ?, phone = ?, date_of_birth = ?, nationality = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Error in SQL preparations: ' . $conn->error);
    }
    $stmt->bind_param("ssissi", $name, $surname, $phone, $dateOfBirth, $city, $userId);

    // Αν είναι γιατρός
    if ($role == "3") {
        $specialization = $_POST['specialization'];
        $information = $_POST['info'];
        $photo = $_FILES['image'];  // Νέα εικόνα αν υπάρχει

        // 🔍 Πρώτα φέρνουμε την τρέχουσα φωτογραφία από τη βάση (default_image)
        $sqlDefaultImage = "SELECT photo FROM doctors_info WHERE doctor_id = ?";
        $stmtDefault = $conn->prepare($sqlDefaultImage);
        $stmtDefault->bind_param("i", $userId);
        $stmtDefault->execute();
        $resultDefault = $stmtDefault->get_result();
        $defaultImage = "";
        if ($row = $resultDefault->fetch_assoc()) {
            $defaultImage = $row['photo'];
        }

        // 💡 Ανέβασμα νέας φωτογραφίας ή χρήση της υπάρχουσας
        $photoName = $defaultImage;  // Χρησιμοποιείται ως default αν δεν ανέβει νέα

        if (!empty($photo['name'])) {
            $targetDir = "assets/img/doctors/";
            $newPhotoName = basename($photo['name']);
            $targetFilePath = $targetDir . $newPhotoName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            // Έλεγχος επέκτασης
            if (in_array($fileType, ['jpg', 'jpeg', 'png'])) {
                if (move_uploaded_file($photo['tmp_name'], $targetFilePath)) {
                    $photoName = $newPhotoName;
                } else {
                    header("Location: {$_SERVER['PHP_SELF']}?status=error&message=Σφάλμα κατά την αποθήκευση της νέας φωτογραφίας.");
                    exit();
                }
            } else {
                header("Location: {$_SERVER['PHP_SELF']}?status=error&message=Μη αποδεκτός τύπος αρχείου.");
                exit();
            }
        }

        // Ενημέρωση doctors_info
        $sql2 = "UPDATE doctors_info SET photo = ?, specialization = ?, information = ? WHERE doctor_id = ?";
        $stmt2 = $conn->prepare($sql2);
        if ($stmt2 === false) {
            die('Σφάλμα SQL: ' . $conn->error);
        }
        $stmt2->bind_param("sssi", $photoName, $specialization, $information, $userId);
        $stmt2->execute();
    }

    // Ενημέρωση session
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['surname'] = $surname;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['dateOfBirth'] = $dateOfBirth;
    $_SESSION['user']['nationality'] = $city;

    // Εκτέλεση βασικού update
    if ($stmt->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}?status=success&message=Τα δεδομένα ενημερώθηκαν επιτυχώς.");
        exit();
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?status=error&message=Αποτυχία ενημέρωσης δεδομένων.");
        exit();
    }
}
?>





<style>
    .profile-info{
        font-size: 18px !important; /* Ή άλλο μέγεθος που προτιμάς */
    }
    
    .profile-info p {
    font-size: 14px !important;
}

    .custom-alert {
        position: fixed;
        top: 0%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80%; /* Μπορείς να το ρυθμίσεις όπως θέλεις */
        max-width: 600px; /* Ρύθμιση μέγιστου πλάτους */
        padding: 20px;
        font-size: 18px; /* Μεγαλύτερο μέγεθος γραμματοσειράς */
        text-align: center;
        z-index: 9999; /* Για να είναι μπροστά από τα υπόλοιπα στοιχεία */
    }
    .modal-body .form-control {
        font-size: 16px; /* Μεγαλύτερο μέγεθος γραμματοσειράς */
        padding: 15px;   /* Μεγαλύτερο padding */
        border-radius: 8px; /* Στρογγυλεμένες γωνίες */
        border: 2px solid #ddd; /* Σαφή όρια */
        margin-bottom: 20px; /* Απόσταση κάτω από κάθε πεδίο */
    }
    
    /* Εμφάνιση με σκιές και διαφορετικό χρώμα για τα πεδία εισαγωγής όταν εστιάζει ο χρήστης */
    .modal-body .form-control:focus {
        border-color: #007bff; /* Χρώμα όριου όταν το πεδίο έχει focus */
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.25); /* Σκιά γύρω από το πεδίο */
    }

    /* Προσαρμογή των κουμπιών στο modal */
    .modal-footer .btn {
        font-size: 16px; /* Μεγαλύτερη γραμματοσειρά για τα κουμπιά */
        padding: 12px 20px; /* Περισσότερο padding για πιο μεγάλα κουμπιά */
        border-radius: 6px; /* Στρογγυλεμένες γωνίες στα κουμπιά */
    }

    .modal-header h5 {
        font-size: 24px; /* Μεγαλύτερη γραμματοσειρά τίτλου */
    }
    .header {
        padding:0px;
    }
    #userImage {
        padding:0px;
    }
</style>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/myProfile.css">
    <link href="../../assets/img/favicon1.png" rel="icon">

    
</head>
<body>
    <?php
        include "header.php";  // Συμπερίληψη του header της σελίδας
        
      
        if (isset($_GET['status'])) {
            $status = $_GET['status'];
            $message = $_GET['message'];
            if ($status == 'success') {
                echo '<div class="alert alert-success custom-alert" role="alert">' . htmlspecialchars($message) . '</div>';
            } else {
                echo '<div class="alert alert-danger custom-alert" role="alert">' . htmlspecialchars($message) . '</div>';
            }
        }
        
        if (!isset($_SESSION['user']['user_id'])) {
            // Ανακατεύθυνση στη σελίδα login
            header("Location: landing_page/login.php");
            exit();
        }
    ?>
    <?php
        if (isset($_SESSION['user']['user_id'])) {
            $userId = $_SESSION['user']['user_id']; 
              // Ανακτάμε το user_id από το session
        
            // Ερώτημα για να πάρεις τα υπόλοιπα στοιχεία του χρήστη από τη βάση δεδομένων
            $sql = "SELECT u.name, u.role_id, u.surname, u.date_of_birth, u.nationality, u.phone, u.email, u.registration_number, 
                di.photo, di.information, di.specialization 
        FROM users u
        LEFT JOIN doctors_info di ON u.user_id = di.doctor_id
        WHERE u.user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);  // Δέσμευση παραμέτρου (user_id)
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Αν βρεθούν δεδομένα για τον χρήστη
            if ($row = $result->fetch_assoc()) {
                $firstName = $row['name'];
                $lastName = $row['surname'];
                $userType = $row['role_id'];
                $dateOfBirth = $row['date_of_birth'];
                $nationality = $row['nationality'];
                $phoneNumber = $row['phone'];
                $info = $row['information'];
                $specialization = $row['specialization'];
                $image = $row['photo'];
                
                if ($phoneNumber == 0) {
                    $phoneNumber = '-';
                }
                $email = $row['email'];
                $idNumber = $row['registration_number'];
            }
        }
    ?>
        

        <div class="profile-container">
        <h1>Προφίλ Χρήστη</h1>
<div class="profile-info">
    <p><span class="icon"><i class="bi bi-person"></i></span><strong> Όνομα: </strong> <?php echo " " . htmlspecialchars($firstName); ?></p>
    <p><span class="icon"><i class="bi bi-people"></i></span><strong> Επώνυμο: </strong> <?php echo " " . htmlspecialchars($lastName); ?></p>
    <p><span class="icon"><i class="bi bi-calendar"></i></span><strong> Ημερομηνία Γέννησης: </strong> <?php echo " " . htmlspecialchars($dateOfBirth); ?></p>
    <p><span class="icon"><i class="bi bi-geo-alt"></i></span><strong> Πόλη-Επαρχεία: </strong> <?php echo " " . htmlspecialchars($nationality); ?></p>
    <p><span class="icon"><i class="bi bi-telephone"></i></span><strong> Κινητό: </strong> <?php echo " " . htmlspecialchars($phoneNumber); ?></p>
    <p><span class="icon"><i class="bi bi-envelope"></i></span><strong> Email: </strong> <?php echo " " . htmlspecialchars($email); ?></p>
    <p><span class="icon"><i class="bi bi-credit-card"></i></span><strong> Αριθμός Μητρώου: </strong> <?php echo " " . htmlspecialchars($idNumber); ?></p>

    <!-- Εμφάνιση επιπλέον πεδίων μόνο για γιατρούς -->
    <?php if ($userType == 3) { ?>
        
        <p><span class="icon"><i class="bi bi-journal-bookmark"></i></span><strong> Ειδικότητα: </strong> <?php echo " " . htmlspecialchars($specialization); ?></p>

        <!-- Προβολή φωτογραφίας, αν υπάρχει -->
        <?php if ($image) { ?>
            <p><span class="icon"><i class="bi bi-image"></i></span><strong> Φωτογραφία: </strong></p>
            <img src="assets/img/doctors/<?php echo htmlspecialchars($image); ?>" alt="Φωτογραφία Γιατρού" style="width: 150px; height: auto; border-radius: 10px; margin-bottom: 20px;"/>
        <?php } ?>
        <p><span class="icon"><i class="bi bi-info-circle"></i></span><strong> Πληροφορίες: </strong> <?php echo " " . htmlspecialchars($info); ?></p>

    <?php } ?>
</div>
<button id="openModal" class="modal-button" style="border-radius: 20px; text-align:end;" data-toggle="modal" data-target="#exampleModal">Άνοιγμα Επιλογών</button>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Επεξεργασία Δεδομένων Χρήστη</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body Modal -->
            <div class="modal-body">
                <form id="updateUserForm" onsubmit="updateUser(event)" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Όνομα</label>
                        <input type="text" id="userName" name="name" class="form-control" value="<?php echo htmlspecialchars($firstName); ?>">
                    </div>
                    <div class="form-group">
                        <label for="surname">Επίθετο</label>
                        <input type="text" id="userLastName" name="surname" class="form-control" value="<?php echo htmlspecialchars($lastName); ?>">
                    </div>
                    <div class="form-group">
                        <label for="dateOfBirth">Ημερομηνία Γέννησης</label>
                        <input type="date" id="userDateOfBirth" name="dateOfBirth" class="form-control" value="<?php echo htmlspecialchars($dateOfBirth); ?>">
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Email</label>
                        <input type="email" id="userEmail" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="userPhone">Τηλέφωνο</label>
                        <input type="tel" id="userPhone" name="phone" class="form-control" value="<?php echo htmlspecialchars($phoneNumber); ?>">
                    </div>
                    <div class="form-group">
                        <label for="userAddress">Πόλη-Επαρχεία</label>
                        <input type="text" id="userAddress" name="city" class="form-control" value="<?php echo htmlspecialchars($nationality); ?>">
                    </div>

                    <!-- Εμφάνιση επιπλέον πεδίων μόνο για γιατρούς -->
                    <?php if ($userType == 3) { ?>
                        <div class="form-group">
                            <label for="userInfo">Πληροφορίες</label>
                            <textarea id="userInfo" name="info" class="form-control"><?php echo htmlspecialchars($info); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="userSpecialization">Ειδικότητα</label>
                            <input type="text" id="userSpecialization" name="specialization" class="form-control" value="<?php echo htmlspecialchars($specialization); ?>">
                        </div>
                        <div class="form-group">
    <label for="userImage">Φωτογραφία</label>
    <input type="file" value="dwa" id="userImage" name="image" class="form-control" />

    
</div>
                    <?php } ?>
                </form>
            </div>

            <!-- Footer Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλείσιμο</button>
                <button type="submit" class="btn btn-primary" form="updateUserForm">Επιβεβαίωση</button>
            </div>
        </div>
    </div>
</div>
<script>
function submitUser(event) {
    event.preventDefault(); // Ακύρωση της προεπιλεγμένης υποβολής φόρμας
    const selectedUser = document.getElementById('selectedUser').value;
    alert('Επιλέξατε το Registration Number: ' + selectedUser);
    // Μπορείς να προσθέσεις εδώ επιπλέον λειτουργικότητα
}

    setTimeout(function() {
        var alert = document.querySelector('.custom-alert');
        if (alert) {
            alert.style.opacity = 0; // Κάνει το μήνυμα διαφανές
            setTimeout(function() {
                alert.style.display = 'none'; // Κρύβει το μήνυμα από τη σελίδα
            }, 1000); // Χρειάζεται 1 δευτερόλεπτο για να ολοκληρωθεί η εξαφάνιση
        }
    }, 3000); // Εμφάνιση για 3 δευτερόλεπτα
</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
