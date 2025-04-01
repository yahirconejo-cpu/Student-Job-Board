// stores all of user selected and saved preferences
const userPreferences = {};

// element used to show the user's preferences
var openedPreference;


// makes element pop up show up
function openPopup(name, type, ifSearchBar, popupName, popupHeader, clickedEl, user) {
    document.getElementById(popupName).style.display = "flex";
    document.getElementById(popupName + "popUpOverlay").style.display = "block";
    openedPreference = document.getElementById(clickedEl.getAttribute("name"));
    inputOptions(name, type, ifSearchBar == 'true', popupName, popupHeader == 'null' ? null : popupHeader, user);
}

// closes popup and displays the results
function closePopup(popupName) {
    document.getElementById(popupName).style.display = "none";
    document.getElementById(popupName + "popUpOverlay").style.display = "none";
}

// update user chosen preferences
function updateChosenOptions(option, chosenList, isChecked,  definedOpenedUserButton = null, user) {

    // update list
    if (isChecked) {
        if (!chosenList.includes(option)) {
            chosenList.push(option);
            // add to data base ie: grab info and send new info back

        }
    } else {
        chosenList.splice(chosenList.indexOf(option), 1);
        // remove from data base ie: grab info and send new info back


    }

    
    // display user preference result
    
    definedOpenedUserButton = ( definedOpenedUserButton == null ? openedPreference : document.getElementById(definedOpenedUserButton));    
    
    definedOpenedUserButton.innerHTML = "";


    // Find list of all pos choces
    let originalKey = Object.keys(userPreferences).find(key => userPreferences[key] === chosenList);
    let allListKey = originalKey.replace("prefered", ""); // Remove "prefered" from key name
    var allList = userPreferences[allListKey];

    let chosenListJson = JSON.stringify(chosenList);

    let updateChosenOptionsXML = new XMLHttpRequest();
    updateChosenOptionsXML.open("POST", "../allFunctions/addEditUserButton/editUserButtons.php", true);
    updateChosenOptionsXML.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    updateChosenOptionsXML.onreadystatechange = function () {
        if (updateChosenOptionsXML.readyState === 4 && updateChosenOptionsXML.status === 200) {
            console.log(updateChosenOptionsXML.responseText);
        }
    };
    
    updateChosenOptionsXML.send(`edit=${encodeURIComponent(chosenListJson)}&column=${originalKey}`);

    

    if (typeof allList === "object" && !Array.isArray(allList)) {

        let groupedSelections = {};

        // Group selected options by their original headers
        Object.keys(allList).forEach(header => {
            let selectedUnderHeader = allList[header].filter(opt => chosenList.includes(opt));
            if (selectedUnderHeader.length > 0) {
                groupedSelections[header] = selectedUnderHeader;
            }
        });

        // Display grouped selections under headers
        Object.keys(groupedSelections).forEach(header => {
            let headerElement = document.createElement("h4");
            headerElement.innerHTML = header;
            definedOpenedUserButton.appendChild(headerElement);

            groupedSelections[header].forEach(chosenOption => {
                let item = document.createElement("p");
                item.innerHTML = chosenOption;
                definedOpenedUserButton.appendChild(item);
            });
        });
    } else {
        // Default behavior for normal lists
        chosenList.forEach(chosenOption => {
            let item = document.createElement("p");
            item.innerHTML = chosenOption;
            definedOpenedUserButton.appendChild(item);
        });
    }

}

// createCheckboxes 
function createCheckbox(option, container, chosenList, user) {
    let label = document.createElement("label");
    let checkbox = document.createElement("input");

    checkbox.type = "checkbox";
    checkbox.value = option;
    checkbox.checked = chosenList.includes(option);

    checkbox.addEventListener("change", function() {
        updateChosenOptions(option, chosenList, checkbox.checked, user);
    });

    label.appendChild(checkbox);
    label.appendChild(document.createTextNode(option));
    label.classList.add("currentOptions");

    container.appendChild(label);
}

// createSearchList
function createSearchList(option, container, chosenList, user) {
    let optionItem = document.createElement("div");
    optionItem.textContent = option;
    optionItem.classList.add("currentOptions");

    if (chosenList.includes(option)) {
        optionItem.classList.add("selectededitUserButtonsOptions");
    }

    optionItem.addEventListener("click", function() {
        optionItem.classList.toggle("selectededitUserButtonsOptions");
        updateChosenOptions(option, chosenList, optionItem.classList.contains("selectededitUserButtonsOptions"), user);
    });

    container.appendChild(optionItem);
}

