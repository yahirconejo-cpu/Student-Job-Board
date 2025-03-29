<?php
    include("../allFunctions/Sanitization/checkValidInputs.php");

    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['userType'])) {
        if (!sanitizeString($_POST['username']) || !sanitizePW($_POST['password']) || !sanitizeString($_POST['userType'])) {
            die("Stop hacking my client");
        }
    
        $validUserTypes = ['student', 'employer', 'admin'];
        if (!in_array($_POST['userType'], $validUserTypes)) {
            die("Invalid user type.");
        }
    
        try {
            $myPDO = new PDO('sqlite:../Database/Website2.db');
    
            // Check if username already exists
            $stmt = $myPDO->prepare("SELECT COUNT(*) FROM Users WHERE username = :username");
            $stmt->execute([':username' => $_POST['username']]);
            if ($stmt->fetchColumn() > 0) {
                die("Username already taken.");
            }
    
            // Generate salt and hash password
            $salt = bin2hex(random_bytes(32));  // 64-character salt
            $saltedPassword = $salt . $_POST['password'];
            $hashedPassword = hash('sha512', $saltedPassword);
    
            // Store `SALT + HASHED_PASSWORD`
            $finalPassword = $salt . $hashedPassword;
    
            // Insert user securely
            $stmt = $myPDO->prepare("INSERT INTO Users (username, password, usertype) VALUES (:username, :password, :userType)");
            $stmt->execute([
                ':username' => $_POST['username'],
                ':password' => $finalPassword,  // Store SALT + HASH
                ':userType' => $_POST['userType']
            ]);
    
            // Get user ID
            $userId = $myPDO->lastInsertId();
    
            // Insert into Settings table
            $stmt = $myPDO->prepare("INSERT INTO Settings (userid) VALUES (:userid)");
            $stmt->execute([':userid' => $userId]);
    
            echo json_encode(["success" => true, "message" => "Account created!"]);
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
        }
    }