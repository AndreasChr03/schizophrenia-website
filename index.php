<?php
session_start();
include "config/config.php";

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user']; 
	$userName = $user['name'];
	$userRole = $user['role_id'];
	$user_email = $user['email'];



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
	
$stmt = $conn->prepare("SELECT banned FROM blocked_users WHERE user_email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->bind_result($banned);
$stmt->fetch();



if ($banned) {
    header("Location: error-banned.php");
    exit();
}
	
	
	
	$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
      
        case 'participate':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['id_event']) && isset($_POST['email'])) {
                    $eventId = $_POST['id_event'];
                    $email = $_POST['email'];
            
                    // Συνδέσου με την βάση δεδομένων και αποθήκευσε τη συμμετοχή
                    $sql = "INSERT INTO participants (email_user, id_event) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('si',  $email, $eventId);
                    
                    $action = "Συμμετοχή σε εκδήλωση";
                    $stmt1 = $conn->prepare("INSERT INTO logs (email, action) VALUES (?, ?)");
                    $stmt1->bind_param("ss", $email, $action);
                    $stmt1->execute();
                    
                    if ($stmt->execute()) {
                        $successMessage =  "Επιτυχής συμμετοχή.";
                    } else {
                        $errorMessage = "Σφάλμα κατά την αποθήκευση της συμμετοχής.";
                    }
                }
            }
        break;
        
        case 'cancel':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'cancel') {
                $email = $_POST['email'];
                $eventId = $_POST['id_event'];
            
                $sql = "DELETE FROM participants WHERE email_user = ? AND id_event = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('si', $email, $eventId);
                
                $action = "Ακύρωση συμμετοχής σε εκδήλωση";
                $stmt1 = $conn->prepare("INSERT INTO logs (email, action) VALUES (?, ?)");
                $stmt1->bind_param("ss", $email, $action);
                $stmt1->execute();
                
                if ($stmt->execute()) {
                    $successMessage =  "Η συμμετοχή διαγράφηκε επιτυχώς.";
                } else {
                    $errorMessage = "Σφάλμα στη διαγραφή συμμετοχής: ";
                }
            }
        break;

    default:
        // Αν δεν υπάρχει καμία δράση, μπορούμε να επιστρέψουμε ένα προεπιλεγμένο μήνυμα ή να μην κάνουμε τίποτα.
        break;
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>SchizoCare</title>
  <link rel="stylesheet" href="assets/css/home.css">
  
  <style>
  
.icon-box {
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  justify-content: center !important;
  width: 100% !important;
  min-height: 250px !important; /* Κάνε το μεγαλύτερο αν χρειάζεται */
  padding: 20px !important;
  text-align: center !important;
  padding-top: 40px;
  padding-bottom: 40px;
}

.icon-box i {
  font-size: 50px !important; /* Αυξάνει το μέγεθος του icon */
  margin-bottom: 15px !important;
  display: block !important;
  width: auto !important;
  height: auto !important;
}
  </style>
</head>

<body class="index-page">

  <?php include "header.php"?>
  
  <?php if (isset($successMessage)): ?>
    <div id="successMessage1" class="alert alert-success" role="alert">
        <?php echo $successMessage; ?>
    </div>
<?php endif; ?>
  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">

  <img src="assets/img/hero-bg.jpg" alt="" data-aos="fade-in">

  <div class="container position-relative">

    <div class="welcome position-relative" data-aos="fade-down" data-aos-delay="100">
      <h2>Καλώς ήρθατε!</h2>
      <p>Μια πηγή ενημέρωσης και υποστήριξης για όσους ζουν με σχιζοφρένεια.</p>
    </div><!-- End Welcome -->

    <div class="content row gy-4">
      <div class="col-lg-4 d-flex align-items-stretch">
        <div class="why-box" data-aos="zoom-out" data-aos-delay="200">
          <h3>Γιατί να μας επιλέξετε;</h3>
          <p>
            Παρέχουμε αξιόπιστες πληροφορίες, πρακτικά εργαλεία και καθοδήγηση για τη διάγνωση και διαχείριση της σχιζοφρένειας. 
            Στόχος μας είναι να προσφέρουμε υποστήριξη τόσο στα άτομα με τη διαταραχή όσο και στις οικογένειές τους.
          </p>
          <div class="text-center">
            <a href="#about" class="more-btn"><span>Μάθετε περισσότερα</span> <i class="bi bi-chevron-right"></i></a>
          </div>
        </div>
      </div><!-- End Why Box -->

      <div class="col-lg-8 d-flex align-items-stretch">
        <div class="d-flex flex-column justify-content-center">
          <div class="row gy-4">

            <div class="col-xl-4 d-flex align-items-stretch">
              <div class="icon-box" data-aos="zoom-out" data-aos-delay="300">
                <i class="bi bi-clipboard-data"></i>
                <h4>Έγκυρες πληροφορίες</h4>
                <p>Συγκεντρώνουμε επιστημονικά τεκμηριωμένες πληροφορίες για τη σχιζοφρένεια και τις διαθέσιμες θεραπείες.</p>
              </div>
            </div><!-- End Icon Box -->
            
            <?php
// Έλεγχος αν ο χρήστης είναι συνδεδεμένος

$isLoggedIn = isset($_SESSION['user']['user_id']); // Υποθέτουμε ότι 'user_id' είναι η μεταβλητή της συνεδρίας για τον συνδεδεμένο χρήστη
?>