// create input box
// parm 1 @ option - list of the presete options or allowed boxes
// parm 2 @ container - container that the input box will be added to
// parm 3 @ currentData - list of all the data that the user has chosen or added
function createInputBox(option, container, currentData, user) {

    let inputBox = document.createElement("input");
    inputBox.type = "text";

    let index = container.children.length; // Get the index of the input box
    
    if (index < currentData.length) {
        inputBox.value = currentData[index];
    } else {
        inputBox.placeholder = option;
    }
    

    if (currentData.includes(option)) {
        inputBox.classList.add("selectededitUserButtonsOptions");
    }

    inputBox.addEventListener("change", function () {
        let trimmedValue = this.value.trim();

        if (trimmedValue) {
            // Ensure currentData[index] is updated or added correctly
            currentData[index] = trimmedValue;
            updateChosenOptions(trimmedValue, currentData, true, user);
        } else {
            // If input is empty, remove from currentData and update choices
            let removedValue = currentData[index]; 
            updateChosenOptions(removedValue, currentData, false, user);
        }
    });

    container.appendChild(inputBox);
}

// create searchList with headers
function createSearchListWithHeaders(option, container, chosenList, user) {
    for (var key in option) {
        let optionHeader = document.createElement("h4");
        optionHeader.innerHTML = key;
        container.appendChild(optionHeader);
        option[key].forEach(searchOption => createCheckbox(searchOption, container, chosenList));
    }
}


// search results
function filterOptions(searchInput, container, chosenList, allList, chosenType) {
    let searchInputValue = searchInput.toLowerCase();
    container.innerHTML = ""; // Clear previous results


    if (chosenType === "checkbox" || chosenType === "searchList") {
        let filteredOptions = allList.filter(option => option.toLowerCase().includes(searchInputValue));

        filteredOptions.forEach(option => {
            if (chosenType === "checkbox") {
                createCheckbox(option, container, chosenList);
            } else {
                createSearchList(option, container, chosenList);
            }
        });
    } else if (chosenType === "searchListWithHeaders") {
        let filteredOptions = {};

        Object.keys(allList).forEach(header => {
            let filteredSubOptions = allList[header].filter(option => option.toLowerCase().includes(searchInputValue));

            if (filteredSubOptions.length > 0) {
                filteredOptions[header] = filteredSubOptions;
            }
        });

        createSearchListWithHeaders(filteredOptions, container, chosenList);
    }
}

