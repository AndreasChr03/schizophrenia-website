<?php
include "config/config.php";

// Prepare and execute the SQL query
$sql = "SELECT * FROM questionnaire";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>SchizoCare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #1977CC;
            color: white;
        }

        /* Styling for the radio button without border and background */
        td input[type="radio"] {
            width: 20px;
            height: 20px;
            appearance: none;
            border-radius: 50%; /* Circular appearance */
            border: 2px solid #1977CC;
            background-color: #ecf0f1;
            cursor: pointer;
            transition: background-color 0.3s, border-color 0.3s;
            position: relative;
        }

        /* When the radio button is checked, it becomes green with a checkmark */
        td input[type="radio"]:checked {
            background-color: #27ae60; /* Green color when checked */
            border-color: #27ae60;
        }

        td input[type="radio"]:checked::before {
            content: '✔'; /* Simple checkmark */
            color: white;
            position: absolute;
            top: -2px;
            left: 0px;
            font-size: 14px; /* Smaller size */
        }

        td input[type="radio"]:hover {
            border-color: #2980b9;
        }

        .submit-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #1977CC;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #2980b9;
        }

        .submit-btn:disabled {
            background-color: #b0bec5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Ερωτηματολόγιο</h1>
        <form id="quiz-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Αριθμός</th>
                        <th>Ερώτηση</th>
                        <th>Διαφωνώ Απόλυτα</th>
                        <th>Διαφωνώ Ελαφρώς</th>
                        <th>Συμφωνώ Ελαφρώς</th>
                        <th>Συμφωνώ Απόλυτα</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    if ($result->num_rows > 0) {
                        $ctr = 0;
                        while ($row = $result->fetch_assoc()) {
                            $ctr++;
                            $questionId = $ctr;
                            echo "<tr>";
                            echo "<td>" . $questionId . "</td>";
                            echo "<td>" . $row["question"] . "</td>";
                            echo "<td><input type='radio' name='answer_" . $questionId . "' value='1'></td>";
                            echo "<td><input type='radio' name='answer_" . $questionId . "' value='2'></td>";
                            echo "<td><input type='radio' name='answer_" . $questionId . "' value='3'></td>";
                            echo "<td><input type='radio' name='answer_" . $questionId . "' value='4'></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No questions found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="button" style="border-color: #1977CC;" class="submit-btn" id="submit-button" data-toggle="modal" data-target="#exampleModal" >Submit</button>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ερωτηματολόγιο</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-justify" id="modal-body">
                <p>Your results will appear here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλείσιμο</button>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#submit-button').click(function () {
                var formData = $('#quiz-form').serializeArray();
                var results = '';
                var totalScore = 0;
                var remind = '';
                var totalQuestions = formData.length;
                
                // Process responses
                formData.forEach(function (item) {
                    
                    totalScore += parseInt(item.value);
                });
                
                
                var maxPoints = totalQuestions * 4;
                var percentage = (totalScore / maxPoints)* 100;
                // var percentage = points / maxPoints;

                var performance = '';
                if (totalScore < totalQuestions * 2.5) {
                    results = 'Χωρίς Ενδείξεις Σχιζοφρένειας';
                    performance = 'Με βάση τις απαντήσεις σας σε αυτή τη δοκιμή ανίχνευσης σχιζοφρένειας, δεν φαίνεται να παρουσιάζετε σημάδια που συνδέονται συνήθως με τη σχιζοφρένεια ή μια σχετική διαταραχή. Οι απαντήσεις σας είναι παρόμοιες με εκείνες των ατόμων που δεν βιώνουν συμπτώματα όπως ψευδαισθήσεις, παραληρητικές ιδέες, αποδιοργανωμένη ομιλία ή συναισθηματική "επίπεδη διάθεση".';
                    remind = 'Είναι σημαντικό να σημειωθεί ότι αυτό το διαδικτυακό τεστ δεν αποτελεί διαγνωστικό εργαλείο και δεν μπορεί να επιβεβαιώσει ή να αποκλείσει οποιαδήποτε ψυχική διαταραχή. Εάν έχετε ανησυχίες για την ψυχική σας υγεία ή κάποιου αγαπημένου σας προσώπου, εξετάστε το ενδεχόμενο να συμβουλευτείτε έναν εξειδικευμένο επαγγελματία ψυχικής υγείας για περαιτέρω αξιολόγηση και υποστήριξη.';
                } else if (totalScore < totalQuestions * 3.5) {
                    results = 'Πιθανότητα πρώιμης σχιζοφρένειας';
                    performance = 'Με βάση τις απαντήσεις σας σε αυτή τη δοκιμή ανίχνευσης σχιζοφρένειας, ενδέχεται να εμφανίζετε κάποια πρώιμα σημάδια που σχετίζονται με τη σχιζοφρένεια ή μια σχετική διαταραχή. Οι απαντήσεις σας είναι παρόμοιες με εκείνες των ατόμων που εμφανίζουν πρώιμα συμπτώματα σχιζοφρένειας ή μιας σχετικής διαταραχής, συμπεριλαμβανομένων συμπτωμάτων όπως ψευδαισθήσεις, παραληρητικές ιδέες, αποδιοργανωμένη ομιλία και/ή συναισθηματική "επίπεδη διάθεση".';
                    remind = 'Δεδομένου ότι αυτό το διαδικτυακό τεστ δεν μπορεί να διαγνώσει κάποια διαταραχή, δεν μπορεί να σας πει αν έχετε σχιζοφρένεια ή όχι. Απλώς δείχνει ότι οι απαντήσεις σας είναι παρόμοιες με εκείνες που ενδέχεται να πληρούν τα κριτήρια για διάγνωση σχιζοφρένειας.';
                } else {
                    results = 'Πρώιμη σχιζοφρένεια';
                    performance = 'Με βάση τις απαντήσεις σας σε αυτή τη δοκιμή ανίχνευσης σχιζοφρένειας, φαίνεται ότι παρουσιάζετε κάποια σημάδια που συνδέονται συνήθως με τη σχιζοφρένεια ή μια σχετική διαταραχή. Οι απαντήσεις σας είναι παρόμοιες με εκείνες των ατόμων που εμφανίζουν συμπτώματα σχιζοφρένειας ή μιας σχετικής διαταραχής, συμπεριλαμβανομένων συμπτωμάτων όπως ψευδαισθήσεις, παραληρητικές ιδέες, αποδιοργανωμένη ομιλία και/ή συναισθηματική "επίπεδη διάθεση". Δεδομένου ότι αυτό το διαδικτυακό τεστ δεν μπορεί να διαγνώσει κάποια διαταραχή, δεν μπορεί να σας πει αν έχετε σχιζοφρένεια ή όχι. Απλώς δείχνει ότι οι απαντήσεις σας είναι παρόμοιες με εκείνες που ενδέχεται να πληρούν τα κριτήρια για διάγνωση σχιζοφρένειας.';
                    remind = 'Δεδομένου ότι αυτό το διαδικτυακό τεστ δεν μπορεί να διαγνώσει κάποια διαταραχή, δεν μπορεί να σας πει αν έχετε σχιζοφρένεια ή όχι. Απλώς δείχνει ότι οι απαντήσεις σας είναι παρόμοιες με εκείνες που ενδέχεται να πληρούν τα κριτήρια για διάγνωση σχιζοφρένειας.';
                }

                
                $('#modal-body').html(`
                    <h6>Το αποτέλεσμα σου:</h6>
                    <ul>${results}</ul>
                    <h6>Περιγραφή: </h6>
                    <ul>${performance}</ul>
                    <h6>Υποσημείωση: </h6>
                    <ul>${remind}</ul>
                `);
                $('#exampleModal').modal('show');
                
                        $.ajax({
                url: 'save_percentage.php', // Το script για την αποθήκευση
                method: 'POST',
                data: {
                    percentage: percentage.toFixed(2),
                    result: results // Προαιρετικά, μπορείς να στείλεις και άλλα δεδομένα
                },
                success: function (response) {
                    console.log('Percentage saved successfully:', response);
                },
                error: function (xhr, status, error) {
                    console.error('Error saving percentage:', error);
                }
            });
        });
    });
    </script>
    <script>
        // Επιλογή των απαραίτητων στοιχείων
