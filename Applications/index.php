<?php
//session_start();
include_once("../allFunctions/connectPDO.php");

// if (!isset($_SESSION['userid'])) {
//     die("Error: You must be logged in to view applications.");
// }

$pdo = connectedPDO();
//$currentUserId = $_SESSION['userid'];
//$userType = $_SESSION['userType'] ?? 'student'; // Defaults to 'student' if undefined
$currentUserId = 1;
$userType = 'student';
// Query: Students see their applications, employers see all with applicant details
$query = ($userType === 'employer') 
    ? "SELECT * FROM Applications ORDER BY id DESC"
    : "SELECT * FROM Applications WHERE userid = :userid ORDER BY id DESC";

$stmt = $pdo->prepare($query);
if ($userType !== 'employer') {
    $stmt->execute([':userid' => $currentUserId]);
} else {
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
    <link rel="stylesheet" href="applications.css">

    <script></script>
</head>
<body>

<h2><?= $userType === 'employer' ? "All Job Applications" : "Your Job Applications" ?></h2>

<?php if ($userType === 'student'): ?>
    <h3>Upload Your Resume (PDF only)</h3>
    <form id="uploadForm" enctype="multipart/form-data">
        <label for="resume">Choose your file (PDF, PNG, JPG):</label>
        <input type="file" id="resume" name="resume" required>
        <!-- <input type="hidden" id="jobpostid" value="<?php //echo $_GET['jobpostid']?>"> -->
        <input type="hidden" id="jobpostid" value="12345">
        <button type="button" onclick="processResumes()">Upload</button>
    </form>
<div id="response"></div>
<?php endif; ?>

<div class="application-box">
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="application-card">
            <div class="job-title">Job Post ID: <?= $row['jobpostid'] ?></div>
            <div class="job-description">
                <?= $userType === 'employer' ? "<p>Applicant: {$row['applicant_name']}</p>" : "" ?>
                <p>Status: <span class="status <?= strtolower($row['status']) ?>"><?= ucfirst($row['status']) ?></span></p>
            </div>
            <div class="action-buttons">
                <a href="../Data/Uploads/<?= htmlspecialchars($row['resumes']) ?>" target="_blank">Download Resume</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<script src="applications.js"></script>

</body>
</html>