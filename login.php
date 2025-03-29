<?php
include("./allFunctions/Sanitization/checkValidInputs.php");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!sanitizeString($username) || !sanitizePW($password)) {
        die('Invalid Input');
    }

    try {
        $myPDO = new PDO('sqlite:./Database/Website2.db');

        // Secure SQL Query
        $stmt = $myPDO->prepare("SELECT id, password FROM Users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $storedPassword = $user['password'];
            $salt = substr($storedPassword, 0, 64); // Extract the salt (first 64 hex characters)

            $saltedPassword = $salt . $password;
            $hashedPassword = hash('sha512', $saltedPassword);
            
            // Correct password format: SALT + HASH
            $computedPassword = $salt . $hashedPassword;

            // Compare against stored password
            if ($computedPassword === $storedPassword) {
                echo 'signed in';
            } else {
                echo 'password wrong';
            }
        } else {
            echo 'username or password wrong';
        }
    } catch (PDOException $e) {
        echo 'database error';
    }
}