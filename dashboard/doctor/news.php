<?php 
    include "../../config/config.php";
    
    
    if ($_SESSION['user']['role_id'] != 3) { 
    
        header("Location: ../../index.php");//prepei na einai o admin
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
    <link rel="stylesheet" href="reports.css">
    <link rel="icon" href="../../assets/img/favicon_32x32.png" sizes="32x32" type="image/png">
</head>
<body>
<?php include "header.php"?>
    
</body>
</html>