function inputOptions(nameChosen, type, ifSearchBar, popupName, popupHeader, user) {

    // updates chosen type
    userChosenType = type;

    var optionName = nameChosen.replace("prefered", "").trim();
    // stores all possible user data into userPreferences if not currently there
    // gets data from possible options table
    if (userPreferences[optionName] == undefined) {
        // I need this is querying the SettingsOptions table with the collume name and setting userPreferences[name] equle to the options set in it
        // In the database in the table I will formate the text to look like a list for dictionary so that it matches with the placeholder lists
        // name is just a varible that is a string
        // the php that it will access is called editUserButtons.php

        var getPosOptionsPromise = new Promise((resolve, reject) => {
            let getPosOptions = new XMLHttpRequest();
            getPosOptions.open("POST", "../allFunctions/addEditUserButton/editUserButtons.php", true);
            getPosOptions.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
            getPosOptions.onreadystatechange = function () {
                if (getPosOptions.readyState === 4 && getPosOptions.status === 200) {
                    userPreferences[optionName] = JSON.parse(getPosOptions.responseText);
                    resolve();  // Resolve the promise once the request completes
                } else if (getPosOptions.readyState === 4) {
                    reject();  // Reject if an error occurs
                }
            };
            getPosOptions.send(`table=SettingsOptions&column=${optionName}`);
        });

        // // PlaceHolder lists
        // // for normal lists
        // userPreferences[name] = ["Full-Time", "Part-Time", "Internship", "Temporary", "Contract"];

        // // for lists if you want headers
        // if (type == "searchListWithHeaders") {
        //     userPreferences[name] = { "Days": ["Monday to Friday", "Weekends as needed", "Weekends Only", "No weekends", "Holidays"], "Shifts": ["After school", "4 hour shift", "8 hour shift", "10 hour shift", "12 hour shift", "Day shift", "Night shift", "Evening shift", "No nights", "Overnight shift"] };
        // } else if (type == "inputBox") {
        //     userPreferences[name] = [""];
        // }
    }
    // creates list to store user selected data
    // gets data from user table
    if (userPreferences[nameChosen] == undefined) {

        // I need this to query the Settings table with the collume nameChosen
        // I need to set the information in the query create userPreferences[nameChosen] with the right info

        var getChosenOptionsPromise = new Promise((resolve, reject) => {
            let getChosenOptions = new XMLHttpRequest();
            getChosenOptions.open("POST", "../allFunctions/addEditUserButton/editUserButtons.php", true);
            getChosenOptions.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
            getChosenOptions.onreadystatechange = function () {
                if (getChosenOptions.readyState === 4 && getChosenOptions.status === 200) {
                    userPreferences[nameChosen] = JSON.parse(getChosenOptions.responseText);
                    resolve();  // Resolve the promise once the request completes
                } else if (getChosenOptions.readyState === 4) {
                    reject();  // Reject if an error occurs
                }
            };
            getChosenOptions.send(`table=Settings&column=${nameChosen}`);
        });


        // // place holder lists
        // if (type == "inputBox") {
        //     userPreferences[nameChosen] = [""];
        // }
        
        // userPreferences[nameChosen] = [];
    }

    Promise.all([getPosOptionsPromise, getChosenOptionsPromise])
    .then(() => {


        // element that will store the data
        let optionsContainer = document.getElementById(popupName + "Container");
        optionsContainer.innerHTML = "";

        // element that will be used as a search bar
        let optionSearchInput = document.getElementById(popupName + "SearchBar");
        optionSearchInput.oninput = function() {
            filterOptions(this.value, optionsContainer, userPreferences[nameChosen], userPreferences[optionName], type);
        };

        // element that will be the popup header
        let optionPopupHeader = document.getElementById(popupName + "PopupHeader");
        optionPopupHeader.innerHTML = "";

        // if element will have a search bar
        if (ifSearchBar) {
            optionSearchInput.style.display = "";
        } else {
            optionSearchInput.style.display = "none";
        }

        // if element will have a title

        if (popupHeader != null) {
            optionPopupHeader.style.display = "";
            optionPopupHeader.innerHTML = popupHeader;
        } else {
            optionPopupHeader.style.display = "none";
        }

        // addes data into element
        if (type == "checkbox") {
            userPreferences[optionName].forEach(option => createCheckbox(option, optionsContainer, userPreferences[nameChosen], user));
        } else if (type == "searchList") {
            userPreferences[optionName].forEach(option => createSearchList(option, optionsContainer, userPreferences[nameChosen], user));
        } else if (type == "searchListWithHeaders") {
            createSearchListWithHeaders(userPreferences[optionName], optionsContainer, userPreferences[nameChosen], user);
        } else if (type == "inputBox") {
            userPreferences[optionName].forEach(option => createInputBox(option, optionsContainer, userPreferences[nameChosen], user));
        }

    })
    .catch((error) => {
        console.error("An error occurred: ", error);
    });

}

function loadDataForEditUserButtons(allData, chosenData, optionName, container) {
    if (allData != null) {
        userPreferences[optionName] = allData;
        // updateChosenOptions(option, chosenList, isChecked);
        // userPreferences[name].forEach( option => updateChosenOptions(option, chosenData, true));
    } else {
        allData = [];
    }

    if (chosenData != null) {
        var nameChosen = "prefered" + optionName ;
        userPreferences[nameChosen] = chosenData;
        userPreferences[nameChosen].forEach( option => updateChosenOptions(option, chosenData, true, container ));
    } else {
        chosenData = [];
    }

    // console.log(allData);
    // console.log(chosenData);
    // allData.forEach( option => updateChosenOptions(option, chosenData, true));

}

