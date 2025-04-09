<?php
include '../config/config.php';

$message = "";
$toastClass = "";
$nationality = "";
$role = "";

$email_err = "";
$password_err = "";
$name_err = "";
$surname_err = "";
$dateOfBirth_err = "";
$nationality_err = "";
$phone_err = "";
$confirm_password_err = "";
$registration_num_err = "";
$gender_err = "";
$role_err = "";
$specialization_err = "";
$information_err = "";
$profile_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is empty
    
    
    if (isset($_FILES['profilePicture'])) {
        if ($_FILES['profilePicture']['error'] == 0) {
            $image_name = $_FILES['profilePicture']['name'];
            $target_dir = "../assets/img/doctors/"; // Κατάλογος αποθήκευσης εικόνων
            $target_file = $target_dir . basename($image_name);
            
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'png'];
        
            // Έλεγχος αν είναι έγκυρος τύπος αρχείου
            if (in_array($imageFileType, $allowed_types)) {
                // Παίρνουμε τις διαστάσεις της εικόνας
                list($width, $height) = getimagesize($_FILES['profilePicture']['tmp_name']);
                
                if ($width == 600 && $height == 600) {
                    // Αν είναι σωστές οι διαστάσεις, προχωράμε με το ανέβασμα
                    if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $target_file)) {
                        $image = $target_file; // Αποθήκευση του path για τη βάση δεδομένων
                    } else {
                        $profile_err = "Σφάλμα κατά το ανέβασμα της εικόνας.";
                    }
                } else {
                    $profile_err = "Η εικόνα πρέπει να έχει ακριβείς διαστάσεις 600x600 pixels.";
                }
            } else {
                $profile_err = "Η εικόνα πρέπει να είναι τύπου .jpg ή .png.";
            }
        } else {
            // Προσθήκη διαχείρισης σφαλμάτων αρχείων
            $upload_errors = [
                1 => "Το αρχείο υπερβαίνει το επιτρεπτό μέγεθος του διακομιστή.",
                2 => "Το αρχείο υπερβαίνει το μέγιστο μέγεθος που έχει οριστεί στη φόρμα.",
                3 => "Το αρχείο ανέβηκε μερικώς.",
                4 => "Δεν επιλέχθηκε αρχείο.",
                6 => "Λείπει ο προσωρινός φάκελος.",
                7 => "Αποτυχία εγγραφής αρχείου στον δίσκο.",
                8 => "Το ανέβασμα αρχείου σταμάτησε από μια επέκταση PHP."
            ];
            $error_code = $_FILES['profilePicture']['error'];
            $profile_err = isset($upload_errors[$error_code]) ? $upload_errors[$error_code] : "Άγνωστο σφάλμα κατά το ανέβασμα της εικόνας.";
        }
    } else {
        $profile_err = "Παρακαλώ προσθέστε μια φωτογραφία.";
    }
    
    
    
    if (empty(trim($_POST['email']))) {
        $email_err = "Απαιτείται το email.";
    } else {
        $email = $_POST['email'];
    }
    
    if (empty(trim($_POST['gender']))) {
        $gender_err = "Απαιτείται το φύλο.";
    } else {
        $gender = $_POST['gender'];
    }

    // Check if password is empty
    if (empty(trim($_POST['password']))) {
        $password_err = "Απαιτείται ο κωδικός πρόσβασης.";
    } else {
        $password = $_POST['password'];
    }
    
    if (empty(trim($_POST['specialization']))) {
        $specialization_err = "Η ειδικότητα είναι υποχρεωτική.";
    } else {
        $specialization = $_POST['specialization'];
    }
    
    if (empty(trim($_POST['information']))) {
        $information_err = "Οι πληροφορίες είναι υποχρεωτική.";
    } else {
        $information = $_POST['information'];
    }
    // Check if name is empty
    if (empty(trim($_POST['name']))) {
        $name_err = "Απαιτείται το όνομα.";
    } else {
        $name = $_POST['name'];
    }

    // Check if surname is empty
    if (empty(trim($_POST['surname']))) {
        $surname_err = "Απαιτείται το επίθετο.";
    } else {
        $surname = $_POST['surname'];
    }

    // Check if dateOfBirth is empty or invalid
    if (empty(trim($_POST['dateOfBirth']))) {
        $dateOfBirth_err = "απαιτείται η ημερομηνία γέννησης ";
    } else {
        $dateOfBirth = $_POST['dateOfBirth'];
    }

    // Check if nationality is empty
    if (empty(trim($_POST['nationality']))) {
        $nationality_err = "Απαιτείται η επαρχεία-πόλη.";
    } else {
        $nationality = $_POST['nationality'];
    }

    // Check if phone is empty
    if (empty(trim($_POST['phone']))) {
        $phone = 0;
    } else {
        $phone = $_POST['phone'];
    }

    // Check if confirm password is empty or doesn't match the password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = "Απαιτείται η επιβεβαίωση ταυτότητας.";
    } elseif ($_POST['confirm_password'] !== $_POST['password']) {
        $confirm_password_err = "Οι κωδικοί πρόσβασης δεν ταιριάζουν.";
    }
    if (empty(trim($_POST['registration_number']))) {
        $registration_num_err = "Απαιτείται η ταυτότητα.";
    } else {
        $registration_num = $_POST['registration_number'];
    }


    // Proceed if there are no errors
    if (empty($email_err) && empty($password_err) && empty($name_err) && empty($surname_err) && empty($dateOfBirth_err)&& empty($gender_err) && empty($nationality_err) && empty($confirm_password_err) && empty($registration_num_err) && empty($role_err) && empty($specialization_err) && empty($information_err)) {
        
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows > 0) {
            $message = "Επίλεξε άλλο email.";
            $toastClass = "#dc3545"; // Danger color
            $email_err = "Επίλεξε άλλο email.";
            
            
            
            } else {
            
                $checkResigrationStmt = $conn->prepare("SELECT registration_number FROM users WHERE registration_number = ?");
                $checkResigrationStmt->bind_param("i", $registration_num);
                $checkResigrationStmt->execute();
                $checkResigrationStmt->store_result();
                
                if ($checkResigrationStmt->num_rows > 0) {
                    $message = "Αυτή η ταυτότητα υπάρχει ήδη μέσα στο σύστημα.";
                    $toastClass = "#dc3545"; // Danger color
                    $registration_num_err = "Αυτή η ταυτότητα υπάρχει ήδη μέσα στο σύστημα.";
                    
                    
                    
                } else {
                
                    $role_id = 3; 
                }
                $stmt = $conn->prepare("INSERT INTO users (name, surname, date_of_birth, nationality, phone, email, password, role_id, gender, registration_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssissisi", $name, $surname, $dateOfBirth, $nationality, $phone, $email, $hashed_password, $role_id,$gender, $registration_num);
                
                $action = "Δημιουργία λογαριασμού";
                
                $stmt1 = $conn->prepare("INSERT INTO logs (email, action) VALUES (?, ?)");
                $stmt1->bind_param("ss", $email, $action);
                $stmt1->execute();
                
                if ($stmt->execute()) {
                    
                    $user_id = $stmt->insert_id;
                    if($role_id == 3) {
                        // Προετοιμασία του SQL statement για τον πίνακα doctors_info
                        $stmt2 = $conn->prepare("INSERT INTO doctors_info (doctor_id, specialization, photo ,information) VALUES (?, ?, ?, ?)");
                        $stmt2->bind_param("isss", $user_id, $specialization, $image_name, $information);
                        
                    // Εκτέλεση της εισαγωγής στον πίνακα doctors_info
                        $stmt2->execute();
                    }
                    
                
                    $message = "Account created successfully.";
                    $toastClass = "#28a745"; // Success color
                    $delay = 1; // 1 second
                    header("Refresh: $delay; URL=login.php");
                    exit;
                } else {
                    $message = "Error: " . $stmt->error;
                    $toastClass = "#dc3545"; // Danger color
                }
            

            $stmt->close();
        }
    }

        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../assets/img/favicon_32x32.png" sizes="32x32" type="image/png">

    <style>
    /* Βασικό στυλ του dropdown */
