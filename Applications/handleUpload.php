<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['resume']['tmp_name'];
        $fileName = $_FILES['resume']['name'];
        $fileType = $_FILES['resume']['type'];

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
        
        $jobpostid = $_POST['jobpostid']; // Get jobpostid from the form
        $stmt = $pdo->prepare("INSERT INTO Applications (userid, jobpostid, resumes, status) VALUES (:userid, :jobpostid, :resumes, :status)");
        $stmt->execute([
            ':userid' => $currentUserId,
            ':jobpostid' => $jobpostid,
            ':resumes' => $newFileName,
            ':status' => 'pending'
        ]);

        // ✅ Success message & redirect (after 3 seconds)
        echo "✅ Application submitted successfully! Redirecting to home page...";
        echo "<script>
                setTimeout(function() {
                    window.location.href = '../Home/index.php';
                }, 3000); // 3-second delay
              </script>";
        exit;
    } else {
        echo "❌ No file uploaded.";
    }
}
