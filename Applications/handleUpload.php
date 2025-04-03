<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../allFunctions/connectPDO.php");
$pdo = connectedPDO(); // Ensure database connection is established

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        try {
            $fileTmpPath = $_FILES['resume']['tmp_name'];
            $fileName = $_FILES['resume']['name'];
            $fileType = $_FILES['resume']['type'];

            //$currentUserId = $_SESSION['userid']; // Uncomment if using sessions
            $currentUserId = 1;

            // Allowed file types
            $allowedTypes = ['application/pdf', 'image/png', 'image/jpeg'];
            if (!in_array($fileType, $allowedTypes)) {
                echo "❌ Error: Only PDF, PNG, or JPEG files are allowed.";
                exit;
            }

            // Ensure uploads directory exists
            $uploadDir = '../Data/Uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate a unique file name
            $newFileName = time() . "_" . basename($fileName);
            $destPath = $uploadDir . $newFileName;

            move_uploaded_file($fileTmpPath, $destPath);

            // ✅ Debugging: Check if `postid` is being received
            if (!isset($_POST['postid']) || empty($_POST['postid'])) {
                echo "❌ Error: Job Post ID is missing.";
                exit;
            }

            $jobpostid = intval($_POST['postid']); // Convert to integer for security
            
            // Insert into database
            $stmt = $pdo->prepare("INSERT INTO Applications (userid, jobpostid, resumes, status) VALUES (:userid, :jobpostid, :resumes, :status)");
            
            if (!$stmt->execute([
                ':userid' => $currentUserId,
                ':jobpostid' => $jobpostid,
                ':resumes' => $newFileName,
                ':status' => 'pending'
            ])) {
                echo "❌ Database error: Could not insert application.";
                exit;
            }

            // ✅ Success message & redirect (after 3 seconds)
            echo "✅ Application submitted successfully! Redirecting to home page...";
            exit;
        } catch (PDOException $e) {
            echo "❌ Database Error: " . $e->getMessage();
            exit;
        }
    } else {
        echo "❌ No file uploaded.";
    }
}