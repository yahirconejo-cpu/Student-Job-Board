<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function returnUserType() {
        if(isset($_SESSION['userid'])){
            $myPDO = connectedPDO();

            $isUser = $myPDO->prepare("SELECT usertype FROM Users WHERE id = :userid");
            $isUser->execute([
                ':userid' => $_SESSION['userid']
            ]);
            $grabedUserType = $isUser->fetch(PDO::FETCH_ASSOC);

            if($grabedUserType){
                return $grabedUserType['usertype'];
            }else{
                return 'Not Signed in';
            }

        }else{
            return "Not Signed in";
        }
    }