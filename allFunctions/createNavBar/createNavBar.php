<?php
function createNavBar($currentPage){
    $userType = returnUserType();
    $currentLargeNavDiv = 'id="currentlyOn"';
    $notCurrentLargeNavDiv = 'onmouseenter="navHoverOn(this)" onmouseleave="navHoverOff()"';

    $currentSmallNavDiv = 'id="currentlyOnBurgerNav"';

    $searchText = ($userType == 'student') ? 'Find Jobs' : 'All Job Posts';
    
    $employerNavItem = '';
    $employerOverlayItem = '';
    if ($userType == 'employer') {
        $employerNavItem = '<div ' . ($currentPage == "create" ? $currentLargeNavDiv : $notCurrentLargeNavDiv) . '><a href="../Create/">Create Posts</a></div>';
        $employerOverlayItem = '<a href="../Create/"><div ' . ($currentPage == "create" ? $currentSmallNavDiv : '') . '>Create Posts</div></a>';
    }

    echo '
    <div id="navBar">
        <div id="largeNav">
            <div id="navButtons">
                <div '. ( "home" == $currentPage ? $currentLargeNavDiv : $notCurrentLargeNavDiv) .'><a href="../Home/">Home</a></div>
                <div '. ( "search" == $currentPage ? $currentLargeNavDiv : $notCurrentLargeNavDiv) .'><a href="../Search/">' . $searchText . '</a></div>
                '.$employerNavItem.'
                <div id="logoutBtn" class="logout-btn">
                    <a href="../allFunctions/createNavBar/logout.php" onclick="return confirm(\'Are you sure you want to log out?\')">Logout</a>
                </div>
            </div>
        </div>
        <div id="smallNav">
            <div id="burgerNav">
                <div id="burger"></div>
            </div>
        </div>
        <div id="overlayContainer">
            <div id="burgerOverlay">
                <a href="../Home/"><div '.( "home" == $currentPage ? $currentSmallNavDiv : '') .'>Home</div></a>
                <a href="../Search/"><div '. ( "search" == $currentPage ? $currentSmallNavDiv : '') . '>' . $searchText . '</div></a>
                '.$employerOverlayItem.'
                <a href="../allFunctions/createNavBar/logout.php" onclick="return confirm(\'Are you sure you want to log out?\')"><div id="logoutBtn" class="logout-btn" >Logout</div></a>
            </div>
        </div>
    </div>
    ';
}
?>
