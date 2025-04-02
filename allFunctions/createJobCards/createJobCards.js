

// creates a job card for the applicants that displays the accepted status
// parm 1 @ status - string - applied, interviewing, waitlisted, accepted, rejected
// parm 2 @ jobTitle - string - the job title of the job
// parm 3 @ jobDescription - string - the job description of the job
// parm 4 @ container - element - the container that will hold the job cards
function createJobCardApplicant(status, jobTitle, jobDescription, container){
  
  const box = document.createElement("div");
  box.classList.add("createJobCardApplicant");

  const statusDiv = document.createElement("div");
  statusDiv.classList.add(`createJobCardStatus`)
  statusDiv.classList.add(`createJobCardApplicantStatus_${status.toLowerCase()}`);
  statusDiv.innerHTML = status;
  box.appendChild(statusDiv);

  const titleDiv = document.createElement("div");
  titleDiv.classList.add("createJobCardJobTitle");
  titleDiv.innerHTML = jobTitle;
  box.appendChild(titleDiv);

  
  const descDiv = document.createElement("div");
  descDiv.classList.add("createJobCardJobDescription");
  
  const descTextDiv = document.createElement("div");
  descTextDiv.classList.add("createJobCardJobDescriptionText");
  descTextDiv.innerHTML = jobDescription;

  const fadeDiv = document.createElement("div");
  fadeDiv.classList.add("createJobCardJobDescriptionFade");

  descDiv.appendChild(descTextDiv);
  descDiv.appendChild(fadeDiv);
  box.appendChild(descDiv);

  container.appendChild(box);
}

// creates a job card for the employers that displays the accepted status and applicant status
// parm 1 @ status - string - open,closed

function createJobCardEmployer(status, jobTitle, applicantsCount, jobDescription, container){
  const box = document.createElement("div");
  box.classList.add("createJobCardEmployer");

  const statusDiv = document.createElement("div");
  statusDiv.classList.add(`createJobCardStatus`);
  const statusClass = `createJobCardEmployerStatus_${status.toLowerCase()}`;
  statusDiv.classList.add(statusClass);
  statusDiv.innerHTML = status;
  box.appendChild(statusDiv);

  const titleDiv = document.createElement("div");
  titleDiv.classList.add("createJobCardJobTitle");
  titleDiv.innerHTML = jobTitle;
  box.appendChild(titleDiv);

  const applicantDiv = document.createElement("div");
  applicantDiv.classList.add("createJobCardJobApplicants");
  applicantDiv.innerHTML = `Applicants: ${applicantsCount}`;
  box.appendChild(applicantDiv);

  const descDiv = document.createElement("div");
  descDiv.classList.add("createJobCardJobDescription");
  
  const descTextDiv = document.createElement("div");
  descTextDiv.classList.add("createJobCardJobDescriptionText");
  descTextDiv.innerHTML = jobDescription;

  const fadeDiv = document.createElement("div");
  fadeDiv.classList.add("createJobCardJobDescriptionFade");

  descDiv.appendChild(descTextDiv);
  descDiv.appendChild(fadeDiv);
  box.appendChild(descDiv);

  container.appendChild(box);

}

// creates generic job card
function createJobCardGenericCard(jobTitle, companyName, jobDescription, container){

  const box = document.createElement("div");
  box.classList.add("createJobCardGenericCard");

  const titleDiv = document.createElement("div");
  titleDiv.classList.add("createJobCardJobTitle");
  titleDiv.innerHTML = jobTitle;
  box.appendChild(titleDiv);

  const companyDiv = document.createElement("div");
  companyDiv.classList.add("createJobCardCompany");
  companyDiv.innerHTML = companyName;

  const descDiv = document.createElement("div");
  descDiv.classList.add("createJobCardJobDescription");

  const descTextDiv = document.createElement("div");
  descTextDiv.classList.add("createJobCardJobDescriptionText");
  descTextDiv.innerHTML = jobDescription;

  const fadeDiv = document.createElement("div");
  fadeDiv.classList.add("createJobCardJobDescriptionFade");

  descDiv.appendChild(descTextDiv);
  descDiv.appendChild(fadeDiv);
  box.appendChild(descDiv);

  const applyBtn = document.createElement("button");
  applyBtn.classList.add("createJobCardApplyButton");
  applyBtn.innerHTML = "Apply Now";
  box.appendChild(applyBtn);

  container.appendChild(box);
}

// adds a add more job card
// parm 1 @ container - element - the container that will hold the job cards
function createJobCardAddMoreCard(container){
  const box = document.createElement("a");
  box.href = "../Search/";
  box.classList.add("createJobCardApplicationBoxAddMore");

  const pluseSign = document.createElement("div");
  pluseSign.classList.add("createJobCardApplicationBoxAddMoreSign");
  pluseSign.innerHTML = "+";

  box.appendChild(pluseSign);

  container.appendChild(box);
}

// parm 1 @ container - string - the id of the container that will hold the job cards
// parm 2 @ quryCondition - object - uses key to check if value is equle to it {"owner": "bob", "status": "accepted", "jobTitle": "Computer Science"} should also be a { "owner" : null} option which will just pull the current user through session
function createJobCardInitialize(container, quryCondition) {

  
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../allFunctions/createJobCards/createJobCards.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
          let response = JSON.parse(xhr.responseText);// is a list with objects in it
          console.log(response);

          let containerElement = document.getElementById(container);
          //containerElement.innerHTML = ""; // Clear existing job cards

          response.forEach(job => {
              // Destructure job properties
              let { type, status, jobTitle, jobDescription, applicantsCount, companyName } = job;
              console.log(type, status, jobTitle, jobDescription, applicantsCount, companyName);
              // Determine which job card function to call
              if (type === "applicant") {
                  createJobCardApplicant(status, jobTitle, jobDescription, containerElement);
              } else if (type === "employer") {
                  createJobCardEmployer(status, jobTitle, applicantsCount, jobDescription, containerElement);
              } else if (type === "generic") {
                  createJobCardGenericCard(jobTitle, companyName, jobDescription, containerElement);
              }
          });

          // Add "Add More" button if applicable
          createJobCardAddMoreCard(containerElement);
      }
  };

  xhr.send("cardQuery=" + JSON.stringify(quryCondition));
}