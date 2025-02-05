<?php
include "../../config/config.php";

if ($_SESSION['user']['role_id'] != 1) { 
    
    header("Location: ../../index.php");//prepei na einai o admin
    exit();
}

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php"); 
    exit();
  }
 $userRole = $_SESSION['user']['role_id'];
  
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

  $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$roleFilter = isset($_GET['role']) ? $_GET['role'] : ''; // Λήψη φίλτρου για ρόλο (admin ή client)

// Τροποποιημένο SQL query με δυνατότητα αναζήτησης και φιλτραρίσματος με ρόλο
$query = "
    SELECT users.*, 
           blocked_users.*, 
           roles.role_name 
    FROM users 
    LEFT JOIN blocked_users 
    ON users.email = blocked_users.user_email
    LEFT JOIN roles 
    ON users.role_id = roles.id
    WHERE (users.name LIKE ? OR users.surname LIKE ? OR users.email LIKE ?)
";

// Αν υπάρχει φίλτρο ρόλου (admin ή client), προσθέτουμε επιπλέον συνθήκη
if ($roleFilter !== '') {
    $query .= " AND roles.role_name = ?";
}

$stmt = $conn->prepare($query);
$searchTerm = "%" . $searchQuery . "%"; // Προσθήκη wildcards για αναζήτηση
if ($roleFilter !== '') {
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $roleFilter); // Δέσιμο παραμέτρων με το query
} else {
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm); // Δέσιμο παραμέτρων χωρίς φίλτρο ρόλου
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
    <link rel="stylesheet" type="text/css" href="https://elearning.cut.ac.cy/theme/yui_combo.php?rollup/3.17.2/yui-moodlesimple-min.css">
    <link rel="stylesheet" type="text/css" href="https://elearning.cut.ac.cy/theme/styles.php/lambda/1735614005_1691466492/all">

  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/user_management.css">

    <style>
        .searchBox {
    width: 28%;  /* Κάνει το input πεδίο πιο φαρδύ */
    font-size: 14px;  /* Μικρότερο μέγεθος γραμματοσειράς */
    padding: 10px;  /* Προσθήκη padding για καλύτερη εμφάνιση */
}

.role {
    width: 28%;  /* Το ίδιο με το input */
    font-size: 14px;  /* Μικρότερο μέγεθος γραμματοσειράς */
     
}
    </style>
</head>


<body>
<header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:contact@example.com">contact@example.com</a></i>
          <i class="bi bi-phone d-flex align-items-center ms-4"><span>+1 5589 55488 55</span></i>
        </div>
        <div class="social-links d-none d-md-flex align-items-center">
          <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center me-auto">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <!-- <img src="assets/img/logo.png" alt=""> -->
          <h1 class="sitename">Medilab</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="../../index.php" class="active">Home<br></a></li>
            <li><a href="../../index.php#departments">Schizophrenia</a></li>
            <li><a href="../../index.php#doctors">Doctors</a></li>
            <li><a href="../../index.php#questions">Questions</a></li>
            <li><a href="../../index.php#events">Events</a></li>
            <li><a href="../../contact.php">Contact</a></li>
            
              
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <!-- <a class="cta-btn d-none d-sm-block" href="#appointment">Make an Appointment</a> -->
		<a class="cta-btn d-none d-sm-block" href="questionnaire.php">Questionnaire</a>

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
                            <span class="userinitials size-80">
								<?php
									if (isset($_SESSION['user'])) {
										// Αν υπάρχει το 'user' στο session, ανακτούμε το όνομα του χρήστη
										$userName = $_SESSION['user']['name'];
										$userSurname = $_SESSION['user']['surname'];
  										$userSurname = substr($userSurname,0,1);
										$userName = substr($user_name,0,1);
										$userName = $userName . $userSurname;
										echo $userName;
									} else {
										// Αν δεν υπάρχει, εμφανίζουμε το "Guest"
										$user_name = "-";
										echo $user_name;
									}
								?>
							</span>
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