<div class="col-xl-4 d-flex align-items-stretch">
    <?php if ($isLoggedIn): // Αν είναι συνδεδεμένος, επιτρέπουμε την πρόσβαση στο questionnaire ?>
        <a href="questionnaire.php">
            <div class="icon-box" data-aos="zoom-out" data-aos-delay="400">
                <i class="bi bi-gem"></i>
                <h4>Εργαλεία αυτοαξιολόγησης</h4>
                <p>Δοκιμάστε το διαδραστικό μας τεστ και δείτε αν τα συμπτώματά σας συνδέονται με τη διαταραχή.</p>
            </div>
        </a>
    <?php else: ?>
        <div class="icon-box" data-aos="zoom-out" data-aos-delay="400" style="background-color: #f8f9fa;">
            <i class="bi bi-gem"></i>
            <h4>Εργαλεία αυτοαξιολόγησης</h4>
            <p>Για να δοκιμάσετε το διαδραστικό μας τεστ, παρακαλώ συνδεθείτε πρώτα.</p>
        </div>
    <?php endif; ?>
</div>
            

            <div class="col-xl-4 d-flex align-items-stretch">
              <div class="icon-box" data-aos="zoom-out" data-aos-delay="500">
                <i class="bi bi-inboxes"></i>
                <h4>Υποστήριξη από ειδικούς</h4>
                <p>Αν οι απορίες σας δεν καλύπτονται, μπορείτε να επικοινωνήσετε με ειδικούς μέσω της ιστοσελίδας μας.</p>
              </div>
            </div><!-- End Icon Box -->

          </div>
        </div>
      </div>
    </div><!-- End Content -->

  </div>

</section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row gy-4 gx-5">

          <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="200">
            <img src="assets/img/about.jpg" class="img-fluid" alt="" style="border-radius:30px;">
            <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox pulsating-play-btn"></a>
          </div>

          <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
            <h3>Σχετικά με εμάς</h3>
            <p>
              Η ιστοσελίδα μας δημιουργήθηκε με σκοπό την ενημέρωση και υποστήριξη ατόμων που ζουν με σχιζοφρένεια, καθώς και των οικογενειών τους. Παρέχουμε αξιόπιστες πληροφορίες, εργαλεία αυτοαξιολόγησης και καθοδήγηση σχετικά με τη διάγνωση και τη θεραπεία της νόσου.
            </p>
            <ul>
              <li>
                <i class="fa-solid fa-vial-circle-check"></i>
                <div>
                  <h5>Έγκυρη και επιστημονική ενημέρωση</h5>
                  <p>Παρέχουμε αξιόπιστες πληροφορίες βασισμένες σε επιστημονικά δεδομένα και έρευνες.</p>
                </div>
              </li>
              <li>
                <i class="fa-solid fa-pump-medical"></i>
                <div>
                  <h5>Ερωτηματολόγιο αυτοαξιολόγησης</h5>
                  <p>Ένα χρήσιμο εργαλείο που βοηθά στην αναγνώριση πιθανών συμπτωμάτων της σχιζοφρένειας.</p>
                </div>
              </li>
              <li>
                <i class="fa-solid fa-heart-circle-xmark"></i>
                <div>
                  <h5>Υποστήριξη και καθοδήγηση</h5>
                  <p>Στόχος μας είναι η στήριξη των ατόμων με σχιζοφρένεια και η βελτίωση της ποιότητας ζωής τους.</p>
                </div>
              </li>
            </ul>
          </div>

        </div>

      </div>

    </section>
<!-- /About Section -->

    <!-- Stats Section -->
    <section id="doctors" class="doctors section light-background">

<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
    <h2>Οι Γιατροί μας</h2>
    <p>Αυτοί είναι οι γιατροί που είναι εγγεγραμμένοι στην υπηρεσία μας και μπορείτε να επικοινωνήσετε μαζί τους για περισσότερες πληροφορίες.</p>
</div><!-- End Section Title -->

<div class="container">

    <!-- Search Box -->
    <div class="row mb-4 ">
    <div class="col-12 col-md-8">
        <div class="input-group">
            <input type="text" id="searchBox" class="form-control" placeholder="Αναζήτηση γιατρού..." onkeyup="searchDoctors()" style="margin-bottom: 0px !important;">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
        </div>
    </div>
</div>

<style>
    #searchBox{
      padding: 20px 10px;
    }
    .input-group {
        width: 50%;
    }

    .input-group .form-control {
        border-radius: 25px 0 0 25px; /* Γωνίες για το input */
        padding: 10px 15px;
    }

    .input-group .input-group-text {
        border-radius: 0 25px 25px 0; /* Γωνίες για το span */
        background-color: #f1f1f1;
    }

    .bi-search {
        color: #007bff;
    }
