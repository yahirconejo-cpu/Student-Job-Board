
// Everything for the background MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM
// ********************** Makes rocks ***************************************
// all diffrent rocks. Key - rock num. Value - number of rock parts
rockList = { 1: 6, 2: 6, 3: 5 };


// parm 1 @ transX - int - offset from center X direction
// parm 2 @ transY - int - offset from center Y direction
function createRock(transX = 0, transY = 0) {
    randRockNum = Object.keys(rockList)[Math.floor(Math.random() * (Object.keys(rockList).length))];
    randRockNumPartsLength = rockList[randRockNum];

    // random rock rotation
    randRotation = Math.floor(Math.random() * (360 + 1));
    randScale = (Math.random() * (1.5 - 0.7)) + 0.7;


    // rock container
    rockContElement = document.createElement("div");
    rockContElement.classList.add("rockDivCont");
    rockContElement.style.transform = `translate(${transX}px,${transY}px) rotate(${randRotation}deg) scale(${randScale})`;

    for (var r = 1; r <= randRockNumPartsLength; r++) {
        // rock parts
        rockElement = document.createElement("div");
        rockElement.classList.add("rock" + randRockNum + "Piece" + r);
        rockContElement.append(rockElement);
    }

    document.getElementById("rockLayer").append(rockContElement);
}


// ******************** Populates area with rocks ******************************

// parm 1 @ areaWidth - int - input width of area in px
// parm 2 @ areaHeight - int - input height of area in px
// parm 3 @ gapBetObjects - int - the spacing between each rock in px
// parm 4 @ randomDistVary - int - distance the objects will vary from the *** parm 3 @ gapBetObjects *** in px

function populateWithRocks(areaWidth, areaHeight, gapBetObjects = 200, randomDistVary = 0) {

    var randomDistX;
    var randomDistY;

    for (var x = 0; x < areaWidth; x += gapBetObjects) {
        for (var y = 0; y < areaHeight; y += gapBetObjects) {

            randomDistX = Math.floor(Math.random() * (randomDistVary)) * (Math.random() < 0.5 ? -1 : 1);
            randomDistY = Math.floor(Math.random() * (randomDistVary)) * (Math.random() < 0.5 ? -1 : 1);

            createRock((x - (areaHeight / 2)) + randomDistX, (y - (areaWidth / 2)) + randomDistY);


        }
    }
}

populateWithRocks(window.innerWidth, window.innerHeight, 200, 200);

// ********************************* Wave maker *************************************

// parm 1 @ waveWidth - int - set the width of the wave in px
// parm 2 @ waveAmpitude - int - set the ampitude of the wave in px
// parm 3 @ wavePartsWidth - int - set the individual elements that make up the wave width
// parm 4 @ wavePartsHeight - int - set the individual elements that make up the wave height
// parm 5 @ wavePeriod - int - set how many periods are made in the div AKA. how many up and downs the wave funstion should make
// parm 6 @ percentOpacity - int: 0-1 - defaults to 0. Sets how much from each end of the wave is going to have an opacity ranging from 0-1 from the end of the wave to the center
function createWave(waveWidth, waveAmpitude, wavePartsWidth, wavePartsHeight, wavePeriod, percentOpacity = 0) {
    var waveCont = document.createElement("div");
    waveCont.classList.add("createdWave");
    waveCont.style.width = `${waveWidth}px`;
    waveCont.style.height = `${waveAmpitude}px`;
    numOfOpacityElements = Math.floor(Math.ceil(waveWidth / wavePartsWidth) * percentOpacity);

    for (var i = 0; i < waveWidth; i += wavePartsWidth) {
        let wavePart = document.createElement("div");
        wavePart.classList.add("createdWavePart");
        wavePart.style.width = `${wavePartsWidth}px`;
        wavePart.style.height = `${wavePartsHeight}px`;
        wavePart.style.transform = `translateY(${(Math.sin(i * ((1 / (waveWidth * (1 / wavePeriod))) * 2 * Math.PI)) * ((waveAmpitude / 2)))}px)`;
        waveCont.append(wavePart);

        wavePart.addEventListener("mouseenter", (e) => {
            onMouseWaveEvent(e.target, numOfOpacityElements);
        });
    }

    // wave opacity on cutoff
    if (numOfOpacityElements > 0) {
        var curOpacity = 0
        Object.values(waveCont.children).slice(0, numOfOpacityElements).forEach(x => {
            x.style.opacity = `${curOpacity}`;
            curOpacity += 1 / numOfOpacityElements;
        });
        curOpacity = 0;
        Object.values(waveCont.children).slice(-numOfOpacityElements).reverse().forEach(x => {
            x.style.opacity = `${curOpacity}`;
            curOpacity += 1 / numOfOpacityElements;
        });

    }

    return {
        itself: waveCont,
        changeWave: function(newwaveAmpitude, newWavePeriod) {
            var waveIntervalIncrease = 0;
            var waveChilderen = waveCont.children;
            for (var i = 0; i < waveChilderen.length; i++) {
                waveIntervalIncrease += wavePartsWidth;
                waveChilderen[i].style.transform = `translateY(${(Math.sin(waveIntervalIncrease * ((1 / (waveWidth * (1 / newWavePeriod))) * 2 * Math.PI)) * ((newwaveAmpitude / 2) - (wavePartsHeight / 2)))}px)`;
            }
        },
        stopChangeWave: function() {
            Object.values(waveCont.children).forEach(x => {
                var compute = getComputedStyle(x);
                var transform = compute.transform;
                x.style.transition = "none";

                x.style.transform = transform;

                // causes reflow
                void x.offsetHeight;
            });
        },
        freezeWaveElement: function(waveElement = "everyThing") {
            if (waveElement == "everyThing") {
                var compute = getComputedStyle(waveCont);
                var transform = compute.transform;
                waveCont.style.transition = "none";

                waveCont.style.transform = transform;

                // causes reflow
                void waveCont.offsetHeight;
            } else {
                var compute = getComputedStyle(waveElement);
                var topVal = compute.top;
                var leftVal = compute.left;
                waveElement.style.top = topVal;
                waveElement.style.left = leftVal;
                waveElement.style.position = "absolute";

                void waveElement.style.offsetHeight;
            }
        }

    }
}