const form = document.getElementById('quiz-form');
const submitButton = document.getElementById('submit-button');
const questions = form.querySelectorAll('tbody tr'); // Κάθε ερώτηση είναι μια σειρά στον πίνακα

// Αρχικοποίηση: Απενεργοποίηση κουμπιού υποβολής
submitButton.disabled = true;

// Συνάρτηση για έλεγχο αν όλες οι ερωτήσεις έχουν απαντηθεί
function checkIfAllAnswered() {
    let allAnswered = true;

    // Έλεγχος κάθε σειράς (ερώτησης)
    questions.forEach(question => {
        const radioButtons = question.querySelectorAll('input[type="radio"]');
        let answered = false;

        // Έλεγχος αν κάποιο κουμπί είναι επιλεγμένο
        radioButtons.forEach(button => {
            if (button.checked) {
                answered = true;
            }
        });

        // Αν μια ερώτηση δεν έχει απαντηθεί, ορίζουμε τη μεταβλητή ως false
        if (!answered) {
            allAnswered = false;
        }
    });

    // Ενεργοποίηση ή απενεργοποίηση του κουμπιού βάσει της κατάστασης
    submitButton.disabled = !allAnswered;
}

// Εκτέλεση της συνάρτησης κάθε φορά που αλλάζει η φόρμα
form.addEventListener('change', checkIfAllAnswered);
</script>
</body>
</html>