</style>


    <div class="row gy-4" id="doctorsList">
        <?php
        // Βασική παράμετρος για σελιδοποίηση
        $doctorsPerPage = 6; 
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($currentPage - 1) * $doctorsPerPage;

        // SQL Query για την ανάκτηση των γιατρών (με όριο)
        $sql4 = "SELECT 
                    users.name, 
                    users.surname, 
                    users.email, 
                    users.user_id, 
                    users.nationality,
                    doctors_info.doctor_id, 
                    doctors_info.specialization, 
                    doctors_info.information, 
                    doctors_info.photo 
                FROM users
                JOIN doctors_info 
                ON users.user_id = doctors_info.doctor_id
                WHERE users.role_id = 3
                LIMIT $doctorsPerPage OFFSET $offset";
        
        $result4 = $conn->query($sql4);
        
        // Έλεγχος αν υπάρχουν αποτελέσματα
        if ($result4->num_rows > 0) {
            // Εμφάνιση των αποτελεσμάτων
            while($row4 = $result4->fetch_assoc()) {
                echo '<div class="col-lg-6 doctor-card" data-aos="fade-up" data-aos-delay="100">';
                echo '<div class="team-member d-flex align-items-start">';
                echo '<div class="pic"><img src="assets/img/doctors/' . $row4["photo"] . '" class="img-fluid" alt=""></div>';
                echo '<div class="member-info">';
                echo '<h4>' . $row4["name"] . ' ' . $row4["surname"] . '</h4>';
                echo '<span>' . $row4["specialization"] . '</span>';
                echo '<p>' .  'Περιοχή Ιατρίου: ' . $row4["nationality"] . '</p>';
                echo '<p>' .$row4["information"] . '</p>';
                echo '<div class="social">';
                echo '<a href="mailto:'. $row4['email'] .'"><i class="bi bi-envelope"></i></a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "Δεν βρέθηκαν γιατροί.";
        }
        ?>
    </div>

    <!-- Σελιδοποίηση -->
    <div class="d-flex justify-content-center mt-4">
        <?php
        // Υπολογισμός του αριθμού των σελίδων
        $sqlCount = "SELECT COUNT(*) as total FROM users WHERE role_id = 3";
        $resultCount = $conn->query($sqlCount);
        $rowCount = $resultCount->fetch_assoc();
        $totalDoctors = $rowCount['total'];
        $totalPages = ceil($totalDoctors / $doctorsPerPage);

        // Προηγούμενη Σελίδα
        if ($currentPage > 1) {
          echo '<a href="?page=' . ($currentPage - 1) . '#doctors" class="cta-btn mx-1">Προηγούμενη</a>';
      }
      
      // Εμφάνιση αριθμών σελίδων
      for ($i = 1; $i <= $totalPages; $i++) {
          echo '<a href="?page=' . $i . '#doctors" class="cta-btn mx-1">' . $i . '</a>';
      }
      
      // Επόμενη Σελίδα
      if ($currentPage < $totalPages) {
          echo '<a href="?page=' . ($currentPage + 1) . '#doctors" class="cta-btn mx-1">Επόμενη</a>';
      }
      ?>
    </div>

</div>

</section>
<style>
.cta-btn {
    padding: 5px 10px;
    font-size: 16px;
    font-weight: bold;
    color: white !important;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s ease;
}

/* Hover effect για τα κουμπιά */
.cta-btn:hover {
    background-color: #0056b3;
}

/* Επιπλέον στυλ για τα κουμπιά πλοήγησης (Προηγούμενη, Επόμενη, Αριθμοί Σελίδων) */
.cta-btn.mx-1 {
    margin: 5px;
    cursor: pointer;
}


</style>
<script>
// Λειτουργία αναζήτησης γιατρού
function searchDoctors() {
    var input, filter, doctorsList, doctorCards, name, i, txtValue;
    input = document.getElementById('searchBox');
    filter = input.value.toUpperCase();
    doctorsList = document.getElementById('doctorsList');
    doctorCards = doctorsList.getElementsByClassName('doctor-card');

    for (i = 0; i < doctorCards.length; i++) {
        name = doctorCards[i].getElementsByClassName('member-info')[0];
        if (name) {
            txtValue = name.textContent || name.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                doctorCards[i].style.display = "";
            } else {
                doctorCards[i].style.display = "none";
            }
        }
    }
}
</script>



    
    <!-- <section id="services" class="services section">

   
      <div class="container section-title" data-aos="fade-up">
        <h2>Services</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div>

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item  position-relative">
              <div class="icon">
                <i class="fas fa-heartbeat"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Nesciunt Mete</h3>
              </a>
              <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure perferendis tempore et consequatur.</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="fas fa-pills"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Eosle Commodi</h3>
              </a>
              <p>Ut autem aut autem non a. Sint sint sit facilis nam iusto sint. Libero corrupti neque eum hic non ut nesciunt dolorem.</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="fas fa-hospital-user"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Ledo Markt</h3>
              </a>
              <p>Ut excepturi voluptatem nisi sed. Quidem fuga consequatur. Minus ea aut. Vel qui id voluptas adipisci eos earum corrupti.</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="fas fa-dna"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Asperiores Commodit</h3>
              </a>
              <p>Non et temporibus minus omnis sed dolor esse consequatur. Cupiditate sed error ea fuga sit provident adipisci neque.</p>
              <a href="#" class="stretched-link"></a>
            </div>
          </div>

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="fas fa-wheelchair"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Velit Doloremque</h3>
              </a>
              <p>Cumque et suscipit saepe. Est maiores autem enim facilis ut aut ipsam corporis aut. Sed animi at autem alias eius labore.</p>
              <a href="#" class="stretched-link"></a>
            </div>
          </div>

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="fas fa-notes-medical"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Dolori Architecto</h3>
              </a>
              <p>Hic molestias ea quibusdam eos. Fugiat enim doloremque aut neque non et debitis iure. Corrupti recusandae ducimus enim.</p>
              <a href="#" class="stretched-link"></a>
            </div>
          </div>

        </div>

      </div>

    </section> -->

    
    <!-- <section id="appointment" class="appointment section">

      
      <div class="container section-title" data-aos="fade-up">
        <h2>Appointment</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div>
      

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <form action="forms/appointment.php" method="post" role="form" class="php-email-form">
          <div class="row">
            <div class="col-md-4 form-group">
              <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required="">
            </div>
            <div class="col-md-4 form-group mt-3 mt-md-0">
              <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required="">
            </div>
            <div class="col-md-4 form-group mt-3 mt-md-0">
              <input type="tel" class="form-control" name="phone" id="phone" placeholder="Your Phone" required="">
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 form-group mt-3">
              <input type="datetime-local" name="date" class="form-control datepicker" id="date" placeholder="Appointment Date" required="">
            </div>
            <div class="col-md-4 form-group mt-3">
              <select name="department" id="department" class="form-select" required="">
                <option value="">Select Department</option>
                <option value="Department 1">Department 1</option>
                <option value="Department 2">Department 2</option>
                <option value="Department 3">Department 3</option>
              </select>
            </div>
            <div class="col-md-4 form-group mt-3">
              <select name="doctor" id="doctor" class="form-select" required="">
                <option value="">Select Doctor</option>
                <option value="Doctor 1">Doctor 1</option>
                <option value="Doctor 2">Doctor 2</option>
                <option value="Doctor 3">Doctor 3</option>
              </select>
            </div>
          </div>

          <div class="form-group mt-3">
            <textarea class="form-control" name="message" rows="5" placeholder="Message (Optional)"></textarea>
          </div>
          <div class="mt-3">
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your appointment request has been sent successfully. Thank you!</div>
            <div class="text-center"><button type="submit">Make an Appointment</button></div>
          </div>
        </form>

      </div>

    </section> -->

    <!-- Departments Section -->
    <style>
      .nav-tabs .nav-link.active {
  background-color: #1977cc; /* Χρώμα φόντου */
  color: white; /* Άσπρο χρώμα για τα γράμματα */
}
    </style>
    <section id="departments" class="departments section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Σχιζοφρένεια</h2>
        <p>Γενικές πληροφορίες για την σχιζοφρένεια</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
          <div class="col-lg-3" style="padding-top: 20px; padding-bottom: 20px; padding-left: 10px; padding-right: 0px; border-radius:25px;">
            <div style="padding-top: 20px; padding-bottom: 20px; padding-left: 10px; padding-right: 0px; border-radius:25px; background-color: #F1F7FC;">
            <ul class="nav nav-tabs flex-column" >
              <li class="nav-item">
                <a class="nav-link active show" data-bs-toggle="tab" style="padding-left: 15px; border-radius:25px;" href="#departments-tab-1">Τι είναι η σχιζοφρένεια</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" style="padding-left: 15px; border-radius:25px;" href="#departments-tab-2">Συμπτώματα της σχιζοφρένειας</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" style=" padding-left: 15px; border-radius:25px;" href="#departments-tab-3">Αίτια και παράγοντες κινδύνου</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" style="padding-left: 15px; border-radius:25px;" href="#departments-tab-4">Διάγνωση της σχιζοφρένειας</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" style="padding-left: 15px; border-radius:25px;" href="#departments-tab-5">Θεραπείες και διαχείριση</a>
              </li>
            </ul>
            </div>
            
          </div>
          <div class="col-lg-9 mt-4 mt-lg-0">
            <div class="tab-content" style="overflow-x: hidden;">
              <div class="tab-pane active show" id="departments-tab-1">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Τι είναι η σχιζοφρένεια</h3>
                    <!-- <p class="fst-italic">Qui laudantium consequatur laborum sit qui ad sapiente dila parde sonata raqer a videna mareta paulona marka</p> -->
                    <p>Ο όρος σχιζοφρένεια χρησιμοποιείται στην ιατρική επιστήμη για να περιγράψει μια άκρως σύνθετη και δυσνόητη κατάσταση, η οποία είναι χρόνια και αναπηρική, και είναι αποδεδειγμένα μία από τις πιο σοβαρές ψυχιατρικές διαταραχές. Η νόσος μπορεί να είναι μια ενιαία διαταραχή, μπορεί όμως να αποτελεί και ένα μεγάλο σύνολο διαταραχών με διαφορετικές αιτιολογίες.</p>
                    <p>Η σχιζοφρένεια είναι μια ψυχική διαταραχή, και το κύριο χαρακτηριστικό της είναι ότι το άτομο που νοσεί χάνει την επαφή με την πραγματικότητα ή αδυνατεί να αναγνωρίσει το πραγματικό από τις ψευδαισθήσεις. Η νόσος προκαλεί τη λανθασμένη αντίληψη των ερεθισμάτων του περιβάλλοντος και επηρεάζει τις σκέψεις του ίδιου του πάσχοντος, με αποτέλεσμα τα συμπεράσματά του για την εξωτερική πραγματικότητα και για τους άλλους ανθρώπους να είναι εσφαλμένα. Η σχιζοφρένεια στις μέρες μας θεωρείται ένα από τα πιο σοβαρά ψυχικά νοσήματα.</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/departments-1.jpg" alt="" class="img-fluid" style="border-radius: 30px;">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="departments-tab-2">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Συμπτώματα της σχιζοφρένειας</h3>
                    <p class="fst-italic">Αρχικά Συμπτώματα</p>
                        <p>•	Το άτομο ακούει ή βλέπει κάτι που δεν υπάρχει.</p>
                        <p>•	Έχει τη διαρκή αίσθηση ότι τον παρακολουθούν.</p>  
                        <p>•	Έχει παράξενη στάση σώματος.</p>
                        <p>•	Έχει παράξενο τρόπο ομιλίας ή γραφής που δεν βγάζει νόημα.</p>  
                        <p>•	Δείχνει αδιαφορία, ακόμα και για σημαντικά θέματα.</p> 
                        <p>•	Παρουσιάζει πτώση στη σχολική ή εργασιακή του απόδοση.</p>
                        <p>•	Εμφανίζει αλλαγές στην προσωπικότητά του.</p>
                        <p>•	Αποσύρεται από την κοινωνική δραστηριότητα.</p>
                        <p>•	Αντιδρά παράλογα και έντονα απέναντι σε αγαπημένα πρόσωπα.</p>
                        <p>•	Παρουσιάζει αδυναμία συγκέντρωσης και ύπνου.</p>
                        <p>•	Εκδηλώνει απρεπή και παράξενη συμπεριφορά.</p>
                        <p>•	Βιώνει το αίσθημα ότι δεν μπορεί να σκεφτεί.</p>
                        
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/departments-2.jpg" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="departments-tab-3">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Αίτια και παράγοντες κινδύνου</h3>
                    <p class="fst-italic">Η πραγματική αιτία της σχιζοφρένειας είναι αγνωστή αλλά έχει παρατηρηθεί ότι σε πολλούς ασθενείς στους οποίους έγιναν διάφορες μελέτες ότι τα αίτια είναι τα εξης:</p>
                    <p>1.	Η κληρονομικότητα παίζει σημαντικό ρόλο στην εμφάνιση της σχιζοφρένειας. Άτομα με οικογενειακό ιστορικό της νόσου, όπως γονείς ή αδέλφια που έχουν διαγνωστεί με σχιζοφρένεια, διατρέχουν μεγαλύτερο κίνδυνο από ένα άτομο που δεν περιέχει κάποιο οικογενειακό ιστορικό.</p>
                    <p>2.	Είτε από βιολογικούς παράγοντες που έχουν να κάνουν με τη δομή του εγκεφάλου, οι ανισορροπίες σε κάποιες του εγκεφάλου όπως ντοπαμίνη και σεροτονίνη.</p>
                    <p>3.	Είτε από ψυχοκοινωνικοί παράγοντες από την καθημερινότητα ενός ατόμου, για παράδειγμα από το πολύ σοβαρό στρες, από την χρήση ναρκωτικών ουσιών, από κάποια ψυχολογικά τραύματα.</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/departments-3.jpg" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="departments-tab-4">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Διάγνωση της σχιζοφρένειας</h3>
                    <p class="fst-italic">Η διάγνωση και διαχείριση της σχιζοφρένειας απαιτούν εξειδικευμένη προσέγγιση, βασισμένη σε κλινική αξιολόγηση, ψυχολογική υποστήριξη και μακροπρόθεσμη παρακολούθηση.</p>
                    <p>Η διάγνωση της σχιζοφρένειας γίνεται μέσω λεπτομερούς κλινικής αξιολόγησης από εξειδικευμένο ψυχίατρο, ο οποίος βασίζεται σε συνεντεύξεις, παρατήρηση της συμπεριφοράς και ψυχομετρικά εργαλεία. Οι γιατροί συχνά χρησιμοποιούν διεθνή διαγνωστικά κριτήρια, όπως αυτά του DSM-5, για την ακριβή ταυτοποίηση της νόσου. Η διαχείριση της σχιζοφρένειας περιλαμβάνει συνεχή παρακολούθηση, φαρμακευτική θεραπεία, ψυχολογικές παρεμβάσεις και την παροχή υποστήριξης από οικογένεια και κοινοτικές υπηρεσίες. Η έγκαιρη διάγνωση και η ολιστική αντιμετώπιση της νόσου είναι καθοριστικές για τη βελτίωση της ποιότητας ζωής του ασθενούς.

