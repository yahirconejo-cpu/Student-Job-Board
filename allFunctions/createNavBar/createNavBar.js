// ********************************* nav *********************************

function navHoverOn(newTab) {

    var navButtons = document.getElementById("navButtons");
    var newTabWidth = (newTab.offsetWidth - 10) / navButtons.offsetWidth;
    navButtons.style.setProperty("--_opaTrans", 200 + "ms")

    navButtons.style.setProperty("--_opa", 1);
    navButtons.style.setProperty("--_width", newTabWidth);
    navButtons.style.setProperty("--_left", newTab.offsetLeft + 5 + "px");

}

function navHoverOff() {
    var navButtons = document.getElementById("navButtons");
    navButtons.style.setProperty("--_opa", 0);
    navButtons.style.setProperty("--_opaTrans", 600 + "ms")
}


var burgerNav = document.getElementById("burgerNav");
var burgerOverlay = document.getElementById("burgerOverlay");
var burgerOverlayA = document.querySelectorAll("#burgerOverlay a");
var burgerOverlayDiv = document.querySelectorAll("#burgerOverlay div");
var burgerNavOpen = false;

function burgerNavClick() {
    if (burgerNavOpen) {
        burgerNav.classList.remove("BNopen");
        burgerOverlay.style.maxHeight = "0px";
        burgerOverlay.style.borderTop = "none";
        for (var i = 0; i < burgerOverlayA.length; i++) {
            burgerOverlayA[i].style.pointerEvents = "none";
            burgerOverlayDiv[i].style.opacity = "0";
        }
        burgerNavOpen = false;
    } else if (!burgerNavOpen) {
        burgerNav.classList.add("BNopen");
        burgerOverlay.style.maxHeight = "1000px";
        burgerOverlay.style.borderTop = "2px solid gray";
        for (var i = 0; i < burgerOverlayA.length; i++) {
            burgerOverlayA[i].style.pointerEvents = "auto";
            burgerOverlayDiv[i].style.opacity = "1";
        }

        burgerNavOpen = true;
    }
}

burgerNav.addEventListener("click", burgerNavClick);

window.onresize = function() {
    if (window.innerWidth >= 650 && burgerNavOpen == true) {
        burgerNavClick();
    }
}