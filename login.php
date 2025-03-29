<?php

include("./allFunctions/Sanitization/checkValidInputs.php");
include("./allFunctions/connectPDO.php");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!sanitizeString($username) || !sanitizePW($password)) {
        die('Invalid Input');
    }

    try {
        $myPDO = connectedPDO();


        $ifUniqueUN = $myPDO->prepare("SELECT id, password FROM Users WHERE username = :username");
        $ifUniqueUN ->execute([':username' => $username]);
        $user = $ifUniqueUN ->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            $salt = substr($user['password'], 0, 64); // Extract the salt (first 64 hex characters)

            $saltedPassword = $salt . $password;
            $hashedPassword = hash('sha512', $saltedPassword);
            
            
            $computedPassword = $salt . $hashedPassword;

            if ($computedPassword === $user['password']) {
                echo 'signed in';
            } else {
                echo 'username or password wrong';
            }
        } else {
            echo 'username or password wrong';
        }
    } catch (PDOException $e) {
        echo 'database error';
    }
}

function createSession($userid) {


}