</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/departments-4.jpg" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="departments-tab-5">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Θεραπείες και διαχείριση</h3>
                    <p class="fst-italic">Η διαχείριση της σχιζοφρένειας απαιτεί μια ολιστική προσέγγιση, η οποία περιλαμβάνει θεραπείες, ψυχολογική υποστήριξη και οικογενειακή βοήθεια.</p>
                    <p>Η θεραπεία της σχιζοφρένειας περιλαμβάνει έναν συνδυασμό φαρμακευτικής αγωγής, ψυχολογικής υποστήριξης και κοινωνικών παρεμβάσεων. Τα αντιψυχωτικά φάρμακα μειώνουν τα κύρια συμπτώματα της νόσου, ενώ η ψυχοθεραπεία, όπως η γνωσιακή-συμπεριφορική θεραπεία, βοηθά τους ασθενείς να διαχειρίζονται τις σκέψεις και τα συναισθήματά τους. Παράλληλα, η κοινωνική υποστήριξη, οι κοινοτικές υπηρεσίες και η ενεργή συμμετοχή της οικογένειας παρέχουν ένα ισχυρό δίκτυο, το οποίο διευκολύνει την επανένταξη στην καθημερινότητα και τη βελτίωση της ποιότητας ζωής του ατόμου.</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/departments-5.jpg" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Departments Section -->

    
    <!-- Faq Section -->
    <section id="questions" class="faq section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Συχνές Ερωτήσεις</h2>
        <p>Έχουμε συγκεντρώσει απαντήσεις σε ερωτήσεις που απασχολούν πολλούς σχετικά με τη σχιζοφρένεια.</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row justify-content-center">

          <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">

            <div class="faq-container">

              <div class="faq-item">
                <h3>Μπορεί κάποιος με σχιζοφρένεια να ζήσει μια φυσιολογική ζωή;</h3>
                <div class="faq-content">
                  <p>Με σωστή θεραπεία, υποστήριξη και παρακολούθηση, πολλοί ασθενείς ζουν πλήρεις και παραγωγικές ζωές.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Πώς μπορώ να βοηθήσω κάποιον με σχιζοφρένεια;</h3>
                <div class="faq-content">
                  <p>Δείχνοντας κατανόηση, προσφέροντας συναισθηματική υποστήριξη, αποφεύγοντας την κριτική και ενθαρρύνοντας τη συνέχιση της θεραπείας.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Είναι η σχιζοφρένεια θεραπεύσιμη;</h3>
                <div class="faq-content">
                  <p>Δεν θεραπεύεται πλήρως, αλλά διαχειρίζεται με φαρμακευτική αγωγή, ψυχοθεραπεία και υποστήριξη.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Ποια είναι η καλύτερη θεραπεία για τη σχιζοφρένεια;</h3>
                <div class="faq-content">
                  <p>Ένας συνδυασμός φαρμακευτικής αγωγής (αντιψυχωτικά), ψυχολογικής υποστήριξης (CBT) και κοινωνικής βοήθειας.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Πώς η ψυχολογική και κοινωνική υποστήριξη συμπληρώνει τη φαρμακευτική αγωγή;</h3>
                <div class="faq-content">
                  <p>Η ψυχολογική και κοινωνική υποστήριξη συχνά λειτουργεί ως κρίσιμο συμπλήρωμα της θεραπείας. Η ψυχοθεραπεία, όπως η γνωσιακή-συμπεριφορική θεραπεία (CBT), μπορεί να βοηθήσει τους ασθενείς να κατανοήσουν τις σκέψεις τους και να διαχειριστούν καλύτερα τις κρίσεις, ενώ η κοινωνική υποστήριξη τους βοηθά να αντιμετωπίσουν τις προκλήσεις της καθημερινότητας. Επιπλέον, οι κοινοτικές υπηρεσίες υποστήριξης παίζουν έναν σημαντικό ρόλο, παρέχοντας πρακτική βοήθεια, όπως καθοδήγηση για την εύρεση εργασίας, την εκπαίδευση και την κοινωνική επανένταξη. Αυτές οι υπηρεσίες συνεισφέρουν σημαντικά στην ψυχολογική ευημερία των ασθενών, προσφέροντας ένα δίκτυο υποστήριξης που τους βοηθά να παραμείνουν σταθεροί και να βελτιώσουν την ποιότητα της ζωής τους.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Ποιος είναι ο ρόλος των αντιψυχωτικών φαρμάκων στη θεραπεία της σχιζοφρένειας;</h3>
                <div class="faq-content">
                  <p>Η κύρια θεραπεία της σχιζοφρένειας περιλαμβάνει τη χρήση αντιψυχωτικών φαρμάκων, τα οποία στοχεύουν στη μείωση των συμπτωμάτων όπως οι παραισθήσεις, οι παραληρηματικές ιδέες και η αποδιοργανωμένη σκέψη. Αυτά τα φάρμακα βοηθούν τον ασθενή να διαχειριστεί τα συμπτώματα και να έχει μια πιο σταθερή καθημερινή ζωή. Ωστόσο, η φαρμακευτική αγωγή από μόνη της δεν επαρκεί για τη συνολική αντιμετώπιση της νόσου.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->
              
              <div class="faq-item">
                <h3>Ποια είναι η σημασία της οικογενειακής υποστήριξης για την ψυχική ισορροπία των ασθενών με σχιζοφρένεια;</h3>
                <div class="faq-content">
                  <p>Η οικογένεια αποτελεί επίσης έναν από τους πιο κρίσιμους παράγοντες στην ψυχική υγεία του ατόμου με σχιζοφρένεια. Με την κατάλληλη εκπαίδευση και καθοδήγηση, τα μέλη της οικογένειας μπορούν να κατανοήσουν τη φύση της ασθένειας και να παρέχουν ένα περιβάλλον αγάπης και υποστήριξης που θα προάγει την ψυχική γαλήνη και την αρμονία του ασθενή. Η σωστή επικοινωνία, η αποφυγή έντασης και η διασφάλιση ενός σταθερού, υποστηρικτικού περιβάλλοντος είναι καθοριστικής σημασίας για την επίτευξη και τη διατήρηση της ψυχικής ισορροπίας του ασθενούς.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>

            </div>

          </div><!-- End Faq Column-->

        </div>

      </div>
      <div class="container section-title" style="margin-top: 50px;" data-aos="fade-up">
	      <p>Εαν οι πιο πάνω ερωτήσεις δεν σας καλύπτουν τότε μπορείτε να μας στείλετε στο προσωπικό 
	      μας προφίλ πατώντας <a href="contact.php">εδώ</a>.</p>
	  </div>

    </section><!-- /Faq Section -->

    <!-- Testimonials Section -->
    <?php 
    if (isset($_SESSION['user'])) {
    
  
  ?>
    <section id="events" class="testimonials section">

    <div class="container section-title" data-aos="fade-up" style="padding-bottom:0px;">
        <h2>Εκδηλώσεις</h2>
        <div class="container" style="display: flex; justify-content:center;">

            

                <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">
                    
                    <!-- Swiper Container -->
                    <div class="swiper init-swiper" id="allEvent">
                        <script type="application/json" class="swiper-config">
                        {
                            "loop": true,
                            "speed": 600,
                            "autoplay": {
                                "delay": 5000
                            },
                            "slidesPerView": "auto",
                            "pagination": {
                                "el": ".swiper-pagination",
                                "type": "bullets",
                                "clickable": true
                            }
                        }
                        </script>

                        <div class="swiper-wrapper"> <!-- Επανεισαγωγή του swiper-wrapper -->

						<?php 
						
// SQL query με LEFT JOIN για να εμφανίζονται όλα τα events και αν υπάρχει συμμετοχή να επιστρέφεται
$sql = "SELECT e.id, e.user_id, e.description, e.date, e.time, e.submit, e.title,
               u.name, u.surname,  -- Επιλογή δεδομένων από τον πίνακα users
               CASE 
                   WHEN p.email_user IS NOT NULL THEN 1 
                   ELSE 0 
               END AS is_participating
        FROM events e
        LEFT JOIN participants p ON e.id = p.id_event AND p.email_user = ?
        LEFT JOIN users u ON e.user_id = u.user_id  -- Σύνδεση με τον πίνακα users
        WHERE e.date >= CURDATE() AND e.submit = 1
        ORDER BY e.date ASC";

$user_email = $_SESSION['user']['email']; 

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_email); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $event_id = $row["id"];
        $date = date("Y-m-d", strtotime($row["date"]));
        $time = date("H:i", strtotime($row["time"]));
        $isParticipating = $row["is_participating"];

        echo '<div class="swiper-slide">
		          <div class="testimonial-item">
		            <div class="d-flex">
		              <img src="assets/img/default_image.png" class="testimonial-img flex-shrink-0" alt="Οργανωτής Εικόνα" style="border-radius: 50%; width: 100px; height: 100px; object-fit: cover; margin-right: 20px;">
		              <div>
		                <h3>' . htmlspecialchars($row["name"]) . " " . htmlspecialchars($row["surname"]) . '</h3>
		                <h4>Διοργανωτής/τρια</h4>
                        <div class="stars">
                          <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                          <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <div class="event-container">
                            <div class="event-date"><b>Date: ' . $date . '</b></div>
                            <div class="event-time"><b>Time: ' . $time . '</b></div>
                        </div>
                      </div>
                    </div>
                    <strong>' . htmlspecialchars($row["title"]) .'</strong>
                    <p>
                      <i class="bi bi-quote quote-icon-left"></i>
                      <span>' . htmlspecialchars($row["description"]) . '</span>
                      <i class="bi bi-quote quote-icon-right"></i>
                    </p>';

        // Εμφάνιση κουμπιών συμμετοχής ή ακύρωσης
        echo '<div class="event-actions">';
        if ($isParticipating) {
            echo "<button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#cancelModal' 
                    data-email='" . htmlspecialchars($user_email) . "' 
                    data-id='" . $event_id . "' 
                    data-action='cancel'>
                    <i class='bi bi-x-circle'></i> Ακύρωση Συμμετοχής
                </button>";
        } else {
            echo "<button class='btn btn-success btn-sm' data-bs-toggle='modal' data-bs-target='#participationModal' 
                    data-email='" . htmlspecialchars($user_email) . "' 
                    data-id='" . $event_id . "' 
                    data-action='participate'>
                    <i class='bi bi-check-circle'></i> Συμμετοχή
                </button>";
        }
        echo '</div>'; // Κλείσιμο event-actions div
        echo '</div>'; // Κλείσιμο testimonial-item div
        echo '</div>'; // Κλείσιμο swiper-slide div
    }
} else {
    echo '<div class="no-events-message">
            <i class="bi bi-calendar-x"></i>
            <p>Αυτή τη στιγμή δεν υπάρχουν διαθέσιμα events. Παρακαλώ ελέγξτε αργότερα για ενημερώσεις.</p>
          </div>';
}


