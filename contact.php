<?php
include "config/config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Εισάγουμε το PHPMailer μέσω Composer

if (!isset($_SESSION['user'])) {
    header("Location:index.php"); 
    exit();
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

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">


</head>
<script>
  document.querySelector('.php-email-form').addEventListener('submit', async function (e) {
    e.preventDefault(); // Αποφυγή ανανέωσης της σελίδας

    const form = e.target;
    const formData = new FormData(form);

    const loading = document.querySelector('.loading');
    const errorMessage = document.querySelector('.error-message');
    const sentMessage = document.querySelector('.sent-message');

    loading.style.display = 'block';
    errorMessage.style.display = 'none';
    sentMessage.style.display = 'none';

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
        });

        if (response.ok) {
            loading.style.display = 'none';
            sentMessage.style.display = 'block';
            form.reset(); // Καθαρισμός της φόρμας
        } else {
            throw new Error('Η αποστολή απέτυχε.');
        }
    } catch (error) {
        loading.style.display = 'none';
        errorMessage.style.display = 'block';
        errorMessage.textContent = error.message;
    }
});
</script>

<style>
.info-item i {
    color: #1977CC; /* Ορισμός χρώματος στα icons */
}
  .loading {
    display: none; /* Κρύβεται μέχρι να ενεργοποιηθεί */
}
</style>


<?php include "header.php";
if (!isset($_SESSION['user']['user_id'])) {
    // Ανακατεύθυνση στη σελίδα login
    header("Location: landing_page/login.php");
    exit();
}

?>

<?php
// Αρχικοποιήστε τη μεταβλητή για να δείξει εάν στάλθηκε το μήνυμα
$messageSent = false;

// Έλεγχος αν το αίτημα είναι POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Έλεγχος αν υπάρχουν τα απαιτούμενα πεδία και εάν ο χρήστης είναι συνδεδεμένος
    if (
        isset($_POST['subject'], $_POST['message']) &&
        !empty($_POST['subject']) &&
        !empty($_POST['message']) &&
        isset($_SESSION['user'])
    ) {
        // Ανάκτηση email χρήστη από συνεδρία
        $user = $_SESSION['user'];
        $email = $user['email'];
        $subject = htmlspecialchars($_POST['subject']);
        $message = htmlspecialchars($_POST['message']);
        $to = "andreasggchristou@gmail.com";  // Το email του admin

        $mail = new PHPMailer(true);

        try {
            // Ρυθμίσεις SMTP για Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'andreasggchristou@gmail.com';
            $mail->Password = 'jbiq besk iazs obgs';  // App password - Μην το κρατάς στο κώδικα
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Ρυθμίσεις αποστολέα και παραλήπτη
            $mail->setFrom($email, 'User');
            $mail->addAddress($to);

            // Θέμα και μήνυμα
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Αποστολή email
            $mail->send();
            $messageSent = true;  // Ενημέρωση ότι το μήνυμα στάλθηκε
        } catch (Exception $e) {
            echo "Το μήνυμα δεν στάλθηκε. Σφάλμα: {$mail->ErrorInfo}";
        }
    }
}
?>
<body class="index-page">
<section id="contact" class="contact section">
    <div class="container section-title" data-aos="fade-up">
            <h2>Contact</h2>
            <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
          </div><!-- End Section Title -->
    
          <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
            <iframe style="border:0; width: 100%; height: 270px;" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus" frameborder="0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div><!-- End Google Maps -->
    
          <div class="container" style="padding-bottom: 40px;" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
            <!-- Στήλη για τα πληροφοριακά στοιχεία -->
            
            <div class="col-lg-6">
                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                <i class="bi bi-geo-alt"></i>
                    <div>
                        <h3>Location</h3>
                        <p>A108 Adam Street, New York, NY 535022</p>
                    </div>
                </div><!-- End Info Item -->
    
                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                <i class="bi bi-telephone"></i>
                    <div>
                        <h3>Call Us</h3>
                        <p>+357 5589 55488 55</p>
                    </div>
                </div><!-- End Info Item -->
    
                <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
                    <i class="bi bi-envelope"></i>
                    <div>
                        <h3>Email Us</h3>
                        <p>andreasggchristou@gmail.com</p>
                    </div>
                </div><!-- End Info Item -->
            </div>
    
            <!-- Στήλη για τη φόρμα -->
            <div class="col-lg-6">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="php-email-form">
                    <div class="row gy-4">
                        <h3>Όσα έχουν * είναι υποχρεωτικά</η>
                        <div class="col-md-12">
                            <label for="">Θέμα *</label>
                            <input type="text" class="form-control" name="subject" placeholder="Θέμα" required>
                        </div>
                        <div class="col-md-12">
                            <label for="">Μήνυμα *</label>
                            <textarea class="form-control" name="message" rows="6" placeholder="Μήνυμα" required></textarea>
                        </div>
                        <div class="col-md-12 text-center" style="padding-top:20px;">
                          <?php if ($messageSent): ?>
                              <div class="sent-message">Το μήνυμά σας στάλθηκε με επιτυχία. Ευχαριστούμε!</div>
                              <button type="submit" class="btn btn-primary mt-3">Αποστολή Νέου Μηνύματος</button>
                          <?php else: ?>
                              <button type="submit" class="btn btn-primary" >Αποστολή Μηνύματος</button>
                          <?php endif; ?>
                      </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>


<!-- Main JS File -->
<script src="assets/js/main.js"></script>
</body>
</html>