-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2026 at 12:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `healthnet_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `publisher_type` enum('hospital','laboratory','pharmacy','medical_centre','admin') NOT NULL,
  `publisher_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `announcement_type` enum('health_camp','special_offer','new_service','emergency','general') NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `publisher_type`, `publisher_id`, `title`, `content`, `announcement_type`, `image_path`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(6, 'admin', 1, 'test aAnnonsment', 'aaaaaaaaaaaa', 'health_camp', 'announcements/UMpOLbJHZh7NdlrPIOPCDYrHsXPTmXEb1Cw1V7rb.png', '2026-03-02', '2026-03-04', 1, '2026-03-02 11:13:57', '2026-03-02 11:13:57');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `appointment_number` varchar(50) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `workplace_type` enum('hospital','medical_centre','private') NOT NULL,
  `workplace_id` bigint(20) UNSIGNED DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed','no_show') NOT NULL DEFAULT 'pending',
  `reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `advance_payment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
  `cancelled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `appointment_number`, `patient_id`, `doctor_id`, `workplace_type`, `workplace_id`, `appointment_date`, `appointment_time`, `status`, `reason`, `notes`, `consultation_fee`, `advance_payment`, `payment_status`, `cancelled_by`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
(11, 'APT-699F4DB1808B4', 2, 3, 'hospital', 1, '2026-02-28', '12:12:00', 'cancelled', 'ද්ස්ස්ද්ද්', 'ව්ද්', 4000.00, 0.00, 'paid', 8, 'Cancelled by patient', '2026-02-25 13:59:53', '2026-02-25 14:07:38'),
(12, 'APT-699F4FB97D2E1', 2, 3, 'hospital', 1, '2026-02-27', '23:42:00', 'cancelled', 'ර්වෙ', NULL, 4000.00, 0.00, 'paid', 8, 'Cancelled by patient', '2026-02-25 14:08:33', '2026-02-25 14:09:16'),
(13, 'APT-699F50B3CF8C3', 2, 3, 'hospital', 1, '2026-02-28', '21:32:00', 'pending', 'ට්ට්ට්ට්ට්ට්ට්ට්ට්', NULL, 4000.00, 0.00, 'paid', NULL, NULL, '2026-02-25 14:12:43', '2026-02-25 14:13:04'),
(14, 'APT-699F514574D9D', 2, 1, 'hospital', 1, '2026-02-27', '03:45:00', 'pending', 'ග්ෆ්ඩ්ග්', NULL, 2500.00, 0.00, 'paid', NULL, NULL, '2026-02-25 14:15:09', '2026-02-25 14:15:22'),
(15, 'APT-699F538F4AED9', 2, 1, 'hospital', 1, '2026-02-27', '12:31:00', 'pending', '213', NULL, 2500.00, 0.00, 'paid', NULL, NULL, '2026-02-25 14:24:55', '2026-02-25 14:25:10'),
(16, 'APT-69A1DC08ABE4A', 2, 3, 'hospital', 1, '2026-02-28', '12:00:00', 'pending', 'අස්', NULL, 4000.00, 0.00, 'unpaid', NULL, NULL, '2026-02-27 12:31:44', '2026-02-27 12:31:44'),
(17, 'APT-69A1E228235FF', 2, 3, 'hospital', 1, '2026-02-28', '09:00:00', 'pending', 'ද්ව්', 'ව්', 4000.00, 0.00, 'unpaid', NULL, NULL, '2026-02-27 12:57:52', '2026-02-27 12:57:52'),
(18, 'APT-69A1E283F092B', 2, 3, 'hospital', 1, '2026-02-28', '09:00:00', 'pending', 'ද්ව්', 'ව්', 4000.00, 0.00, 'unpaid', NULL, NULL, '2026-02-27 12:59:23', '2026-02-27 12:59:23'),
(19, 'APT-69A1E2A6B8B83', 2, 3, 'hospital', 1, '2026-02-28', '10:00:00', 'pending', 'ද්ව්', 'ව්', 4000.00, 0.00, 'unpaid', NULL, NULL, '2026-02-27 12:59:58', '2026-02-27 12:59:58'),
(20, 'APT-69A1E36AEC779', 2, 3, 'hospital', 1, '2026-02-28', '08:00:00', 'confirmed', 'ද්ව්', 'ව්', 4000.00, 0.00, 'paid', NULL, NULL, '2026-02-27 13:03:14', '2026-03-01 17:00:42'),
(21, 'APT-69A1E41097F74', 2, 3, 'hospital', 1, '2026-02-28', '08:00:00', 'pending', 'ද්ව්', 'ව්', 4000.00, 0.00, 'unpaid', NULL, NULL, '2026-02-27 13:06:00', '2026-02-27 13:06:00'),
(22, 'APT-69A1E525E81DC', 2, 3, 'hospital', 1, '2026-02-28', '08:00:00', 'pending', 'ද්ව්', 'ව්', 4000.00, 0.00, 'unpaid', NULL, NULL, '2026-02-27 13:10:37', '2026-02-27 13:10:37'),
(23, 'APT-69A1E5774B2E9', 2, 1, 'hospital', 1, '2026-02-28', '09:00:00', 'confirmed', 'චෙස්ට් පෛන්', 'ද්ව්ද්ව්', 2500.00, 0.00, 'unpaid', NULL, NULL, '2026-02-27 13:11:59', '2026-03-01 16:07:51'),
(24, 'APT-69A1E8B15C6E0', 2, 1, 'hospital', 1, '2026-02-28', '09:00:00', 'pending', 'චෙස්ට් පෛන්', 'ද්ව්ද්ව්', 2500.00, 0.00, 'unpaid', NULL, NULL, '2026-02-27 13:25:45', '2026-02-27 13:25:45'),
(25, 'APT-69A1E8DEC95BD', 2, 1, 'hospital', 1, '2026-02-28', '09:00:00', 'pending', 'චෙස්ට් පෛන්', 'ද්ව්ද්ව්', 2500.00, 0.00, 'unpaid', NULL, NULL, '2026-02-27 13:26:30', '2026-02-27 13:26:30'),
(26, 'APT-69A1EA1D0CA27', 2, 3, 'hospital', 1, '2026-02-28', '09:00:00', 'pending', 'as', NULL, 4000.00, 0.00, 'unpaid', NULL, NULL, '2026-02-27 13:31:49', '2026-02-27 13:31:49'),
(27, 'APT-69A1EA38D7E15', 2, 3, 'hospital', 1, '2026-02-28', '09:00:00', 'pending', 'as', NULL, 4000.00, 0.00, 'unpaid', NULL, NULL, '2026-02-27 13:32:16', '2026-02-27 13:32:16'),
(28, 'APT-69A58EA5AB19D', 2, 1, 'medical_centre', 1, '2026-03-11', '09:00:00', 'pending', 'suger', NULL, 2500.00, 0.00, 'paid', NULL, NULL, '2026-03-02 07:50:37', '2026-03-02 08:46:27'),
(29, 'APT-69A5EBC3A8947', 4, 3, 'hospital', 1, '2026-03-11', '09:00:00', 'pending', 'Suger', NULL, 4000.00, 0.00, 'paid', NULL, NULL, '2026-03-02 14:27:55', '2026-03-02 14:29:09'),
(30, 'APT-69A9EC8D1CAEE', 5, 2, 'hospital', 1, '2026-04-03', '09:03:00', 'pending', 'wqdwqd', NULL, 0.00, 0.00, 'unpaid', NULL, NULL, '2026-03-05 15:20:21', '2026-03-05 15:20:21');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_conversations`
--

CREATE TABLE `chatbot_conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_name` varchar(100) DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `mode` enum('bot','admin') NOT NULL DEFAULT 'bot',
  `status` enum('active','closed') NOT NULL DEFAULT 'active',
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatbot_conversations`
--

INSERT INTO `chatbot_conversations` (`id`, `session_id`, `user_id`, `guest_name`, `guest_email`, `mode`, `status`, `admin_id`, `created_at`, `updated_at`) VALUES
(30, 'fd8d-da16-85fb', NULL, 'ඇළ්', 'hmika68@gmail.com', 'admin', 'active', 1, '2026-03-05 05:24:15', '2026-03-05 05:25:04'),
(31, '383e-75dc-d6ef', 14, NULL, NULL, 'admin', 'active', 1, '2026-03-05 05:25:59', '2026-03-05 05:26:32'),
(32, '6526-71b0-defd', NULL, 'kasun', 'malli@gmail.com', 'admin', 'active', 1, '2026-03-05 05:27:14', '2026-03-05 05:30:25'),
(33, '28f7-e4e0-fe44', NULL, 'Dinal rashmika', 'dinalrashmika68@gmail.com', 'admin', 'active', 1, '2026-03-05 05:56:19', '2026-03-05 05:56:40'),
(34, '3086-b123-07a6', 13, NULL, NULL, 'bot', 'active', NULL, '2026-03-07 16:04:45', '2026-03-07 16:04:45');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_faqs`
--

CREATE TABLE `chatbot_faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(500) NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(100) DEFAULT 'general',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatbot_faqs`
--