select {
    background-color: #E8F0FE !important; color: black !important; /* Αρχικό χρώμα γραμμάτων */
    transition: color 0.3s ease, background-color 0.3s ease !important;
}

/* Όταν το dropdown ανοίγει */
select:focus,
select:hover {
    color: black !important; /* Αλλαγή χρώματος γραμμάτων σε μαύρο */
    background-color: rgba(255, 255, 255, 0.2) !important; /* Προαιρετική αλλαγή στο φόντο */
    outline: none !important; /* Αφαίρεση περιγράμματος του browser */
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url('../assets/img/background_image.png') no-repeat center center/cover;
    background-attachment: fixed; /* Το φόντο μένει σταθερό */
    backdrop-filter: blur(20px); /* Το θολό φόντο */
    overflow-y: auto; /* Ενεργοποιεί την κύλιση στο body */
    backdrop-filter: blur(10px);
}

.blur-background {
    backdrop-filter: blur(10px);
    background-color: rgba(0, 0, 0, 0.5); /* Ρυθμίζεις την διαφάνεια του φόντου */
    border-radius: 8px;
    padding: 20px;
    position: absolute; /* Φορέας για τη φόρμα να είναι πάνω από το θολό φόντο */
    z-index: 1; /* Η φόρμα θα έχει πάντα μικρότερο z-index από το φόντο */
    width: 100%; /* Κάλυψη όλου του πλάτους */
    max-width: 600px; /* Περιορισμός στο μέγιστο πλάτος της φόρμας */
    top: 50px; /* Στοχεύει την κάθετη θέση της φόρμας */
    max-height: 80vh; /* Περιορισμός ύψους της περιοχής της φόρμας */
    overflow-y: scroll; /* Ενεργοποιεί την κύλιση μόνο στην περιοχή της φόρμας */
}