// parm 1 @ createWaveDiv - function - the return value of createWave() 
// parm 2 @ totalTime - int - the total time in seconds that it takes the wave to go across the screen
// parm 3 @ howManyAn - int - how many animations the wave will go though with the amount of totalTime given
// parm 4 @ minAmpitude - int - the minimum ampitude the wave can change to
// parm 5 @ maxAmpitude - int - the maximum ampitude the wave can change to
// parm 6 @ minPeriod - int - the minimum period the wave can change to 
// parm 7 @ maxPeriod - int - the maximum period the wave can change to 
// parm 8 @ percentVari - int : 0-1 - the amount in percentage 0 (0%) to 1 (100%) the time it takes from each animation from the avarge (how much it varys if it was divided evenly)
function waveAmpitudeAndPeriodChange(createWaveDiv, totalTime, howManyAn, minAmpitude, maxAmpitude, minPeriod, maxPeriod, percentVari) {
    if (howManyAn > 0) {
        var averageTime = totalTime / howManyAn
        var ranIntervalTime = ((Math.random() * ((averageTime * (1 + percentVari)) - (averageTime * (1 - percentVari)))) + (averageTime * (1 - percentVari)));
        totalTime -= ranIntervalTime;
    } else {
        var ranIntervalTime = totalTime
    }
    var ranAmpitude = Math.random() * (maxAmpitude - minAmpitude) + minAmpitude;
    var ranPeriod = Math.random() * (maxPeriod - minPeriod) + minPeriod;

    Object.values(createWaveDiv.itself.children).forEach(x => {
        x.style.transition = `transform ${ranIntervalTime}s linear`;
        void x.offsetHeight;
    });

    createWaveDiv.changeWave(ranAmpitude, ranPeriod);


    if (howManyAn > 0) {
        setTimeout(() => {
            waveAmpitudeAndPeriodChange(createWaveDiv, totalTime, howManyAn - 1, minAmpitude, maxAmpitude, minPeriod, maxPeriod, percentVari);
        }, ranIntervalTime * 1000);
    } else {
        return;
    }
}

