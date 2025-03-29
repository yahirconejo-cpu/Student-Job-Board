<?php
    include("../allFunctions/Sanitization/checkValidInputs.php");

    if(isset($_GET['username']) && isset($_GET['password']) && isset($_GET['userType'])) {
        if(!sanitizeString($_GET['username']) || !sanitizePW($_GET['password']) || !sanitizeString($_GET['userType'])) {
            die("Stop hacking my client");
        }

        $validUserTypes = ['student', 'employer', 'admin'];
        if (!in_array($_GET['userType'], $validUserTypes)) {
            die("Invalid user type.");
        }

        try {
            $salt = bin2hex(random_bytes(32));

            $saltedPassword = $salt . $_GET['password'];

            $hashedPassword = hash('sha512', $saltedPassword);

            $myPDO = new PDO('sqlite:../Database/Website2.db');

            $stmt = $myPDO->query("SELECT COUNT(*) FROM Users WHERE username = '".$_GET['username']."'");

            if ($stmt->fetchColumn() > 0) {
                die("Username already taken.");
            }

            $myPDO->exec("INSERT INTO Users (username, password, usertype) VALUES ('".$_GET['username']."', '".$hashedPassword."', '".$_GET['userType']."')");
            
            $stmt = $myPDO->query("SELECT id FROM Users WHERE username = '".$_GET['username']."'");
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $userId = $user['id'];

            $myPDO->exec("INSERT INTO Settings (userid) VALUES ('$userId')");
            $stmt = $myPDO->query("SELECT * FROM Users");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Retrieve all the data from the Settings table
            $stmt = $myPDO->query("SELECT * FROM Settings");
            $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the data as JSON (you can also return it in any other format you need)
            echo json_encode([
                'users' => $users,
                'settings' => $settings
            ]);
        } catch(PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }


    }