<?php
// Ξεκινάς τη συνεδρία
session_start();

// Καθαρίζεις όλα τα δεδομένα της συνεδρίας
session_unset();

// Καταστρέφεις τη συνεδρία
session_destroy();

// Ανακατεύθυνση του χρήστη στη σελίδα login ή αρχική
header("Location: ../index.php");
exit();
?>