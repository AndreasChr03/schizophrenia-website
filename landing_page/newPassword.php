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
    <title>Set New Password</title>
    <style>
        .form-container {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border-radius: 8px;
            padding: 20px; /* Προσθήκη padding για απόσταση γύρω από τα στοιχεία */
        }
    </style>
</head>
<body class="bg-light" style="background-image:url('background_2.webp'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container p-5 d-flex flex-column align-items-center">
        <form method="post" class="form-control mt-5 w-50 col-lg-4 mx-auto form-container">
            <div class="text-center mb-3">
                <h5>Set New Password</h5>
                <p>Enter and confirm your new password below.</p>
            </div>
            <?php if ($success_message): ?>
                <div class="text-success text-center mb-3"><?= htmlspecialchars($success_message) ?></div>
            <?php elseif ($error_message): ?>
                <div class="text-danger text-center mb-3"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
            <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email']) ?>">
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" id="new_password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</body>
</html>