?>

                        </div> <!-- Τέλος swiper-wrapper -->

                        <div class="swiper-pagination"></div>
                    </div>

                </div>

            </div>
		
        </div>
        
        <!-- modal participants -->
<div class="modal fade" id="participationModal" tabindex="-1" aria-labelledby="participationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="participationModalLabel">Επιβεβαίωση Συμμετοχής</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Θέλετε να συμμετέχετε σε αυτή την εκδήλωση;

        <!-- Φόρμα για την αποστολή συμμετοχής -->
        <form id="participationForm" method="POST" action="index.php?action=participate">
          <!-- Κρυφό πεδίο για το email του χρήστη -->
          <input type="hidden" name="email" id="userEmail">
          <!-- Κρυφό πεδίο για το eventId -->
          <input type="hidden" name="id_event" id="id_event">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Άκυρο</button>
        <button type="submit" form="participationForm" class="btn btn-primary">Συμμετοχή</button>
      </div>
    </div>
  </div>
</div>



<!-- modal akirosis simmetoxis -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cancelModalLabel">Επιβεβαίωση Ακύρωσης</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Είστε σίγουροι ότι θέλετε να ακυρώσετε τη συμμετοχή σας σε αυτή την εκδήλωση;

        <!-- Φόρμα για την αποστολή ακύρωσης -->
        <form id="cancelForm" method="POST" action="index.php?action=cancel">
          <!-- Κρυφό πεδίο για το email του χρήστη -->
          <input type="hidden" name="email" id="cancelUserEmail">
          <!-- Κρυφό πεδίο για το eventId -->
          <input type="hidden" name="id_event" id="cancelIdEvent">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Όχι</button>
        <button type="submit" form="cancelForm" class="btn btn-danger">Ακύρωση Συμμετοχής</button>
      </div>
    </div>
  </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    var participationModal = document.getElementById('participationModal');
    
    participationModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Κουμπί που ενεργοποίησε το modal
        const email = button.getAttribute('data-email');
        const id = button.getAttribute('data-id');
        // Λαμβάνουμε το email από τη session του PHP
        document.getElementById('userEmail').value = email;
        document.getElementById('id_event').value = id;
        // Εμφανίζουμε το email στο modal
        
    });
});
document.addEventListener('DOMContentLoaded', function () {
    var cancelModal = document.getElementById('cancelModal');

    cancelModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Κουμπί που ενεργοποίησε το modal
        const email = button.getAttribute('data-email');
        const id = button.getAttribute('data-id');
        // Λαμβάνουμε το email και το eventId
        document.getElementById('cancelUserEmail').value = email;
        document.getElementById('cancelIdEvent').value = id;
    });
});

