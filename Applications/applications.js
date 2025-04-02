function processResumes() {
    // Get the file input and form data
    const fileInput = document.getElementById('resume');  // Make sure the input has id="resume"
    const jobPostId = document.getElementById('jobpostid').value;  // Make sure there's a hidden or visible input with id="jobpostid"

    // Check if a file is selected
    if (fileInput.files.length === 0) {
        alert('❌ Please select a resume to upload.');
        return;
    }

    // Create a FormData object to hold the file and other data
    const formData = new FormData();
    formData.append('resume', fileInput.files[0]);  // Append the file
    formData.append('jobpostid', jobPostId);  // Append the jobpostid

    // Create a new XMLHttpRequest
    let xhr = new XMLHttpRequest();

    // Set up the AJAX request
    xhr.open('POST', 'handleUpload.php', true);
    // Set up an event listener for when the request is complete
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Success: Display the response (success message or error)
            document.getElementById('response').innerHTML = xhr.responseText;
        }
    };
    console.log(xhr.responseText);
    // Send the form data with the resume and job post ID
    xhr.send(formData);
}