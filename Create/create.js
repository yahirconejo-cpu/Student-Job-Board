
document.getElementById("jobTitleInput").addEventListener("input", function() {
    let input = this.value;
    let options = document.querySelectorAll("#jobTitle option");
    let valid = false;

    options.forEach(option => {
        if (option.value === input) {
            valid = true;
        }
    });

    if (!valid) {
        this.setCustomValidity("Please select a valid job title from the list.");
    } else {
        this.setCustomValidity("");
    }
});