<section id="doctors" class="doctors section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Διαχείρηση</h2>
        <p>Εδώ είναι όλοι οι εγγεγραμμένοι χρήστες και τα στοιχεία τους.</p>
    <!-- End Section Title -->

    <!-- Πεδία Αναζήτησης και Επιλογή Ρόλου -->
    <div class="container mb-4 d-flex justify-content-between">
    <div>
        <input type="text" id="searchBox" class="form-control search-box" 
            style="padding-top:15px; padding-bottom:14px; padding-right:10px; padding-left:10px; margin-bottom:0px; border-radius: 5px; width: 300px;" 
            placeholder="Αναζήτηση χρηστών..." />
    </div>
    <div class="role">
        <select id="roleFilter" class="form-control role-select">
            <option value="">Όλοι οι χρήστες</option>
            <option value="Admin">Admin</option>
            <option value="Client">Client</option>
        </select>
    </div>
    <style>
        .user-role,
.user-banned {
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

.user-banned {
    color: red;
}
    </style>

       
    </div>
    <div class="row gy-4">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $name = htmlspecialchars($row["name"]);
                $surname = htmlspecialchars($row["surname"]);
                $email = htmlspecialchars($row["email"]);
                $role_name = htmlspecialchars($row["role_name"]);  // Ρόλος από τον πίνακα roles
                $banned_status = $row['banned'];
        
                $banned_class = $banned_status == "1" ? "banned" : "";
        ?>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="team-member d-flex align-items-start <?php echo $banned_class; ?>">
                        <div class="pic">
                            <img src="assets/img/default-avatar.png" class="img-fluid" alt="Default Avatar">
                        </div>
                        <div class="member-info">
                            <h4><?php echo $name . " " . $surname; ?></h4>
                            <span><?php echo $email; ?></span>
                            <p class="user-role">Τύπος χρήστη: <?php echo $role_name; ?></p>
                            <?php if ($banned_status == "1"): ?>
                                <p class="user-banned">🚫 Αυτός ο χρήστης είναι αποκλεισμένος</p>
                            <?php endif; ?>
                            
                            <div class="social">
                                <a href="#" data-toggle="modal" data-target="#view" title="View" data-user-id="<?php echo $row['user_id']; ?>">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="#" data-toggle="modal" data-target="#block" title="Block" data-user-email="<?php echo $row['email']; ?>" data-user-banned="<?php echo $row['banned']; ?>">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                                <a href="#" data-toggle="modal" data-target="#upgrade" title="Upgrade to Admin" data-user-id="<?php echo $row['user_id']; ?>" data-user-name="<?php echo $row['name']; ?>" data-role-id="<?php echo $row['role_id']; ?>">
                                    <i class="bi bi-arrow-up-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div><!-- End Team Member -->
        <?php
            }
        } else {
            echo "<p>Δεν υπάρχουν χρήστες στη βάση δεδομένων.</p>";
        }
        ?>
        
    </div>
</div>
</section>



<script>
    let searchTimeout;
    document.getElementById('searchBox').addEventListener('keyup', function() {
        clearTimeout(searchTimeout); // Καθαρίζει το προηγούμενο timeout
        searchTimeout = setTimeout(function() {
            var searchValue = document.getElementById('searchBox').value;
            var roleValue = document.getElementById('roleFilter').value;
            window.location.href = "?search=" + searchValue + "&role=" + roleValue; // Επανεκκίνηση αναζήτησης με καθυστέρηση
        }, 2000); // Η καθυστέρηση είναι 500ms (μισό δευτερόλεπτο)
    });

    document.getElementById('roleFilter').addEventListener('change', function() {
        var searchValue = document.getElementById('searchBox').value;
        var roleValue = this.value;
        window.location.href = "?search=" + searchValue + "&role=" + roleValue; // Επανεκκίνηση αναζήτησης όταν αλλάξει ο ρόλος
    });
