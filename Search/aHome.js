
createJobCardInitialize("searchSectionResultsContainer", { "owner" : null});

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
        const dayPattern = "%" + e.target.value + "%";
        if (e.target.checked) {
            selectedDays.push(dayPattern);
        } else {
            selectedDays = selectedDays.filter(day => day !== dayPattern);
        }
        updateQuery();
    });
});


function updateQuery(){
    let cardQuery = {"owner" : null};
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