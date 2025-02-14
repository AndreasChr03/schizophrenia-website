<?php
include '../config/config.php';

$message = ""; // To hold user feedback
$password_err = "";
$email_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $password = "";

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Proceed if no errors
    if (empty($password_err) && empty($email_err)) {
        $sql = "SELECT email, password FROM users WHERE email = ?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                // Check if email exists
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $email, $hashed_password);

                    if (mysqli_stmt_fetch($stmt)) {
                        // Verify password
                        if (password_verify($password, $hashed_password)) {
                            // Fetch the rest of the user details
                            $sql = "SELECT * FROM users WHERE email = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $email);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                    
                                // Store user data in session
                                session_start();
                                $_SESSION['user'] = [
                                    'user_id' => $row['user_id'],
                                    'name' => $row['name'],
                                    'role_id' => $row['role_id'],
                                    'surname' => $row['surname'],
                                    'email' => $row['email'],
                                    'phone' => $row['phone'],
                                    'dateOfBirth' => $row['date_of_birth'],
                                    'nationality' => $row['nationality']
                                ];
                                // Redirect on successful login
                                header("Location: ../index.php");
                                exit;
                            }
                        } else {
                            $email_err = "Λανθασμένο email ή κωδικός πρόσβασης.";
                            $password_err = "Λανθασμένο email ή κωδικός πρόσβασης.";
                        }
                    } else {
                        $email_err = "Λανθασμένο email ή κωδικός πρόσβασης.";
                        $password_err = "Λανθασμένο email ή κωδικός πρόσβασης.";
                    }
                    
            } else {
                $email_err = "Λανθασμένο email ή κωδικός πρόσβασης.";
                $password_err = "Λανθασμένο email ή κωδικός πρόσβασης.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            $message = "Failed to prepare the statement.";
        }
    }
}

    // Close the connection
    mysqli_close($conn);
}
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
    <script defer>
     
  
    </script>
    
    <title>Registration</title>
</head>

<body class="bg-light" 
      style="background: linear-gradient(135deg, #1e3c72, #2a5298, #9b59b6); background-size: cover; 
             background-position: center; background-attachment: fixed;">
    <div class="container p-5 d-flex flex-column align-items-center">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white border-0" 
                role="alert" aria-live="assertive" aria-atomic="true"
                style="background-color: <?php echo $toastClass; ?>;position:relative; z-index: 100;">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                            data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        


        <!-- Form with transparent background -->
        <form method="post" class="form-control mt-5 p-5 w-100 col-lg-4 mx-auto" 
      style="position: relative; z-index: 10; height: auto; max-width: 500px; 
      box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px; 
      background-color: rgba(0, 0, 0, 0.5); border-radius: 8px; color: white;" 
      onsubmit="validateForm(event)">

    <div class="text-center mb-3">
        <i class="fa fa-user-circle-o fa-3x mt-1 mb-2" style="color: #1997CC !important;"></i>
        <h5 class="p-2" style="font-weight: 700;">Login to your Account</h5>
    </div>

    <div class="mb-3">
    <label for="email"><i class="fa fa-envelope"></i> Email</label>
    <input type="email" name="email" id="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" style="background-color: rgba(255, 255, 255, 0.1); color: white;">
    <div class="invalid-feedback">
        <?php echo $email_err ?? ''; ?>
    </div>
    <div class="valid-feedback">
        <i class="fa fa-check-circle" style="color: green;"></i> Email is valid!
    </div>
</div>

<!-- Password Field -->
<div class="mb-3">
    <label for="password"><i class="fa fa-lock"></i> Password</label>
    <input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" style="background-color: rgba(255, 255, 255, 0.1); color: white;">
    <div class="invalid-feedback">
        <?php echo $password_err ?? ''; ?>
    </div>
    <div class="valid-feedback">
        <i class="fa fa-check-circle" style="color: green;"></i> Password is valid!
    </div>
</div>

    <!-- Submit button -->
    <div>
        <a href="forgotPassword.php" style="color: white;">Forgot Password?</a>
        <div class="d-flex justify-content-end mb-3">
            <button type="submit" class="btn btn-primary" onclick="checkForm()" 
                    style="font-weight: 600; background-color: #1997CC !important; border-color: #1997CC;">
                Login 
            </button>
        </div>
    </div>

    <!-- Login link -->
    <div class="text-center mb-2">
        <p style="font-weight: 600;">I don't have an Account
            <a href="register.php" style="text-decoration: none; color: lightblue;">Sign up</a>
        </p>
    </div>
</form>
    </div>
</body>



</html>