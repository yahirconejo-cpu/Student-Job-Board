function processApplication(button) {
    let form = button.closest(".applyForm");
    let formData = new FormData(form);


    fetch("handleUpload.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        form.nextElementSibling.innerHTML = data; // Show response message
        form.reset(); // Clear form after submission

        setTimeout(function() {
            window.location.href = '../Home/index.php'; // Redirect to home page
        }, 3000); // 3-second delay
    })
    .catch(error => console.error("Error:", error));
}


function updateStatus(applicationId, newStatus) {
    const formData = new FormData();
    formData.append("applicationId", applicationId);
    formData.append("newStatus", newStatus);


    fetch("updateStatus.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Show feedback
        location.reload(); // Refresh the page to reflect changes
    })
    .catch(error => console.error("Error:", error));
}

