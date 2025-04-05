// ********************************* Wave maker *************************************
const parentOfWave = document.getElementById("sideBarLeftBackground");

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
    var windowDiagonalLength = Math.sqrt(Math.pow(parentOfWave.getBoundingClientRect().width, 2) + Math.pow(parentOfWave.getBoundingClientRect().height, 2));
    var createdWaveWidth = Math.floor(Math.random() * (windowDiagonalLength - 300) + 300);
    var createdWavePeriod = ((Math.random() * (2.5 - 1.2)) + 1.2);
    var createdWaveAmpitude = (createdWaveWidth * 0.15) / (createdWavePeriod);
    var createdWavePartHeight = Math.random() * (150 - 80) + 80;
    var createdWave = createWave(createdWaveWidth, createdWaveAmpitude, 1, createdWavePartHeight, createdWavePeriod, 0.2);

    // wave initial placement 
    createdWave.itself.style.transform = `translate(${parentOfWave.getBoundingClientRect().width}px, ${parentOfWave.getBoundingClientRect().height}px) rotate(${-45}deg)`;

    createdWave.itself.style.transition = `transform ${randomWaveSpeed}s linear`;

    parentOfWave.append(createdWave.itself);

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

//  wave end ----------------------------------------------------------------------------------

// Does all the clicking buttons and stuff

document.addEventListener("DOMContentLoaded", function() {

    const sidebarButtons = document.querySelectorAll(".leftSideBarItems");

    sidebarButtons.forEach(button => {

        button.addEventListener("click", function() {
            document.querySelectorAll(".leftSideBarItems").forEach(btn => {
                btn.classList.remove("selected");
                document.getElementById(btn.name + "Header").style.display = "none";
                document.getElementById(btn.name + "Container").style.display = "none";
            });

            this.classList.add("selected");
            document.getElementById(this.name + "Header").style.display = "flex";
            document.getElementById(this.name + "Container").style.display = "flex";
        });
    });
});

//.....................................................................................................................................................................


createJobCardInitialize("jobPostsContainer", { "owner" : null});

function checkIfValid() {
    let username = document.getElementsByName("username")[0].value;
    let password = document.getElementsByName("password")[0].value;
    let userType = document.getElementById("userType").value;

    let error = checkUN(username == undefined ? "" : username) + "\n" + checkPW(password == undefined ? "" : password);
    if (error.trim() !== "") {
        alert(error);
    } else {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "createAccounts.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        let params = `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}&userType=${encodeURIComponent(userType)}`;
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText);
                document.getElementsByName("username")[0].reset();
                document.getElementsByName("password")[0].reset();
                updateKnownUsers();
            }
        };

        xhr.send(params);

    }
}

function updateKnownUsers(){
    let userContainer = document.getElementById("confirmedUser");

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./grabCurrentAccounts.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {

            let users = JSON.parse(xhr.responseText); 

            userContainer.innerHTML = ""; 

            users.forEach(user => {
                let userDiv = document.createElement("div");
                userDiv.classList.add("eachConfirmedUser");

                let userName = document.createElement("div");
                userName.classList.add("eachConfirmedUserName");
                userName.textContent = user.username; 
                userDiv.append(userName);

                let userType = document.createElement("div");
                userType.classList.add("eachConfirmedType");
                userType.textContent = user.usertype; 
                userDiv.append(userType);

                userContainer.append(userDiv);
            });

        }
    };

    xhr.send();
}

updateKnownUsers();