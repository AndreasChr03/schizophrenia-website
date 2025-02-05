<?php 

  
$stmt = $conn->prepare("SELECT banned FROM blocked_users WHERE user_email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->bind_result($banned);
$stmt->fetch();
$stmt->close();

if ($banned) {
    header("Location: error_banned.php");
    exit();
}
?>