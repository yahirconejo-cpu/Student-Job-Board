<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include_once("./allFunctions/Sanitization/checkValidInputs.php");
    include_once("./allFunctions/connectPDO.php");
    $myPDO = connectedPDO();
    function createSession($userid, $myPDO) {
        $delPastSession = $myPDO->prepare("DELETE FROM Session WHERE userid = :userid");
        $delPastSession->execute([':userid' => $userid]);

        $sessionCode = bin2hex(random_bytes(32));

        $checkIfUniqueCode = $myPDO->prepare("SELECT COUNT(*) FROM Session WHERE sessioncode = :sessioncode");
        $checkIfUniqueCode->execute([':sessioncode' => $sessionCode]);

        $expirationDate = date('Y-m-d H:i:s', strtotime('+3 days'));

        $stmt = $myPDO->prepare("INSERT INTO Session (userid, sessioncode, expirationdate) VALUES (:userid, :sessioncode, :expirationdate)");
        $stmt->execute([
            ':userid' => $userid,
            ':sessioncode' => $sessionCode,
            ':expirationdate' => $expirationDate
        ]);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['userid'] = $userid;
        $_SESSION['sessioncode'] = $sessionCode;
    }

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if (!sanitizeString($username) || !sanitizePW($password)) {
                die('Invalid Input');
            }

            try {
                $myPDO = connectedPDO();


                $ifUniqueUN = $myPDO->prepare("SELECT id, password, usertype FROM Users WHERE username = :username");
                $ifUniqueUN ->execute([':username' => $username]);
                $user = $ifUniqueUN ->fetch(PDO::FETCH_ASSOC);

                if ($user) {

                    $salt = substr($user['password'], 0, 64); // Extract the salt (first 64 hex characters)

                    $saltedPassword = $salt . $password;
                    $hashedPassword = hash('sha512', $saltedPassword);
                    
                    
                    $completePassword = $salt . $hashedPassword;

                    if ($completePassword === $user['password']) {
                        createSession($user['id'], $myPDO);
                        echo json_encode([ "success" => true, "usertype" => $user['usertype']]);
                    } else {
                        echo json_encode([ "success" => false, "message" => "Invalid Input"]);
                    }
                } else {
                    echo json_encode([ "success" => false, "message" => "No found User"]);
                }
            } catch (PDOException $e) {
                echo json_encode([ "success" => false, "message" => "Error"]);
            }
        }