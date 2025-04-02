<?php
    // session_start();
    include_once("../allFunctions/connectPDO.php");

    // if (!isset($_SESSION['userid'])) {
    //     die("Error: You must be logged in to apply.");
    // }

    $pdo = connectedPDO();
    //$currentUserId = $_SESSION['userid'];
    $currentUserId = 1; // For testing purposes

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $jobpostid = $_POST['jobpostid'];

        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['resume']['tmp_name'];
            $fileName = $_FILES['resume']['name'];
            $fileType = $_FILES['resume']['type'];

            // Debugging - Print file details
            echo "<pre>";
            print_r($_FILES['resume']);
            echo "</pre>";

            // Allow multiple MIME types for PDFs
            $allowedTypes = ['application/pdf', 'application/x-pdf', 'application/acrobat', 'application/vnd.pdf'];
            if (!in_array($fileType, $allowedTypes)) {
                die("❌ Error: Only PDF files are allowed. Detected type: $fileType");
            }

            // Ensure uploads directory exists
            $uploadDir = '../uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate unique filename
            $newFileName = time() . "_" . basename($fileName);
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Corrected SQL query using parameter binding for `userid`
                $stmt = $pdo->prepare("INSERT INTO Applications (userid, jobpostid, resumes) VALUES (:userid, :jobpostid, :resumes)");
                $stmt->execute([
                    ':userid' => $currentUserId, // Use the actual user ID
                    ':jobpostid' => $jobpostid,
                    ':resumes' => $newFileName
                ]);

                echo "✅ Application submitted successfully!";
                echo "<br>📄 Resume saved as: <a href='$destPath' target='_blank'>$newFileName</a>";
            } else {
                echo "❌ Error uploading resume.";
            }
        } else {
            echo "❌ No resume uploaded.";
        }
    }