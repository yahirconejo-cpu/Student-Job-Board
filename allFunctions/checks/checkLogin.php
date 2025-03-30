<?php
    include_once("../allFunctions/connectPDO.php");

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SESSION['sessioncode']) && isset($_SESSION['userid'])){
        $myPDO = connectedPDO();

        // Check if session is expired or not there
        $ifSessionFound = $myPDO->prepare("SELECT id FROM Session WHERE userid = :userid AND sessioncode = :sessioncode AND expirationdate > datetime('now')");
        $ifSessionFound->execute([
            ':userid' => $_SESSION['userid'],
            ':sessioncode' => $_SESSION['sessioncode']
        ]);
        
        $sessionExists = $ifSessionFound->fetch(PDO::FETCH_ASSOC);

        if (!$sessionExists) {
            // Session not found or expired, redirect to login
            header("Location: ../index.php");
            die();
        }


    }else{
        header("Location: ../index.php");
        die();
    }