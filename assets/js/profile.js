window.onload = function() {
    var updateBtn = document.getElementById("updateBtn");
    updateBtn.onclick = function() {
        var firstName = document.getElementById("firstname").value.trim();
        var lastName = document.getElementById("lastname").value.trim();
        var email = document.getElementById("email").value.trim();
        
        var formError = document.getElementById("formError");
        formError.innerHTML = "";
        
        var valid = true;

        if (firstName === "") {
            document.getElementById("firstError").innerHTML = "First name is required.";
            valid = false;
        } else {
            document.getElementById("firstError").innerHTML = "";
        }

        if (lastName === "") {
            document.getElementById("lastError").innerHTML = "Last name is required.";
            valid = false;
        } else {
            document.getElementById("lastError").innerHTML = "";
        }
        

        if (email === "") {
            document.getElementById("emailError").innerHTML = "Email is required.";
            valid = false;
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            document.getElementById("emailError").innerHTML = "Invalid email format.";
            valid = false;
        } else {
            document.getElementById("emailError").innerHTML = "";
        }
        
        if (!valid) {
            formError.innerHTML = "Please fix the errors above.";
            return false;
        }
        
        alert("Profile update successful!"); 
        return true;
    };
};