<?php

    include_once("../allFunctions/connectPDO.php");

    $myPDO = connectedPDO();

    // Fetch all users with username and usertype
    $stmt = $myPDO->prepare("SELECT username, usertype FROM Users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);