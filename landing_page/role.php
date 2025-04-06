<?php
include "../config/config.php";
$success_message = null;
$error_message = null;

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
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container p-5 d-flex flex-column align-items-center">
    <form id="myForm"
      class="form-control mt-5 p-4 w-100 col-lg-3" 
      style="position: relative; z-index: 10; height:auto; width:320px; top:10px;  
      box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px; 
      background-color: rgba(0, 0, 0, 0.7); border-radius: 8px; color: white;">

    <div class="row text-center">
        <i class="fa fa-user-circle-o fa-3x mt-1 mb-2" style="color: #1997CC !important;"></i>
        <h5 class="p-4" style="font-weight: 700;">Επιλογή Ρόλου</h5>
    </div>

    <?php
    // Υποθέτουμε ότι έχεις ήδη συνδεθεί στη βάση με $conn
    $stmt = $conn->prepare("SELECT * FROM roles WHERE role_name != 'admin'");
    $stmt->execute();
    $result = $stmt->get_result();
    ?>


    <div class="mb-3">
        <label for="role" style="font-weight: 500;">Επέλεξε Ρόλο *</label>
        <select name="role" id="role" class="form-control" required style="background-color: #E8F0FE; color: black;">
            <option value="">-- Επιλογή Ρόλου --</option>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['role_name']) . '">' . htmlspecialchars($row['role_name']) . '</option>';
                }
            } else {
                echo '<option disabled>Δεν υπάρχουν διαθέσιμοι ρόλοι</option>';
            }
            ?>
        </select>
        <div id="roleError" class="text-danger" style="display: none; font-size: 13px; margin-top: 5px;">
        Παρακαλώ επίλεξε έναν ρόλο.
    </div>
    </div>

    <!-- Αντί για button submit, βάζουμε ένα <a> -->
    <div class="d-flex justify-content-end">
    <button type="button" onclick="submitForm()" 
        class="btn btn-primary" 
        style="font-weight: 600; background-color: #1997CC !important; border-color: #1997CC;">
    Υποβολή
</button>
    </div>
</form>


<script>
function submitForm() {
    const roleElement = document.getElementById("role");
    const role = roleElement.value;

    if (!role) {
        roleElement.classList.add("is-invalid");
        document.getElementById("roleError").style.display = "block";
        return;
    } else {
        roleElement.classList.remove("is-invalid");
        document.getElementById("roleError").style.display = "none";
    }

    const formData = new FormData();
    formData.append("role", role);

    let url = "";

    if (role === "doctor") {
        url = "registerDoctor.php"; // δεν προσθέτεις ?role=doctor
    } else if (role === "client" || role === "volunteer") {
        url = "register.php?role=" + encodeURIComponent(role); // προσθέτεις ?role=value
    } else {
        url = "login.php";
    }

    fetch(url, {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            window.location.href = url;
        }
    })
    .catch(error => {
        console.error("Σφάλμα:", error);
    });
}
</script>
</div>
    
</body>
</html>
