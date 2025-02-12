<?php
include "../../config/config.php";

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']; 
	  $userRole = $user['role_id'];
	  $userName = $user['name'];




	// Prepare and execute the SQL query
	$sql = "SELECT * FROM users WHERE email = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $user['email']);
	$stmt->execute();
	$result = $stmt->get_result();
	
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			
		}
	}
}
$stmt = $conn->prepare("SELECT banned FROM blocked_users WHERE user_email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->bind_result($banned);
$stmt->fetch();
$stmt->close();

if ($banned) {
    header("Location: error-banned.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - Medilab Bootstrap Template</title>
  <!-- auto gia kapoio logo epireazei ta koutia sto index prepei na to do kapote -->
  <link rel="stylesheet" type="text/css" href="https://elearning.cut.ac.cy/theme/yui_combo.php?rollup/3.17.2/yui-moodlesimple-min.css">
  <link rel="stylesheet" type="text/css" href="https://elearning.cut.ac.cy/theme/styles.php/lambda/1735614005_1691466492/all">

  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="../../assets/img/favicon.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../../assets/css/main.css" rel="stylesheet">
  <style>
  body {
    font-size: 14px; /* Ή άλλο μέγεθος που προτιμάς */
}
#loggedin-user {
  font-size: 14px; /* Ή άλλο μέγεθος που προτιμάς */

}
</style>
  <!-- =======================================================
  * Template Name: Medilab
  * Template URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<body class="index-page">

  <header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:contact@example.com">andreasggchristou@gmail.com</a></i>
        </div>
        <div class="social-links d-none d-md-flex align-items-center">
          <!-- <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a> -->
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="../../index.php " class="logo d-flex align-items-center me-auto">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <!-- <img src="assets/img/logo.png" alt=""> -->
          <h1 class="sitename">Medilab</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="../../index.php" class="active">Αρχική<br></a></li>
            <li><a href="../../index.php#departments">Σχιζοφρένεια</a></li>
            <li><a href="../../index.php#questions">Ερωτήσεις</a></li>
            <li><a href="../../index.php#events">Εκδηλώσεις</a></li>
            <li><a href="../../contact.php">Επικοινωνία</a></li>
            
              
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <!-- <a class="cta-btn d-none d-sm-block" href="#appointment">Make an Appointment</a> -->
		<a class="cta-btn d-none d-sm-block" href="../../questionnaire.php">Ερωτηματολόγιο</a>

			<div class="row-fluid" style="display: flex; justify-content: flex-end;">
                <div class="span6 login-header">
                    <div class="profileblock">
                        <div id="loggedin-user">
                            <div class="usermenu">
                                <div class="action-menu moodle-actionmenu nowrap-items">
                                    <div class="menubar d-flex">
                                        <div class="action-menu-trigger">
                                            <div class="dropdown">
                                                <a href="#" class="nav-link dropdown-toggle" id="action-menu-toggle-0" aria-label="User menu" data-toggle="dropdown" role="button" aria-haspopup="true">
                                                    
                                                        <span class="usertext mr-1">
															<?php
																if (isset($_SESSION['user'])) {
																	// Αν υπάρχει το 'user' στο session, ανακτούμε το όνομα του χρήστη
																	$user_name = $_SESSION['user']['name'] . ' ' . $_SESSION['user']['surname'];;
																	$user_name = strtoupper($user_name);
																	echo $user_name;
																} else {
																	// Αν δεν υπάρχει, εμφανίζουμε το "Guest"
																	$user_name = "Guest";
																	$user_name = strtoupper($user_name);
																	echo $user_name;
																}
															?>
														</span>
                                                        <span class="avatars">
                                                            <span class="avatar current">
                                                                <span class="userinitials size-35">
																	<?php
																		
																	?>
																</span>
                                                            </span>
                                                        </span>
                                                    
                                                </a>
                                                <div class="dropdown-menu menu dropdown-menu-right" id="action-menu-0-menu">
                                                <a href="../../myProfile.php" class="dropdown-item menu-action">Profile</a>
                                                    <?php 
                                                        if($userRole == 1) {
															echo '<a href="../../dashboard/admin/functions.php" class="dropdown-item menu-action">Dashoboard</a>';

														}
														else {
															echo '<a href="../../dashboard/client/functions.php" class="dropdown-item menu-action">Dashoboard</a>';
														}
                                                    
                                                    ?>
                                                    
                                                    <a href="../../landing_page/logout.php" class="dropdown-item menu-action">Log out</a>
                                               </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
  </header>
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/vendor/php-email-form/validate.js"></script>
  <script src="../../assets/vendor/aos/aos.js"></script>
  <script src="../../assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="../../assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="../../assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="../../assets/js/main.js"></script>

</body>

</html>