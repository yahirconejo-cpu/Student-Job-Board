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
    <style>
        body { font-family: 'Roboto', sans-serif; margin: 20px; }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f4f4f4; }
        a { color: blue; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h2><?= $userType === 'employer' ? "All Job Applications" : "Your Job Applications" ?></h2>

<!-- Resume Upload (Students Only) -->
<?php if ($userType === 'student'): ?>
    <h3>Upload Your Resume (PDF only)</h3>
    <form action="handleUpload.php" method="post" enctype="multipart/form-data">
        <label for="resume">Choose PDF:</label>
        <input type="file" name="resume" accept=".pdf" required>
        <button type="submit">Upload Resume</button>
    </form>
<?php endif; ?>

<!-- Applications Table -->
<table>
    <tr>
        <th>ID</th>
        <th>Job Post ID</th>
        <?= $userType === 'employer' ? "<th>Applicant</th>" : "" ?>
        <th>Status</th>
        <th>Resume</th>
    </tr>

    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['jobpostid'] ?></td>
            <?= $userType === 'employer' ? "<td>{$row['applicant_name']}</td>" : "" ?>
            <td><?= ucfirst($row['status']) ?></td>
            <td><a href="../uploads/<?= htmlspecialchars($row['resumes']) ?>" target="_blank">Download</a></td>
        </tr>
    <?php endwhile; ?>

</table>

</body>
</html>