// parm 1 @ minSpeed - int - the min time in seconds for the wave to go across the screen
// parm 2 @ maxSpeed - int - the max time in seconds for the wave to go across the screen
// parm 3 @ minIntervalTime - int - the minimum time in seconds before a new wave is created by adding this your create a loop
// parm 4 @ maxIntervalTime - int - the maximum time in seconds before a new wave is created by adding this you create a loop
// parm 5 @ ifWave - Boolean - Gives the wave an animation which changes the amptiude and period of it as it goes by
function waveMovement(minSpeed, maxSpeed, minIntervalTime = null, maxIntervalTime = null, ifWaveAn = false) {

    var randomWaveSpeed = (Math.random() * (maxSpeed - minSpeed) + minSpeed);


    // Wave creation 
    var windowDiagonalLength = Math.sqrt(Math.pow(window.innerWidth, 2) + Math.pow(window.innerHeight, 2));
    windowDiagonalLength = (windowDiagonalLength > 700 ? 700 : windowDiagonalLength); // set max wave length -----------------------------------------------
    var createdWaveWidth = Math.floor(Math.random() * (windowDiagonalLength - 300) + 300);
    var createdWavePeriod = ((Math.random() * (2.5 - 1.2)) + 1.2);
    var createdWaveAmpitude = (createdWaveWidth * 0.15) / (createdWavePeriod);
    var createdWavePartHeight = Math.random() * (150 - 80) + 80;
    var createdWave = createWave(createdWaveWidth, createdWaveAmpitude, 2, createdWavePartHeight, createdWavePeriod, 0.2);

    // wave initial placement 
    createdWave.itself.style.transform = `translate(${100}vw, ${100}vh) rotate(${-45}deg)`;

    createdWave.itself.style.transition = `transform ${randomWaveSpeed}s linear`;

    document.getElementById("waveCont").append(createdWave.itself);

    // causes reflow
    void createdWave.itself.offsetHeight;


    // wave end position
    createdWave.itself.style.transform = `translate(-50%,-50%) rotate(${-45}deg)`;

    // deletes Wave after it gets to destination
    setTimeout(() => {
        createdWave.itself.remove();
    }, randomWaveSpeed * 1000);

    // wave amptitude and period animation
    if (ifWaveAn) {
        // sets how many animations it does as it goes across
        var randWaveAn = Math.floor(Math.random() * (4 - 1) + 1);

        waveAmpitudeAndPeriodChange(createdWave, randomWaveSpeed, randWaveAn, createdWaveAmpitude * 0.7, createdWaveAmpitude * 1.7, createdWavePeriod - 1, createdWavePeriod, 0.3);
    }

    // spawns a created wave
    if (maxIntervalTime != null && minIntervalTime != null) {
        var randomWaveInterval = (Math.random() * (maxIntervalTime - minIntervalTime) + minIntervalTime) * 1000;
        setTimeout(() => {
            waveMovement(minSpeed, maxSpeed, minIntervalTime, maxIntervalTime, ifWaveAn);
        }, randomWaveInterval);
    }

}

waveMovement(10, 15, 10, 20, true);
waveMovement(10, 15, 10, 20, true);



//  ******************************** Fish maker *************************************

// // parm 1 @ fishWidth - int - the width the fish is made in px
// // parm 2 @ fishWidth - int - the height the fish is made in px
// // parm 3 @ fishColor - 
// function createFish(fishWidth, fishHeight, fishColor){

// }

// // parm 1 @ numOfFish - number of fish to spawn
// function fishMaker(numOfFish){

// }


// ******************************** mouse interactions with wave *******************************


