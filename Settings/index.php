<?php
  include("../allFunctions/createNavBar/createNavBar.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="settings.css">
    <link rel="stylesheet" href="../allFunctions/createNavBar/createNavBar.css">

    <link rel="stylesheet" href="../allFunctions/addEditUserButton/editUserButtons.css">
    <script src="../allFunctions/addEditUserButton/editUserButtons.js"></script>
  </head>
  <body>
    <?php
      createNavBar("settings");   
    ?>
    <div id="settingsMain">
      <?php 
        //changes pages based on what type the usr is
        //$usrid = isset($_GET['usrid']);
        
        //$whichPage = "SELECT role, usrType FROM users WHERE". $usrid." = ? 
        $whichPage = "student";
        if($whichPage == "student") {
          echo '
            <h1 id="header">Account Settings</h1>
  
            <script>
              createPreferenceSelectElements("Email", "inputEmail", "email", "inputBox", false ,"settingsPopup", "Email", ["Your email here..."], null);
              createPreferenceSelectElements("Phone Number", "inputPhone", "phone", "inputBox", false ,"settingsPopup", "Phone Number", ["Your number here..."], null);
  
              createPreferenceSelectPopups("settingsPopup");
            </script>
          ';
        } elseif($whichPage == "employer"){
          echo '
          
          ';
        }
      ?>
    </div>
    <script src="settings.js"></script>
    <script src="../allFunctions/createNavBar/createNavBar.js"></script>
  </body>
</html>