function validateEmail() {
    const email = document.getElementById('email').value;
    const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!regex.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }
    return true;
}