// mouse interact with wave __________________________________________________________________________
// startes the whole wave interact sequence
// parm 1 @ element - what element the mouse touched or where the wave effect starts
// parm 2 @ numOfOpacityElements - how far is the ripple effect from the element that innitally started it
function onMouseWaveEvent(element, numOfOpacityElements) {

    // Mouse collison with Wave
    var timeItTakesAfterHitToRemove = 0.5

    onWaveElementHoverStop(element, timeItTakesAfterHitToRemove);

    // Get Neighboring Waves

    var elementIndex = Array.from(element.parentElement.children).indexOf(element);

    var howFarDominoEffect = numOfOpacityElements;

    var waitTime = 10;

    // makes sure waves hit cant get hit again
    Array.from(element.parentElement.children).slice((elementIndex - howFarDominoEffect) >= 0 ? (elementIndex - howFarDominoEffect) : 0, (elementIndex + howFarDominoEffect) < element.parentElement.children.length ? (elementIndex + howFarDominoEffect) : (element.parentElement.children.length - 1)).forEach(x => {
        x.style.pointerEvents = "none";
    });


    setTimeout(() => {
        dominoEffectOnWaveHover(element.parentElement, elementIndex, 1, howFarDominoEffect, waitTime, numOfOpacityElements / 2, timeItTakesAfterHitToRemove);
    }, waitTime);

}
// creates a copy of the designated wave element and makes it stay in place so that it lookes like the user made it stop moving
// parm 1 @ element - what element you want to freeze in the wave effect
// parm 2 @ timeItTakesAfterHitToRemove - how long it takes for the element chosen to fully disapear or fade away
function onWaveElementHoverStop(element, timeItTakesAfterHitToRemove) {

    var topVal = element.getBoundingClientRect().top;
    var leftVal = element.getBoundingClientRect().left;


    var newElement = element.cloneNode(false);

    newElement.style.transition = `opacity ${timeItTakesAfterHitToRemove}s linear`;

    newElement.style.top = topVal + window.scrollY + "px";
    newElement.style.left = leftVal + "px";

    newElement.style.position = "absolute";

    newElement.style.transform = `rotate(${element.parentElement.style.transform.split(")")[1].split("(")[1]})`;

    void newElement.offsetHeight;

    setTimeout(() => {
        newElement.style.opacity = "0";
        setTimeout(() => {
            newElement.remove();
        }, (timeItTakesAfterHitToRemove * 1000));
    }, 10);



    document.getElementById("background").appendChild(newElement);

    element.style.opacity = "0";
    element.pointerEvents = "none";
}

// loops through the wave elements and makes them fade away creating a hole
// parm 1 @ parentElement - the parent element of the element that is going to be affected by the domino effect aka the wave
// parm 2 @ elementIndex - what index the wave effect thats going to act as the center is at (based on the parent Element)
// parm 3 @ start - addes to the element Index to find witch element is going to be affected by the domino effect
// parm 4 @ end - how far the domino effect is going to go
// parm 5 @ waitTime - how long it takes for the next element to be affected
// parm 6 @ newCutoffOpacityRange - after the wave effect finishes there is now a hole or cutoff in the wave so this determains the range of opacity that the new cutoff is going to have so it fades better
// parm 7 @ timeItTakesAfterHitToRemove - how long it takes for the element chosen to fully disapear
function dominoEffectOnWaveHover(parentElement, elementIndex, start, end, waitTime, newCutoffOpacityRange, timeItTakesAfterHitToRemove) {
    var changePerLoop = (waitTime * 0.9) / (end - start);
    var changeInTimeToRemove = (timeItTakesAfterHitToRemove * 0.9) / (end - start);
    if (elementIndex - start >= 0) {
        onWaveElementHoverStop(parentElement.children[elementIndex - start], timeItTakesAfterHitToRemove - changeInTimeToRemove);
    }

    if (elementIndex + start < parentElement.children.length) {
        onWaveElementHoverStop(parentElement.children[elementIndex + start], timeItTakesAfterHitToRemove - changeInTimeToRemove);

    }

    if (start <= end) {
        setTimeout(() => { dominoEffectOnWaveHover(parentElement, elementIndex, start + 1, end, waitTime - changePerLoop, numOfOpacityElements, timeItTakesAfterHitToRemove - changeInTimeToRemove) }, waitTime);
    }
    if (start == end) {
        waveCutoffOpacityChange(parentElement.children[elementIndex], end, newCutoffOpacityRange);
    }

}