// creates a button that will let the user to edit any user table collume
// parm 1 @ header - string - set the header of the button. aka the name that will show up for the button
// parm 2 @ elName - string - set the id of the button
// parm 3 @ colName - string - the name of the collume that will be edited
// parm 4 @ type - string - the type of button that will be created: checkbox, searchList, searchListWithHeaders, inputBox.
// parm 5 @ ifSearchBar - boolean - if the popup will have a search bar
// parm 6 @ popupName - string - the name of the popup that will be created. This has to be the same name as you give to createPreferenceSelectPopups() function
// parm 7 @ popupHeader - string - the header of the popup that will be created. place null if you dont want a header.//
// parm 8 @ loadAllData - array / object - if you want to load possible options that the user can choose from. null if you dont want to load possible options.
// parm 9 @ loadChosenData - array - if you want to load the data that the user has chosen before. null if you dont want to load the data.
// param 10 @ user - string - username of user to edit ie: checks access level (admin only)
function createSettingSelectElements(header, elName, colName, type, ifSearchBar = true, popupName, popupHeader = null, loadAllData = null, loadChosenData = null, user = null) {
    let PreferenceSelectElementsBtn = document.createElement("div");
    PreferenceSelectElementsBtn.setAttribute("name", "selected" + elName);
    PreferenceSelectElementsBtn.classList.add("userEditButtons");
    PreferenceSelectElementsBtn.setAttribute("onclick", "openPopup('" + colName + "', '" + type + "','" + ifSearchBar + "','" + popupName + "','" + popupHeader + "', this,"+user+")");

    let PreferenceSelectElementsHeader = document.createElement("h4");
    PreferenceSelectElementsHeader.innerHTML = header + "<span class='userEditButtonPlusSigns'>+</span>";

    PreferenceSelectElementsBtn.appendChild(PreferenceSelectElementsHeader);

    let PreferenceSelectElementsContainer = document.createElement("div");
    PreferenceSelectElementsContainer.setAttribute("id", "selected" + elName);
    PreferenceSelectElementsContainer.classList.add("selectedUserEditButtonItems");

    // Insert the elements where the function is called

    let currentScript = document.currentScript;
    if (currentScript) {
        currentScript.parentNode.insertBefore(PreferenceSelectElementsBtn, currentScript);
        currentScript.parentNode.insertBefore(PreferenceSelectElementsContainer, currentScript);
    } else {
        console.log("Could not find the script tag calling the function.");
    }

    // Load data for editUserButtons
    if (loadAllData != null || loadChosenData != null) {
        loadDataForEditUserButtons(loadAllData, loadChosenData, colName, "selected" + elName);
    }

}

// create popupElement
// parm 1 @ elName - string - the id of the popup / name of the popup
function createSettingSelectPopups(elName) {
    let popupElementOverlay = document.createElement("div");
    popupElementOverlay.setAttribute("id", elName + "popUpOverlay");
    popupElementOverlay.classList.add("editUserButtonsPopupOverlay");

    popupElementOverlay.onclick = function() {
        closePopup(elName);
    }

    let popupElement = document.createElement("div");
    popupElement.setAttribute("id", elName);
    popupElement.classList.add("editUserButtonsPopup");

    let popupHeader = document.createElement("h4");
    popupHeader.setAttribute("id", elName + "PopupHeader");
    popupHeader.classList.add("editUserButtonsPopupHeader");

    let popupElementInput = document.createElement("input");
    popupElementInput.setAttribute("id", elName + "SearchBar");
    popupElementInput.setAttribute("type", "text");
    popupElementInput.setAttribute("placeholder", "Search...");
    popupElementInput.classList.add("editUserButtonsSearchBar");

    let popupElementContainer = document.createElement("div");
    popupElementContainer.setAttribute("id", elName + "Container");
    popupElementContainer.classList.add("editUserButtonsContainer");

    let popupElementCloseBtn = document.createElement("div");
    popupElementCloseBtn.classList.add("editUserButtonsContainerCloseBtn");

    popupElementCloseBtn.onclick = function() {
        closePopup(elName);
    }

    popupElementCloseBtn.innerHTML = "Save";

    popupElement.appendChild(popupHeader);
    popupElement.appendChild(popupElementInput);
    popupElement.appendChild(popupElementContainer);
    popupElement.appendChild(popupElementCloseBtn);


    // Insert the elements where the function is called
    let currentScript = document.currentScript;
    if (currentScript) {
        document.currentScript.parentNode.insertBefore(popupElementOverlay, document.currentScript);
        document.currentScript.parentNode.insertBefore(popupElement, document.currentScript);
    } else {
        console.log("Could not find the script tag calling the function.");
    }
}