</script>
    
    <!-- Εδώ είναι το modal που θα εμφανίζει τα δεδομένα -->
<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Επιλέξτε Χρήστη</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="userDetails" style="display:none;">
                    <p><strong>Όνομα:</strong> <span id="name"></span></p>
                    <p><strong>Επίθετο:</strong> <span id="surname"></span></p>
                    <p><strong>Email:</strong> <span id="email"></span></p>
                    <p><strong>Πόλη-Επαρχεία:</strong> <span id="city"></span></p>
                    <p><strong>Ημερομηνία Γέννησης:</strong> <span id="dateOfBirth"></span></p>
                    <p><strong>Τηλέφωνο:</strong> <span id="phone"></span></p>
                    <p><strong>Ταυτότητα:</strong> <span id="registrationNum"></span></p>
                    <p><strong>Είδος χρήστη:</strong> <span id="role_id"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλείσιμο</button>
            </div>
        </div>
    </div>
</div>



<!-- modal block -->
    
<div class="modal fade" id="block" tabindex="-1" aria-labelledby="blockLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="blockLabel">Επιβεβαίωση Μπλοκαρίσματος Χρήστη</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- Τοποθέτηση του μηνύματος μέσα στο modal-content και πάνω από το footer -->
      <div id="statusMessage" class="alert alert-success" style="display:none; margin: 10px;">
        <!-- Μήνυμα για επιτυχία ή αποτυχία -->
      </div>
      
      <div class="modal-body">
        <div id="confirmationMessage">Είστε σίγουροι ότι θέλετε να μπλοκάρετε αυτόν τον χρήστη;</div>
        <input type="hidden" id="emailToBlock"> <!-- Κρυφό πεδίο για το email του χρήστη -->
        <input type="hidden" id="blockStatus"> <!-- Κρυφό πεδίο για το status του χρήστη -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλείσιμο</button>
        <button type="button" class="btn btn-danger" id="confirmBlockButton">Μπλοκάρισμα Χρήστη</button>
      </div>
    </div>
  </div>
</div>
<!-- modal upgrade -->

<div class="modal fade" id="upgrade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Αλλαγή Ρόλου Χρήστη</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Τοποθέτηση του μηνύματος επιτυχίας ακριβώς κάτω από τον τίτλο και πάνω από την φόρμα -->
            <div id="successMessage" class="alert alert-success" style="display: none; margin: 10px;">
                Ο ρόλος ενημερώθηκε με επιτυχία!
            </div>
            <div class="modal-body">
                <form id="updateRoleForm">
                    <input type="hidden" id="userId" name="userId"> <!-- Κρυφό πεδίο για το user_id -->
                    <div class="form-group">
                        <label for="roleSelect">Επιλέξτε Ρόλο</label>
                        <select class="form-control" id="roleSelect" name="role_id">
                            <option value="1">Client</option>
                            <option value="2">Admin</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλείσιμο</button>
                <button type="button" class="btn btn-primary" id="saveRoleButton">Αποθήκευση</button>
            </div>
        </div>
    </div>
</div>
			
			
			
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 
    
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  
  <script>
    // Όταν ανοίγει το modal
    $('#upgrade').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Παίρνουμε το κουμπί που άνοιξε το modal
    var userId = button.data('user-id'); // Παίρνουμε το user_id από το κουμπί
    var userName = button.data('user-name'); // Παίρνουμε το όνομα του χρήστη
    var userRole = button.data('role-id'); // Παίρνουμε τον ρόλο του χρήστη (1 = Client, 2 = Admin)

    // Ενημερώνουμε το μήνυμα επιβεβαίωσης και το κρυφό πεδίο
    $('#userId').val(userId);
    $('#roleSelect').val(userRole); // Προεπιλογή στο dropdown
});



        $(document).ready(function () {
            $('#activateButton').change(function () {
                if ($(this).prop('checked')) {
                    $('#actionButton').removeClass('btn-secondary').addClass('btn-active').prop('disabled', false);
                } else {
                    $('#actionButton').removeClass('btn-active').addClass('btn-secondary').prop('disabled', true);
                }
            });
        });

    
    
    
