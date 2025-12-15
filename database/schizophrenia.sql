-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2025 at 10:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schizophrenia`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment_details`
--

CREATE TABLE `appointment_details` (
  `id` int(11) NOT NULL,
  `appointmentDate` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `clientEmail` varchar(255) NOT NULL,
  `medications` varchar(255) NOT NULL,
  `therapy_compliance` varchar(255) NOT NULL,
  `symptoms` varchar(255) NOT NULL,
  `symptom_severity` varchar(255) NOT NULL,
  `psychiatrist_notes` varchar(255) NOT NULL,
  `doctor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_details`
--

INSERT INTO `appointment_details` (`id`, `appointmentDate`, `name`, `clientEmail`, `medications`, `therapy_compliance`, `symptoms`, `symptom_severity`, `psychiatrist_notes`, `doctor_id`) VALUES
(1, '2025-01-01', 'Stelios', 'andreasggchristou@gmail.com', 'Medication B', 'Partially Compliant', 'Moderate depression', 'moderate', 'The patient has intermittent issues with compliance.', 50),
(2, '2025-01-02', 'Andreas', 'andreasggchristou1@gmail.com', 'Medication C', 'Non-Compliant', 'Severe psychosis', 'severe', 'The patient reports worsening symptoms due to therapy interruption.', 52),
(3, '2024-02-03', 'Stelios', 'andreasggchristou@gmail.com', 'Medication D', 'Compliant', 'Mild anxiety', 'mild', 'The patient shows improvement with medication.', 60),
(4, '2025-01-04', 'Andreas', 'andreasggchristou1@gmail.com', 'Medication E', 'Partially Compliant', 'Moderate depression', 'moderate', 'The patient has intermittent issues with compliance.', 60),
(5, '2025-01-05', 'Stelios', 'andreasggchristou@gmail.com', 'Medication A', 'Non-Compliant', 'Severe psychosis', 'severe', 'The patient reports worsening symptoms due to therapy interruption.', 60),
(6, '2025-01-06', 'Andreas', 'andreasggchristou1@gmail.com', 'Medication B', 'Compliant', 'Mild anxiety', 'mild', 'The patient shows improvement with medication.', 0),
(7, '2023-01-07', 'Stelios', 'andreasggchristou@gmail.com', 'Medication C', 'Partially Compliant', 'Moderate depression', 'moderate', 'The patient has intermittent issues with compliance.', 60),
(8, '2023-01-08', 'Andreas', 'andreasggchristou1@gmail.com', 'Medication D', 'Non-Compliant', 'Severe psychosis', 'severe', 'The patient reports worsening symptoms due to therapy interruption.', 60),
(9, '2025-01-09', 'Stelios', 'andreasggchristou@gmail.com', 'Medication E', 'Compliant', 'Mild anxiety', 'mild', 'The patient shows improvement with medication.', 60),
(10, '2025-01-10', 'Andreas', 'andreasggchristou1@gmail.com', 'Medication A', 'Partially Compliant', 'Moderate depression', 'moderate', 'The patient has intermittent issues with compliance.', 0),
(11, '2025-01-11', 'Stelios', 'andreasggchristou@gmail.com', 'Medication B', 'Non-Compliant', 'Severe psychosis', 'severe', 'The patient reports worsening symptoms due to therapy interruption.', 60),
(12, '2025-01-12', 'Andreas', 'andreasggchristou1@gmail.com', 'Medication C', 'Compliant', 'Mild anxiety', 'mild', 'The patient shows improvement with medication.', 0),
(13, '2025-01-13', 'Stelios', 'andreasggchristou@gmail.com', 'Medication D', 'Partially Compliant', 'Moderate depression', 'moderate', 'The patient has intermittent issues with compliance.', 0),
(14, '2025-01-14', 'Andreas', 'andreasggchristou1@gmail.com', 'Medication E', 'Non-Compliant', 'Severe psychosis', 'severe', 'The patient reports worsening symptoms due to therapy interruption.', 60),
(15, '2025-01-15', 'Stelios', 'andreasggchristou@gmail.com', 'Medication A', 'Compliant', 'Mild anxiety', 'mild', 'The patient shows improvement with medication.', 50),
(16, '2025-01-16', 'Andreas', 'andreasggchristou1@gmail.com', 'Medication B', 'Partially Compliant', 'Moderate depression', 'moderate', 'The patient has intermittent issues with compliance.', 52),
(17, '2025-01-17', 'Stelios', 'andreasggchristou@gmail.com', 'Medication C', 'Non-Compliant', 'Severe psychosis', 'severe', 'The patient reports worsening symptoms due to therapy interruption.', 52),
(18, '2025-01-18', 'Andreas', 'andreasggchristou1@gmail.com', 'Medication D', 'Compliant', 'Mild anxiety', 'mild', 'The patient shows improvement with medication.', 52),
(19, '2025-01-19', 'Stelios', 'andreasggchristou@gmail.com', 'Medication E', 'Partially Compliant', 'Moderate depression', 'moderate', 'The patient has intermittent issues with compliance.', 50),
(20, '2025-01-20', 'Andreas', 'andreasggchristou1@gmail.com', 'Medication A', 'Non-Compliant', 'Severe psychosis', 'severe', 'The patient reports worsening symptoms due to therapy interruption.', 0),
(35, '2025-02-01', 'Andreas', 'client@gmail.com', 'medicine A', 'ναι', 'ειναι καλυτερα', 'moderate', 'τιποτα', 0),
(36, '2025-02-05', 'Andreas', 'client@gmail.com', 'medicine A for the next 3 weeks', '123', '1234', 'severe', '123', 0),
(37, '2025-02-05', 'Andreas', 'client@gmail.com', 'medicine A for the next 3 weeks', '123', 'tdyhg', 'moderate', 'hug', 0),
(38, '2025-04-15', 'Andreas', 'doctor2@gmail.com', 'medicine A for the next 3 weeks', '123', 'daw', 'moderate', 'dwa', 60),
(39, '2025-04-15', 'Andreas', 'andreasggchristou123@gmail.com', 'medicine A for the next 3 weeks', '321', 'wda', 'severe', 'adw', 60),
(40, '2025-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'kati', 'kati', 'test', 'moderate', 'test', 60),
(41, '2023-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'kati', 'kati', 'test', 'moderate', 'test', 60),
(42, '2023-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'kati', 'kati', 'test', 'moderate', 'test', 60),
(43, '2023-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'tedt', 'test', 'testte', 'mild', 'test', 60),
(44, '2023-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'tedt', 'test', 'testte', 'mild', 'test', 60),
(45, '2023-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'tedt', 'test', 'testte', 'mild', 'test', 60),
(46, '2024-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'tedt', 'test', 'testte', 'mild', 'test', 60),
(47, '2024-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'tedt', 'test', 'testte', 'mild', 'test', 60),
(48, '2024-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'tedt', 'test', 'testte', 'mild', 'test', 60),
(49, '2024-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'tedt', 'test', 'testte', 'mild', 'test', 60),
(50, '2023-05-19', 'wad', '123@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(51, '2023-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(52, '2023-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(53, '2023-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(54, '2024-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(55, '2024-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(56, '2024-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(57, '2024-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(58, '2024-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(59, '2024-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(60, '2025-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'moderate', 'test', 60),
(61, '2025-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'moderate', 'test', 60),
(62, '2024-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'moderate', 'test', 60),
(63, '2024-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'moderate', 'test', 60),
(64, '2024-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'moderate', 'test', 60),
(65, '2024-05-19', 'Andreas', 'doctor2@gmail.com', 'test', 'test', 'test', 'moderate', 'test', 60),
(66, '2025-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(67, '2025-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(68, '2025-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(69, '2025-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(70, '2025-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(71, '2025-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(72, '2025-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(73, '2025-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(74, '2025-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(75, '2025-05-19', 'Andreas', 'andrdaweasggchristou@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60),
(76, '2025-05-22', 'Stelios', 'andreasggchristou@gmail.com', 'test', 'test', 'test', 'mild', 'test', 60);

-- --------------------------------------------------------

--
-- Table structure for table `blocked_users`
--

CREATE TABLE `blocked_users` (
  `id` int(11) NOT NULL,
  `banned` tinyint(4) NOT NULL,
  `user_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blocked_users`
--

INSERT INTO `blocked_users` (`id`, `banned`, `user_email`) VALUES
(47, 1, '123@gmail.com'),
(48, 1, 'doctor2@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `doctors_info`
--

CREATE TABLE `doctors_info` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `information` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors_info`
--

INSERT INTO `doctors_info` (`id`, `doctor_id`, `specialization`, `photo`, `information`) VALUES
(1, 60, 'Ψυχίατρος | Ψυχοθεραπεύτης', 'doctors-1.jpg', 'Με εμπειρία άνω των 10 ετών στον τομέα της ψυχιατρικής, έχω εξειδικευτεί στη διάγνωση και θεραπεία ψυχικών διαταραχών όπως κατάθλιψη, άγχος, σχιζοφρένεια και διαταραχές προσωπικότητας. Δίνω μεγάλη έμφαση στην ανάπτυξη εξατομικευμένων θεραπευτικών προσεγγί'),
(2, 50, 'Ψυχίατρος | Ψυχοθεραπεύτης', 'doctors-3.jpg', 'tpt'),
(3, 52, 'Ψυχίατρος | Ψυχοθεραπεύτρια', 'doctors-2.jpg', 'Με 12 χρόνια εμπειρίας στην κλινική ψυχιατρική, η Δρ. Ελένη Παπαγεωργίου έχει εξειδικευτεί στη θεραπεία ψυχικών διαταραχών και αναπτυξιακών προβλημάτων. Αξιοποιώντας σύγχρονες ψυχοθεραπευτικές τεχνικές, όπως η γνωσιακή-συμπεριφορική θεραπεία (CBT), προσφέ'),
(10, 74, 'Κλινικός Ψυχολόγος', 'doctors-5.jpg', '123'),
(11, 75, 'Παιδοψυχολόγος', 'doctors-5.jpg', '123'),
(12, 76, 'Συμβουλευτικός Ψυχολόγος', 'doctors-1.jpg', 'daee'),
(13, 78, 'Κλινικός Ψυχολόγος', 'doctors-4.jpg', '789');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL DEFAULT current_timestamp(),
  `description` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `submit` tinyint(1) NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `date`, `time`, `description`, `user_id`, `submit`, `creator_id`) VALUES
(1, 'Εκδρομή στο Τρόοδος', '2025-10-24', '14:44:00', 'Την Κυριακή θα πάμε εκδρομή στο Τρόοδος με λεωφορείο. Θα επισκεφθούμε έναν όμορφο εκδρομικό χώρο, όπου θα χαλαρώσουμε και θα απολαύσουμε τη φύση. Μετά θα γευματίσουμε όλοι μαζί σε έναν κοντινό χώρο. Το απόγευμα θα επιστρέψουμε γεμάτοι όμορφες στιγμές!', 62, 1, 0),
(6, 'Βόλτα στη Λεμεσό με ιστιοπλοϊκό', '2025-07-21', '21:14:00', 'Το Σάββατο θα πάμε μια μοναδική βόλτα στη Λεμεσό με ιστιοπλοϊκό. Θα απολαύσουμε τη θάλασσα, θα δούμε το ηλιοβασίλεμα και θα χαλαρώσουμε παρέα με καλή μουσική. Κατά τη διάρκεια της βόλτας, θα προσφερθούν σνακ και δροσερά ροφήματα. Μια εμπειρία που θα μείνε', 62, 1, 0),
(7, 'Πεζοπορία στον Ακάμα', '2025-08-24', '05:26:00', 'Την επόμενη Κυριακή, θα οργανώσουμε μια όμορφη πεζοπορία στο μονοπάτι της φύσης στον Ακάμα. Θα ανακαλύψουμε την ομορφιά του τοπίου, θα σταματήσουμε σε σημεία με πανοραμική θέα και θα τραβήξουμε φωτογραφίες. Στη συνέχεια, θα έχουμε πικνίκ με τοπικά προϊόντ', 61, 1, 62),
(12, 'Εκδρομή στο Τρόοδος', '2025-07-29', '19:09:00', 'Μια ημερήσια εκδρομή στο βουνό Τρόοδος, με περίπατο στα μονοπάτια της φύσης και στάση στους καταρράκτες Καληδονίων. Ιδανική ευκαιρία για χαλάρωση και επαφή με τη φύση.', 62, 1, 62),
(14, 'Επίσκεψη στην Κακοπετριά', '2025-11-21', '20:38:00', 'Ανακάλυψε την παραδοσιακή ομορφιά της Κακοπετριάς με περίπατο στο παλιό χωριό και γεύμα σε ταβέρνα δίπλα στο ποτάμι.', 60, 1, 0),
(15, 'Ημερήσια εκδρομή στον Ακάμα', '2025-08-01', '15:12:00', 'Εξερεύνηση του φυσικού πάρκου Ακάμα με στάση στα Λουτρά της Αφροδίτης και πεζοπορία στο μονοπάτι της Αφροδίτης με πανοραμική θέα στη θάλασσα.', 60, 1, 0),
(16, 'Πεζοπορια στο Τροοδος', '2025-09-22', '17:00:00', 'Θα παμε πεζοπορια στο βουνο και μετα θα φαμε στο εστιατοριο', 76, 1, 0),
(19, 'Βόλτα στην Θάλασσα(KOT)', '2025-12-25', '12:12:00', 'Απογευματινή χαλαρωτική βόλτα δίπλα στο κύμα με φίλους, παγωτό στο χέρι και θέα το ηλιοβασίλεμα. Μια απλή αλλά αξέχαστη εμπειρία.', 62, 0, 0),
(20, 'Eκδρομή στο Τροοδος', '2025-11-30', '01:00:00', 'Μια ημερήσια εκδρομή στο βουνό Τρόοδος, με περίπατο στα μονοπάτια της φύσης και στάση στους καταρράκτες Καληδονίων. Ιδανική ευκαιρία για χαλάρωση και επαφή με τη φύση.', 60, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id_log` int(11) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `action` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id_log`, `date_time`, `action`, `email`) VALUES
(1, '2025-01-26 14:21:30', 'Δημιουργεία λογαριασμού', 'andrea2sggchristou@gmail.com'),
(2, '2025-01-26 14:51:37', 'Συμμετοχή σε εκδήλωση', 'andreasggchristou@gmail.com'),
(3, '2025-01-26 14:55:58', 'Συμμετοχή σε εκδήλωση', 'andreasggchristou@gmail.com'),
(4, '2025-01-26 15:13:02', 'Διαγραφή ερώτησης', 'andreasggchristou@gmail.com'),
(5, '2025-01-26 15:13:07', 'Διαγραφή ερώτησης', 'andreasggchristou@gmail.com'),
(6, '2025-01-26 15:18:35', 'Φόρμα αξιολόγησης', 'andreasggchristou123@gmail.com'),
(7, '2025-01-26 15:26:56', 'Φόρμα αξιολόγησης Πελάτη', 'andreasggchristou123@gmail.com'),
(8, '2025-01-26 15:27:08', 'Φόρμα αξιολόγησης Πελάτη', 'andreasggchristou123@gmail.com'),
(9, '2025-01-26 15:28:01', 'Φόρμα αξιολόγησης Πελάτη', 'andreasggchristou123@gmail.com'),
(10, '2025-01-26 15:29:11', 'Φόρμα αξιολόγησης Πελάτη', 'andreasggchristou123@gmail.com'),
(11, '2025-01-26 15:29:21', 'Φόρμα αξιολόγησης Πελάτη', 'andreasggchristou123@gmail.com'),
(12, '2025-01-26 15:32:37', 'Φόρμα αξιολόγησης Πελάτη', 'andreasggchristou123@gmail.com'),
(13, '2025-01-26 15:33:11', 'Φόρμα αξιολόγησης Πελάτη', 'andreasggchristou123@gmail.com'),
(14, '2025-01-26 16:43:30', 'Συμμετοχή σε εκδήλωση', 'client@gmail.com'),
(15, '2025-01-26 16:43:34', 'Συμμετοχή σε εκδήλωση', 'client@gmail.com'),
(16, '2025-01-26 16:44:04', 'Ακύρωση συμμετοχής σε εκδήλωση', 'client@gmail.com'),
(17, '2025-01-28 15:26:01', 'Δημιουργία νέας εκδήλωσης', 'andreasggchristou@gmail.com'),
(18, '2025-01-28 15:26:31', 'Δημιουργία νέας εκδήλωσης', 'andreasggchristou@gmail.com'),
(19, '2025-01-29 15:50:05', 'Συμμετοχή σε εκδήλωση', 'andreasggchristou@gmail.com'),
(20, '2025-01-29 15:50:10', 'Ακύρωση συμμετοχής σε εκδήλωση', 'andreasggchristou@gmail.com'),
(21, '2025-02-01 10:29:04', 'Συμμετοχή σε εκδήλωση', ''),
(22, '2025-02-01 10:42:06', 'Συμμετοχή σε εκδήλωση', ''),
(23, '2025-02-01 10:43:10', 'Συμμετοχή σε εκδήλωση', ''),
(24, '2025-02-01 10:54:37', 'Ακύρωση συμμετοχής σε εκδήλωση', ''),
(25, '2025-02-01 11:07:54', 'Ακύρωση συμμετοχής σε εκδήλωση', 'client@gmail.com'),
(26, '2025-02-01 11:08:05', 'Συμμετοχή σε εκδήλωση', 'client@gmail.com'),
(27, '2025-02-01 11:09:22', 'Συμμετοχή σε εκδήλωση', 'client@gmail.com'),
(28, '2025-02-01 11:10:00', 'Ακύρωση συμμετοχής σε εκδήλωση', 'client@gmail.com'),
(29, '2025-02-01 11:10:32', 'Ακύρωση συμμετοχής σε εκδήλωση', 'client@gmail.com'),
(30, '2025-02-01 11:11:22', 'Συμμετοχή σε εκδήλωση', 'client@gmail.com'),
(31, '2025-02-01 11:15:01', 'Ακύρωση συμμετοχής σε εκδήλωση', 'client@gmail.com'),
(32, '2025-02-01 11:15:23', 'Συμμετοχή σε εκδήλωση', 'client@gmail.com'),
(33, '2025-02-01 12:39:42', 'Φόρμα αξιολόγησης Πελάτη', 'client@gmail.com'),
(34, '2025-02-02 22:11:11', 'Προσθήκη νέας ερώτησης', 'andreasggchristou@gmail.com'),
(35, '2025-02-02 22:11:23', 'Ενημέρωση ερώτησης', 'andreasggchristou@gmail.com'),
(36, '2025-02-02 22:11:48', 'Ενημέρωση ερώτησης', 'andreasggchristou@gmail.com'),
(37, '2025-02-02 22:12:33', 'Διαγραφή ερώτησης', 'andreasggchristou@gmail.com'),
(38, '2025-02-02 22:13:08', 'Προσθήκη νέας ερώτησης', 'andreasggchristou@gmail.com'),
(39, '2025-02-02 22:13:40', 'Προσθήκη νέας ερώτησης', 'andreasggchristou@gmail.com'),
(40, '2025-02-02 22:13:55', 'Ενημέρωση ερώτησης', 'andreasggchristou@gmail.com'),
(41, '2025-02-02 22:14:02', 'Διαγραφή ερώτησης', 'andreasggchristou@gmail.com'),
(42, '2025-02-02 22:14:12', 'Διαγραφή ερώτησης', 'andreasggchristou@gmail.com'),
(43, '2025-02-02 22:14:39', 'Συμμετοχή σε εκδήλωση', 'andreasggchristou@gmail.com'),
(44, '2025-02-02 22:14:47', 'Ακύρωση συμμετοχής σε εκδήλωση', 'andreasggchristou@gmail.com'),
(45, '2025-02-02 22:15:00', 'Συμμετοχή σε εκδήλωση', 'andreasggchristou@gmail.com'),
(46, '2025-02-02 22:15:04', 'Ακύρωση συμμετοχής σε εκδήλωση', 'andreasggchristou@gmail.com'),
(47, '2025-02-02 22:16:05', 'Δημιουργία νέας εκδήλωσης', 'andreasggchristou@gmail.com'),
(48, '2025-02-02 22:16:18', 'Ακύρωση εκδήλωσης', 'andreasggchristou@gmail.com'),
(49, '2025-02-05 10:34:35', 'Συμμετοχή σε εκδήλωση', 'client@gmail.com'),
(50, '2025-02-05 10:56:21', 'Φόρμα αξιολόγησης Πελάτη', 'client@gmail.com'),
(51, '2025-02-05 10:57:08', 'Φόρμα αξιολόγησης Πελάτη', 'client@gmail.com'),
(52, '2025-02-05 10:57:57', 'Ενημέρωση εκδήλωσης', 'andreasggchristou@gmail.com'),
(53, '2025-02-05 10:59:35', 'Ενημέρωση εκδήλωσης', 'andreasggchristou@gmail.com'),
(54, '2025-02-05 10:59:53', 'Ενημέρωση εκδήλωσης', 'andreasggchristou@gmail.com'),
(55, '2025-02-05 11:04:11', 'Ενημέρωση εκδήλωσης', 'andreasggchristou@gmail.com'),
(56, '2025-02-05 11:04:50', 'Ενημέρωση εκδήλωσης', 'andreasggchristou@gmail.com'),
(57, '2025-02-05 11:05:15', 'Ενημέρωση εκδήλωσης', 'andreasggchristou@gmail.com'),
(58, '2025-02-05 13:12:31', 'Ακύρωση συμμετοχής σε εκδήλωση', 'andreasggchristou@gmail.com'),
(59, '2025-02-05 13:19:52', 'Συμμετοχή σε εκδήλωση', 'andreasggchristou@gmail.com'),
(60, '2025-02-05 13:19:55', 'Ακύρωση συμμετοχής σε εκδήλωση', 'andreasggchristou@gmail.com'),
(61, '2025-03-09 13:21:38', 'Δημιουργία λογαριασμού', 'volunteer@gmail.com'),
(62, '2025-03-09 13:22:45', 'Δημιουργία λογαριασμού', 'doctor@gmail.com'),
(63, '2025-03-10 18:34:55', 'Ακύρωση εκδήλωσης', 'andreasggchristou@gmail.com'),
(64, '2025-03-11 14:39:52', 'Συμμετοχή σε εκδήλωση', 'andreasggchristou@gmail.com'),
(65, '2025-03-11 14:39:59', 'Ακύρωση συμμετοχής σε εκδήλωση', 'andreasggchristou@gmail.com'),
(66, '2025-03-21 09:45:01', 'Φόρμα αξιολόγησης Πελάτη', 'client@gmail.com'),
(67, '2025-03-21 12:24:02', 'Ενημέρωση είδησης', 'doctor@gmail.com'),
(68, '2025-03-21 12:24:11', 'Ενημέρωση είδησης', 'doctor@gmail.com'),
(69, '2025-03-21 12:31:26', 'Ενημέρωση είδησης', 'doctor@gmail.com'),
(70, '2025-03-21 12:32:50', 'Ενημέρωση είδησης', 'doctor@gmail.com'),
(71, '2025-03-21 12:34:41', 'Ενημέρωση είδησης', 'doctor@gmail.com'),
(72, '2025-03-21 12:44:12', 'Προσθήκη νέας ιατρικής είδησης', 'doctor@gmail.com'),
(73, '2025-03-21 12:55:23', 'Προσθήκη νέας ιατρικής είδησης', 'doctor@gmail.com'),
(74, '2025-03-21 12:57:39', 'Προσθήκη νέας ιατρικής είδησης', 'doctor@gmail.com'),
(75, '2025-03-21 12:57:46', 'Διαγραφή ερώτησης', 'doctor@gmail.com'),
(76, '2025-03-21 13:05:55', 'Διαγραφή ερώτησης', 'doctor@gmail.com'),
(77, '2025-03-21 13:06:19', 'Διαγραφή ερώτησης', 'doctor@gmail.com'),
(78, '2025-03-21 13:10:30', 'Προσθήκη νέας ιατρικής είδησης', 'doctor@gmail.com'),
(79, '2025-03-21 14:15:37', 'Δημιουργία λογαριασμού', 'doctor2@gmail.com'),
(80, '2025-03-25 11:43:25', 'Δημιουργία λογαριασμού', 'volunteer2@gmail.com'),
(81, '2025-03-25 12:17:30', 'Δημιουργία νέας εκδήλωσης', 'volunteer2@gmail.com'),
(82, '2025-03-25 12:20:03', 'Ακύρωση εκδήλωσης', 'volunteer2@gmail.com'),
(83, '2025-03-25 12:26:17', 'Δημιουργία νέας εκδήλωσης', 'volunteer2@gmail.com'),
(84, '2025-03-25 12:27:28', 'Δημιουργία νέας εκδήλωσης', 'volunteer2@gmail.com'),
(85, '2025-03-25 12:27:33', 'Ακύρωση εκδήλωσης', 'volunteer2@gmail.com'),
(86, '2025-03-25 14:19:45', 'Συμμετοχή σε εκδήλωση', 'volunteer2@gmail.com'),
(87, '2025-03-25 14:19:51', 'Ακύρωση συμμετοχής σε εκδήλωση', 'volunteer2@gmail.com'),
(88, '2025-03-25 14:19:54', 'Συμμετοχή σε εκδήλωση', 'volunteer2@gmail.com'),
(89, '2025-03-25 14:20:04', 'Ακύρωση εκδήλωσης', 'volunteer2@gmail.com'),
(90, '2025-03-25 14:21:05', 'Ακύρωση συμμετοχής σε εκδήλωση', 'volunteer2@gmail.com'),
(91, '2025-03-26 11:52:28', 'Δημιουργία λογαριασμού', 'client3@gmail.com'),
(92, '2025-03-26 11:58:01', 'Προσθήκη νέας ιατρικής είδησης', 'client3@gmail.com'),
(93, '2025-03-26 12:06:31', 'Δημιουργία νέας εκδήλωσης', 'volunteer2@gmail.com'),
(94, '2025-03-30 14:18:02', 'Ενημέρωση εκδήλωσης', 'doctor@gmail.com'),
(95, '2025-03-30 14:19:08', 'Ενημέρωση εκδήλωσης', 'doctor@gmail.com'),
(96, '2025-03-30 14:21:12', 'Δημιουργία νέας εκδήλωσης', 'doctor@gmail.com'),
(97, '2025-03-30 14:21:18', 'Συμμετοχή σε εκδήλωση', 'doctor@gmail.com'),
(98, '2025-03-30 14:21:21', 'Ακύρωση συμμετοχής σε εκδήλωση', 'doctor@gmail.com'),
(99, '2025-03-30 14:21:26', 'Ακύρωση εκδήλωσης', 'doctor@gmail.com'),
(100, '2025-03-30 14:21:29', 'Ενημέρωση εκδήλωσης', 'doctor@gmail.com'),
(101, '2025-03-30 14:22:51', 'Ακύρωση εκδήλωσης', 'doctor@gmail.com'),
(102, '2025-03-30 14:23:04', 'Ενημέρωση εκδήλωσης', 'doctor@gmail.com'),
(103, '2025-03-30 14:35:19', 'Δημιουργία νέας εκδήλωσης', 'doctor@gmail.com'),
(104, '2025-03-30 14:38:21', 'Δημιουργία νέας εκδήλωσης', 'doctor@gmail.com'),
(105, '2025-03-30 14:38:37', 'Συμμετοχή σε εκδήλωση', 'doctor@gmail.com'),
(106, '2025-03-30 14:49:02', 'Ακύρωση συμμετοχής σε εκδήλωση', 'doctor@gmail.com'),
(107, '2025-03-30 15:03:40', 'Συμμετοχή σε εκδήλωση', 'doctor@gmail.com'),
(108, '2025-03-30 17:49:28', 'Δημιουργία λογαριασμού', 'client4@gmail.com'),
(109, '2025-03-31 13:05:56', 'Δημιουργία λογαριασμού', 'doctor3@gmail.com'),
(110, '2025-03-31 13:24:47', 'Δημιουργία λογαριασμού', 'doctor4@gmail.com'),
(111, '2025-03-31 13:35:20', 'Δημιουργία λογαριασμού', 'doctor5@gmail.com'),
(112, '2025-04-02 12:27:36', 'Δημιουργία λογαριασμού', 'client6@gmail.com'),
(113, '2025-04-02 12:31:27', 'Δημιουργία λογαριασμού', 'client7@gmail.com'),
(114, '2025-04-02 12:39:13', 'Δημιουργία λογαριασμού', 'doctor7@gmail.com'),
(115, '2025-04-02 12:39:43', 'Δημιουργία λογαριασμού', 'dwadwa@gmail.com'),
(116, '2025-04-02 13:07:30', 'Δημιουργία λογαριασμού', 'doctor8@gmail.com'),
(117, '2025-04-04 10:37:04', 'Δημιουργία λογαριασμού', 'andreasggch123ristou@gmail.com'),
(118, '2025-04-04 10:42:36', 'Δημιουργία λογαριασμού', 'doctor9@gmail.com'),
(119, '2025-04-04 10:50:18', 'Δημιουργία λογαριασμού', 'doctor4@gmail.com'),
(120, '2025-04-04 11:09:51', 'Δημιουργία λογαριασμού', 'doctor5@gmail.com'),
(121, '2025-04-04 11:12:41', 'Δημιουργία νέας εκδήλωσης', 'doctor5@gmail.com'),
(122, '2025-04-04 11:12:45', 'Συμμετοχή σε εκδήλωση', 'doctor5@gmail.com'),
(123, '2025-04-04 11:34:25', 'Δημιουργία λογαριασμού', 'client5@gmail.com'),
(124, '2025-04-09 08:24:24', 'Δημιουργία νέας εκδήλωσης', 'doctor@gmail.com'),
(125, '2025-04-09 08:24:29', 'Συμμετοχή σε εκδήλωση', 'doctor@gmail.com'),
(126, '2025-04-09 08:24:35', 'Ενημέρωση εκδήλωσης', 'doctor@gmail.com'),
(127, '2025-04-09 08:24:39', 'Ακύρωση εκδήλωσης', 'doctor@gmail.com'),
(128, '2025-04-09 08:35:32', 'Δημιουργία νέας εκδήλωσης', 'volunteer2@gmail.com'),
(129, '2025-04-09 08:35:38', 'Συμμετοχή σε εκδήλωση', 'volunteer2@gmail.com'),
(130, '2025-04-09 08:36:08', 'Ακύρωση εκδήλωσης', 'volunteer2@gmail.com'),
(131, '2025-04-09 09:33:38', 'Δημιουργία λογαριασμού', 'doctor10@gmail.com'),
(132, '2025-04-09 09:36:20', 'Προσθήκη νέας ιατρικής είδησης', 'doctor10@gmail.com'),
(133, '2025-04-09 09:36:36', 'Ενημέρωση είδησης', 'doctor10@gmail.com'),
(134, '2025-04-15 11:55:17', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(135, '2025-04-15 11:58:44', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(136, '2025-04-15 12:16:09', 'Φόρμα αξιολόγησης Πελάτη', 'andreasggchristou123@gmail.com'),
(137, '2025-04-15 12:35:36', 'Δημιουργία νέας εκδήλωσης', 'volunteer2@gmail.com'),
(138, '2025-04-15 12:35:46', 'Συμμετοχή σε εκδήλωση', 'volunteer2@gmail.com'),
(139, '2025-04-18 08:57:39', 'Δημιουργία νέας εκδήλωσης', 'doctor@gmail.com'),
(140, '2025-04-18 09:00:09', 'Διαγραφή ερώτησης', 'doctor@gmail.com'),
(141, '2025-05-19 09:56:55', 'Συμμετοχή σε εκδήλωση', 'volunteer2@gmail.com'),
(142, '2025-05-19 09:57:02', 'Συμμετοχή σε εκδήλωση', 'volunteer2@gmail.com'),
(143, '2025-05-19 09:57:14', 'Συμμετοχή σε εκδήλωση', 'volunteer2@gmail.com'),
(144, '2025-05-19 09:57:23', 'Συμμετοχή σε εκδήλωση', 'volunteer2@gmail.com'),
(145, '2025-05-19 09:57:41', 'Συμμετοχή σε εκδήλωση', 'volunteer@gmail.com'),
(146, '2025-05-19 09:57:49', 'Συμμετοχή σε εκδήλωση', 'volunteer@gmail.com'),
(147, '2025-05-19 09:58:01', 'Συμμετοχή σε εκδήλωση', 'volunteer@gmail.com'),
(148, '2025-05-19 09:59:22', 'Συμμετοχή σε εκδήλωση', 'client@gmail.com'),
(149, '2025-05-19 09:59:31', 'Συμμετοχή σε εκδήλωση', 'client@gmail.com'),
(150, '2025-05-19 10:00:35', 'Συμμετοχή σε εκδήλωση', 'client4@gmail.com'),
(151, '2025-05-19 10:00:46', 'Συμμετοχή σε εκδήλωση', 'client4@gmail.com'),
(152, '2025-05-19 10:00:54', 'Συμμετοχή σε εκδήλωση', 'client4@gmail.com'),
(153, '2025-05-19 10:01:17', 'Συμμετοχή σε εκδήλωση', 'client5@gmail.com'),
(154, '2025-05-19 10:02:01', 'Συμμετοχή σε εκδήλωση', 'client5@gmail.com'),
(155, '2025-05-19 10:02:13', 'Συμμετοχή σε εκδήλωση', 'client5@gmail.com'),
(156, '2025-05-19 10:02:39', 'Συμμετοχή σε εκδήλωση', 'client5@gmail.com'),
(157, '2025-05-19 10:07:17', 'Συμμετοχή σε εκδήλωση', 'client5@gmail.com'),
(158, '2025-05-19 10:12:10', 'Συμμετοχή σε εκδήλωση', 'andreasggchristou@gmail.com'),
(159, '2025-05-19 11:35:21', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(160, '2025-05-19 11:35:43', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(161, '2025-05-19 11:35:47', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(162, '2025-05-19 11:50:49', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(163, '2025-05-19 11:50:53', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(164, '2025-05-19 11:50:59', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(165, '2025-05-19 11:51:03', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(166, '2025-05-19 11:51:08', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(167, '2025-05-19 11:51:12', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(168, '2025-05-19 11:51:15', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(169, '2025-05-19 12:09:04', 'Φόρμα αξιολόγησης Πελάτη', '123@gmail.com'),
(170, '2025-05-19 12:09:35', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(171, '2025-05-19 12:10:21', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(172, '2025-05-19 12:10:24', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(173, '2025-05-19 12:10:27', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(174, '2025-05-19 12:10:30', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(175, '2025-05-19 12:10:34', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(176, '2025-05-19 12:10:38', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(177, '2025-05-19 12:10:41', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(178, '2025-05-19 12:10:46', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(179, '2025-05-19 12:16:31', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(180, '2025-05-19 12:16:33', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(181, '2025-05-19 12:16:36', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(182, '2025-05-19 12:16:38', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(183, '2025-05-19 12:16:41', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(184, '2025-05-19 12:16:44', 'Φόρμα αξιολόγησης Πελάτη', 'doctor2@gmail.com'),
(185, '2025-05-19 12:20:47', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(186, '2025-05-19 12:20:50', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(187, '2025-05-19 12:20:55', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(188, '2025-05-19 12:20:57', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(189, '2025-05-19 12:21:00', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(190, '2025-05-19 12:21:03', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(191, '2025-05-19 12:21:07', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(192, '2025-05-19 12:21:10', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(193, '2025-05-19 12:21:14', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(194, '2025-05-19 12:21:17', 'Φόρμα αξιολόγησης Πελάτη', 'andrdaweasggchristou@gmail.com'),
(195, '2025-05-19 14:08:21', 'Δημιουργία λογαριασμού', 'test1@gmail.com'),
(196, '2025-05-22 06:13:40', 'Συμμετοχή σε εκδήλωση', 'client@gmail.com'),
(197, '2025-05-22 06:13:48', 'Ακύρωση συμμετοχής σε εκδήλωση', 'client@gmail.com'),
(198, '2025-05-22 06:22:05', 'Φόρμα αξιολόγησης Πελάτη', 'andreasggchristou@gmail.com'),
(199, '2025-05-22 07:31:22', 'Συμμετοχή σε εκδήλωση', 'client@gmail.com'),
(200, '2025-05-22 07:34:40', 'Συμμετοχή σε εκδήλωση', 'client@gmail.com'),
(201, '2025-05-22 07:38:59', 'Δημιουργία νέας εκδήλωσης', 'volunteer2@gmail.com'),
(202, '2025-05-22 07:39:26', 'Ενημέρωση εκδήλωσης', 'volunteer2@gmail.com'),
(203, '2025-05-22 07:39:33', 'Ακύρωση εκδήλωσης', 'volunteer2@gmail.com'),
(204, '2025-05-22 07:42:56', 'Προσθήκη νέας ιατρικής είδησης', 'doctor@gmail.com'),
(205, '2025-05-22 07:43:08', 'Ενημέρωση είδησης', 'doctor@gmail.com'),
(206, '2025-05-22 07:43:11', 'Διαγραφή ερώτησης', 'doctor@gmail.com'),
(207, '2025-05-22 07:45:21', 'Φόρμα αξιολόγησης Πελάτη', 'andreasggchristou@gmail.com'),
(208, '2025-05-22 07:45:32', 'Φόρμα αξιολόγησης Πελάτη', 'andreasggchristou@gmail.com'),
(209, '2025-05-22 07:51:19', 'Προσθήκη νέας ερώτησης', 'andreasggchristou@gmail.com'),
(210, '2025-05-22 07:51:24', 'Ενημέρωση ερώτησης', 'andreasggchristou@gmail.com'),
(211, '2025-05-22 07:51:29', 'Διαγραφή ερώτησης', 'andreasggchristou@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `author_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `date_post` date NOT NULL DEFAULT current_timestamp(),
  `status` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `author_id`, `title`, `description`, `date_post`, `status`) VALUES
(1, '60', 'Νέες Προσεγγίσεις στη Θεραπεία της Σχιζοφρένειας', 'Τελευταίες μελέτες δείχνουν ότι ο συνδυασμός ψυχοθεραπείας με εξατομικευμένη φαρμακευτική αγωγή βελτιώνει σημαντικά την ποιότητα ζωής των ασθενών με σχιζοφρένεια. Ερευνητές εξετάζουν επίσης τον ρόλο της γνωσιακής αποκατάστασης και της κοινωνικής υποστήριξης στην ενίσχυση της λειτουργικότητας και της κοινωνικής ενσωμάτωσης των ασθενών, προσφέροντας νέες προοπτικές για την αντιμετώπιση της πάθησης', '2025-03-21', 0),
(2, '50', 'Νέες Προσεγγίσεις στη Θεραπεία της Σχιζοφρένειας', 'Τελευταίες μελέτες δείχνουν ότι ο συνδυασμός ψυχοθεραπείας με εξατομικευμένη φαρμακευτική αγωγή βελτιώνει σημαντικά την ποιότητα ζωής των ασθενών με σχιζοφρένεια. Ερευνητές εξετάζουν επίσης τον ρόλο της γνωσιακής αποκατάστασης και της κοινωνικής υποστήριξ', '2025-03-21', 0),
(6, '63', 'kati', 'kati', '2025-03-26', NULL),
(7, '78', 'nknk', 'tora\r\n', '2025-04-09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `id` int(11) NOT NULL,
  `email_user` varchar(255) NOT NULL,
  `id_event` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `participants`
--

INSERT INTO `participants` (`id`, `email_user`, `id_event`) VALUES
(7, 'andreasggchristou@gmail.com', 1),
(14, 'andreasggchristou@gmail.com', 6),
(24, 'client@gmail.com', 6),
(34, 'doctor@gmail.com', 15),
(35, 'doctor5@gmail.com', 16),
(36, 'doctor@gmail.com', 17),
(37, 'volunteer2@gmail.com', 18),
(38, 'volunteer2@gmail.com', 19),
(39, 'volunteer2@gmail.com', 12),
(40, 'volunteer2@gmail.com', 6),
(41, 'volunteer2@gmail.com', 15),
(42, 'volunteer2@gmail.com', 14),
(43, 'volunteer@gmail.com', 6),
(44, 'volunteer@gmail.com', 14),
(45, 'volunteer@gmail.com', 1),
(46, 'client@gmail.com', 12),
(47, 'client@gmail.com', 14),
(48, 'client4@gmail.com', 6),
(49, 'client4@gmail.com', 12),
(50, 'client4@gmail.com', 14),
(51, 'client5@gmail.com', 6),
(52, 'client5@gmail.com', 6),
(53, 'client5@gmail.com', 14),
(54, 'client5@gmail.com', 14),
(55, 'client5@gmail.com', 6),
(56, 'andreasggchristou@gmail.com', 16),
(57, 'client@gmail.com', 15),
(58, 'client@gmail.com', 16),
(59, 'client@gmail.com', 7);

-- --------------------------------------------------------

--
-- Table structure for table `questionnaire`
--

CREATE TABLE `questionnaire` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questionnaire`
--

INSERT INTO `questionnaire` (`id`, `question`) VALUES
(1, 'Πιστεύω ότι οι άλλοι ελέγχουν αυτό που σκέφτομαι και αισθάνομαι'),
(2, 'Ακούω ή βλέπω πράγματα που οι άλλοι δεν ακούν ή δεν βλέπουν'),
(3, 'Νιώθω ότι είναι πολύ δύσκολο για μένα να εκφραστώ με λέξεις που μπορούν να καταλάβουν οι άλλοι'),
(5, 'Πιστεύω σε περισσότερα από ένα πράγματα για την πραγματικότητα και τον κόσμο γύρω μου στα οποία κανείς άλλος δεν φαίνεται να πιστεύει'),
(6, 'Οι άλλοι δεν με πιστεύουν όταν τους λέω αυτά που βλέπω ή ακούω'),
(7, 'Δεν μπορώ να εμπιστευτώ αυτό που σκέφτομαι γιατί δεν ξέρω αν είναι αληθινό ή όχι'),
(8, 'Έχω μαγικές δυνάμεις που κανείς άλλος δεν έχει ή δεν μπορεί να τις εξηγήσει'),
(9, 'Άλλοι σχεδιάζουν να με πάρουν'),
(10, 'Δυσκολεύομαι να κατακτήσω τις σκέψεις μου'),
(11, 'Μου φέρονται άδικα επειδή οι άλλοι ζηλεύουν τις ιδιαίτερες ικανότητές μου'),
(12, 'Μιλάω με άλλο άτομο ή άλλα άτομα μέσα στο κεφάλι μου που κανείς άλλος δεν μπορεί να ακούσει');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `percentage` int(4) NOT NULL,
  `result` varchar(250) NOT NULL,
  `client_email` varchar(250) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id`, `percentage`, `result`, `client_email`, `date`) VALUES
(10, 46, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2024-02-05'),
(11, 46, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2023-02-05'),
(12, 100, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2025-02-05'),
(13, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-02-05'),
(14, 75, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'andreasggchristou@gmail.com', '2024-02-05'),
(15, 94, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2024-02-05'),
(16, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2024-02-05'),
(17, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2024-02-05'),
(18, 50, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-02-05'),
(19, 75, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-02-05'),
(20, 67, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'client@gmail.com', '2025-02-05'),
(21, 54, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'client@gmail.com', '2024-02-05'),
(22, 54, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'client@gmail.com', '2025-02-05'),
(23, 27, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-02-05'),
(24, 100, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2025-02-05'),
(25, 100, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2025-02-05'),
(26, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-02-05'),
(27, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-02-12'),
(28, 75, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-02-12'),
(29, 100, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2025-02-12'),
(30, 100, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2025-02-21'),
(31, 100, 'Πρώιμη σχιζοφρένεια', 'volunteer2@gmail.com', '2025-03-25'),
(32, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'volunteer2@gmail.com', '2025-03-25'),
(34, 100, 'Πρώιμη σχιζοφρένεια', 'client@gmail.com', '2025-03-25'),
(35, 64, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'volunteer2@gmail.com', '2025-03-30'),
(36, 68, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'volunteer2@gmail.com', '2025-03-30'),
(37, 66, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'volunteer2@gmail.com', '2025-03-30'),
(38, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'client@gmail.com', '2025-05-07'),
(39, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'client@gmail.com', '2025-05-14'),
(40, 82, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'client5@gmail.com', '2025-05-19'),
(41, 82, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'client5@gmail.com', '2025-05-19'),
(42, 98, 'Πρώιμη σχιζοφρένεια', 'client5@gmail.com', '2024-05-19'),
(43, 95, 'Πρώιμη σχιζοφρένεια', 'client5@gmail.com', '2024-05-19'),
(44, 100, 'Πρώιμη σχιζοφρένεια', 'client5@gmail.com', '2024-05-19'),
(45, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(46, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(47, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2024-05-19'),
(48, 64, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'andreasggchristou@gmail.com', '2024-05-19'),
(49, 80, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'andreasggchristou@gmail.com', '2023-05-19'),
(50, 80, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'andreasggchristou@gmail.com', '2023-05-19'),
(51, 93, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2023-05-19'),
(52, 93, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2023-05-19'),
(53, 93, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2023-05-19'),
(54, 93, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2023-05-19'),
(55, 80, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(56, 80, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(57, 80, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'andreasggchristou@gmail.com', '2023-05-19'),
(58, 80, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'andreasggchristou@gmail.com', '2023-05-19'),
(59, 80, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'andreasggchristou@gmail.com', '2023-05-19'),
(60, 80, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'andreasggchristou@gmail.com', '2023-05-19'),
(61, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2024-05-19'),
(62, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2024-05-19'),
(63, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2024-05-19'),
(64, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2024-05-19'),
(65, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(66, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(67, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(68, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(69, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(70, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(71, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(72, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(73, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2025-05-19'),
(74, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2024-05-19'),
(75, 52, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'andreasggchristou@gmail.com', '2023-05-19'),
(76, 93, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2024-05-19'),
(77, 93, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2024-05-19'),
(78, 93, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2023-05-19'),
(79, 93, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2023-05-19'),
(80, 93, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2023-05-19'),
(81, 93, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2023-05-19'),
(82, 93, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2023-05-19'),
(83, 93, 'Πρώιμη σχιζοφρένεια', 'andreasggchristou@gmail.com', '2023-05-19'),
(84, 100, 'Πρώιμη σχιζοφρένεια', 'client@gmail.com', '2023-05-19'),
(85, 100, 'Πρώιμη σχιζοφρένεια', 'client@gmail.com', '2023-05-19'),
(86, 100, 'Πρώιμη σχιζοφρένεια', 'client@gmail.com', '2024-05-19'),
(87, 100, 'Πρώιμη σχιζοφρένεια', 'client@gmail.com', '2024-05-19'),
(88, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'client@gmail.com', '2023-05-19'),
(89, 25, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'client@gmail.com', '2024-05-19'),
(90, 75, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'client@gmail.com', '2024-05-19'),
(91, 75, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'client@gmail.com', '2023-05-19'),
(92, 75, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'client@gmail.com', '2024-05-19'),
(93, 75, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'client@gmail.com', '2023-05-19'),
(94, 75, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'client@gmail.com', '2024-05-19'),
(95, 91, 'Πρώιμη σχιζοφρένεια', 'client@gmail.com', '2024-05-19'),
(96, 91, 'Πρώιμη σχιζοφρένεια', 'client@gmail.com', '2023-05-19'),
(97, 91, 'Πρώιμη σχιζοφρένεια', 'client@gmail.com', '2023-05-19'),
(98, 91, 'Πρώιμη σχιζοφρένεια', 'client@gmail.com', '2023-05-19'),
(99, 91, 'Πρώιμη σχιζοφρένεια', 'client@gmail.com', '2024-05-19'),
(100, 91, 'Πρώιμη σχιζοφρένεια', 'client@gmail.com', '2024-05-19'),
(101, 48, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'client@gmail.com', '2025-05-22'),
(102, 39, 'Χωρίς Ενδείξεις Σχιζοφρένειας', 'client@gmail.com', '2025-05-22'),
(103, 84, 'Πιθανότητα πρώιμης σχιζοφρένειας', 'client@gmail.com', '2025-05-22');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'client'),
(3, 'doctor'),
(4, 'volunteer');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `nationality` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL,
  `resetCode` int(6) NOT NULL,
  `registration_number` int(11) NOT NULL,
  `gender` varchar(23) NOT NULL,
  `creation_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `name`, `surname`, `email`, `password`, `date_of_birth`, `nationality`, `phone`, `resetCode`, `registration_number`, `gender`, `creation_date`) VALUES
(47, 1, 'Stelios', 'Kestorass', 'andreasggchristou@gmail.com', '$2y$10$vyrjr57p08b79.p74g0HOuOKebCuEX/U2jf.Zv5ad/kOcHp0Qrawy', '2025-01-31', 'Λεμεσός', 96444081, 443668, 1067998, 'female', '2025-01-17'),
(48, 2, 'Andreas', 'Christou', 'andreasggchristou1@gmail.com', '$2y$10$TJETSrjbp/AaI4wD2kWcQeGnrM79OkeNCclqpvWVEKlo85eTDH5t.', '2025-01-17', 'Λεμεσός', 96444081, 0, 21412, 'female', '2025-01-17'),
(49, 2, 'Andreas', 'Christou', 'andreasggchristou123@gmail.com', '$2y$10$zGDTJ.hFEdG5FZRzSXoiyeEcGfhQjoJtMI0UB/aRCZKARLaz/fYKW', '2025-01-28', 'Κερύνεια', 123, 0, 412, 'male', '2024-01-17'),
(50, 3, 'wad', 'awd', '123@gmail.com', '$2y$10$YUT1GV6iIOEc.goMo5F16u.Ic8lYrbviKp2jnOhd1dTSh1.cT0OHC', '2025-01-03', 'Λεμεσός', 123, 0, 1245432, 'male', '2024-01-17'),
(51, 1, 'Andreas', 'Christou', 'andrdaweasggchristou@gmail.com', '$2y$10$let5HuJHUuxQcVnJIqri3.f/cmwYAgvbPqdXMAYoFuk/FHSDucEbO', '2025-01-25', 'Πάφος', 96444081, 0, 421, 'male', '2025-01-17'),
(52, 3, 'Ελένη', 'Παπαγεωργίου', 'andreasgg1christou@gmail.com', '$2y$10$0.0LtmvRGnTimLVpNoVbaOI922za8cch1Hh4ppMYQJ.60GYOkstWm', '2025-01-21', 'Λεμεσός', 96444081, 0, 12313, 'female', '2025-01-17'),
(53, 2, 'Andreas', 'Christou', 'andreaschristou745@gmail.com', '$2y$10$Sy4Cmx4dG8RtlosDIyMzaeuVxMyY3sYzthvnyLloMULcnO.q5YWNS', '2025-01-23', 'Λεμεσός', 96444081, 91265, 1324124, 'male', '2025-01-17'),
(54, 1, 'Giorgos', 'Shikkis', '1234@gmail.com', '$2y$10$FVjyFtL8EyI1mD2h4lGunO4GkfOfZv.NIS6xPlKJ.EIkgXG5PMLdi', '2024-12-29', 'Λεμεσός', 0, 0, 2134124, 'female', '2025-01-17'),
(55, 2, 'Andreas', 'Christou', 'andreasggchristoudadw@gmail.com', '$2y$10$WsWOuTxIu/xs/ARTUXxBguTNqgX.aU4HMohVGtKKiAo5xbWrVtDLS', '2025-01-23', 'Λεμεσός', 96444081, 0, 123213, 'female', '2025-01-21'),
(56, 2, 'Andreas', 'Christou', 'andreasggchrist123ou@gmail.com', '$2y$10$Ig3Qk4lCSnfiFOQznUtddeBFol2FXPDVpBHPNq2k48U2.v.OUfloa', '2025-01-23', 'Πάφος', 96444081, 0, 1231232, 'female', '2025-01-21'),
(57, 2, 'Andreas', 'Christou', 'client@gmail.com', '$2y$10$mmBivx7DoGmUqXfu1bJ5/./p.sRC9WW6HMtoYB8rzB.QYVNujFWii', '2020-04-22', 'Λεμεσός', 96444081, 0, 123, 'male', '2025-01-26'),
(58, 2, 'Andreas', 'Christou', 'andrea2sggchristou@gmail.com', '$2y$10$dgKbWPpYaiU5FsQRlBaIRO6LPMR2s.RUBelzDPmnGHbG.LxocMZUe', '2025-01-27', 'Λευκωσία', 96444081, 0, 31232, 'female', '2025-01-26'),
(59, 4, 'Andreas', 'Christou', 'volunteer@gmail.com', '$2y$10$GodhazREJFs61zLj2pH9mO8WG/1TAA4pSQzQtF.iryZIUUcgxf8De', '2004-12-05', 'Λεμεσός', 0, 0, 909090, 'male', '2025-03-09'),
(60, 3, 'Antreas', 'Christou', 'doctor@gmail.com', '$2y$10$fz0BS55osLjSZIsfY6z4SeBi32cPOea1L6hz/DcOVW64YcEq9DIZy', '2004-04-10', 'Αμμόχωστος', 96444081, 0, 808080, 'male', '2025-03-09'),
(61, 3, 'Andreas', 'Christou', 'doctor2@gmail.com', '$2y$10$C8FXs6CedReBc/95arF8Xen8S/7F0H40CKWuyhj9pwCGljmcNcjt.', '2025-03-26', 'Αμμόχωστος', 96444081, 0, 555, 'female', '2024-03-21'),
(62, 4, 'Giorgos', 'Shikkis', 'volunteer2@gmail.com', '$2y$10$7154p5y36dMLFZPqM2ju3uQLPrnN9P1GYTcwULDUV3aEM.fcZ6GWq', '2025-03-29', 'Λευκωσία', 213321, 0, 9999999, 'male', '2025-03-25'),
(63, 3, 'Andreas', 'Christou', 'client3@gmail.com', '$2y$10$2mkaCoX3h36KCLLXWjkfbOqgtaBxorog07En7W8wSlsvpJTC0d/vu', '2025-02-23', 'Αμμόχωστος', 96444081, 0, 890897, 'male', '2025-03-26'),
(64, 2, 'Giannis', 'komo', 'client4@gmail.com', '$2y$10$nwb/f.IDdWr3GoKTBpofwOAUaRT2wldvPZ4oFEjAv3kbZcG3/7eJi', '2025-03-18', 'Κερύνεια', 0, 0, 1231231, 'male', '2025-03-30'),
(65, 3, 'Andreas', 'Christou', 'doctor3@gmail.com', '$2y$10$cPbTB6dS4kSXOlAnItdJ8uP2Mg7SPJ.LPDx7siQAlyfGZGmWpCgie', '2025-03-13', 'Λάρνακα', 96444081, 0, 321654, 'male', '2025-03-31'),
(72, 3, 'Andreas', 'Christou', 'doctor8@gmail.com', '$2y$10$ncsQnVSXA0kjYIeUXIabeub0moV0zkkfZ906P6h4GPJ9TjlQyRBBy', '2025-04-29', 'Κερύνεια', 96444081, 0, 32532, 'male', '2025-04-02'),
(74, 3, 'Andreas', 'Christou', 'doctor9@gmail.com', '$2y$10$DLE2wSuwN1d.RhDzGBZlnOYx3cvyXygjv6o4c.h7iWXg9d6qJUvT.', '2025-04-23', 'Πάφος', 96444081, 0, 321322, 'female', '2025-04-04'),
(75, 3, 'Antreas', 'Christou', 'doctor4@gmail.com', '$2y$10$07SDSI8FIO7.N4WCcH3Kle10Hd7p0qaXg7X58YzEksd.rW3b6MepK', '2025-04-15', 'Κερύνεια', 96444081, 0, 12312322, 'male', '2025-04-04'),
(76, 3, 'Andreas', 'Christou', 'doctor5@gmail.com', '$2y$10$/HkcmupP.v7ksPNMEmEpyu1tSXpY2Be9QSuBAIzW3Fdh360Tr7uma', '2025-03-31', 'Αμμόχωστος', 96444081, 0, 321222, 'female', '2025-04-04'),
(77, 2, 'Andreas', 'Georgiou', 'client5@gmail.com', '$2y$10$PEHnAiGI0ASApI9KXZG29uaSovEvCu.y8FXLMopNn3Cvh6xvGDVRK', '2025-04-08', 'Λάρνακα', 96444081, 0, 32224, 'male', '2025-04-04'),
(78, 3, 'Andreas', 'Christou', 'doctor10@gmail.com', '$2y$10$Hf51xZ5px2l6cIoI2Tkej.5mHCiZFqtHCdTSxZBE3S2CDOkg3RXa2', '2025-04-12', 'Κερύνεια', 96444081, 0, 21233, 'male', '2025-04-09'),
(79, 2, 'Andreas', 'Andreas', 'test1@gmail.com', '$2y$10$QasVC4ZNYE79LlUgDssf5.Pd2BDBWHNBmqlLNX0wi1YxnNu5B9qrO', '2025-05-05', 'Πάφος', 96444081, 0, 55654, 'male', '2025-05-19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment_details`
--
ALTER TABLE `appointment_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blocked_users`
--
ALTER TABLE `blocked_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors_info`
--
ALTER TABLE `doctors_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questionnaire`
--
ALTER TABLE `questionnaire`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment_details`
--
ALTER TABLE `appointment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `blocked_users`
--
ALTER TABLE `blocked_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `doctors_info`
--
ALTER TABLE `doctors_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `questionnaire`
--
ALTER TABLE `questionnaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