// smoothes out the end of the wave aka the new cutoff or hole created by the domino effect
// parm 1 @ effectCenter - refers to the center of the dominoEffectOnWaveHover() function
// parm 2 @ effectStart - how far from the center did the dominoEffectOnWaveHover() function end
// parm 3 @ effectEnd - how far the center of the dominoEffectOnWaveHover() function does the opacity change end
function waveCutoffOpacityChange(effectCenter, effectStart, effectEnd) {

    var effectCenterIndex = Array.from(effectCenter.parentElement.children).indexOf(effectCenter);
    // left side opacity for new wave cutoff
    var newCutoffLeftElements = Array.from(effectCenter.parentElement.children).slice((effectCenterIndex - effectStart - effectEnd) >= 0 ? (effectCenterIndex - effectStart - effectEnd) : 0, (effectCenterIndex - effectStart) >= 0 ? (effectCenterIndex - effectStart) : 0).reverse();

    if (newCutoffLeftElements.length != 0) {
        var newCutoffOpacityLeftStart = 0;
        var newCutoffOpacityLeftChange = Number(window.getComputedStyle(newCutoffLeftElements[newCutoffLeftElements.length - 1]).opacity) / newCutoffLeftElements.length;

        function leftWaveAnCutoff(index) {
            newCutoffOpacityLeftStart += newCutoffOpacityLeftChange;
            if (index >= newCutoffLeftElements.length) {
                return false
            } else {
                newCutoffLeftElements[index].style.opacity = newCutoffOpacityLeftStart;
                return true
            }

        }
    } else {
        function leftWaveAnCutoff(index) {
            return false;
        }
    }

    // right side opacity for new wave cutoff
    var newCutoffRightElements = Array.from(effectCenter.parentElement.children).slice((effectCenterIndex + effectStart) < effectCenter.parentElement.children.length ? (effectCenterIndex + effectStart) : (effectCenter.parentElement.children.length - 1), (effectCenterIndex + effectStart + effectEnd) < effectCenter.parentElement.children.length ? (effectCenterIndex + effectStart + effectEnd) : (effectCenter.parentElement.children.length - 1));

    if (newCutoffRightElements.length != 0) {
        var newCutoffOpacityRightStart = 0;
        var newCutoffOpacityRightChange = Number(window.getComputedStyle(newCutoffRightElements[newCutoffRightElements.length - 1]).opacity) / newCutoffRightElements.length;

        function rightWaveAnCutoff(index) {
            newCutoffOpacityRightStart += newCutoffOpacityRightChange;
            if (index >= newCutoffRightElements.length) {
                return false
            } else {
                newCutoffRightElements[index].style.opacity = newCutoffOpacityRightStart;
                return true
            }

        }
    } else {
        function rightWaveAnCutoff(index) {
            return false;
        }
    }


    var index = 0;
    var multCheck = [true, true];
    var myCutOffInterval = setInterval(() => {
        multCheck[0] = leftWaveAnCutoff(index);
        multCheck[1] = rightWaveAnCutoff(index);
        if (!multCheck[0] && !multCheck[1]) {
            clearInterval(myCutOffInterval);
        }
        index++
    }, 5);
}

// end of all background script MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM

// ************************ make shadow for sign in box move *****************************

// the div that has the shadow that had to move
var signInDiv = document.getElementById("signInDiv");

// parm 1 @ mouseXPos - the x position of the mouse
// parm 2 @ mouseYPos - the y position of the mouse
// parm 3 @ offsetFromElementEdge - how far from the box center is max distance the mouse can be from the box center till it reaches maxOffsetOfShadow or the max shadow distance
// parm 4 @ minOffsetOfShadow - the min shadow distance x-direction and y-direction the box-shadow can be (same as putting that value in the css so negative is in the negative direction) 
// parm 5 @ maxOffsetOfShadow - the max shadow distance x-direction and y-direction the box-shadow can be (same as putting that value in the css)
function signInDivShadowMove(mouseXPos, mouseYPos, offsetFromElementEdge, minOffsetOfShadow, maxOffsetOfShadow){
    // Get the bounding box of the div
    var rect = signInDiv.getBoundingClientRect();

    // Calculate the center of the div
    var centerX = rect.x + 55 + (rect.width / 2);
    var centerY = rect.y + 57 + (rect.height / 2);

    // Calculate the offset of the mouse from the center
    var offsetX = mouseXPos - centerX;
    var offsetY = mouseYPos - centerY;

    // Normalize the offset to a range of -1 to 1
    var normalizedX = Math.max(-1, Math.min(1, offsetX / offsetFromElementEdge));
    var normalizedY = Math.max(-1, Math.min(1, offsetY / offsetFromElementEdge));

    // get value in px
    var shadowX = minOffsetOfShadow + (normalizedX + 1) / 2 * (maxOffsetOfShadow - minOffsetOfShadow);
    var shadowY = minOffsetOfShadow + (normalizedY + 1) / 2 * (maxOffsetOfShadow - minOffsetOfShadow);


    signInDiv.style.boxShadow = `${-shadowX}px ${-shadowY}px 17px 2px rgba(11, 15, 39, 0.6)`;

}

document.addEventListener("mousemove", (e)=>{
    signInDivShadowMove(e.clientX, e.clientY, 400, -10, 10);
});


function userSignIn(){
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    let checkInputs =  checkUN(username) + checkPW(password);
    if(checkInputs.trim() != ""){
        alert(checkInputs+ "cool");
    }else{
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "login.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText);
            }
        };
        xhr.send(`username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`);
    }
}