form {
    background-color: rgba(0, 0, 0, 0.7); /* Σκοτεινό φόντο για την φόρμα */
    border-radius: 8px;
    color: white;
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
    padding: 20px;
    width: 100%;
    height: auto;
    margin-top: 20px;
    z-index: 2; /* Διασφαλίζει ότι η φόρμα βρίσκεται πάνω από το θολό φόντο */
}

</style>
</head>


<body class="bg-light">
    <div class="container p-5 d-flex flex-column align-items-center">
    <?php if ($message): ?>
        <div class="toast align-items-center text-white border-0" 
             style="background-color: <?php echo $toastClass; ?>;position:relative; z-index: 10000;" 
             id="toastMessage">
        <div class="d-flex">
            <div class="toast-body">
                <?php echo $message; ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                    data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<script>
    // This code ensures that the toast is visible for 4 seconds and then hides
    document.addEventListener('DOMContentLoaded', function () {
        var toastEl = document.getElementById('toastMessage');
        if (toastEl) {
            var toast = new bootstrap.Toast(toastEl);
            toast.show();

            // Set a timeout to hide the toast after 4 seconds
            setTimeout(function () {
                toast.hide();
            }, 4000); // 4000 milliseconds = 4 seconds
        }
    });
</script>

        


        <!-- Form with transparent background -->
            <form id = "myForm" method="post" class="form-control mt-5 p-4 w-100 col-lg-3" 
                style="position: relative; z-index: 10; height:auto; width:320px; top:10px;  
                box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px; 
                background-color: rgba(0, 0, 0, 0.7); border-radius: 8px; color: white;"onsubmit="validateForm(event)" enctype="multipart/form-data">

                <div class="row text-center">
                    <i class="fa fa-user-circle-o fa-3x mt-1 mb-2" style="color: #1997CC !important;"></i>
                    <h5 class="p-4" style="font-weight: 700;">Δημιουργία λογαριασμού</h5>
                </div>
                <h6>Όσα έχουν * είναι υποχρεωτικά</h6>
       
        
        
               
            




        
        <div class="row mb-2">
            <div class="col-6">
                <label for="name"><i class="fa fa-user"></i> Όνομα*</label>
                <input type="text" name="name" id="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" style="background-color: #E8F0FE; color: black;">
                <div class="invalid-feedback">
                    Παρακαλώ συμπληρώστε το κενό.
                    <?php echo $name_err ?? ''; ?>
                </div>
                <div class="valid-feedback">
                    <i class="fa fa-check-circle" style="color: green;"></i> Έγκυρο όνομα!
                </div>
            </div>
            <div class="col-6">
                <label for="surname"><i class="fa fa-user"></i> Επίθετο*</label>
                <input type="text" name="surname" id="surname" class="form-control <?php echo (!empty($surname_err)) ? 'is-invalid' : ''; ?>" style="background-color: #E8F0FE; color: black;">
                <div class="invalid-feedback">
                    Παρακαλώ συμπληρώστε το κενό.
                <?php echo $surname_err ?? ''; ?>
            </div>
            <div class="valid-feedback">
                    <i class="fa fa-check-circle" style="color: green;"></i> Έγκυρο επίθετο!
                </div>
            </div>
        </div>
        
        <div class="row mb-2">
            <div class="col-6">
                <label for="name"><i class="fa fa-user"></i> Ιατρικό Μητρώο*</label>
                <input type="number" name="registration_number" id="registration_number" class="form-control <?php echo (!empty($registration_num_err)) ? 'is-invalid' : ''; ?>" style="background-color: #E8F0FE; color: black;">
                <div class="invalid-feedback">
                    Παρακαλώ συμπληρώστε το κενό
                    <?php echo $registration_num_err ?? ''; ?>
                </div>
                <div class="valid-feedback">
                    <i class="fa fa-check-circle" style="color: green;"></i> Έγκυρο ταυτότητα!
                </div>
            </div>
            
            <div class="col-6">
                        <label for="dateOfBirth"><i class="fa fa-user"></i> Ημερομηνία γέννησης*</label>
                        <input type="date" name="dateOfBirth" id="dateOfBirth" class="form-control <?php echo (!empty($dateOfBirth_err)) ? 'is-invalid' : ''; ?>" style="background-color: #E8F0FE; color: black;">
                        <div class="invalid-feedback">
                                Παρακαλώ συμπληρώστε το κενό.
                            <?php echo !empty($dateOfBirth_err) ? $dateOfBirth_err : 'Παρακαλώ συμπληρώστε το κενό.'; ?>
                        </div>
                    <div class="valid-feedback">
                    <i class="fa fa-check-circle" style="color: green;"></i> Έγκυρη ημερομηνία γέννησης!
                </div>
            </div>
            
        </div>
        
        <div class="row mb-2">
            <div class="col-6">
                <label for="phone"><i class="fa fa-phone"></i> Κινητό</label>
                <input type="number" name="phone" id="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" 
            style="background-color: #E8F0FE; color: black;">
                <div class="invalid-feedback">
                    Παρακαλώ συμπληρώστε το κενό.
                    <?php echo !empty($phone_err) ? $phone_err : 'Παρακαλώ συμπληρώστε το κενό.'; ?>
                    </div>
                    
                    <div class="valid-feedback">
                        <i class="fa fa-check-circle" style="color: green;"></i> Έγκυρο κινητό!
                    </div>
                </div>
                <div class="col-6">
            <label for="email"><i class="fa fa-envelope"></i> Email*</label>
            <input type="email" name="email" id="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" style="background-color: #E8F0FE; color: black;">
            <div class="invalid-feedback">
                Παρακαλώ συμπληρώστε το κενό
                <?php echo $email_err ?? ''; ?>
            </div>
            <div class="valid-feedback">
                    <i class="fa fa-check-circle" style="color: green;"></i> Έγκυρο Email !
                </div>
        </div>

        </div>
        
        <div class="row mb-2" style="position: relative;">
        <div class="col-6">
            <label for="nationality"><i class="fa fa-flag"></i> Επαρχία-Πόλη*</label>
    
            <!-- Dropdown -->
            <select
    class="form-select <?php echo (!empty($nationality_err)) ? 'is-invalid' : ''; ?>" 
    id="nationality" 
    name="nationality" 
    onblur="this.size = 1;" 
    style="background-color: #E8F0FE; color: black;">
    <option selected value="">Επιλέξτε Επαρχία-Πόλη</option>
    
    <?php
        $options = [
            "Λάρνακα", "Λεμεσός", "Λευκωσία", "Πάφος", "Αμμόχωστος", "Κερύνεια"];

        foreach ($options as $option) {
            echo "<option value='$option'" . ($nationality === $option ? " selected" : "") . ">$option</option>";
        }
    ?>
    <option value="Other">Other</option>