// Όταν γίνεται κλικ στο κουμπί αποθήκευσης
$('#saveRoleButton').on('click', function () {
        var formData = $('#updateRoleForm').serialize(); // Παίρνουμε τα δεδομένα της φόρμας
        $.ajax({
            url: 'update_role.php', // Το script για την ενημέρωση του role_id
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.success) {
                    // Εμφάνιση του μηνύματος επιτυχίας
                    $('#successMessage').show();
                    
                    // Με καθυστέρηση 3 δευτερολέπτων θα φρεσκάρουμε τη σελίδα
                    setTimeout(function() {
                        location.reload(); // Φρεσκάρουμε τη σελίδα
                    }, 3000); // Καθυστέρηση 3000ms (3 δευτερόλεπτα)
                } else {
                    alert('Σφάλμα: ' + response.message);
                }
            },
            error: function () {
                alert('Παρουσιάστηκε πρόβλημα κατά την αποθήκευση!');
            }
        });
    });


  </script>
  
  
  
  <script>
    // Όταν ανοίγει το modal
$('#view').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Παίρνουμε το κουμπί που άνοιξε το modal
    var userId = button.data('user-id'); // Παίρνουμε το user_id από το κουμπί
    

    // Κλήση AJAX για να πάρουμε τα δεδομένα του χρήστη
    $.ajax({
        url: 'get_user_data.php', // Το αρχείο PHP που επιστρέφει τα δεδομένα
        method: 'GET',
        data: { user_id: userId },
        success: function(response) {
            var data = JSON.parse(response); // Ανάλυση του JSON που επιστρέφει ο server
            if (data.error) {
                $('#userDetails').hide(); // Αν υπάρχει σφάλμα, κρύβουμε τα δεδομένα
                alert(data.error);
            } else {
                // Εμφάνιση των δεδομένων στο modal
                $('#name').text(data.name); // Εμφανίζουμε το όνομα
                $('#surname').text(data.surname); // Εμφανίζουμε το επίθετο
                $('#email').text(data.email); // Εμφανίζουμε το email
                $('#phone').text(data.phone); 
                $('#dateOfBirth').text(data.date_of_birth); // Εμφανίζουμε το email
                $('#registrationNum').text(data.registration_number); // Εμφανίζουμε το email
                $('#city').text(data.nationality);
                if (data.role_id == 1) {
                    $('#role_id').text('Client'); 
                } else if (data.role_id == 2) {
                    $('#role_id').text('Admin'); 
                } else {
                    $('#role_id').text('Κάτι πήγε στραβά!');
                }
            }
        },
        error: function() {
            alert('Σφάλμα στην επικοινωνία με τον server.');
        }
    });
});
</script>
  



