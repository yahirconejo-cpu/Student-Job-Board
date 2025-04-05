

function createAnotherShift(){
    var container = document.getElementById("allShifts");

    // Create the outer div
    let newShift = document.createElement("div");
    newShift.classList.add("addedShift");

    // Create input for start time
    let startInput = document.createElement("input");
    startInput.type = "time";
    startInput.name = "startShifts";
    startInput.required = true;

    // Create input for end time
    let endInput = document.createElement("input");
    endInput.type = "time";
    endInput.name = "endShifts";
    endInput.required = true;

    // Create remove button (X)
    let removeBtn = document.createElement("div");
    removeBtn.classList.add("removeAddedShift");
    removeBtn.textContent = "X";

    removeBtn.onclick = function(){
        this.parentElement.remove()
    };

    // Append inputs and button to the shift div
    newShift.appendChild(startInput);
    newShift.appendChild(endInput);
    newShift.appendChild(removeBtn);

    container.appendChild(newShift);

}


function checkJobTitles(){
    var jobTitleErrorText = document.getElementById("jobTitleErrorText");
    var jobTitleData = document.querySelectorAll("#jobTitleData option");
    
    var inputField = document.querySelector("input[list='jobTitleData']");

    var inputValue = inputField.value.toLowerCase(); 
    console.log(inputValue);

    var matchFound = false;

    if(inputValue != ""){

        for(let options of jobTitleData){
            if(options.value.toLowerCase() == inputValue){
                matchFound = "perfect";
                break;
            }else if (options.value.toLowerCase().includes(inputValue)) {
                matchFound = true;
                break;
            } 
        }


        if(matchFound == "perfect"){
            jobTitleErrorText.innerHTML = "";
            inputField.style.border = "1px solid rgb(179, 179, 179)";

        }else if (matchFound) {
            jobTitleErrorText.innerHTML = "Invalid job title";
            inputField.style.border = " 1px solid red";
        } else {
            jobTitleErrorText.innerHTML = "Invalid job title"; 
            inputField.style.border = " 1px solid red";
        }
    }else{
        jobTitleErrorText.innerHTML = "Please Input a Job Title";
        inputField.style.border = " 1px solid red";
    }

}
checkJobTitles();
document.querySelector("input[list='jobTitleData']").addEventListener("input", checkJobTitles);


function createJobPost(){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./createJobPost.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");


    let title = encodeURIComponent(document.getElementById("title").value);
    let description = encodeURIComponent(document.getElementById("description").value);
    let jobTitle = encodeURIComponent(document.querySelector("input[name='jobTitle']").value);
    let jobType = encodeURIComponent(document.getElementById("jobType").value);
    
    let days = Array.from(document.querySelectorAll("input[name='days[]']:checked"))
    .map(checkbox => encodeURIComponent(checkbox.value))
    .join(','); 

    let pay = encodeURIComponent(document.getElementById("pay").value);
    let address = encodeURIComponent(document.getElementById("address").value);


    let shifts = [];
    document.querySelectorAll(".addedShift").forEach(shift => {
        let startShift = shift.querySelector("input[name='startShifts']").value;
        let endShift = shift.querySelector("input[name='endShifts']").value;
        if (startShift && endShift) {
            shifts.push(`${startShift}-${endShift}`);
        }
    });

    let postData = `title=${title}&description=${description}&jobTitle=${jobTitle}&jobType=${jobType}&days=${days}&pay=${pay}&address=${address}&shifts=${JSON.stringify(shifts)}`;


    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            alert(xhr.responseText);
            document.getElementById("title").value = "";
            document.getElementById("description").value = "";
            document.querySelector("input[name='jobTitle']").value = "";
            document.getElementById("jobType").value = "";
            
            let days = document.querySelectorAll("input[name='days']");
            days.forEach( day => {
                days.checked = false;
            });

            document.getElementById("pay").value = "";
            document.getElementById("address").value = "";
        }
    };


    xhr.send(postData);
}