</select>

<div class="invalid-feedback">
    <?php echo !empty($nationality_err) ? $nationality_err : 'Παρακαλώ συμπληρώστε το κενό.'; ?>
</div>
<div class="valid-feedback">
    <i class="fa fa-check-circle" style="color: green;"></i> Έγκυρη Τοποθεσία!
</div>
        </div>
        
        <div class="col-6">
    <label for="gender"><i class="fa fa-envelope"></i> Φύλο*</label>
    <select name="gender" id="gender" style="background-color: #E8F0FE; color: black;"
        class="form-control <?php echo (!empty($gender_err)) ? 'is-invalid' : ''; ?>" 
        style="background-color: #E8F0FE; color: black;">
        <option value=""> Επιλέξτε φύλο </option>
        <option value="male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'male') ? 'selected' : ''; ?>>Άντρας</option>
        <option value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'female') ? 'selected' : ''; ?>>Γυναίκα</option>
    </select>

    <!-- Μήνυμα σφάλματος -->
    <div class="invalid-feedback">
        Παρακαλώ επιλέξτε το φύλο σας.
        <?php echo $gender_err ?? ''; ?>
    </div>

    <!-- Μήνυμα επιτυχίας -->
    <div class="valid-feedback">
        <i class="fa fa-check-circle" style="color: green;"></i> Έγκυρη επιλογή!
    </div>