<!-- Μετά το jQuery, προσθέτουμε τον υπόλοιπο κώδικα που το χρησιμοποιεί -->
<script>
    $(document).on('click', '[data-toggle="modal"]', function (event) {
        var userId = $(this).data('user-id');  // Παίρνουμε το user_id από το κουμπί
        
        // Αν το user_id υπάρχει, το εμφανίζουμε στο modal
        if (userId) {
            $('#name').text('User ID: ' + userId);  // Προβολή του user_id μέσα στο modal
            $('#userDetails').show();  // Εμφάνιση των δεδομένων
        }
    });
    
    
    $('a[data-toggle="modal"]').on('click', function() {
    var userId = $(this).data('user-id'); // Ανάκτηση του ID του χρήστη
    $('#userIdToBlock').val(userId); // Ορισμός του ID στο κρυφό πεδίο
});
$('#block').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Παίρνουμε το στοιχείο που ενεργοποίησε το modal
    var email = button.data('user-email'); // Παίρνουμε το email από το data-user-email
    var userBanned = button.data('user-banned'); // Παίρνουμε την τιμή του block status (1 ή 0)

    $('#blockStatus').val(userBanned); // Βάζουμε την τιμή του block status στο κρυφό πεδίο
    $('#emailToBlock').val(email); // Βάζουμε το email στο κρυφό πεδίο

    // Προσαρμογή του κειμένου και του κειμένου του κουμπιού σύμφωνα με την κατάσταση του μπλοκαρίσματος
    if (userBanned == 1) {
        $('#confirmationMessage').html('Ο χρήστης είναι ήδη μπλοκαρισμένος. Θέλετε να τον ξεμπλοκάρετε;');
        $('#confirmBlockButton').text('Ξεμπλοκάρισμα Χρήστη').removeClass('btn-danger').addClass('btn-success');
    } else {
        $('#confirmationMessage').html('Θέλετε να μπλοκάρετε αυτόν τον χρήστη;');
        $('#confirmBlockButton').text('Μπλοκάρισμα Χρήστη').removeClass('btn-success').addClass('btn-danger');
    }
});

$('#confirmBlockButton').on('click', function() {
    var email = $('#emailToBlock').val(); // Παίρνουμε το email του χρήστη
    var block_status = $('#blockStatus').val(); // Παίρνουμε το block status

    $.ajax({
        url: 'block_user.php', // Η διεύθυνση του script που θα επεξεργαστεί το αίτημα
        type: 'POST',
        data: { email: email, banned: block_status }, // Στέλνουμε το email και το νέο status στον backend
        success: function(response) {
            var message = '';
            
            if(response == 'success') {
                message = (block_status == 0) ? 'Ο χρήστης μπλοκαρίστηκε επιτυχώς!' : 'Ο χρήστης ξεμπλοκαρίστηκε επιτυχώς!';
                $('#statusMessage').removeClass('alert-danger').addClass('alert-success').text(message).show();
            } else if(response == 'error_inserting') {
                message = 'Υπήρξε πρόβλημα κατά την καταχώρηση του χρήστη στο μπλοκάρισμα.';
                $('#statusMessage').removeClass('alert-success').addClass('alert-danger').text(message).show();
            } else if(response == 'error_missing_email') {
                message = 'Δεν παραλήφθηκε το email για το μπλοκάρισμα.';
                $('#statusMessage').removeClass('alert-success').addClass('alert-danger').text(message).show();
            } else if(response == 'error_deleting') {
                message = 'Υπήρξε πρόβλημα κατά την διαγραφή του χρήστη από τον πίνακα των μπλοκαρισμένων.';
                $('#statusMessage').removeClass('alert-success').addClass('alert-danger').text(message).show();
            } else if(response == 'error_self_ban') {
                message = 'Δεν μπορείτε να μπλοκάρετε τον εαυτό σας!';
                $('#statusMessage').removeClass('alert-success').addClass('alert-danger').text(message).show();
            } else {
                message = 'Υπήρξε κάποιο άγνωστο πρόβλημα με το μπλοκάρισμα.';
                $('#statusMessage').removeClass('alert-success').addClass('alert-danger').text(message).show();
            }

            // Αν θέλεις να επαναφορτώσεις τη σελίδα μετά από επιτυχία
            if(response == 'success') {
                setTimeout(function() {
                    location.reload(); // Φρεσκάρουμε τη σελίδα για να δεις τις αλλαγές
                }, 3000); // Καθυστέρηση 3 δευτερολέπτων πριν το refresh
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);  // Εμφάνιση του σφάλματος της AJAX για αποσφαλμάτωση
            $('#statusMessage').removeClass('alert-success').addClass('alert-danger').text('Σφάλμα κατά την αποστολή του αιτήματος.').show(); // Εμφάνιση σφάλματος
        }
    });
});


    
</script>
</body>
</html>




