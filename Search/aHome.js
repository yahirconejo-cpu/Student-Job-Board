
createJobCardInitialize("searchSectionResultsContainer");

var jobTitle = "";
const jobTitleInput = document.querySelector("input[name='jobTitle']");
jobTitleInput.addEventListener("input", (e) => {
    jobTitle = e.target.value.trim();
    updateQuery();
});

var jobType = "";
const jobTypeSelect = document.getElementById("jobType");
jobTypeSelect.addEventListener("change", (e) => {
    jobType = e.target.value;
    updateQuery();
});

var selectedDays = [];
const checkboxes = document.querySelectorAll("input[name='days[]']");
checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", (e) => {
        if (e.target.checked) {
            selectedDays.push(e.target.value);
        } else {
            selectedDays = selectedDays.filter(day => day !== e.target.value);
        }
        updateQuery();
    });
});


function updateQuery(){
    let cardQuery = {};
    if(jobTitle != ""){
        cardQuery["jobtitle"] = jobTitle;
    }
    if(jobType != ""){
        cardQuery["jobtype"] = jobType;
    }
    if(selectedDays.length != 0){
        cardQuery["jobdays"] = selectedDays;
    }

    document.getElementById("searchSectionResultsContainer").innerHTML = "";

    createJobCardInitialize("searchSectionResultsContainer", cardQuery);
}