// Function to validate the registration form before submission
function validateForm() {
    let valid = true;

    // Clear previous error messages
    document.getElementById("nameError").innerText = "";
    document.getElementById("usernameError").innerText = "";
    document.getElementById("emailError").innerText = "";
    document.getElementById("passwordError").innerText = "";
    document.getElementById("confirm_passwordError").innerText = "";

    // Fetch field values
    const name = document.getElementById("name").value;
    const username = document.getElementById("username").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const confirm_password = document.getElementById("confirm_password").value;

    // Validate name
    if (name === "") {
        document.getElementById("nameError").innerText = "Name cannot be empty.";
        valid = false;
    }

    // Validate username
    if (username === "") {
        document.getElementById("usernameError").innerText = "Username cannot be empty.";
        valid = false;
    }

    // Validate email
    if (email === "") {
        document.getElementById("emailError").innerText = "Email cannot be empty.";
        valid = false;
    } else if (!email.includes("@")) {  // Basic email validation
        document.getElementById("emailError").innerText = "Email must include '@'.";
        valid = false;
    }

    // Validate password
    if (password === "") {
        document.getElementById("passwordError").innerText = "Password cannot be empty.";
        valid = false;
    }

    // Validate confirm_password
    if (confirm_password === "") {
        document.getElementById("confirm_passwordError").innerText = "Confirm Password cannot be empty.";
        valid = false;
    } else if (password !== confirm_password) {
        document.getElementById("confirm_passwordError").innerText = "Passwords do not match.";
        valid = false;
    }

    return valid; // Return the status of the form validation
}

// Function to validate the login form before submission
function validateLoginForm() {
    let valid = true;

    // Clear previous error messages
    document.getElementById("email_usernameError").innerText = "";
    document.getElementById("passwordError").innerText = "";

    const email_username = document.getElementById("email_username").value;
    const password = document.getElementById("password").value;

    if (email_username === "") {
        document.getElementById("email_usernameError").innerText = "Username or E-mail cannot be empty.";
        valid = false;
    }

    if (password === "") {
        document.getElementById("passwordError").innerText = "Password cannot be empty.";
        valid = false;
    }

    return valid; // Return the status of the form validation
}

