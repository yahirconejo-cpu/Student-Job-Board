<?php
include_once("../allFunctions/connectPDO.php");


$pdo = connectedPDO();
$currentUserId = 2; // Test ID
$userType = 'employer'; // Change to 'employer' for employer view


// Query: Students see available job posts, employers see applications
if ($userType === 'student') {
    $query = "SELECT * FROM JobPosts WHERE poststatus = 'accepting' AND adminstatus = 'accepted' ORDER BY id DESC";
} else {
    $query = "SELECT * FROM Applications ORDER BY id DESC";
}


$stmt = $pdo->prepare($query);
$stmt->execute();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $userType === 'employer' ? "All Job Applications" : "Available Jobs" ?></title>
    <link rel="stylesheet" href="applications.css">
</head>
<body>


<h2><?= $userType === 'employer' ? "All Job Applications" : "Available Job Posts" ?></h2>


<div class="application-box">
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="application-card">
            <div class="job-title"><?= htmlspecialchars($row['jobtitle']) ?></div>
            <p><strong>Type:</strong> <?= htmlspecialchars($row['jobtype']) ?></p>
            <p><strong>Days:</strong> <?= htmlspecialchars($row['jobdays']) ?></p>
            <p><strong>Shifts:</strong> <?= htmlspecialchars($row['shifts']) ?></p>
            <p><strong>Pay:</strong> $<?= htmlspecialchars(number_format($row['pay'], 2)) ?>/hr</p>
            <p><strong>Location:</strong> <?= htmlspecialchars($row['address'] ?? 'N/A') ?></p>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($row['description'])) ?></p>


            <?php if ($userType === 'student'): ?>
                <h3>Apply for This Job</h3>
                <form class="applyForm" enctype="multipart/form-data">
                    <input type="hidden" name="jobpostid" value="<?= $row['id'] ?>">
                    <label for="resume">Upload Resume (PDF, PNG, JPG):</label>
                    <input type="file" name="resume" required>
                    <label for="motivation">Why do you want this job? (200 words max)</label>
                    <textarea name="motivation" rows="4" maxlength="200" required></textarea>
                    <button type="button" onclick="processApplication(this)">Apply</button>
                </form>
                <div class="response"></div>
            <?php endif; ?>


            <?php if ($userType === 'employer'): ?>
                <p><strong>Applicant:</strong> <?= htmlspecialchars($row['applicant_name'] ?? 'N/A') ?></p>
                <p><strong>Status:</strong> <span class="status <?= strtolower($row['status']) ?>"><?= ucfirst($row['status']) ?></span></p>
                <a href="../Data/Uploads/<?= htmlspecialchars($row['resumes']) ?>" target="_blank">Download Resume</a>
                <div id="optionBtnsContainer">
                    <button id="acceptBtn" class="optionBtns" onclick="updateStatus(<?= $row['id'] ?>, 'accepted')">Accept</button>
                    <button id="denyBtn" class="optionBtns" onclick="updateStatus(<?= $row['id'] ?>, 'denied')">Deny</button>
                </div>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>


<script src="applications.js"></script>


</body>
</html>

