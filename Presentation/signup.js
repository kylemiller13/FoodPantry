// Utility Functions for Common Validation Checks
function isFieldEmpty(fieldValue) {
    return fieldValue.trim() === '';
}

function isValidEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return emailRegex.test(email);
}

function arePasswordsMatching(password, confirmPassword) {
    return password === confirmPassword;
}

// Field-Specific Validation Functions
function validateName() {
    const name = document.getElementById("name").value;
    const errorField = document.getElementById("nameError");
    if (isFieldEmpty(name)) {
        errorField.innerText = "Name cannot be empty.";
        return false;
    }
    errorField.innerText = "";
    return true;
}

function validateUsername() {
    const username = document.getElementById("username").value;
    const errorField = document.getElementById("usernameError");
    if (isFieldEmpty(username)) {
        errorField.innerText = "Username cannot be empty.";
        return false;
    }
    errorField.innerText = "";
    return true;
}

function validateEmail() {
    const email = document.getElementById("email").value;
    const errorField = document.getElementById("emailError");
    if (isFieldEmpty(email)) {
        errorField.innerText = "Email cannot be empty.";
        return false;
    } else if (!isValidEmail(email)) {
        errorField.innerText = "Email must be a valid email address.";
        return false;
    }
    errorField.innerText = "";
    return true;
}

function validatePassword() {
    const password = document.getElementById("password").value;
    const errorField = document.getElementById("passwordError");

    // Check for emptiness
    if (isFieldEmpty(password)) {
        errorField.innerText = "Password cannot be empty.";
        return false;
    }

    // Check for minimum length
    if (password.length < 8) {
        errorField.innerText = "Password must be at least 8 characters long.";
        return false;
    }

    // Check for uppercase letter
    if (!/[A-Z]/.test(password)) {
        errorField.innerText = "Password must contain at least one uppercase letter.";
        return false;
    }

    // Check for lowercase letter
    if (!/[a-z]/.test(password)) {
        errorField.innerText = "Password must contain at least one lowercase letter.";
        return false;
    }

    // Check for a number
    if (!/\d/.test(password)) {
        errorField.innerText = "Password must contain at least one number.";
        return false;
    }

    // Check for a special character
    if (!/[^a-zA-Z\d]/.test(password)) {
        errorField.innerText = "Password must contain at least one special character.";
        return false;
    }

    // If all checks pass
    errorField.innerText = "";
    return true;
}

function validateConfirmPassword() {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;
    const errorField = document.getElementById("confirm_passwordError");
    if (!arePasswordsMatching(password, confirmPassword)) {
        errorField.innerText = "Passwords do not match.";
        return false;
    }
    errorField.innerText = "";
    return true;
}

// Main Validation Function for the Registration Form
function validateForm() {
    const isNameValid = validateName();
    const isUsernameValid = validateUsername();
    const isEmailValid = validateEmail();
    const isPasswordValid = validatePassword();
    const isConfirmPasswordValid = validateConfirmPassword();

    return isNameValid && isUsernameValid && isEmailValid && isPasswordValid && isConfirmPasswordValid;
}

// Validation Function for the Login Form
function validateLoginForm() {
    const email_username = document.getElementById("email_username").value;
    const password = document.getElementById("password").value;
    const emailUsernameErrorField = document.getElementById("email_usernameError");
    const passwordErrorField = document.getElementById("passwordError");
    let valid = true;

    if (isFieldEmpty(email_username)) {
        emailUsernameErrorField.innerText = "Username or E-mail cannot be empty.";
        valid = false;
    } else {
        emailUsernameErrorField.innerText = "";
    }

    if (isFieldEmpty(password)) {
        passwordErrorField.innerText = "Password cannot be empty.";
        valid = false;
    } else {
        passwordErrorField.innerText = "";
    }

    return valid;
}
