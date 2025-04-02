<?php
  include("../allFunctions/createNavBar/createNavBar.php");
  include_once("../allFunctions/connectPDO.php");

  $indexPDO = connectedPDO();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>replit</title>
    <link rel="stylesheet" href="aHome.css" >
    <link rel="stylesheet" href="../allFunctions/createJobCards/createJobCards.css">
  
    <!-- for nav  -->
    <link rel="stylesheet" href="../allFunctions/createNavBar/createNavBar.css">

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
          <!-- search filters -->
          <select name="Job Type">
            <option value = "computer science">Computer Science</option>

          </select>
          
        </div>
        <!-- search results or right side bar on big screens -->
        <div id="searchSectionResultsContainer">

          

          
        </div>
      </div>
      
    </div>
    
  
    <script source="aHome.js"></script>
    <script src="../allFunctions/createNavBar/createNavBar.js"></script>
  </body>
</html>