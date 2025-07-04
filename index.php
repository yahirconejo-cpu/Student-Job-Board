<?php

  include_once("./allFunctions/connectPDO.php");

  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  // Check to see if they are already signed in
  if(isset($_SESSION['sessioncode']) && isset($_SESSION['userid'])){
    $myPDO = connectedPDO();

    $ifSessionFound = $myPDO->prepare("SELECT id FROM Session WHERE userid = :userid AND sessioncode = :sessioncode AND expirationdate > datetime('now')");
    $ifSessionFound->execute([
        ':userid' => $_SESSION['userid'],
        ':sessioncode' => $_SESSION['sessioncode']
    ]);
    
    $sessionExists = $ifSessionFound->fetch(PDO::FETCH_ASSOC);

    if ($sessionExists) {
        // If already signed in
        header("Location: ./Home/");
        die();
    }

    

  }

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="home.css" rel="stylesheet">
  </head>
  <body>
    <!-- <div id="mousePointer"></div> -->
    <div id="section1">
      <div id="background">
        <div id="rockLayer">
          <!-- Rock Templets -->
          <!-- <div class="rockDivCont">
            <div class="rock1Piece1"></div>
            <div class="rock1Piece2"></div>
            <div class="rock1Piece3"></div>
            <div class="rock1Piece4"></div>
            <div class="rock1Piece5"></div>
            <div class="rock1Piece6"></div>
          </div>
          <div class="rockDivCont">
            <div class="rock2Piece1"></div>
            <div class="rock2Piece2"></div>
            <div class="rock2Piece3"></div>
            <div class="rock2Piece4"></div>
            <div class="rock2Piece5"></div>
            <div class="rock2Piece6"></div>
          </div>
          <div class="rockDivCont">
            <div class="rock3Piece1"></div>
            <div class="rock3Piece2"></div>
            <div class="rock3Piece3"></div>
            <div class="rock3Piece4"></div>
            <div class="rock3Piece5"></div>
          </div> -->

          
          
        </div>
        <div id="fishLayer">
          <!-- fish template -->
        </div>
        <div id="oceanLayer">
          <div id="waveCont">
            <!-- defs for waves -->
            <!-- <svg>
              <defs>
                <linearGradient id="waveGradient" x1="0" x2="0" y1="1" y2="0">
                  <stop offset="0%" stop-color="var(--waveGradientsStart)" />
                  <stop offset="70%" stop-color="var(--waveGradientsStop)" />
                </linearGradient>
                <linearGradient id="waveShadowGradient" x1="0" x2="0" y1="1" y2="0">
                  <stop offset="0%" stop-color="var(--waveShadowGradientsStart)" />
                  <stop offset="70%" stop-color="var(--waveShadowGradientsStop)" />
                </linearGradient>
              </defs>
            </svg>
      
            <svg class="waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path>
            </svg>
            <svg class="wavesShadow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
              <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path>
            </svg> -->
          </div>
        </div>
      </div>
      <div id="signInLayer">
        <div id="signInDiv">
          <h3>Sign In</h3>
          <div class="signInInputTitle">Username:</div>
          <input type="text" placeholder="Username..." id="username" class="signInInput">
          <div class="signInInputTitle">Password:</div>
          <input type="password" placeholder="Password..." id="password" class="signInInput">
          <button id="signInButton" onclick="userSignIn();">Sign In</button>
        </div>
      </div>
    </div>
    <!-- <div id="section2">
      <div id="section2InfoText">
          <h1>Trouble Signing in?</h1>
          <p>Contact your school cousuler</p>
          <h1>Want to Sign up?</h1>
          <p> Are you a company and need some extra help? You can contact (school name) at (email) to put job postings</p>
      </div>
    </div> -->
    <script src="allFunctions/Sanitization/checkValidInputs.js"></script>
    <script src="home.js"></script>
  </body>
</html>