<?php
include "../config/config.php";
$success_message = null;
$error_message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
        $user_code = implode('', $_POST['code']); // Συνένωση των τιμών από τα πεδία
        
    
        $query = "SELECT resetCode FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $actual_code = $row['resetCode'];
                    if ($user_code == $row['resetCode']) {
                        $success_message = "Code verified successfully! Redirecting...";
                        header("Location: newPassword.php?email=" . urlencode($email));
                        exit();
                    } else {
                        $error_message = "Invalid code. Please try again.";
                    }
            }   
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
    <link rel="icon" href="../assets/img/favicon_32x32.png" sizes="32x32" type="image/png">

    <style>
        .code-input {
            width: 40px;
            height: 40px;
            text-align: center;
            margin: 0 5px;
            font-size: 18px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid white;
            border-radius: 6px;
        }
        #success-message {
            font-size: 14px;
            font-weight: bold;
            color: green;
        }
        
        #error-message {
            font-size: 14px;
            font-weight: bold;
            color: red;
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
            <form method="post" class="form-control mt-5 p-5 w-100 col-lg-4 mx-auto" 
            style="background-color: rgba(0, 0, 0, 0.7); border-radius: 8px; color: white;">
                <div class="text-center mb-3">
                    <h5>Επαναφορά Κωδικού</h5>
                    <p>Έχει αποσταλεί ένας κωδικός στο email σας: <strong><?= htmlspecialchars($_GET['email']) ?></strong></p>
                </div>
                
                <div class="mb-3 d-flex justify-content-center" style="border-radius: 30px;">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <input 
                            type="text" 
                            name="code[]" 
                            maxlength="1" 
                            style="background-color: #E8F0FE; color:black;"
                            class="code-input" 
                            oninput="moveToNext(this, <?= $i ?>)" 
                            onkeydown="handleBackspace(this, <?= $i ?>, event)"
                            onpaste="handlePaste(event)" 
                        >
                    <?php endfor; ?>
                </div>
                <div id="success-message" class="text-success text-center mt-2"></div>
                <div id="error-message" class="text-danger text-center mt-2"></div>
    
                
                
                <?php if ($success_message): ?>
                    <div class="text-success text-center"><?= htmlspecialchars($success_message) ?></div>
                <?php elseif ($error_message): ?>
                    <div class="text-danger text-center"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" style="background-color: #1997CC !important; border-color: #1997CC !important;">Επιβεβαίωση</button>
                </div>
            </form>
        </div>
    
        <script>
            function moveToNext(element, index) {
                if (element.value.length === 1) {
                    const nextInput = document.querySelectorAll('.code-input')[index + 1];
                    if (nextInput) nextInput.focus();
                }
            }
            
        function moveToNext(element, index) {
            if (element.value.length === 1) {
                const nextInput = document.querySelectorAll('.code-input')[index + 1];
                if (nextInput) nextInput.focus();
            }
        }
    
        function handleBackspace(element, index, event) {
            if (event.key === "Backspace" && element.value === "") {
                const previousInput = document.querySelectorAll('.code-input')[index - 1];
                if (previousInput) {
                    previousInput.focus();
                    previousInput.value = ""; // Καθαρισμός του προηγούμενου πεδίου
                }
            }
        }
        function handlePaste(event) {
        const pasteData = (event.clipboardData || window.clipboardData).getData('text');
        const codeInputs = document.querySelectorAll('.code-input');
        const errorContainer = document.getElementById('error-message'); // Το div για το μήνυμα σφάλματος
    
        // Καθαρισμός προηγούμενου μηνύματος σφάλματος
        errorContainer.textContent = '';
    
        // Έλεγχος έγκυρου κωδικού
        if (/^\d+$/.test(pasteData) && pasteData.length <= codeInputs.length) {
            for (let i = 0; i < pasteData.length; i++) {
                codeInputs[i].value = pasteData[i];
            }
            // Μετακινούμε τον κέρσορα στο επόμενο κενό πεδίο, αν υπάρχει
            const nextEmptyInput = Array.from(codeInputs).find(input => input.value === "");
            if (nextEmptyInput) nextEmptyInput.focus();
        } else {
            // Εμφάνιση μηνύματος σφάλματος
            errorContainer.textContent = "Please paste a valid numeric code.";
            event.preventDefault(); // Αποτροπή επικόλλησης μη έγκυρων δεδομένων
        }
    }
    
    </script>
    </body>
    </html>
