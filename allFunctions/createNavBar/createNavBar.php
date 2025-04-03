<?php

    function createNavBar($currentPage){

        $currentLargeNavDiv = 'id="currentlyOn"';
        $notCurrentLargeNavDiv = 'onmouseenter="navHoverOn(this)" onmouseleave="navHoverOff()"';

        $currentSmallNavDiv = 'id="currentlyOnBurgerNav"';

        echo '
        <div id="navBar">
            <div id="largeNav">
                <div id="navButtons">
                    <div '. ( "home" == $currentPage ? $currentLargeNavDiv : $notCurrentLargeNavDiv) .'><a href="../Home/">Home</a></div>
                    <div '. ( "search" == $currentPage ? $currentLargeNavDiv : $notCurrentLargeNavDiv) .'><a href="../Search/">Find Jobs</a></div>
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
                    <a href="../Search/"><div '. ( "search" == $currentPage ? $currentSmallNavDiv : '') .'>Find Jobs</div></a>
                </div>
            </div>
        </div>
        ';
        
    }
    
?>