<?php
  include("../allFunctions/createNavBar/createNavBar.php");
  include_once("../allFunctions/connectPDO.php");
  include_once("../allFunctions/checks/checkLogin.php");
  include_once("../allFunctions/checks/fetchUserInfo.php");

  $indexPDO = connectedPDO();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Job Posts</title>
    <link rel="stylesheet" href="aHome.css" >
    <link rel="stylesheet" href="../allFunctions/createJobCards/createJobCards.css">
  
    <!-- for nav  -->
    <link rel="stylesheet" href="../allFunctions/createNavBar/createNavBar.css">

    <!-- for cards -->
    <script src="../allFunctions/createJobCards/createJobCards.js"></script>
    
  </head>
  <body>
    
    <?php
      createNavBar("search");
    ?>

    <div id="searchSection">
      <!-- search bar -->
      <div id="searchSectionSearchBarContainer">

        <!-- search bar background  -->
        <div id=searchSectionSearchBarContainerBackground>
          <svg>
            <defs>
              <linearGradient id="waveGradient" x1="0" x2="0" y1="1" y2="0">
                <stop offset="70%" stop-color="rgb(255, 255, 255)" />
                <stop offset="100%" stop-color="rgba(255, 255, 255, 0)" />
              </linearGradient>
            </defs>
          </svg>

          <svg class="waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
              <path fill="url(#waveGradient)" d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"></path>
          </svg>
        </div>

        <!-- actual search bar -->
        <input type="text" id="searchSectionSearchBarContainerSearchBar" placeholder="Search Job...">
    
      </div>
      
      <!-- under search bar  -->
      <div id="searchSectionMainContainer">
        <!-- search filter container or left side bar on big screen -->
        <div id="searchSectionAdditionalFilters">
          <div id="searchSectionAdditionalFiltersContainer">
            <!-- search filters -->
            <label  for="jobTitle">Job Titles:</label>
            <input id="jobTitleInput" style="width: calc(100% - 20px);" list="jobTitleData" name="jobTitle" >
            <datalist id="jobTitleData" required>
                <?php
                    $jobTitlesQuery = $indexPDO->query("SELECT DISTINCT jobtitles FROM SettingsOptions WHERE jobtitles IS NOT NULL");
                    while ($jobTitle = $jobTitlesQuery->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=\"{$jobTitle['jobtitles']}\">{$jobTitle['jobtitles']}</option>";
                    }
                ?>
            </datalist>
            <div id="jobTitleErrorText"></div>

            <label for="jobType">Job Type:</label>
            <select id="jobType" name="jobType" required>
              <option value=""></option>
                <?php
                    $jobTypesQuery = $indexPDO->query("SELECT DISTINCT jobtypes FROM SettingsOptions WHERE jobtypes IS NOT NULL");
                    while ($jobType = $jobTypesQuery->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=\"{$jobType['jobtypes']}\">{$jobType['jobtypes']}</option>";
                    }
                ?>
            </select>

            <label for="days">Work Days:</label>
            <div id="days">
                <?php
                    // Fetch distinct work days from the database and generate checkboxes
                    $workDaysQuery = $indexPDO->query("SELECT DISTINCT jobdays FROM SettingsOptions WHERE jobdays IS NOT NULL");
                    while ($workDay = $workDaysQuery->fetch(PDO::FETCH_ASSOC)) {
                        $workDayName = htmlspecialchars($workDay['jobdays']);
                        echo "<label><input type='checkbox' name='days[]' value='$workDayName'> $workDayName</label><br>";
                    }
                ?>
            </div>
          </div>
          
        </div>
        <!-- search results or right side bar on big screens -->
        <div id="searchSectionResultsContainer">

          
        </div>
      </div>
      
    </div>
    
  
    <script src="aHome.js"></script>
    <script src="../allFunctions/createNavBar/createNavBar.js"></script>
  </body>
</html>