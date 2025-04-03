<?php

require_once('../connectPDO.php'); 

if(isset($_POST["postId"]) && isset($_POST["status"]) ){

    $myPDO = connectedPDO();

    $updatePostStat = $myPDO->prepare("UPDATE JobPosts SET adminstatus = ? WHERE id = ?");
    $updatePostStat->execute([ 
        $_POST["status"],
        $_POST["postId"]
    ]);

    

}