</div>
</div>

    <!-- Passwords on the same row -->
    <div class="row mb-2">
        <div class="col-6">
            <label for="password"><i class="fa fa-lock"></i> Κωδικός Πρόσβασης*</label>
            <input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" style="background-color: #E8F0FE; color: black;">
            <div class="invalid-feedback">
                <?php echo $password_err ?? ''; ?>
            </div>
            <div class="valid-feedback">
                <i class="fa fa-check-circle" style="color: green;"></i> Έγκυρος κωδικός πρόσβασης!
            </div>
    </div>
    
    <div class="col-6">
        <label for="confirm_password"><i class="fa fa-lock"></i> Επιβεβαίωση Κωδικού*</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" style="background-color: #E8F0FE; color: black;">
        <div class="invalid-feedback">
            <?php echo $confirm_password_err ?? ''; ?>
        </div>
        <div class="valid-feedback">
            <i class="fa fa-check-circle" style="color: green;"></i> Ο κωδικός επιβεβαιώθηκε!
        </div>
    </div>
</div>

<div id="doctorFields">
    <div class="row mb-2">
        <div class="col-6">
                <label for="name"><i class="fa fa-user"></i> Φωτογραφία Προφίλ* (600x600 .jpg ή .png)*</label>
                <input type="file" name="profilePicture" id="profilePicture" class="form-control <?php echo (!empty($profile_err)) ? 'is-invalid' : ''; ?>" style="background-color: #E8F0FE; color: black;">
                <div class="invalid-feedback">
                
                    <?php echo $profile_err ?? ''; ?>
                </div>
                <div class="valid-feedback">
                    <i class="fa fa-check-circle" style="color: green;"></i> Έγκυρο ταυτότητα!
                </div>
            </div>
        <div class="col-6">
            <label for="specialization"><i class="fa fa-user-md"></i> Ειδικότητα*</label>
            <select name="specialization" id="specialization" class="form-control <?php echo (!empty($specialization_err)) ? 'is-invalid' : ''; ?>" style="background-color: #E8F0FE; color: black;">
                <option value="">Επιλέξτε ειδικότητα</option>
                <option value="Κλινικός Ψυχολόγος">Κλινικός Ψυχολόγος</option>
                <option value="Παιδοψυχολόγος">Παιδοψυχολόγος</option>
                <option value="Συμβουλευτικός Ψυχολόγος">Συμβουλευτικός Ψυχολόγος</option>
                <option value="Ψυχολόγος Δικαστικός">Ψυχολόγος Δικαστικός</option>
                <option value="Νευροψυχολόγος">Νευροψυχολόγος</option>
                <option value="Σχολικός Ψυχολόγος">Σχολικός Ψυχολόγος</option>
                <option value="Ψυχολόγος Υγείας">Ψυχολόγος Υγείας</option>
                <option value="Γηριατρικός Ψυχολόγος">Γηριατρικός Ψυχολόγος</option>
                <option value="Ψυχολόγος Αποκατάστασης">Ψυχολόγος Αποκατάστασης</option>
                <option value="Αθλητικός Ψυχολόγος">Αθλητικός Ψυχολόγος</option>
            </select>
            <div class="invalid-feedback">
                Παρακαλώ επιλέξτε μια ειδικότητα.
                <?php echo $specialization_err ?? ''; ?>
            </div>
            <div class="valid-feedback">
                <i class="fa fa-check-circle" style="color: green;"></i> Έγκυρη επιλογή!
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-12">
            <label for="information"><i class="fa fa-info-circle"></i> Πληροφορίες* (400 χαρακτήρες max)</label>
            <textarea name="information" style="background-color: #E8F0FE; color: black;" id="information" class="form-control <?php echo (!empty($information_err)) ? 'is-invalid' : ''; ?>" rows="3"></textarea>
            <div class="invalid-feedback">
                Παρακαλώ συμπληρώστε τις πληροφορίες.
                <?php echo $information_err ?? ''; ?>
            </div>
            <div class="valid-feedback">
                <i class="fa fa-check-circle" style="color: green;"></i> Έγκυρες πληροφορίες!
            </div>
        </div>
    </div>
</div>
        <!-- Submit button -->
        <div class="mb-2 mt-3 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary"onclick="checkForm()" style="font-weight: 600; background-color: #1997CC !important;">Δημιουργία</button>
        </div>
    
        <!-- Login link -->
        <div class="mb-2 mt-4 ">
            <p class="text-center" style="font-weight: 600;">Έχω ήδη λογαριασμό
                <a href="login.php" style="text-decoration: none; color: lightblue;">Σύνδεση</a>
            </p>
        </div>
    </form>

    </div>
    
</body>



</html>