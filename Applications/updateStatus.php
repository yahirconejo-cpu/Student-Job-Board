<?php
include_once("../allFunctions/connectPDO.php");


$pdo = connectedPDO();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicationId = $_POST['applicationId'] ?? null;
    $newStatus = $_POST['newStatus'] ?? null;


    if ($applicationId && in_array($newStatus, ['accepted', 'denied'])) {
        $stmt = $pdo->prepare("UPDATE Applications SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $newStatus,
            ':id' => $applicationId
        ]);


        echo "✅ Application has been " . ucfirst($newStatus) . "!";
    } else {
        echo "❌ Invalid request!";
    }
}