</script>
<script>
    // Αν υπάρχει το μήνυμα επιτυχίας στην οθόνη, κλείσιμο του μετά από 3 δευτερόλεπτα
    setTimeout(function() {
        var successMessage = document.getElementById('successMessage1');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 3000); // 3000 ms = 3 δευτερόλεπτα
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        
</section><!-- /Testimonials Section -->

<section id="events" class="testimonials section">

    <div class="container section-title" data-aos="fade-up" style="padding-bottom:0px;">
        <h2>Ιατρικά Νέα</h2>
        <div class="container" style="display: flex; justify-content:center;">

            

                <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">
                    
                    <!-- Swiper Container -->
                    <div class="swiper init-swiper" id="allEvent">
                        <script type="application/json" class="swiper-config">
                        {
                            "loop": true,
                            "speed": 600,
                            "autoplay": {
                                "delay": 20000
                            },
                            "slidesPerView": "auto",
                            "pagination": {
                                "el": ".swiper-pagination",
                                "type": "bullets",
                                "clickable": true
                            }
                        }
                        </script>

                        <div class="swiper-wrapper"> <!-- Επανεισαγωγή του swiper-wrapper -->

                        <?php
// Έλεγχος σύνδεσης
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sql2 = "SELECT 
            u.name, 
            u.surname, 
            u.email, 
            d.photo, 
            d.specialization, 
            n.title, 
            n.description
        FROM users u
        JOIN doctors_info d ON u.user_id = d.doctor_id
        JOIN news n ON d.doctor_id = n.author_id";
        

$stmt2 = $conn->prepare($sql2);
if (!$stmt2) {
    die("SQL Error: " . $conn->error);
}

$stmt2->execute();
$result2 = $stmt2->get_result();



if ($result2->num_rows > 0) {
    while ($row2 = $result2->fetch_assoc()) {
      echo '<div class="swiper-slide">';
      echo '<div class="news-item">';
      
      // Επάνω μέρος (Φωτογραφία + Όνομα γιατρού)
      echo '<div style="display: flex; align-items: center; justify-content: space-between;">';
      if (!empty($row2["photo"])) {
          echo '<img src="assets/img/doctors/' . $row2["photo"] . '" alt="Γιατρός" style="width:80px;height:80px;border-radius:50%; margin-left: 20px; margin-top:25px;">';
      }
      echo '<div style="flex-grow: 1; text-align: left; margin-left: 15px;">';
      if (!empty($row2["name"]) && !empty($row2["surname"])) {
          echo '<h3 style="font-weight: bold; font-size: 18px;">' . htmlspecialchars($row2["name"]) . ' ' . htmlspecialchars($row2["surname"]) . '</h3>';
      }
      if (!empty($row2["specialization"])) {
        echo '<h5>Ειδικότητα: ' . htmlspecialchars($row2["specialization"]) . '</h5>';
    }
      echo '</div>';
      echo '</div>'; // Κλείσιμο πάνω μέρους
      
      // Περιεχόμενο άρθρου
      if (!empty($row2["title"])) {
          echo '<h3>' . htmlspecialchars($row2["title"]) . '</h3>';
      }
      if (!empty($row2["description"])) {
        echo '<p style = "padding:0px 20px;">
        <i class="bi bi-quote quote-icon-left"></i>
        <span>' . htmlspecialchars($row2["description"]) . '</span>
        <i class="bi bi-quote quote-icon-right"></i>
      </p>';
      }
      
      // // Υπόλοιπες πληροφορίες γιατρού
      // if (!empty($row2["email"])) {
      //     echo '<p><strong>Email:</strong> ' . htmlspecialchars($row2["email"]) . '</p>';
      // }
      
       echo '</div>'; // Κλείσιμο news-item
        echo '</div>'; // Κλείσιμο swiper-slide
        }
    } else {
        echo '<div class="swiper-slide">'; // Για να μην χαλάσει η δομή
        echo '<div class="no-events-message">';
        echo '<i class="bi bi-calendar-x"></i>';
        echo '<p>Αυτή τη στιγμή δεν υπάρχουν διαθέσιμα events. Παρακαλώ ελέγξτε αργότερα για ενημερώσεις.</p>';
        echo '</div>';
        echo '</div>';
    }
?>
</div> <!-- Τέλος swiper-wrapper --> <!-- Τέλος swiper-wrapper -->

                        <div class="swiper-pagination"></div>
                    </div>

                </div>

            </div>
		
        </div>
        
  </section>

<?php
    }
    else {

    }

