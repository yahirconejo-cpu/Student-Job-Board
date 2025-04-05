<?php
  include_once("../allFunctions/createNavBar/createNavBar.php");

  // check if signed in
  include_once("../allFunctions/checks/checkLogin.php");

  // check to see if they are students or employers
  include_once("../allFunctions/checks/fetchUserInfo.php");

  $userType = returnUserType();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student</title>
  <link rel="stylesheet" href="sHome.css">
  <link rel="stylesheet" href="../allFunctions/createNavBar/createNavBar.css">
  <link rel="stylesheet" href="../allFunctions/createJobCards/createJobCards.css">

  <link rel="stylesheet" href="../allFunctions/addEditUserButton/editUserButtons.css">
  <script src="../allFunctions/addEditUserButton/editUserButtons.js"></script>
  <script src="../allFunctions/Sanitization/checkValidInputs.js"></script>
  <script src="../allFunctions/createJobCards/createJobCards.js"></script>
</head>
  
  <?php
    createNavBar("home");
  ?>

  <!-- section ------------------------------------------- -->
  <div id="studentOverview">
    <div id="sideBarLeft">
      <div id="sideBarLeftBackground"></div>
      <button name="applications" type="button" class="leftSideBarItems selected"><?php echo $userType =='employer' ? 'Your Posts' : 'Applications'?></button>
      <button name="settings" type="button" class="leftSideBarItems">Settings</button>
      <button name="tutorials" type="button" class="leftSideBarItems">Tutorials</button>
    </div>
    
    <div id="sideBarRight">

      <!-- Header for the right bar  -->
      
      <div id="sideBarRightHeaderHolder">
        <div id="rightSideHeader" class="rightSideBarHeader">
          <h2><?php echo $userType == 'employer' ? 'Your Posts' : 'Applications'?></h2>
        </div>
      </div>

      <!-- Info for right side bar  -->
      <div id="rightSideBarMain">

        <!-- appliaction container  -->
        <div id="applicationsContainer" class="rightSideBarSections">
          <div id="applicationBoxContainer">
            <script>
              createJobCardInitialize("applicationBoxContainer", { "owner" : null});
            </script>
          </div>
        </div>    
        <!-- Settings container -->
        <div id="settingsContainer" class="rightSideBarSections">
          <script>
            createSettingSelectElements("Add Email", "email", "email", "inputBox" , false ,"PreferencePopup", "Set your email!", ["Email..."], "auto");
            createSettingSelectElements("Add Phone Number", "pnumber", "phonenumber", "inputBox" , false ,"PreferencePopup", "Set your phone number!", ["Your number..."], "auto");
            createSettingSelectElements("Add Name", "name", "name", "inputBox" , false ,"PreferencePopup", "Set your name!", ["Your name..."], "auto");
            <?php
              if ($userType == "student") {
                echo '
                  createSettingSelectElements("Add Job Titles", "jobTitles", "preferedjobtitles", "searchList", true ,"PreferencePopup", null , null ,"auto");
                  createSettingSelectElements("Add Job Types", "jobTypes", "preferedjobtypes", "checkbox", false ,"PreferencePopup", "Pick Prefered Job Types", null, "auto");
                  createSettingSelectElements("Add Prefered Days", "jobDays", "preferedjobdays", "checkbox" , false ,"PreferencePopup", "Pick Prefered Days", null , "auto");
                ';
              }
            ?>
            // creates a button that will let the user to edit any user table collume
            // parm 1 @ header - string - set the header of the button. aka the name that will show up for the button
            // parm 2 @ elName - string - set the id of the button
            // parm 3 @ colName - string - the name of the collume that will be edited
            // parm 4 @ type - string - the type of button that will be created: checkbox, searchList, searchListWithHeaders, inputBox.
            // parm 5 @ ifSearchBar - boolean - if the popup will have a search bar
            // parm 6 @ popupName - string - the name of the popup that will be created. This has to be the same name as you give to createSettingselectPopups() function
            // parm 7 @ popupHeader - string - the header of the popup that will be created. place null if you dont want a header.
            // parm 8 @ loadAllData - array / object - if you want to load possible options that the user can choose from. null if you dont want to load possible options.
            // parm 9 @ loadChosenData - array - if you want to load the data that the user has chosen before. null if you dont want to load the data.
           
            //createSettingselectElements("Input box", "inputJob", "rand", "inputBox", false ,"PreferencePopup", "Input Stuff",);
            // create popupElement
            // parm 1 @ elName - string - the id of the popup / name of the popup
            createSettingSelectPopups("PreferencePopup");
          
          </script>
          
        </div>

        <!-- Contact container  -->
        <div id="contactContainer" class="rightSideBarSections">
          <div class="contactItems">Contact us @adsjakldjdsa</div>
        </div>

        <!-- Tutorials container  -->
        <div id="tutorialsContainer" class="rightSideBarSections">
          <div class="tutorialsItems">Tutorials for how to: </div>
        </div>

      </div>
    </div>

  </div>
  
  <div id="placeHolderSection">
    
  </div>

  <script src="sHome.js"></script>
  <script src="../allFunctions/createNavBar/createNavBar.js"></script>
</body>

</html>