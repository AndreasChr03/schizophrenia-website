<?php
    include "../config/config.php";
    
    // if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //     if (empty(trim($_POST["email"]))) {
    //         $message = "Please enter your email.";//This is the data from the user
    //     } else {
    //         $email = trim($_POST["email"]);
    //     }
    // }
    // $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    // $checkEmailStmt->bind_param("s", $email);
    // $checkEmailStmt->execute();
    // $checkEmailStmt->store_result();

    // if ($checkEmailStmt->num_rows == 0) {
    //     $message = "Sorry, but this email is invalid";
    //     $toastClass = "#dc3545"; // Danger color
    // } else {
    //      //prepei na kamo jino pou stelnei minima online sto parea kai na tou grapso oti to email irten
    // }//entometaksi douleuei apla edo vale to php

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../assets/img/favicon_32x32.png" sizes="32x32" type="image/png">

    <script>
    function validateForm(event) {
            const emailInput = document.getElementById('email');
            
            
            //const passwordInput = document.getElementById('password');
            
            
            let isValid = true;

    if (!emailInput.value) {
        emailInput.classList.add('is-invalid');
        emailInput.classList.remove('is-valid');
        isValid = false;
    } else {
        emailInput.classList.remove('is-invalid');
        emailInput.classList.add('is-valid');
    }
}
</script>

<style>
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
            backdrop-filter: blur(20px);
        }

        .blur-background {
            backdrop-filter: blur(10px);
            background-color: rgba(0, 0, 0, 0.7); /* Adjust the opacity as needed */
            border-radius: 8px;
            padding: 20px;
        }
</style>
</head>
<body class="bg-light">
    <div class="container p-5 d-flex flex-column align-items-center">
        <!-- Form with transparent background -->
        <form method="post" class="form-control mt-5 p-5 w-100 col-lg-4 mx-auto" 
          style="position: relative; z-index: 10; height: auto; max-width: 500px; 
          box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px; 
          background-color: rgba(0, 0, 0, 0.7); border-radius: 8px; color: white;" 
          onsubmit="validateForm(event)">

        <div class="text-center mb-3">
            <i class="fa fa-user-circle-o fa-3x mt-1 mb-2" style="color: #1997CC"></i>
            <h5 class="p-2" style="font-weight: 700;">Ξεχάσατε τον Κωδικό;</h5>
        </div>

        <div class="mb-3">
            <h5 class="p-1" style="font-weight: 400;">Παρακαλώ εισάγετε το email σας</h5>
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email"><i class="fa fa-envelope"></i> Email</label>
            <input type="email" name="email" id="email" class="form-control" 
                   style="background-color: #E8F0FE;">
            <div class="invalid-feedback">
                Παρακαλώ συμπληρώστε το email.
            </div>
            <div class="valid-feedback">
                <i class="fa fa-check-circle" style="color: green;"></i> Το email είναι έγκυρο!
            </div>
        </div>

        <!-- Submit button -->
        <div>
            <div class="d-flex justify-content-end mb-3">
                <button type="submit" class="btn btn-primary" onclick="checkForm()" 
                        style="font-weight: 600; background-color: #1997CC !important; border-color: #1997CC;"">
                    Αποστολή email 
                </button>
            </div>
        </div>
    </form>
    </div>
    
    
    <?php
// Συμπεριλαμβάνουμε το PHPMailer (θα πρέπει να το έχεις εγκαταστήσει μέσω Composer)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Βεβαιώσου ότι έχεις το autoload του Composer
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); // Λήψη του email από τη φόρμα

    // Έλεγχος αν το email είναι κενό
    if (empty($email)) {
        $message = "Please enter your email.";
        $toastClass = "alert-danger"; // CSS class για το στυλ μηνύματος
    } else {
        // Αναζήτηση του email στη βάση δεδομένων
        $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmailStmt->bind_param("s", $email);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();

        if ($checkEmailStmt->num_rows == 0) {
            // Το email δεν βρέθηκε στη βάση δεδομένων
            $message = "Sorry, but this email is invalid.";
            $toastClass = "alert-danger"; // CSS class για το στυλ μηνύματος
        } else {
            // Συνέχιση με τη διαδικασία αποστολής email
            $reset_code = '';
            for ($i = 0; $i < 6; $i++) {
                $reset_code .= rand(0, 9); // Δημιουργία τυχαίου κωδικού
            }

    // Επαλήθευση αν το email υπάρχει στη βάση δεδομένων (εδώ μπορείς να κάνεις το query για να βρεις το email στη βάση δεδομένων)

    // Δημιουργία του αντικειμένου PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Ρυθμίσεις του SMTP server (πρέπει να συμπληρώσεις τα στοιχεία σου)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Αντικατέστησε με τον SMTP server σου
        $mail->SMTPAuth = true;
        $mail->Username = 'andreasggchristou@gmail.com'; // Το email σου για αποστολή
        $mail->Password = 'jbiq besk iazs obgs'; // Ο κωδικός του email σου
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Από ποιο email αποστέλλεται το μήνυμα
        $mail->setFrom('no-reply@yourwebsite.com', 'Medilap');
        $mail->addAddress($email);

        // Θέμα και περιεχόμενο του μηνύματος
        $reset_code = '';
for ($i = 0; $i < 6; $i++) {
    $reset_code .= rand(0, 9); // Προσθήκη τυχαίου αριθμού στο κωδικό
}

// Δημιουργία του HTML του email
$mail->isHTML(true);
$mail->Subject = 'Password Reset Request';
$mail->Body = "
<html>
<head>
    <title>Password Reset</title>
    <style>
        /* Styling for the email content */
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .code-box {
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            padding: 15px;
            font-family: 'Courier New', monospace;
            display: inline-block;
            margin: 5px;
            width: 40px;
            text-align: center;
            font-size: 20px;
            border-radius: 5px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Password Reset Request</h2>
        </div>
        <p>Hi there,</p>
        <p>We received a request to reset your password. If you made this request, please use the code below to reset your password:</p>

        <div style='text-align:center;'>
            <!-- Εμφάνιση κάθε αριθμού σε ξεχωριστό κουτί -->
            ";
            // Προσθήκη του κωδικού με κάθε αριθμό σε δικό του κουτί
            for ($i = 0; $i < strlen($reset_code); $i++) {
                $mail->Body .= "<span class='code-box'>{$reset_code[$i]}</span>";
            }
            $updateQuery = "UPDATE users SET resetCode = ? WHERE email = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("is", $reset_code, $email);
            $stmt->execute(); 
            
            $mail->Body .= "
        </div>

        <p>If you need further assistance, feel free to contact us.</p>
        <p>Best regards,</p>
        <p>Your Medilap Team</p>

        <div class='footer'>
            <p>&copy; 2025</p>
        </div>
    </div>
</body>
</html>
";
    
     


// Αποστολή του email
$mail->send();
                header("Location: confirmCode.php?email=" . urlencode($email));
                exit;
            } catch (Exception $e) {
                $message = "Failed to send the email. Error: {$mail->ErrorInfo}";
                $toastClass = "alert-danger";
            }
        }

        $checkEmailStmt->close(); // Κλείσιμο του statement
    }
}
?>
</body>

</html>
