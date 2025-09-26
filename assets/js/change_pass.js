document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("changePasswordForm");
    const emailInput = document.getElementById("email");
    const oldPasswordInput = document.getElementById("oldPassword");
    const newPasswordInput = document.getElementById("newPassword");
    const confirmNewPasswordInput = document.getElementById("confirmNewPassword");

    const oldPasswordErrorSpan = document.getElementById("oldPasswordError");
    const newPasswordErrorSpan = document.getElementById("newPasswordError");
    const confirmNewPasswordErrorSpan = document.getElementById("confirmNewPasswordError");
    const emailErrorSpan = document.getElementById("emailError");

    function displayUrlErrors() {
        const urlParams = new URLSearchParams(window.location.search);
        
        emailErrorSpan.textContent = "";
        oldPasswordErrorSpan.textContent = "";
        newPasswordErrorSpan.textContent = "";
        confirmNewPasswordErrorSpan.textContent = "";

        if (urlParams.has('e1')) {
            confirmNewPasswordErrorSpan.textContent = 'New Passwords don\'t match';
        } else if (urlParams.has('e2')) {
            emailErrorSpan.textContent = 'All fields are required';
        } else if (urlParams.has('e3')) {
            emailErrorSpan.textContent = 'Incorrect email or old password';
        } else if (urlParams.has('e4')) {
            emailErrorSpan.textContent = 'Password update failed';
        } else if (urlParams.has('success')) {
            alert("Password change successful!");
        }
    }

    form.addEventListener("submit", function(event) {
        event.preventDefault(); 

        oldPasswordErrorSpan.textContent = "";
        newPasswordErrorSpan.textContent = "";
        confirmNewPasswordErrorSpan.textContent = "";
        emailErrorSpan.textContent = "";

        let isValid = true;

        if (emailInput.value.trim() === "") {
            emailErrorSpan.textContent = "Please enter your email.";
            isValid = false;
        }

        if (oldPasswordInput.value.trim() === "") {
            oldPasswordErrorSpan.textContent = "Please enter your old password.";
            isValid = false;
        } 

        if (newPasswordInput.value.trim() === "") {
            newPasswordErrorSpan.textContent = "Please enter a new password.";
            isValid = false;
        } else if (newPasswordInput.value.length < 6) {
            newPasswordErrorSpan.textContent = "New password must be at least 6 characters long.";
            isValid = false;
        }

        if (confirmNewPasswordInput.value.trim() === "") {
            confirmNewPasswordErrorSpan.textContent = "Please confirm your new password.";
            isValid = false;
        }

        if (newPasswordInput.value !== confirmNewPasswordInput.value) {
            confirmNewPasswordErrorSpan.textContent = "New passwords do not match.";
            isValid = false;
        }

        if (isValid) {
            form.submit();
        }
    });

    displayUrlErrors();
});