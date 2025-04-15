<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../allFunctions/connectPDO.php");
include_once("../allFunctions/checks/checkLogin.php");
include_once("../allFunctions/checks/fetchUserInfo.php");

$pdo = connectedPDO();
$currentUserId = $_SESSION['userid'];
$userType = returnUserType();

$jobpostid = isset($_GET['postid']) ? intval($_GET['postid']) : 0;

// Fetch the job post details
$stmtJobs = $pdo->prepare("SELECT * FROM JobPosts WHERE id = :postid");
$stmtJobs->execute([':postid' => $jobpostid]);
$job = $stmtJobs->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    die("❌ Error: Job post not found.");
}

// Fetch job applications related to this job post (for employer view)
$stmtApplications = $pdo->prepare("SELECT * FROM Applications WHERE jobpostid = :postid");
$stmtApplications->execute([':postid' => $jobpostid]);

$stmtAllApplications = $pdo->prepare("SELECT * FROM Applications WHERE jobpostid = :postid AND userid = :userid");
$stmtAllApplications->execute([':postid' => $jobpostid, ':userid' => $currentUserId]);
$allApplications = $stmtAllApplications->fetch(PDO::FETCH_ASSOC);

if (isset($allApplications['status'])) {
    // Handle any additional logic here
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
    <link rel="stylesheet" href="applications.css">
</head>
<body>

<h2><?= $userType == 'employer' ? "All Job Applications" : "Submit your application!" ?></h2>
<a onClick="window.location.href = document.referrer;" class="exit-button">✖</a>
<div class="application-box">

    <!-- Job Post Details Always on Top -->
    <div class="jobpost-card">
        <h3>Job Details</h3>
        <p><strong>Title:</strong> <?= htmlspecialchars($job['posttitle']) ?></p>
        <p><strong>Type:</strong> <?= htmlspecialchars($job['jobtype']) ?></p>
        <p><strong>Days:</strong> <?= htmlspecialchars($job['jobdays']) ?></p>
        <p><strong>Shifts:</strong> <?= htmlspecialchars($job['shifts']) ?></p>
        <p><strong>Pay:</strong> $<?= htmlspecialchars(number_format((float)$job['pay'], 2)) ?>/hr</p>
        <p><strong>Location:</strong> <?= htmlspecialchars($job['address'] ?? 'N/A') ?></p>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($job['description'])) ?></p>
    </div>

    <!-- Student Application Form -->
    <?php if ($userType == 'student' && !isset($allApplications['status'])): ?>
        <div class="application-card">
            <form class="applyForm" enctype="multipart/form-data">
                <input type="hidden" name="postid" value="<?= $jobpostid ?>">
                <label for="resume">Upload Resume (PDF, PNG, JPG):</label>
                <input type="file" name="resume" required>
                <label for="motivation">Why do you want this job? (200 words max)</label>
                <textarea name="motivation" rows="4" maxlength="200" required></textarea>
                <button type="button" onclick="processApplication(this)">Apply</button>
            </form>
            <div class="response"></div>
        </div>
    <?php endif; ?>

    <!-- Employer Applications Section -->
    <?php if ($userType == 'employer'): ?>
        <h3>Applications</h3>
    <div class="application-list">
        <?php while ($row = $stmtApplications->fetch(PDO::FETCH_ASSOC)): ?>

            <?php
            // Fetch the username from Users table if no name exists in Settings table
            $userId = $row['userid'];
            
            // First check if the user has a name in the Settings table
            $stmtUserInfo = $pdo->prepare("SELECT name FROM Settings WHERE userid = :userid");
            $stmtUserInfo->execute([':userid' => $userId]);
            $userInfo = $stmtUserInfo->fetch(PDO::FETCH_ASSOC);
            
            // If no name is found in Settings, fallback to username from Users table
            $userName = $userInfo['name'] ?? '';  // Name from Settings or empty
            
            if (!$userName) {
                $stmtUser = $pdo->prepare("SELECT username FROM Users WHERE id = :userid");
                $stmtUser->execute([':userid' => $userId]);
                $userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);
                $userName = $userRow['username'] ?? 'Unknown User';
            }
            ?>

            <div class="application-card">
                <p><strong>Applicant:</strong> <?= htmlspecialchars($userName) ?></p>
                <p><strong>Status:</strong>
                    <span class="status <?= strtolower($row['status']) ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </p>
                <p><strong>Motivation:</strong> <?= nl2br(htmlspecialchars($row['response'])) ?></p>
                <a href="../Data/Uploads/<?= htmlspecialchars($row['resumes']) ?>" target="_blank">📄 Download Resume</a>
                <div id="optionBtnsContainer">
                    <button class="optionBtns" id="acceptBtn" onclick="updateStatus(<?= $row['id'] ?>, 'accepted')">Accept</button>
                    <button class="optionBtns" id="denyBtn" onclick="updateStatus(<?= $row['id'] ?>, 'denied')">Deny</button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>

</div>

<script src="applications.js"></script>

</body>
</html>