INSERT INTO `chatbot_faqs` (`id`, `question`, `answer`, `category`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 'What should I do in a medical emergency?', 'If you are having severe chest pain, difficulty breathing, uncontrolled bleeding, sudden weakness, or loss of consciousness, seek emergency care immediately.\n\nCall your local emergency number or go to the nearest hospital OPD. Do not wait for an online consultation in life‑threatening situations.', 'general', 1, 1, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(3, 'Can the HealthNet Assistant replace my doctor?', 'No. The HealthNet Assistant only gives general health information and guidance based on your questions.\n\nIt cannot diagnose diseases or prescribe medicines. Always consult a qualified doctor or your regular clinic for diagnosis and treatment.', 'general', 1, 2, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(4, 'When should I see a doctor for fever?', 'You should see a doctor if:\n\n- Fever lasts more than 3 days\n- Fever is very high (above 39°C)\n- You have severe headache, breathing difficulty, chest pain, rash, or confusion\n- You are pregnant, very young, elderly, or have chronic illnesses like diabetes, heart disease, or kidney disease', 'general', 1, 3, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(5, 'How can I manage my blood pressure at home?', 'To help manage high blood pressure:\n\n- Reduce salt in food\n- Maintain healthy weight\n- Exercise at least 30 minutes most days\n- Avoid smoking and limit alcohol\n- Take medicines exactly as prescribed\n- Check your BP regularly and record readings\n\nAlways follow your doctor’s plan and do not change medicines on your own.', 'chronic-disease', 1, 4, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(6, 'What are common warning signs of a heart attack?', 'Common warning signs include:\n\n- Chest pain, pressure, or tightness (especially centre/left side)\n- Pain spreading to arm, neck, jaw, or back\n- Shortness of breath\n- Cold sweat, nausea, or dizziness\n\nIf you suspect a heart attack, call emergency services or go to the nearest hospital immediately. Do not drive yourself.', 'general', 1, 5, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(7, 'I have diabetes. How should I take care of my health?', 'For diabetes care:\n\n- Take diabetes medicines/insulin exactly as prescribed\n- Check blood sugar as advised and record values\n- Follow a balanced diet with controlled rice, bread, sweets, and sugary drinks\n- Exercise regularly according to your doctor’s advice\n- Check your feet daily for wounds or colour changes\n- Attend regular clinic visits and eye, kidney, and foot checks', 'chronic-disease', 1, 6, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(8, 'What should I know about taking long-term medicines?', 'Always:\n\n- Take medicines at the correct time and dose\n- Do not stop medicines suddenly without medical advice\n- Inform your doctor about any side effects, allergies, or other medicines you use\n- Do not share your medicines with others\n- Keep an updated list of all your medicines when visiting clinics or hospitals', 'medication', 1, 7, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(9, 'How do I book a doctor appointment on HealthNet?', 'You can book an appointment by:\n\n1. Logging into your HealthNet patient account\n2. Going to the “Book Appointment” or “Appointments” section\n3. Selecting doctor, specialty, hospital/centre, date, and time\n4. Confirming the booking\n\nYou can also use the Quick Links in the chatbot “Links” tab to open the appointment page directly.', 'appointments', 1, 10, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(10, 'Can I cancel or reschedule my appointment?', 'Yes. Go to your “My Appointments” page in HealthNet.\n\nFrom there you can view details and, where allowed, cancel or reschedule according to the provider’s policy. Some last-minute cancellations may not be possible.', 'appointments', 1, 11, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(11, 'How do I find a doctor or specialist?', 'Use the “Find Doctors” or “Doctors” section in HealthNet.\n\nYou can search by specialty (e.g. cardiology, pediatrics), hospital/medical centre, or city. The chatbot “Links” tab also has shortcuts to doctor listing pages.', 'appointments', 1, 12, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(12, 'How can I see my lab test results?', 'If your lab and hospital are connected to HealthNet, you can:\n\n- Log into your patient account\n- Open the “Lab Orders” or “Reports” section\n- View available test reports and download them if supported\n\nIf you cannot find a report, contact the laboratory or hospital directly.', 'laboratory', 1, 15, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(13, 'How long does it take to get lab results?', 'The time depends on the type of test and the laboratory.\n\nSimple blood tests may be ready within a few hours, while special tests can take 1–3 days or more.\n\nFor exact timelines, please check the information provided by the laboratory or contact them directly.', 'laboratory', 1, 16, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(14, 'Can I order medicines through HealthNet?', 'If your hospital or pharmacy supports online orders, you can:\n\n- Log into your patient account\n- Go to the “Pharmacy Orders” or similar section\n- Upload prescription if required and place an order\n\nAlways follow legal requirements: some medicines need a valid prescription.', 'pharmacy', 1, 20, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(15, 'Is it safe to buy medicines online?', 'Buy medicines only from registered pharmacies and follow these rules:\n\n- Avoid unknown or unverified websites\n- Do not buy prescription-only medicines without a valid prescription\n- Check expiry date and packaging when you receive the medicines\n- If unsure, ask your doctor or pharmacist before using any new medicine.', 'pharmacy', 1, 21, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(16, 'What information can I see in my HealthNet patient profile?', 'Typically you can see:\n\n- Basic details (name, date of birth, contact)\n- Past appointments and visits\n- Lab test orders and some results\n- Ongoing medications and allergies (if recorded)\n\nExact details depend on how your hospital or clinic is connected to HealthNet.', 'platform', 1, 25, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(17, 'How is my health information protected on HealthNet?', 'HealthNet uses secure connections (HTTPS), authentication, and access controls so that only authorised users can view your health information.\n\nAlways:\n\n- Keep your password secret\n- Log out from shared devices\n- Avoid sharing OTP codes or login details with others\n\nIf you suspect misuse of your account, change your password and contact support.', 'platform', 1, 26, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(18, 'What should I do if I forget my HealthNet password?', 'Use the “Forgot Password” option on the HealthNet login page.\n\nEnter your registered email address and follow the instructions sent to your email.\n\nIf you no longer have access to that email, contact the system administrator or hospital IT helpdesk.', 'platform', 1, 27, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(19, 'අපතීත සෞඛ්‍ය තත්ත්වයක් ඇත්නම් මොකද කරන්නේ?', 'භාරවීමක් දැනේනවාද, හදිසි හෘදයාබාධ ලක්ෂණද, හුස්ම ගැනීම අපහසු වීමද, තද රුධිරවාතයකිද, පය/අත ඔලාහු වීමද, හදිසි බරපතල වේදනාවක්ද මෙවැනි ඒවා ඇත්නම්,\n\n👉 වහාම najest හෝ ඔබට අසනීපයකි.\n👉 ඉක්මන් වෛද්‍ය උදව් ලබාගන්න. OPD/EME යන්න.\n\nමෙවැනි අවස්ථාවල HealthNet chatbot එකට පමණක් බලා සිටිය යුත්තෙ නොවේ.', 'general', 1, 30, '2026-03-05 10:02:59', '2026-03-05 10:02:59'),
(20, 'HealthNet chatbot එකෙන් මට ලැබෙන උත්තර වලට විශ්වාස කරන්න පුළුවන්ද?', 'Chatbot එක ඔබගේ ප්‍රශ්න අනුව සාමාන්‍ය සෞඛ්‍ය තොරතුරු හා උපදෙස් ලබාදෙයි.\n\nඑය වෛද්‍යවරුන්ව හෝ රෝහල් සේවාවන්ව ප්‍රතිස්ථාපනය කරන්නේ නැහැ. ඕනෑම බරපතල ලක්ෂණයක්, දිගු කාලීන රෝග තත්ත්වයක්, හදිසි අවශ්‍යතාවක් තිබේ නම් වහාම වෛද්‍යවරයෙකු හමුවන්න.', 'general', 1, 31, '2026-03-05 10:02:59', '2026-03-05 10:02:59');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_messages`
--

CREATE TABLE `chatbot_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `sender_type` enum('user','bot','admin') NOT NULL,
  `sender_id` bigint(20) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatbot_messages`
--

INSERT INTO `chatbot_messages` (`id`, `conversation_id`, `sender_type`, `sender_id`, `message`, `is_read`, `created_at`) VALUES
(85, 30, 'bot', NULL, 'You have been connected to an admin. Please wait for a response. Our team will reply shortly.', 0, '2026-03-05 05:24:21'),
(86, 30, 'user', NULL, 'hello', 1, '2026-03-05 05:24:27'),
(87, 30, 'admin', 1, 'hi dr', 0, '2026-03-05 05:25:04'),
(88, 31, 'bot', NULL, 'You have been connected to an admin. Please wait for a response. Our team will reply shortly.', 0, '2026-03-05 05:26:02'),
(89, 31, 'user', 14, 'hello', 1, '2026-03-05 05:26:05'),
(90, 31, 'admin', 1, 'hi bro', 0, '2026-03-05 05:26:32'),
(91, 32, 'bot', NULL, 'You have been connected to an admin. Please wait for a response. Our team will reply shortly.', 0, '2026-03-05 05:27:53'),
(92, 32, 'user', NULL, 'hello dr', 1, '2026-03-05 05:28:06'),
(93, 32, 'admin', 1, 'hi kasun', 0, '2026-03-05 05:28:24'),
(94, 32, 'user', NULL, 'how my health', 1, '2026-03-05 05:29:25'),
(95, 32, 'bot', NULL, 'Hi Kasun! I’m not a doctor, so I can’t actually examine or diagnose you. If you tell me what symptoms you’re having (fever, pain, cough, etc.), I can give you some general guidance on what might be going on and what kind of doctor you should see. If you’re not feeling well, the best next step is to book an appointment with one of our HealthNet GPs or specialists through the app.', 0, '2026-03-05 05:29:30'),
(96, 32, 'bot', NULL, 'You have been connected to an admin. Please wait for a response. Our team will reply shortly.', 0, '2026-03-05 05:30:25'),
(97, 33, 'bot', NULL, 'You have been connected to an admin. Please wait for a response. Our team will reply shortly.', 0, '2026-03-05 05:56:20'),
(98, 33, 'user', NULL, 'hello', 1, '2026-03-05 05:56:24'),
(99, 33, 'admin', 1, 'hi brother', 0, '2026-03-05 05:56:40');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_quick_links`
--

CREATE TABLE `chatbot_quick_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(200) NOT NULL,
  `url_path` varchar(500) NOT NULL COMMENT 'Relative path e.g. /patient/doctors',
  `icon` varchar(100) NOT NULL DEFAULT 'fas fa-link',
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'NULL=all, or ["patient","doctor"]' CHECK (json_valid(`roles`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatbot_quick_links`
--

INSERT INTO `chatbot_quick_links` (`id`, `label`, `url_path`, `icon`, `roles`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'My Dashboard', '/Main-page', 'fas fa-tachometer-alt', '[\"patient\"]', 1, 1, '2026-03-05 09:17:33', '2026-03-05 04:06:11'),
(2, 'Book Appointment', '/patient/hospitals', 'fas fa-calendar-plus', '[\"patient\"]', 1, 2, '2026-03-05 09:17:33', '2026-03-05 04:07:04'),
(3, 'My Appointments', '/patient/appointments', 'fas fa-calendar-check', '[\"patient\"]', 1, 3, '2026-03-05 09:17:33', '2026-03-05 04:07:27'),
(4, 'Find Doctors', '/patient/hospitals', 'fas fa-user-md', '[\"patient\"]', 1, 4, '2026-03-05 09:17:33', '2026-03-05 04:08:26'),
(5, 'Hospitals', '/patient/hospitals', 'fas fa-hospital', '[\"patient\"]', 1, 5, '2026-03-05 09:17:33', '2026-03-05 04:08:46'),
(6, 'Medical Centres', '/patient/medical-centres', 'fas fa-clinic-medical', '[\"patient\"]', 1, 6, '2026-03-05 09:17:33', '2026-03-05 04:09:53'),
(7, 'Laboratories', '/patient/laboratories', 'fas fa-flask', '[\"patient\"]', 1, 7, '2026-03-05 09:17:33', '2026-03-05 04:10:15'),
(8, 'Pharmacies', '/patient/pharmacies', 'fas fa-pills', '[\"patient\"]', 1, 8, '2026-03-05 09:17:33', '2026-03-05 04:10:39'),
(9, 'Lab Orders', '/patient/lab-orders', 'fas fa-microscope', '[\"patient\"]', 1, 9, '2026-03-05 09:17:33', '2026-03-05 04:10:56'),
(10, 'Pharmacy Orders', '/patient/pharmacies/1/track', 'fas fa-shopping-bag', '[\"patient\"]', 1, 10, '2026-03-05 09:17:33', '2026-03-05 04:12:52'),
(11, 'Health Portfolio', '/patient/health-portfolio', 'fas fa-heartbeat', '[\"patient\"]', 1, 11, '2026-03-05 09:17:33', '2026-03-05 04:13:30'),
(12, 'Medicine Reminders', '/patient/medicine-reminders', 'fas fa-bell', '[\"patient\"]', 1, 12, '2026-03-05 09:17:33', '2026-03-05 09:17:33'),
(13, 'My Profile', '/patient/profile', 'fas fa-user-circle', '[\"patient\"]', 1, 13, '2026-03-05 09:17:33', '2026-03-05 09:17:33'),
(14, 'Notifications', '/patient/notifications', 'fas fa-bell', '[\"patient\"]', 1, 14, '2026-03-05 09:17:33', '2026-03-05 09:17:33'),
(15, 'Sign Up', '/signup', 'fas fa-user-plus', '[]', 1, 15, '2026-03-05 09:17:33', '2026-03-05 09:17:33'),
(16, 'Login', '/login', 'fas fa-sign-in-alt', '[]', 1, 16, '2026-03-05 09:17:33', '2026-03-05 09:17:33');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected','suspended') NOT NULL DEFAULT 'pending',
  `slmc_number` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `qualifications` text DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_ratings` int(11) NOT NULL DEFAULT 0,
  `document_path` varchar(255) DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `status`, `slmc_number`, `first_name`, `last_name`, `specialization`, `qualifications`, `experience_years`, `phone`, `profile_image`, `bio`, `consultation_fee`, `rating`, `total_ratings`, `document_path`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'approved', 'SLMC-10010', 'Kasun', 'Perera', 'Family Medicine', 'MBBS (Colombo)', 8, '0771717599', 'doctors/profile_images/z0sP4j0cZwzdFg5b8vrRWKOJ06bDQu84wktEXZJL.jpg', 'General physician providing primary care and chronic disease management.', 2500.00, 0.00, 0, 'doctors/documents/WsNHsV4KbqQVxDGuCt2o7v5FDzJYRWCDzVwHHKjr.pdf', 1, '2025-12-04 14:13:05', '2025-12-04 14:10:42', '2026-03-01 15:17:07'),
(2, 9, 'approved', 'SF34', 'lasitha', 'lakmal', 'oncology', 'MBBS', 2, '0771717599', 'doctors/profiles/NOPWpjP8VQADqyOPAyYUTU3V10gUqxZnE44WPE.jpg', NULL, NULL, 0.00, 0, 'doctors/documents/j6NpVHK5bTIDL8BXidTMaSSuL98xJtyGwAPe0hP1.png', 1, '2025-12-17 16:34:34', '2025-12-17 16:30:50', '2025-12-17 16:34:34'),
(3, 10, 'approved', 'sda4', 'rashmika', 'dinal', 'pediatrics', 'asd', 4, '768398321', 'doctors/profiles/xsdd5qTJwsMy27coQmNjJlT8AQKdXGI7xizQWskr.jpg', NULL, 4000.00, 0.00, 0, 'doctors/documents/Vw8bW1ASlYnbkngpumhePeRlNqtuH6XgwGLcx1n5.jpg', 1, '2026-02-23 14:44:46', '2026-02-23 14:35:13', '2026-02-23 14:44:46');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedules`
--

CREATE TABLE `doctor_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `workplace_type` enum('hospital','medical_centre','private') NOT NULL,
  `workplace_id` bigint(20) UNSIGNED DEFAULT NULL,
  `day_of_week` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `max_appointments` int(11) NOT NULL DEFAULT 20,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctor_schedules`
--

INSERT INTO `doctor_schedules` (`id`, `doctor_id`, `workplace_type`, `workplace_id`, `day_of_week`, `start_time`, `end_time`, `max_appointments`, `consultation_fee`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 1, 'hospital', 1, 'monday', '10:00:00', '16:00:00', 10, 2500.00, 1, '2026-03-01 15:42:44', '2026-03-01 15:44:22');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_workplaces`
--

CREATE TABLE `doctor_workplaces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `workplace_type` enum('hospital','medical_centre') NOT NULL,
  `workplace_id` bigint(20) UNSIGNED NOT NULL,
  `employment_type` enum('permanent','temporary','visiting') NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctor_workplaces`
--

INSERT INTO `doctor_workplaces` (`id`, `doctor_id`, `workplace_type`, `workplace_id`, `employment_type`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(2, 1, 'hospital', 1, 'visiting', 'approved', 4, '2026-02-23 14:06:48', '2026-02-23 13:53:27', '2026-02-23 14:06:48'),
(3, 3, 'hospital', 1, 'temporary', 'approved', 4, '2026-02-23 14:47:37', '2026-02-23 14:45:20', '2026-02-23 14:47:37'),
(4, 1, 'medical_centre', 1, 'visiting', 'rejected', 7, '2026-03-02 08:54:20', '2026-03-01 14:58:42', '2026-03-02 08:54:20'),
(5, 2, 'hospital', 1, 'temporary', 'approved', 4, '2026-03-02 05:38:41', '2026-03-02 05:38:28', '2026-03-02 05:38:41');

-- --------------------------------------------------------

--
-- Table structure for table `health_articles`
--

CREATE TABLE `health_articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `content` text NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `author_type` enum('admin','doctor') NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `health_metrics`
--

CREATE TABLE `health_metrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `metric_date` date NOT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `blood_pressure_systolic` int(11) DEFAULT NULL,
  `blood_pressure_diastolic` int(11) DEFAULT NULL,
  `heart_rate` int(11) DEFAULT NULL,
  `temperature` decimal(4,2) DEFAULT NULL,
  `blood_sugar` decimal(5,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `health_metrics`
--

INSERT INTO `health_metrics` (`id`, `patient_id`, `metric_date`, `weight`, `height`, `blood_pressure_systolic`, `blood_pressure_diastolic`, `heart_rate`, `temperature`, `blood_sugar`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-02-28', 76.00, 175.00, NULL, NULL, NULL, NULL, 67.00, NULL, '2026-02-28 14:29:56', '2026-02-28 14:52:31'),
(2, 2, '2026-03-01', 76.00, 176.00, NULL, NULL, NULL, NULL, 67.00, NULL, '2026-02-28 15:38:20', '2026-02-28 15:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected','suspended') NOT NULL DEFAULT 'pending',
  `name` varchar(255) NOT NULL,
  `type` enum('government','private') NOT NULL,
  `registration_number` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `specializations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specializations`)),
  `facilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`facilities`)),
  `operatinghours` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_ratings` int(11) NOT NULL DEFAULT 0,
  `document_path` varchar(255) DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `user_id`, `status`, `name`, `type`, `registration_number`, `phone`, `email`, `address`, `city`, `province`, `postal_code`, `latitude`, `longitude`, `specializations`, `facilities`, `operatinghours`, `description`, `website`, `profile_image`, `rating`, `total_ratings`, `document_path`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 4, 'approved', 'Colombo National Hospital', 'government', 'HOS-1001', '0112691111', 'hospital@gmail.com', 'No 32/1 colombo', NULL, 'Central', NULL, 7.43833600, 81.81186600, NULL, NULL, NULL, 'Large tertiary care teaching hospital providing emergency, surgical, medical, maternity and pediatric services with advanced diagnostic facilities.', 'http://www.nhsl.health.gov.lk/web/index.php?lang=en', 'hospitals/profiles/aGHXAC9SE2Ry5SXpmuiPFGaGguvpstgTAxFtFcDS.jpg', 2.50, 2, 'hospitals/documents/UvlJALbkLMhoKev1vEv8LspibBfIlEBYDQurqF8x.pdf', 1, '2025-12-04 14:28:18', '2025-12-04 14:24:41', '2026-03-05 05:33:40');

-- --------------------------------------------------------

--
-- Table structure for table `laboratories`
--

CREATE TABLE `laboratories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected','suspended') NOT NULL DEFAULT 'pending',
  `name` varchar(255) NOT NULL,
  `registration_number` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `services` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`services`)),
  `operating_hours` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_ratings` int(11) NOT NULL DEFAULT 0,
  `document_path` varchar(255) DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laboratories`
--

INSERT INTO `laboratories` (`id`, `user_id`, `status`, `name`, `registration_number`, `phone`, `email`, `address`, `city`, `province`, `postal_code`, `latitude`, `longitude`, `services`, `operating_hours`, `description`, `profile_image`, `rating`, `total_ratings`, `document_path`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 5, 'approved', 'Asiri Laboratory Services', 'LAB-2001', '0114523301', 'Laboratory@gmail.com', 'Kirimandala Mawatha, Colombo 05', 'Colombo', 'western', '00500', NULL, NULL, '\"[\\\"Blood Tests (CBC\\\",\\\"ESR\\\",\\\"etc.)\\\",\\\"Urine Analysis\\\",\\\"X-Ray\\\",\\\"CT Scan\\\",\\\"Allergy Tests\\\",\\\"Cancer Markers\\\",\\\"Microbiology Tests\\\",\\\"Pathology Services\\\"]\"', 'Monday: 08:00-17:00, Tuesday: 08:00-17:00, Wednesday: 08:00-17:00, Thursday: 08:00-17:00, Friday: 08:00-17:00', 'Advanced clinical pathology lab specializing in hormone, cancer marker and cardiac investigations for hospital and walk-in patients.', 'laboratory/profiles/gh1TBChjPeOYJmFhiht8wi7V9qLB08OnCFB8iLjm.png', 3.67, 3, 'laboratorys/documents/5FS5uMimDSAcx5qebiTTvqiQbJc1n4dUsP3nk62K.pdf', 1, '2025-12-04 15:04:25', '2025-12-04 14:33:39', '2026-02-26 14:12:02'),
(2, 11, 'approved', 'tttttt', 'SDAFSD', '0771717599', 'Laboratory1@gmail.com', '43543', '543', 'north central', '4354354', NULL, NULL, '\"[\\\"Urine Analysis\\\",\\\"X-Ray\\\",\\\"ECG\\\",\\\"Echo Cardiogram\\\",\\\"Blood Sugar Tests\\\",\\\"Liver Function Tests\\\"]\"', NULL, NULL, 'laboratorys/profiles/RkOODZHFuGQnfNkIruIq6N2sbutdXo8wqCiZqPW9.png', 0.00, 0, 'laboratorys/documents/qFMHfhahwIuyC4TTPRaIQMXJZ8jaPmna9ySndBTv.png', 1, '2026-02-25 16:09:20', '2026-02-25 16:08:55', '2026-02-25 16:09:20');

-- --------------------------------------------------------

--
-- Table structure for table `lab_orders`
--

CREATE TABLE `lab_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `reference_number` varchar(50) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `laboratory_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `prescription_file` varchar(255) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','sample_collected','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  `payment_method` enum('cash','online','card') DEFAULT NULL,
  `home_collection` tinyint(1) NOT NULL DEFAULT 0,
  `collection_address` text DEFAULT NULL,
  `collection_date` date DEFAULT NULL,
  `collection_time` time DEFAULT NULL,
  `report_file` varchar(255) DEFAULT NULL,
  `report_uploaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_orders`
--

INSERT INTO `lab_orders` (`id`, `order_number`, `reference_number`, `patient_id`, `laboratory_id`, `doctor_id`, `prescription_file`, `order_date`, `status`, `total_amount`, `payment_status`, `payment_method`, `home_collection`, `collection_address`, `collection_date`, `collection_time`, `report_file`, `report_uploaded_at`, `created_at`, `updated_at`) VALUES
(14, 'LO-20260226-HQDT6C', 'REF-ZF0NZWO9', 2, 1, NULL, NULL, '2026-02-26 21:11:22', 'completed', 2000.00, 'unpaid', NULL, 0, NULL, '2026-02-28', NULL, NULL, '2026-02-26 15:57:26', '2026-02-26 15:41:22', '2026-02-26 15:57:26'),
(15, 'LO-20260227-XSW8UQ', 'REF-NQPJEW2F', 2, 1, NULL, NULL, '2026-02-27 17:51:47', 'completed', 2000.00, 'paid', 'card', 0, NULL, '2026-02-28', NULL, 'lab-reports/1/iObpdw15De74NrPb6elB5nrgzCJQYffIWskBVBuA.pdf', '2026-02-27 12:24:29', '2026-02-27 12:21:46', '2026-02-27 12:24:29'),
(16, 'LO-20260305-HXZGV1', 'REF-YGZJLB8H', 2, 1, NULL, NULL, '2026-03-05 20:49:12', 'pending', 2000.00, 'unpaid', NULL, 0, NULL, '2026-03-27', NULL, NULL, NULL, '2026-03-05 15:19:12', '2026-03-05 15:19:12');

-- --------------------------------------------------------

--
-- Table structure for table `lab_order_items`
--

CREATE TABLE `lab_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `test_id` bigint(20) UNSIGNED DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_order_items`
--

INSERT INTO `lab_order_items` (`id`, `order_id`, `test_id`, `package_id`, `item_name`, `price`, `created_at`) VALUES
(9, 14, 5, NULL, 'Allergy Tests', 2000.00, '2026-02-26 21:11:22'),
(10, 15, 5, NULL, 'Allergy Tests', 2000.00, '2026-02-27 17:51:47'),
(11, 16, 5, NULL, 'Allergy Tests', 2000.00, '2026-03-05 20:49:12');

-- --------------------------------------------------------

--
-- Table structure for table `lab_packages`
--

CREATE TABLE `lab_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `laboratory_id` bigint(20) UNSIGNED NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_package_tests`
--

CREATE TABLE `lab_package_tests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `test_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_tests`
--

CREATE TABLE `lab_tests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `laboratory_id` bigint(20) UNSIGNED NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `test_category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_hours` int(11) DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_tests`
--

INSERT INTO `lab_tests` (`id`, `laboratory_id`, `test_name`, `test_category`, `description`, `price`, `duration_hours`, `requirements`, `is_active`, `created_at`, `updated_at`) VALUES
(5, 1, 'Allergy Tests', 'Immunology / Serology', NULL, 2000.00, 20, NULL, 1, '2026-02-26 14:53:28', '2026-02-26 14:57:06');

-- --------------------------------------------------------

--
-- Table structure for table `medical_centres`
--

CREATE TABLE `medical_centres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected','suspended') NOT NULL DEFAULT 'pending',
  `owner_doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `registration_number` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `specializations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specializations`)),
  `facilities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`facilities`)),
  `operatinghours` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_ratings` int(11) NOT NULL DEFAULT 0,
  `document_path` varchar(255) DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_centres`
--

INSERT INTO `medical_centres` (`id`, `user_id`, `status`, `owner_doctor_id`, `name`, `registration_number`, `phone`, `email`, `address`, `city`, `province`, `postal_code`, `latitude`, `longitude`, `specializations`, `facilities`, `operatinghours`, `description`, `profile_image`, `rating`, `total_ratings`, `document_path`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 7, 'approved', NULL, 'Nawaloka Medical Centre', 'MC-4001', '0115577001', 'MedicalCentre@gmail.com', 'Deshamanya H.K. Dharmadasa Mawatha, Colombo 02', 'Colombo', 'north western', '00200', NULL, NULL, '[]', '[]', NULL, 'Urban primary care medical centre offering GP consultations, child and maternal clinics, vaccinations and basic diagnostic services under one roof.', 'medical_centres/profiles/BNMpeAmJwQBxtsYwSmMGNSuUbBAw2yiYozr3pT6T.png', 0.00, 0, 'medical_centres/documents/OzncsMdqubKTMkDBkhDwtSJniVuc8zAUkaKLLjrz.pdf', 1, '2025-12-04 15:04:43', '2025-12-04 15:02:56', '2026-03-02 09:43:50');

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `condition_name` varchar(255) DEFAULT NULL,
  `diagnosed_date` date DEFAULT NULL,
  `status` enum('active','resolved','chronic') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `record_type` enum('clinic_visit','xray','scan','prescription','lab_report','other') NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `record_date` date DEFAULT NULL,
  `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hospital_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medications`
--

CREATE TABLE `medications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pharmacy_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `generic_name` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `stock_status` enum('in_stock','low_stock','out_of_stock') NOT NULL DEFAULT 'in_stock',
  `requires_prescription` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medications`
--

INSERT INTO `medications` (`id`, `pharmacy_id`, `name`, `generic_name`, `category`, `manufacturer`, `description`, `dosage`, `price`, `stock_quantity`, `stock_status`, `requires_prescription`, `is_active`, `created_at`, `updated_at`) VALUES
(10, 1, 'sa', 'as', 'a', 'as', 'asd', '500mg', 50.00, 450, 'in_stock', 0, 1, '2026-03-06 06:14:41', '2026-03-06 08:07:10'),
(11, 1, 'අස්ඩ්', 'ස්ඩ්', 'Antacids / Gastrointestinal', 'ස්ඩ්', 'අඩ්', '500', 50.00, 50, 'in_stock', 0, 1, '2026-03-06 06:19:06', '2026-03-06 06:19:06');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_reminders`
--

CREATE TABLE `medicine_reminders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `frequency` enum('once_daily','twice_daily','thrice_daily','four_times_daily','custom') NOT NULL,
  `times` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`times`)),
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medicine_reminders`
--

INSERT INTO `medicine_reminders` (`id`, `patient_id`, `medicine_name`, `dosage`, `frequency`, `times`, `start_date`, `end_date`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'C vitamins', '20', 'once_daily', '[\"04:26\",\"04:30\"]', '2026-03-01', NULL, 1, NULL, '2026-02-28 15:56:49', '2026-02-28 17:28:31'),
(2, 2, 'laa', '23', 'twice_daily', '[\"08:00\",\"20:00\",\"04:32\"]', '2026-03-01', NULL, 1, NULL, '2026-02-28 17:30:07', '2026-02-28 17:30:07'),
(4, 3, 'C', '12', 'custom', '[\"04:46\"]', '2026-03-01', '2026-03-30', 1, 'qa', '2026-02-28 17:44:23', '2026-02-28 17:44:23');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2025_12_03_201936_create_patients_table', 1),
(4, '2025_12_03_201941_create_doctors_table', 1),
(5, '2025_12_03_201946_create_hospitals_table', 1),
(6, '2025_12_03_201950_create_laboratories_table', 1),
(7, '2025_12_03_201955_create_pharmacies_table', 1),
(8, '2025_12_03_202003_create_medical_centres_table', 1),
(9, '2025_12_03_202229_create_doctor_workplaces_table', 1),
(10, '2025_12_03_202234_create_doctor_schedules_table', 1),
(11, '2025_12_03_202239_create_appointments_table', 1),
(12, '2025_12_03_202244_create_medical_history_table', 1),
(13, '2025_12_03_202249_create_medical_records_table', 1),
(14, '2025_12_03_202254_create_health_metrics_table', 1),
(15, '2025_12_03_202353_create_lab_tests_table', 1),
(16, '2025_12_03_202441_create_lab_packages_table', 1),
(17, '2025_12_03_202446_create_lab_package_tests_table', 1),
(18, '2025_12_03_202452_create_lab_orders_table', 1),
(19, '2025_12_03_202457_create_lab_order_items_table', 1),
(20, '2025_12_03_202502_create_medications_table', 1),
(21, '2025_12_03_202507_create_prescription_orders_table', 1),
(22, '2025_12_03_202512_create_prescription_order_items_table', 1),
(23, '2025_12_03_202518_create_payments_table', 1),
(24, '2025_12_03_202624_create_chat_messages_table', 1),
(25, '2025_12_03_202629_create_notifications_table', 1),
(26, '2025_12_03_202635_create_medicine_reminders_table', 1),
(27, '2025_12_03_202640_create_ratings_table', 1),
(28, '2025_12_03_202645_create_announcements_table', 1),
(29, '2025_12_03_202650_create_health_articles_table', 1),
(30, '2025_12_03_202655_create_activity_logs_table', 1),
(32, '2025_12_03_202811_create_system_settings_table', 2),
(33, '2026_02_25_190838_create_payments_table', 2),
(34, '2026_02_25_212043_create_lab_service_prices_table', 3),
(35, '2026_02_28_195545_create_patient_health_data_table', 4),
(36, '2026_03_06_084935_add_reply_to_ratings_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'general',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `related_type` varchar(100) DEFAULT NULL COMMENT 'appointment, payment, prescription, lab_report,pharmacy_order, etc.',
  `related_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `notifiable_type`, `notifiable_id`, `type`, `title`, `message`, `related_type`, `related_id`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 16, 'general', 'Welcome to HealthNet!', 'Thank you for joining HealthNet. Your account has been created successfully. Please verify your email to access all features.', NULL, NULL, 0, NULL, '2026-03-06 08:21:00', '2026-03-06 08:21:00'),
(2, 'App\\Models\\User', 16, 'reminder', 'Verification Email Sent', 'A verification email has been sent to dinalrashmika6844@gmail.com. Please check your inbox and click the verification link.', NULL, NULL, 0, NULL, '2026-03-06 08:21:04', '2026-03-06 08:21:04'),
(3, 'App\\Models\\User', 8, 'pharmacy_order', '🔄 Order Being Processed', 'Your order #PO-20260306-S5XLCH is now being prepared by Osu Sala Pharmacy – Wijerama.', 'prescriptionorder', 22, 0, NULL, '2026-03-06 08:22:12', '2026-03-06 08:22:12'),
(4, 'App\\Models\\User', 6, 'pharmacy_order', '🔄 Marked as Processing', 'Order #PO-20260306-S5XLCH has been marked as processing.', 'prescriptionorder', 22, 0, NULL, '2026-03-06 08:22:12', '2026-03-06 08:22:12'),
(5, 'App\\Models\\User', 8, 'order_processing', 'Order Being Prepared', 'Your order #PO-20260306-S5XLCH is being prepared by Osu Sala Pharmacy – Wijerama.', 'pharmacy_order', 22, 0, NULL, '2026-03-06 08:22:12', '2026-03-06 08:22:12'),
(6, 'App\\Models\\User', 8, 'pharmacy_order', '✅ Order Delivered Successfully!', 'Your order #PO-20260306-S5XLCH from Osu Sala Pharmacy – Wijerama has been delivered. Thank you!', 'prescriptionorder', 22, 1, '2026-03-06 08:23:32', '2026-03-06 08:23:10', '2026-03-06 08:23:32'),
(7, 'App\\Models\\User', 6, 'pharmacy_order', '✅ Order Delivered', 'Order #PO-20260306-S5XLCH has been marked as delivered.', 'prescriptionorder', 22, 0, NULL, '2026-03-06 08:23:10', '2026-03-06 08:23:10'),
(8, 'App\\Models\\User', 8, 'order_delivered', 'Order Delivered!', 'Your order #PO-20260306-S5XLCH has been delivered. Thank you!', 'pharmacy_order', 22, 0, NULL, '2026-03-06 08:23:10', '2026-03-06 08:23:10'),
(9, 'App\\Models\\User', 6, 'pharmacy_order', '🆕 New Prescription Order #PO-20260307-EZ15ZB', 'New prescription order received from Ravindu rashmika. Delivery type: Home Delivery. Please review the prescription and verify the order.', 'prescriptionorder', 23, 0, NULL, '2026-03-07 15:49:20', '2026-03-07 15:49:20'),
(10, 'App\\Models\\User', 13, 'pharmacy_order', '📋 Prescription Submitted Successfully', 'Your prescription has been submitted to Osu Sala Pharmacy – Wijerama (Order #PO-20260307-EZ15ZB). The pharmacy will review and verify your order shortly.', 'prescriptionorder', 23, 0, NULL, '2026-03-07 15:49:20', '2026-03-07 15:49:20');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `first_name`, `last_name`, `nic`, `date_of_birth`, `gender`, `blood_group`, `phone`, `address`, `city`, `province`, `postal_code`, `emergency_contact_name`, `emergency_contact_phone`, `profile_image`, `created_at`, `updated_at`) VALUES
(1, 3, 'cloude', 'rashmika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 14:11:44', '2025-12-04 14:11:44'),
(2, 8, 'Ravindu', 'Dinal', '200117895622', '2007-12-05', 'male', 'A-', '077 171 7599', 'ffffffffffff', 'fff', 'Central', NULL, NULL, NULL, 'patients/profiles/yhkZ8qZuYFc4IBEWSv2Xv70q16OxrKrzlX9nrHeO.png', NULL, '2026-03-01 13:00:12'),
(3, 12, 'Ravindu', 'Rashmika', '200117902800', '2000-02-22', 'male', 'A-', '077 171 7599', 'thalathuoya\r\nkandy', 'kandy', 'Central', '90230', 'mom', '071 302 30145', 'patients/profiles/IF7vOU3eAeYkCCbcZzkAvmjHKN2JjsdtrVQ0JgmR.jpg', NULL, '2026-02-28 17:23:59'),
(4, 13, 'Ravindu', 'rashmika', '200034345670', '1999-03-03', 'male', 'O-', '+94 76 839 8321', 'Kandy', 'thalathuoya', 'Central', '20200', NULL, NULL, NULL, NULL, NULL),
(5, 14, 'Dinal', 'rashmika', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-05 04:05:09', '2026-03-05 04:05:09'),
(6, 15, 'kanishka', 'lak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-06 08:16:01', '2026-03-06 08:16:01'),
(7, 16, 'Dinal', 'rashmika', '200145457888', '2000-03-08', 'female', 'A-', '077 171 7599', 'අස්ඩ්', 'ස්ඩ්', 'North Central', '213321', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient_health_data`
--

CREATE TABLE `patient_health_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `waist` decimal(5,2) DEFAULT NULL,
  `hip` decimal(5,2) DEFAULT NULL,
  `blood_pressure_systolic` int(11) DEFAULT NULL,
  `blood_pressure_diastolic` int(11) DEFAULT NULL,
  `heart_rate` int(11) DEFAULT NULL,
  `temperature` decimal(4,2) DEFAULT NULL,
  `blood_sugar` decimal(5,2) DEFAULT NULL,
  `blood_sugar_pp` decimal(5,2) DEFAULT NULL,
  `cholesterol_total` decimal(5,2) DEFAULT NULL,
  `cholesterol_hdl` decimal(5,2) DEFAULT NULL,
  `cholesterol_ldl` decimal(5,2) DEFAULT NULL,
  `oxygen_saturation` int(11) DEFAULT NULL,
  `smoking_status` enum('never','former','current') DEFAULT NULL,
  `alcohol_consumption` enum('none','occasional','moderate','heavy') DEFAULT NULL,
  `exercise_frequency` enum('none','1-2/week','3-4/week','5+/week') DEFAULT NULL,
  `diet_type` enum('omnivore','vegetarian','vegan','other') DEFAULT NULL,
  `sleep_hours` int(11) DEFAULT NULL,
  `stress_level` enum('low','moderate','high','very_high') DEFAULT NULL,
  `has_diabetes` tinyint(1) NOT NULL DEFAULT 0,
  `has_hypertension` tinyint(1) NOT NULL DEFAULT 0,
  `has_heart_disease` tinyint(1) NOT NULL DEFAULT 0,
  `has_asthma` tinyint(1) NOT NULL DEFAULT 0,
  `has_kidney_disease` tinyint(1) NOT NULL DEFAULT 0,
  `has_thyroid` tinyint(1) NOT NULL DEFAULT 0,
  `other_conditions` text DEFAULT NULL,
  `current_medications` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `family_diabetes` tinyint(1) NOT NULL DEFAULT 0,
  `family_heart_disease` tinyint(1) NOT NULL DEFAULT 0,
  `family_hypertension` tinyint(1) NOT NULL DEFAULT 0,
  `family_cancer` tinyint(1) NOT NULL DEFAULT 0,
  `recorded_date` date NOT NULL DEFAULT curdate(),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patient_health_data`
--

INSERT INTO `patient_health_data` (`id`, `patient_id`, `weight`, `height`, `waist`, `hip`, `blood_pressure_systolic`, `blood_pressure_diastolic`, `heart_rate`, `temperature`, `blood_sugar`, `blood_sugar_pp`, `cholesterol_total`, `cholesterol_hdl`, `cholesterol_ldl`, `oxygen_saturation`, `smoking_status`, `alcohol_consumption`, `exercise_frequency`, `diet_type`, `sleep_hours`, `stress_level`, `has_diabetes`, `has_hypertension`, `has_heart_disease`, `has_asthma`, `has_kidney_disease`, `has_thyroid`, `other_conditions`, `current_medications`, `allergies`, `family_diabetes`, `family_heart_disease`, `family_hypertension`, `family_cancer`, `recorded_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 76.00, 176.00, 34.00, 34.00, NULL, NULL, NULL, NULL, 67.00, NULL, NULL, NULL, NULL, NULL, 'former', 'occasional', '3-4/week', 'other', 9, 'high', 0, 0, 0, 1, 0, 1, NULL, NULL, NULL, 1, 0, 0, 0, '2026-03-01', NULL, '2026-02-28 14:29:56', '2026-02-28 15:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_number` varchar(50) NOT NULL,
  `payer_id` bigint(20) UNSIGNED NOT NULL,
  `payee_type` enum('hospital','doctor','laboratory','pharmacy','medical_centre') NOT NULL,
  `payee_id` bigint(20) UNSIGNED NOT NULL,
  `related_type` enum('appointment','laborder','prescriptionorder') DEFAULT NULL,
  `related_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','online','bank_transfer') NOT NULL,
  `payment_status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `payment_number`, `payer_id`, `payee_type`, `payee_id`, `related_type`, `related_id`, `amount`, `payment_method`, `payment_status`, `transaction_id`, `payment_date`, `notes`, `created_at`, `updated_at`) VALUES
(6, 'PAY-699F50C8210B7', 2, 'doctor', 3, 'appointment', 13, 4000.00, 'card', 'completed', 'pi_3T4nzBPVg8lyvP3Y0SkwHsHW', '2026-02-24 18:30:00', 'Cardholder: ස්ඩ්සඩ්', '2026-02-25 14:13:04', '2026-02-25 14:13:04'),
(7, 'PAY-699F515221E51', 2, 'doctor', 1, 'appointment', 14, 2500.00, 'card', 'completed', 'pi_3T4o1PPVg8lyvP3Y16q3VRSJ', '2026-02-24 18:30:00', 'Cardholder: ද්වෙව්දෙ', '2026-02-25 14:15:22', '2026-02-25 14:15:22'),
(8, 'PAY-699F539EB2EF0', 2, 'doctor', 1, 'appointment', 15, 2500.00, 'card', 'completed', 'pi_3T4oAtPVg8lyvP3Y140Rtnjo', '2026-02-24 18:30:00', 'Cardholder: 2134324', '2026-02-25 14:25:10', '2026-02-25 14:25:10'),
(9, 'PAY-LAB-HCDZUSSI', 2, 'laboratory', 1, '', 13, 2000.00, 'card', 'completed', 'pi_3T5Bk6PVg8lyvP3Y160Lvx9b', '2026-02-25 18:30:00', 'Cardholder: asd — Online card payment via Stripe', '2026-02-26 15:35:04', '2026-02-26 15:35:04'),
(10, 'PAY-LAB-YYF12ZZQ', 2, 'laboratory', 1, '', 15, 2000.00, 'card', 'completed', 'pi_3T5VDOPVg8lyvP3Y1dwVxYcG', '2026-02-26 18:30:00', 'Cardholder: rrrrrrrrr — Online card payment via Stripe', '2026-02-27 12:22:36', '2026-02-27 12:22:36'),
(11, 'PAY-69A1E3C6B039A', 2, 'doctor', 3, 'appointment', 20, 4000.00, 'card', 'completed', 'pi_3T5Vs9PVg8lyvP3Y2XG14adm', '2026-02-26 18:30:00', 'Cardholder: රවිදු', '2026-02-27 13:04:46', '2026-02-27 13:04:46'),
(12, 'PAY-PH-VOCHNNRL', 8, 'pharmacy', 1, 'prescriptionorder', 1, 1350.00, 'card', 'completed', 'pi_3T5YMrPVg8lyvP3Y2w6amJPd', '2026-02-26 18:30:00', 'Cardholder: ravindu. Online payment via Stripe', '2026-02-27 15:44:37', '2026-02-27 15:44:37'),
(13, 'PAY-69A5EC0DD5DD3', 4, 'doctor', 3, 'appointment', 29, 4000.00, 'card', 'completed', 'pi_3T6ccUPVg8lyvP3Y3U5zMlJ5', '2026-03-01 18:30:00', 'Cardholder: Ravindu', '2026-03-02 14:29:09', '2026-03-02 14:29:09');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacies`
--

CREATE TABLE `pharmacies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected','suspended') NOT NULL DEFAULT 'pending',
  `name` varchar(255) NOT NULL,
  `registration_number` varchar(100) NOT NULL,
  `pharmacist_name` varchar(100) DEFAULT NULL,
  `pharmacist_license` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `operating_hours` text DEFAULT NULL,
  `delivery_available` tinyint(1) NOT NULL DEFAULT 1,
  `profile_image` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_ratings` int(11) NOT NULL DEFAULT 0,
  `document_path` varchar(255) DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pharmacies`
--

INSERT INTO `pharmacies` (`id`, `user_id`, `status`, `name`, `registration_number`, `pharmacist_name`, `pharmacist_license`, `phone`, `email`, `address`, `city`, `province`, `postal_code`, `latitude`, `longitude`, `operating_hours`, `delivery_available`, `profile_image`, `rating`, `total_ratings`, `document_path`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 6, 'approved', 'Osu Sala Pharmacy – Wijerama', 'PHR-3001', 'Nimal Perera', 'PHL-12345', '0112689001', 'Pharmacy@gmail.com', 'Wijerama Mawatha, Colombo 07', 'Colombo', 'western', '00700', NULL, NULL, 'Monday: 08:00-17:00, Tuesday: 08:00-17:00, Wednesday: 08:00-17:00, Thursday: 08:00-17:00, Friday: 08:00-17:00, Saturday: 08:00-17:00', 1, 'pharmacys/profiles/pVndVIDRTUv4fhhCPHQRPsmw6BjJkkzaU0TAYa3P.png', 3.00, 1, 'pharmacys/documents/h3N2WIw4ehfanvTVVhShAxjCHU7oMn8g7DY397vj.pdf', 1, '2025-12-04 15:04:34', '2025-12-04 15:00:09', '2026-02-27 16:02:18');

-- --------------------------------------------------------

--
-- Table structure for table `prescription_orders`
--

CREATE TABLE `prescription_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `pharmacy_id` bigint(20) UNSIGNED NOT NULL,
  `prescription_file` varchar(255) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','verified','processing','ready','dispatched','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) DEFAULT NULL,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash_on_delivery','online') NOT NULL DEFAULT 'cash_on_delivery',
  `payment_status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  `delivery_address` text NOT NULL,
  `delivery_method` enum('uber','pickme','own_delivery') DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `pharmacist_notes` text DEFAULT NULL,
  `cancelled_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prescription_orders`
--

INSERT INTO `prescription_orders` (`id`, `order_number`, `patient_id`, `pharmacy_id`, `prescription_file`, `order_date`, `status`, `total_amount`, `delivery_fee`, `payment_method`, `payment_status`, `delivery_address`, `delivery_method`, `tracking_number`, `pharmacist_notes`, `cancelled_reason`, `created_at`, `updated_at`) VALUES
(5, 'PO-20260227-IOSA0Z', 2, 1, 'prescriptions/zYYxFbP2TVhABiwUNWWnf0S5icZTlY3SHEGCd15G.png', '2026-02-27 22:11:31', 'pending', 0.00, 0.00, 'online', 'unpaid', 'ffffffffffff', 'pickme', NULL, NULL, NULL, '2026-02-27 16:41:31', '2026-02-27 16:41:31'),
(6, 'PO-20260227-AEIW1W', 2, 1, 'prescriptions/rj0ZoMLlyBWUPu7Y9pUOl8sSSHm7IPkVnn7iYjR6.png', '2026-02-27 22:28:21', 'verified', 0.00, 0.00, 'cash_on_delivery', 'unpaid', 'ස්ඩඩ්', 'pickme', NULL, NULL, NULL, '2026-02-27 16:58:21', '2026-02-27 16:58:21'),
(7, 'PO-20260227-QBDO1X', 2, 1, 'prescriptions/NNooZ1hL9Tc46z8ehANjqtVzRYMhJquKr5lApFRD.png', '2026-02-27 22:28:46', 'pending', 0.00, 0.00, 'cash_on_delivery', 'unpaid', 'PICKUP', NULL, NULL, NULL, NULL, '2026-02-27 16:58:46', '2026-02-27 16:58:46'),
(8, 'PO-20260227-G3QA9A', 2, 1, 'prescriptions/QqszeLbsOfbZ1jCR3LmIEuGoP8Obhs1eJ1zMDcEq.png', '2026-02-27 22:28:55', 'pending', 0.00, 0.00, 'cash_on_delivery', 'unpaid', 'ස්ඩඩ්', 'uber', NULL, NULL, NULL, '2026-02-27 16:58:55', '2026-02-27 16:58:55'),
(9, 'PO-20260227-Z3KZEY', 2, 1, 'prescriptions/sNRrSXk0cuhXnKe9sziJsoKSFuIMbQj5mzJl2lBY.png', '2026-02-27 22:31:28', 'pending', 0.00, 0.00, 'cash_on_delivery', 'unpaid', 'ffffffffffff', 'own_delivery', NULL, NULL, NULL, '2026-02-27 17:01:28', '2026-02-27 17:01:28'),
(10, 'PO-20260305-ANAVTP', 5, 1, 'prescriptions/XKl2tNtMrmXTlQ2IRgqUue9zf1hGDxxoWhVTFzRc.png', '2026-03-05 09:42:03', 'verified', 0.00, 0.00, 'cash_on_delivery', 'unpaid', 'PICKUP', NULL, NULL, NULL, NULL, '2026-03-05 04:12:03', '2026-03-05 17:20:02'),
(11, 'PO-20260305-YSNJGB', 2, 1, 'prescriptions/JGf2vpVGdxaveWsR9AeJDxRfP0qzX8b77klO9YHm.jpg', '2026-03-05 21:49:13', 'delivered', 0.00, 0.00, 'cash_on_delivery', 'paid', 'ffffffffffff', 'pickme', 'TRK4465456464', NULL, NULL, '2026-03-05 16:19:13', '2026-03-06 02:53:44'),
(12, 'PO-20260306-SVFYTQ', 2, 1, 'prescriptions/K8vlMZRM8mI8ZMkEnMiGRKhlacVaPZdtUOe1eWbo.png', '2026-03-06 10:02:34', 'pending', 0.00, 0.00, 'cash_on_delivery', 'unpaid', 'ffffffffffff', 'pickme', NULL, NULL, NULL, '2026-03-06 04:32:34', '2026-03-06 04:32:34'),
(13, 'PO-20260306-W7LPOP', 2, 1, 'prescriptions/zrVI2MlnkhztWStFLeoueXdsYKd5bJ7qBIQ2uVzl.png', '2026-03-06 10:03:18', 'pending', 0.00, 0.00, 'online', 'unpaid', 'ffffffffffff', 'uber', NULL, NULL, NULL, '2026-03-06 04:33:18', '2026-03-06 04:33:18'),
(14, 'PO-20260306-JNEGTR', 2, 1, 'prescriptions/o69Ys7WhUBHyxTw3gcohG8dMf4cMgriK1aVK2IQi.png', '2026-03-06 10:03:36', 'pending', 0.00, 0.00, 'cash_on_delivery', 'unpaid', 'ffffffffffff', 'uber', NULL, NULL, NULL, '2026-03-06 04:33:36', '2026-03-06 04:33:36'),
(15, 'RX69AAA82106616', 5, 1, 'prescriptions/qqzWtQw5RG4ZooGXSDv7gerz4j1k7vRJRnzEVGCt.png', '2026-03-06 04:40:41', 'verified', 100.00, 350.00, 'cash_on_delivery', 'unpaid', 'kandy', 'uber', NULL, NULL, NULL, '2026-03-06 04:40:41', '2026-03-06 04:59:50'),
(16, 'PO-20260306-DD5CMO', 2, 1, 'prescriptions/HDGDqnWZZJJ05ATHgOv2VqlxUJK08skZcQrKmFjG.png', '2026-03-06 10:31:12', 'ready', 150.00, 300.00, 'cash_on_delivery', 'unpaid', 'Gedarata එවපන්', 'pickme', NULL, 'Payment Method', NULL, '2026-03-06 05:01:12', '2026-03-06 05:04:57'),
(17, 'PO-20260306-WKFYYU', 2, 1, '', '2026-03-06 12:23:30', 'pending', 150.00, 0.00, 'cash_on_delivery', 'unpaid', 'ffffffffffff', 'pickme', NULL, NULL, NULL, '2026-03-06 06:53:30', '2026-03-06 06:53:30'),
(18, 'PO-20260306-X4IJIM', 2, 1, '', '2026-03-06 12:23:42', 'processing', 150.00, 300.00, 'online', 'unpaid', 'ffffffffffff', 'uber', NULL, NULL, NULL, '2026-03-06 06:53:42', '2026-03-06 07:31:55'),
(19, 'PO-20260306-AEPNSN', 2, 1, 'prescriptions/6vkcuPUvt0MtuBCrK4tjl6Zpc7uud9A0kQngZF1g.png', '2026-03-06 12:26:26', 'verified', 100.00, 200.00, 'cash_on_delivery', 'unpaid', 'ffffffffffff', 'pickme', NULL, NULL, NULL, '2026-03-06 06:56:26', '2026-03-06 07:46:26'),
(20, 'PO-20260306-MEDUTW', 2, 1, 'prescriptions/LXzG88I0iNZ0Y455iXxqwbvXqNc5d3uhgeuqXn4u.png', '2026-03-06 12:52:55', 'verified', 500.00, 200.00, 'cash_on_delivery', 'unpaid', 'ffffffffffff', 'pickme', NULL, NULL, NULL, '2026-03-06 07:22:55', '2026-03-06 07:23:51'),
(21, 'PO-20260306-GJAHYQ', 2, 1, '', '2026-03-06 13:33:37', 'pending', 100.00, 0.00, 'online', 'unpaid', 'PICKUP', NULL, NULL, NULL, NULL, '2026-03-06 08:03:37', '2026-03-06 08:03:37'),
(22, 'PO-20260306-S5XLCH', 2, 1, 'prescriptions/yzW1fTtKU4eSFvEpLiKGvwW9CWD49PRRdNSobpad.png', '2026-03-06 13:34:01', 'delivered', 2000.00, 200.00, 'cash_on_delivery', 'paid', 'ffffffffffff', 'uber', NULL, NULL, NULL, '2026-03-06 08:04:01', '2026-03-06 08:23:10'),
(23, 'PO-20260307-EZ15ZB', 4, 1, 'prescriptions/7nEd4xamxKKMYwYI7YyGIzpOrUxyYLlPAUcLnhAE.png', '2026-03-07 21:19:20', 'pending', 0.00, 0.00, 'online', 'unpaid', 'Kandy', 'pickme', NULL, NULL, NULL, '2026-03-07 15:49:20', '2026-03-07 15:49:20');

-- --------------------------------------------------------

--
-- Table structure for table `prescription_order_items`
--

CREATE TABLE `prescription_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `medication_id` bigint(20) UNSIGNED DEFAULT NULL,
  `medication_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prescription_order_items`
--

INSERT INTO `prescription_order_items` (`id`, `order_id`, `medication_id`, `medication_name`, `quantity`, `price`, `subtotal`, `created_at`) VALUES
(2, 15, NULL, 'පැරසිටමෝල්', 10, 10.00, 100.00, '2026-03-06 10:29:50'),
(3, 16, NULL, 'පැනඩෝල්', 10, 15.00, 150.00, '2026-03-06 10:32:23'),
(4, 17, 11, 'අස්ඩ්', 3, 50.00, 150.00, '2026-03-06 12:23:30'),
(5, 18, 11, 'අස්ඩ්', 3, 50.00, 150.00, '2026-03-06 12:23:42'),
(6, 19, NULL, 'සඩ්', 1, 100.00, 100.00, '2026-03-06 13:16:26'),
(7, 21, 11, 'අස්ඩ්', 2, 50.00, 100.00, '2026-03-06 13:33:37'),
(8, 22, 10, 'sa', 50, 40.00, 2000.00, '2026-03-06 13:37:10');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `ratable_type` enum('doctor','hospital','laboratory','pharmacy','medical_centre') NOT NULL,
  `ratable_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(10) UNSIGNED NOT NULL,
  `review` text DEFAULT NULL,
  `reply` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `related_type` enum('appointment','lab_order','prescription_order') DEFAULT NULL,
  `related_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `patient_id`, `ratable_type`, `ratable_id`, `rating`, `review`, `reply`, `replied_at`, `related_type`, `related_id`, `created_at`, `updated_at`) VALUES
(1, 2, 'laboratory', 1, 4, NULL, NULL, NULL, 'lab_order', 4, '2026-02-26 15:12:28', '2026-02-26 15:12:28'),
(2, 2, 'laboratory', 1, 3, 'as', NULL, NULL, 'lab_order', 15, '2026-02-27 12:26:55', '2026-02-27 12:26:55'),
(3, 2, 'hospital', 1, 3, 'එව්ද්‍ර්', NULL, NULL, NULL, NULL, '2026-02-27 14:05:26', '2026-02-27 14:05:26'),
(4, 2, 'pharmacy', 1, 3, NULL, NULL, NULL, NULL, NULL, '2026-02-27 16:02:18', '2026-02-27 16:02:18'),
(5, 2, 'laboratory', 1, 4, NULL, NULL, NULL, 'lab_order', 14, '2026-03-02 15:03:41', '2026-03-02 15:03:41'),
(6, 5, 'hospital', 1, 2, NULL, NULL, NULL, NULL, NULL, '2026-03-05 05:33:40', '2026-03-05 05:33:40');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES
(1, 'site_name', 'HEALTHNET', 'Website Name', '2026-02-25 19:13:43'),
(2, 'site_email', 'info@healthnet.lk', 'Contact Email', '2026-02-25 19:13:43'),
(3, 'currency', 'LKR', 'Currency Code', '2026-02-25 19:13:43'),
(4, 'appointment_advance_payment_percentage', '50', 'Default advance payment percentage for appointments', '2026-02-25 19:13:43'),
(5, 'max_file_upload_size', '10240', 'Maximum file upload size in KB', '2026-02-25 19:13:43'),
(6, 'enable_chatbot', '1', 'Enable AI Chatbot', '2026-02-25 19:13:43'),
(7, 'enable_notifications', '1', 'Enable Push Notifications', '2026-02-25 19:13:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('patient','doctor','hospital','laboratory','pharmacy','medical_centre','admin') NOT NULL,
  `status` enum('pending','active','suspended','rejected') NOT NULL DEFAULT 'pending',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `user_type`, `status`, `email_verified_at`, `remember_token`, `otp`, `otp_expires_at`, `created_at`, `updated_at`) VALUES
(1, 'admin@healthnet.lk', '$2y$10$rKtfEnyF3p9/2EKz3cLLnu6kGyv1x5/bURJsBEYFa2GDTMvosrSoG', 'admin', 'active', '2025-12-04 14:05:40', NULL, NULL, NULL, '2025-12-04 14:05:40', '2025-12-04 14:05:40'),
(2, 'doctor@gmail.com', '$2y$10$vH9e14bzgCiSm1p9Sy0bJeKco3NKEQOpf8wiiXVp.avC.5RlSPfKe', 'doctor', 'active', NULL, NULL, NULL, NULL, '2025-12-04 14:10:42', '2025-12-04 14:12:58'),
(3, 'rashmikacloude@gmail.com', '$2y$10$wCgfY2wzKh96Lmu.VOfSHOZSfFOLsfGk6FZUwZHMyPv/ayKq0yuPK', 'patient', 'active', '2025-12-04 14:16:52', NULL, NULL, NULL, '2025-12-04 14:11:44', '2025-12-04 14:16:52'),
(4, 'hospital@gmail.com', '$2y$10$apzQWRIsRx4b1e5m2d.8oe.p53pUTFVBtI5ekNqf9hlpDIJ3yUvXO', 'hospital', 'active', NULL, NULL, NULL, NULL, '2025-12-04 14:24:41', '2025-12-04 14:28:18'),
(5, 'Laboratory@gmail.com', '$2y$10$vH9e14bzgCiSm1p9Sy0bJeKco3NKEQOpf8wiiXVp.avC.5RlSPfKe', 'laboratory', 'active', NULL, NULL, NULL, NULL, '2025-12-04 14:33:39', '2025-12-04 15:04:25'),
(6, 'Pharmacy@gmail.com', '$2y$10$spXrKDW9bQgv83xTnbNwwOBS6IVJvv7IDXuumHGOAdr/Jx6uFkHWe', 'pharmacy', 'active', NULL, NULL, NULL, NULL, '2025-12-04 15:00:09', '2025-12-04 15:04:34'),
(7, 'MedicalCentre@gmail.com', '$2y$10$94MGBykb9iuMGTOO5jTolesE/b37vzt5wPW6QfdAK9RYmVJOFMfOu', 'medical_centre', 'active', NULL, NULL, NULL, NULL, '2025-12-04 15:02:56', '2025-12-04 15:04:43'),
(8, 'patient@gmail.com', '$2y$10$JLk2Uf7WiCbsH2k3HkrEJ.2TIdU69/Fot3nc.WR29YNEdietaMWby', 'patient', 'active', NULL, 'dZqhCKdEMEQ89DHE6iPwhMUgDqUcV6yAREjbsMVCa7B9pExFj0THMjOw2FJr', NULL, NULL, '2025-12-13 13:01:41', '2025-12-13 13:01:41'),
(9, 'doctor001@gmail.com', '$2y$10$OZr67P/o4LMLHoVoLJUCDOjJ0R5AA5x7vc.ONbQJRV/15s9lblDKO', 'doctor', 'active', NULL, NULL, NULL, NULL, '2025-12-17 16:30:50', '2025-12-17 16:33:44'),
(10, 'doctortest111@gmail.com', '$2y$10$23xZkGFbvnWgeuczfzq2PeK8ggmJ8y8Hi09bsYfcb93knNQCi8MK6', 'doctor', 'active', NULL, NULL, NULL, NULL, '2026-02-23 14:35:13', '2026-02-23 14:44:46'),
(11, 'Laboratory1@gmail.com', '$2y$10$nSo.BGuU2djTh4/ytJermuYu23K6D.VEUlXyllV3/pbkWHBDeYz1W', 'laboratory', 'active', NULL, NULL, NULL, NULL, '2026-02-25 16:08:55', '2026-02-25 16:09:20'),
(12, 'rashmika@gmail.com', '$2y$10$LR//e/tv8.X5cmwed5psj.voPWYz7n7coAtVVxfhNGYgzEvC7WHoq', 'patient', 'active', NULL, NULL, NULL, NULL, '2026-02-28 17:22:24', '2026-02-28 17:22:24'),
(13, 'ravindudinal599@gmail.com', '$2y$10$PTO8MOtxskLBJ3fJNlfVdufHvJ2k4YLkLX46OLxcmHxCLMCbPVF.6', 'patient', 'active', NULL, NULL, NULL, NULL, '2026-03-02 14:26:36', '2026-03-02 14:26:36'),
(14, 'dinalrashmika68@gmail.com', '$2y$10$WvCnGKOYWvMGoRIjygQage4/fgXUiWc1MvCKHahIw/USdEJWNewKW', 'patient', 'active', '2026-03-05 04:05:25', NULL, NULL, NULL, '2026-03-05 04:05:09', '2026-03-05 04:05:25'),
(15, 'kanishkalak13@gmail.com', '$2y$10$X6HE1TigccRvH6nPStVqnuWEsCRhaVZTd6CaCmOjxtlheZQABTnfq', 'patient', 'active', NULL, NULL, NULL, NULL, '2026-03-06 08:16:01', '2026-03-06 08:16:01'),
(16, 'dinalrashmika6844@gmail.com', '$2y$10$7NABnzx7Ha8hZ8SCQrwXregZ40sqpgq.sZzr8I60O6Dpnx7W0f/t6', 'patient', 'active', NULL, NULL, NULL, NULL, '2026-03-06 08:21:00', '2026-03-06 08:21:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_index` (`user_id`),
  ADD KEY `activity_logs_created_at_index` (`created_at`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_publisher_type_publisher_id_index` (`publisher_type`,`publisher_id`),
  ADD KEY `announcements_announcement_type_index` (`announcement_type`),
  ADD KEY `announcements_is_active_index` (`is_active`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appointments_appointment_number_unique` (`appointment_number`),
  ADD KEY `appointments_cancelled_by_foreign` (`cancelled_by`),
  ADD KEY `appointments_patient_id_index` (`patient_id`),
  ADD KEY `appointments_doctor_id_index` (`doctor_id`),
  ADD KEY `appointments_appointment_date_index` (`appointment_date`),
  ADD KEY `appointments_status_index` (`status`);

--
-- Indexes for table `chatbot_conversations`
--
ALTER TABLE `chatbot_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_session` (`session_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `chatbot_faqs`
--
ALTER TABLE `chatbot_faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_conversation` (`conversation_id`),
  ADD KEY `idx_sender` (`sender_type`,`sender_id`);

--
-- Indexes for table `chatbot_quick_links`
--
ALTER TABLE `chatbot_quick_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctors_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `doctors_slmc_number_unique` (`slmc_number`),
  ADD KEY `doctors_approved_by_foreign` (`approved_by`),
  ADD KEY `doctors_slmc_number_index` (`slmc_number`),
  ADD KEY `doctors_specialization_index` (`specialization`);

--
-- Indexes for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_schedules_doctor_id_day_of_week_index` (`doctor_id`,`day_of_week`),
  ADD KEY `doctor_schedules_workplace_type_workplace_id_index` (`workplace_type`,`workplace_id`);

--
-- Indexes for table `doctor_workplaces`
--
ALTER TABLE `doctor_workplaces`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_workplace` (`doctor_id`,`workplace_type`,`workplace_id`),
  ADD KEY `doctor_workplaces_approved_by_foreign` (`approved_by`),
  ADD KEY `doctor_workplaces_workplace_type_workplace_id_index` (`workplace_type`,`workplace_id`),
  ADD KEY `doctor_workplaces_doctor_id_index` (`doctor_id`);

--
-- Indexes for table `health_articles`
--
ALTER TABLE `health_articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `health_articles_slug_unique` (`slug`),
  ADD KEY `health_articles_category_index` (`category`),
  ADD KEY `health_articles_is_published_index` (`is_published`),
  ADD KEY `health_articles_slug_index` (`slug`);

--
-- Indexes for table `health_metrics`
--
ALTER TABLE `health_metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `health_metrics_patient_id_metric_date_index` (`patient_id`,`metric_date`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hospitals_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `hospitals_registration_number_unique` (`registration_number`),
  ADD KEY `hospitals_approved_by_foreign` (`approved_by`),
  ADD KEY `hospitals_type_index` (`type`),
  ADD KEY `hospitals_city_index` (`city`);

--
-- Indexes for table `laboratories`
--
ALTER TABLE `laboratories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `laboratories_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `laboratories_registration_number_unique` (`registration_number`),
  ADD KEY `laboratories_approved_by_foreign` (`approved_by`),
  ADD KEY `laboratories_city_index` (`city`);

--
-- Indexes for table `lab_orders`
--
ALTER TABLE `lab_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_orders_order_number_unique` (`order_number`),
  ADD UNIQUE KEY `lab_orders_reference_number_unique` (`reference_number`),
  ADD KEY `lab_orders_doctor_id_foreign` (`doctor_id`),
  ADD KEY `lab_orders_patient_id_index` (`patient_id`),
  ADD KEY `lab_orders_laboratory_id_index` (`laboratory_id`),
  ADD KEY `lab_orders_reference_number_index` (`reference_number`),
  ADD KEY `lab_orders_status_index` (`status`);

--
-- Indexes for table `lab_order_items`
--
ALTER TABLE `lab_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lab_order_items_test_id_foreign` (`test_id`),
  ADD KEY `lab_order_items_package_id_foreign` (`package_id`),
  ADD KEY `lab_order_items_order_id_index` (`order_id`);

--
-- Indexes for table `lab_packages`
--
ALTER TABLE `lab_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lab_packages_laboratory_id_index` (`laboratory_id`);

--
-- Indexes for table `lab_package_tests`
--
ALTER TABLE `lab_package_tests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_package_test` (`package_id`,`test_id`),
  ADD KEY `lab_package_tests_test_id_foreign` (`test_id`);

--
-- Indexes for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lab_tests_laboratory_id_index` (`laboratory_id`),
  ADD KEY `lab_tests_test_category_index` (`test_category`);

--
-- Indexes for table `medical_centres`
--
ALTER TABLE `medical_centres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medical_centres_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `medical_centres_registration_number_unique` (`registration_number`),
  ADD KEY `medical_centres_owner_doctor_id_foreign` (`owner_doctor_id`),
  ADD KEY `medical_centres_approved_by_foreign` (`approved_by`),
  ADD KEY `medical_centres_city_index` (`city`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medical_history_patient_id_index` (`patient_id`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medical_records_doctor_id_foreign` (`doctor_id`),
  ADD KEY `medical_records_hospital_id_foreign` (`hospital_id`),
  ADD KEY `medical_records_patient_id_index` (`patient_id`),
  ADD KEY `medical_records_record_type_index` (`record_type`),
  ADD KEY `medical_records_record_date_index` (`record_date`);

--
-- Indexes for table `medications`
--
ALTER TABLE `medications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medications_pharmacy_id_index` (`pharmacy_id`),
  ADD KEY `medications_category_index` (`category`),
  ADD KEY `medications_stock_status_index` (`stock_status`);

--
-- Indexes for table `medicine_reminders`
--
ALTER TABLE `medicine_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicine_reminders_patient_id_is_active_index` (`patient_id`,`is_active`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifiable` (`notifiable_type`,`notifiable_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patients_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `patients_nic_unique` (`nic`),
  ADD KEY `patients_nic_index` (`nic`);

--
-- Indexes for table `patient_health_data`
--
ALTER TABLE `patient_health_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_health_data_patient_id_recorded_date_index` (`patient_id`,`recorded_date`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_payment_number_unique` (`payment_number`),
  ADD KEY `payments_payer_id_index` (`payer_id`),
  ADD KEY `payments_payee_type_payee_id_index` (`payee_type`,`payee_id`),
  ADD KEY `payments_related_type_related_id_index` (`related_type`,`related_id`),
  ADD KEY `payments_payment_status_index` (`payment_status`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pharmacies`
--
ALTER TABLE `pharmacies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pharmacies_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `pharmacies_registration_number_unique` (`registration_number`),
  ADD KEY `pharmacies_approved_by_foreign` (`approved_by`),
  ADD KEY `pharmacies_city_index` (`city`);

--
-- Indexes for table `prescription_orders`
--
ALTER TABLE `prescription_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prescription_orders_order_number_unique` (`order_number`),
  ADD KEY `prescription_orders_patient_id_index` (`patient_id`),
  ADD KEY `prescription_orders_pharmacy_id_index` (`pharmacy_id`),
  ADD KEY `prescription_orders_status_index` (`status`);

--
-- Indexes for table `prescription_order_items`
--
ALTER TABLE `prescription_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescription_order_items_medication_id_foreign` (`medication_id`),
  ADD KEY `prescription_order_items_order_id_index` (`order_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_rating` (`patient_id`,`ratable_type`,`ratable_id`,`related_type`,`related_id`),
  ADD KEY `ratings_ratable_type_ratable_id_index` (`ratable_type`,`ratable_id`),
  ADD KEY `ratings_patient_id_index` (`patient_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_setting_key_unique` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_email_index` (`email`),
  ADD KEY `users_user_type_index` (`user_type`),
  ADD KEY `users_status_index` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `chatbot_conversations`
--
ALTER TABLE `chatbot_conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `chatbot_faqs`
--
ALTER TABLE `chatbot_faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `chatbot_quick_links`
--
ALTER TABLE `chatbot_quick_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `doctor_workplaces`
--
ALTER TABLE `doctor_workplaces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `health_articles`
--
ALTER TABLE `health_articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `health_metrics`
--
ALTER TABLE `health_metrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `laboratories`
--
ALTER TABLE `laboratories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lab_orders`
--
ALTER TABLE `lab_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `lab_order_items`
--
ALTER TABLE `lab_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `lab_packages`
--
ALTER TABLE `lab_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lab_package_tests`
--
ALTER TABLE `lab_package_tests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lab_tests`
--
ALTER TABLE `lab_tests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `medical_centres`
--
ALTER TABLE `medical_centres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medications`
--
ALTER TABLE `medications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `medicine_reminders`
--
ALTER TABLE `medicine_reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `patient_health_data`
--
ALTER TABLE `patient_health_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pharmacies`
--
ALTER TABLE `pharmacies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `prescription_orders`
--
ALTER TABLE `prescription_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `prescription_order_items`
--
ALTER TABLE `prescription_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chatbot_conversations`
--
ALTER TABLE `chatbot_conversations`
  ADD CONSTRAINT `chatbot_conversations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chatbot_conversations_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  ADD CONSTRAINT `chatbot_messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `chatbot_conversations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD CONSTRAINT `doctor_schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_workplaces`
--
ALTER TABLE `doctor_workplaces`
  ADD CONSTRAINT `doctor_workplaces_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `doctor_workplaces_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `health_metrics`
--
ALTER TABLE `health_metrics`
  ADD CONSTRAINT `health_metrics_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD CONSTRAINT `hospitals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hospitals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laboratories`
--
ALTER TABLE `laboratories`
  ADD CONSTRAINT `laboratories_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `laboratories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_orders`
--
ALTER TABLE `lab_orders`
  ADD CONSTRAINT `lab_orders_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lab_orders_laboratory_id_foreign` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_orders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_order_items`
--
ALTER TABLE `lab_order_items`
  ADD CONSTRAINT `lab_order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `lab_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_order_items_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `lab_packages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lab_order_items_test_id_foreign` FOREIGN KEY (`test_id`) REFERENCES `lab_tests` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lab_packages`
--
ALTER TABLE `lab_packages`
  ADD CONSTRAINT `lab_packages_laboratory_id_foreign` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_package_tests`
--
ALTER TABLE `lab_package_tests`
  ADD CONSTRAINT `lab_package_tests_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `lab_packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_package_tests_test_id_foreign` FOREIGN KEY (`test_id`) REFERENCES `lab_tests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD CONSTRAINT `lab_tests_laboratory_id_foreign` FOREIGN KEY (`laboratory_id`) REFERENCES `laboratories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_centres`
--
ALTER TABLE `medical_centres`
  ADD CONSTRAINT `medical_centres_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `medical_centres_owner_doctor_id_foreign` FOREIGN KEY (`owner_doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `medical_centres_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `medical_history_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `medical_records_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `medical_records_hospital_id_foreign` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `medical_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medications`
--
ALTER TABLE `medications`
  ADD CONSTRAINT `medications_pharmacy_id_foreign` FOREIGN KEY (`pharmacy_id`) REFERENCES `pharmacies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medicine_reminders`
--
ALTER TABLE `medicine_reminders`
  ADD CONSTRAINT `medicine_reminders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_health_data`
--
ALTER TABLE `patient_health_data`
  ADD CONSTRAINT `patient_health_data_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_payer_id_foreign` FOREIGN KEY (`payer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pharmacies`
--
ALTER TABLE `pharmacies`
  ADD CONSTRAINT `pharmacies_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pharmacies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescription_orders`
--
ALTER TABLE `prescription_orders`
  ADD CONSTRAINT `prescription_orders_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_orders_pharmacy_id_foreign` FOREIGN KEY (`pharmacy_id`) REFERENCES `pharmacies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescription_order_items`
--
ALTER TABLE `prescription_order_items`
  ADD CONSTRAINT `prescription_order_items_medication_id_foreign` FOREIGN KEY (`medication_id`) REFERENCES `medications` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `prescription_order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `prescription_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
