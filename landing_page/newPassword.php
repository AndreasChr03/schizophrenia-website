<?php
include "../config/config.php";
$success_message = null;
$error_message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        // Δημιουργία hash του νέου κωδικού
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        if ($stmt->execute()) {
            $success_message = "Password updated successfully! Redirecting to login...";
            header("Refresh: 3; URL=login.php");
        } else {
            $error_message = "Failed to update password. Please try again.";
        }
        $stmt->close();
        $conn->close();
    } else {
        $error_message = "Passwords do not match. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../assets/img/favicon_32x32.png" sizes="32x32" type="image/png">

    <style>
        .form-container {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border-radius: 8px;
            padding: 20px; /* Προσθήκη padding για απόσταση γύρω από τα στοιχεία */
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
        <form method="post" class="form-control mt-5 w-50 col-lg-4 mx-auto form-container" style=" background-color: rgba(0, 0, 0, 0.7);">
        <div class="text-center mb-3">
            <h5>Ορισμός Νέου Κωδικού</h5>
            <p>Εισάγετε και επιβεβαιώστε τον νέο σας κωδικό παρακάτω.</p>
        </div>
        <?php if ($success_message): ?>
            <div class="text-success text-center mb-3"><?= htmlspecialchars($success_message) ?></div>
        <?php elseif ($error_message): ?>
            <div class="text-danger text-center mb-3"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <input type="hidden" style="background-color: background-color: #E8F0FE; color:black;" name="email" value="<?= htmlspecialchars($_GET['email']) ?>">
        <div class="mb-3">
            <label for="new_password" class="form-label">Νέος Κωδικός</label>
            <input type="password" name="new_password" class="form-control" id="new_password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Επιβεβαίωση Κωδικού</label>
            <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" style="background-color: #1997CC !important; border-color: #1997CC !important;">Ενημέρωση Κωδικού</button>
        </div>
    </form>
    </div>
</body>
</html>