?>

    
    <!-- <section id="gallery" class="gallery section">

      
      <div class="container section-title" data-aos="fade-up">
        <h2>Gallery</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div>

      <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0">

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-1.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-1.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-2.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-2.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-3.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-3.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-4.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-4.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-5.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-5.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-6.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-6.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-7.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-7.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery-8.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery-8.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div>

        </div>

      </div>

    </section> -->

    <!-- Contact Section -->

      <!-- Section Title -->
      <?php
        // include ".php";  edo eixa to contact alla apo oti katalava tha prepei na figei
      ?>
  </main>

  <footer id="footer" class="footer light-background">
    <div class="container footer-top">
        <div class="row gy-4 justify-content-between">
            <div class="col-lg-4 col-md-6 footer-about">
                <a href="index.html" class="logo d-flex align-items-center">
                    <span class="sitename">SchizoCare</span>
                </a>
                <div class="footer-contact pt-3">
                    <p>Cyprus</p>
                    <p>Contact us for more details</p>
                    <p class="mt-3"><strong>Phone:</strong> <span>+357 5589 55488 55</span></p>
                    <p><strong>Email:</strong> <span>info@schizocare.com</span></p>
                </div>
                
            </div>
          
            <div class="col-lg-6 col-md-6 footer-links">
                <h4>Quick Links</h4>
                <div class="row">
                    <div class="col-6">
                        <ul class="list-unstyled">
                            <li><a href="#hero">Αρχική Σελίδα</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#about">Ερωτήσεις για την σχιζοφρένεια</a></li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul class="list-unstyled">
                            <li><a href="#stats">Στατιστικά</a></li>
                            <li><a href="#questions">Συχνές ερωτήσεις</a></li>
                            <li><a href="contact.php">Επικοινωνία</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container copyright text-center mt-4">
        <p>© <strong class="sitename">SchizoCare</strong> 2025 | All Rights Reserved</p>
        <div class="credits">
            Designed & Developed by <strong>Antreas Christou</strong>, Student at CUT<br>
            Template from <a href="https://bootstrapmade.com/" target="_blank">BootstrapMade (Medilab)</a>
        </div>
    </div>
</footer>







  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>