// function validateForm() {
  let firstname = document.getElementById("firstname").value.trim();
  let lastname = document.getElementById("lastname").value.trim();
  let dob = document.getElementById("dob").value.trim();
  let licenseEl = document.getElementById("license");
  let license = licenseEl ? licenseEl.value.trim() : '';
  let email = document.getElementById("email").value.trim();
  let password = document.getElementById("password").value;
  let confirm = document.getElementById("confirm").value;
  let role = document.getElementById("role").value;

  let valid = true;

  document.getElementById("firstError").innerHTML = "";
  document.getElementById("lastError").innerHTML = "";
  document.getElementById("dobError").innerHTML = "";
  document.getElementById("licenseError").innerHTML = "";
  document.getElementById("emailError").innerHTML = "";
  document.getElementById("passwordError").innerHTML = "";
  document.getElementById("confirmError").innerHTML = "";
  document.getElementById("formError").innerHTML = "";

  if (firstname === "" || firstname.length < 2) {
    document.getElementById("firstError").innerHTML = "First name must be at least 2 characters.";
    valid = false;
  }

  if (lastname === "" || lastname.length < 2) {
    document.getElementById("lastError").innerHTML = "Last name must be at least 2 characters.";
    valid = false;
  }

  if (dob === "") {
    document.getElementById("dobError").innerHTML = "Date of Birth is required.";
    valid = false;
  }

  if (role === 'driver') {
    if (license === "" || license.length < 6 || license.length > 15) {
      document.getElementById("licenseError").innerHTML = "License number must be 6–15 characters.";
      valid = false;
    }
    var carModel = document.getElementById('car_model').value.trim();
    var carOwner = document.getElementById('car_owner').value.trim();
    if (carModel === "") { document.getElementById('carModelError').innerHTML = 'Car model required.'; valid = false; }
    if (carOwner === "") { document.getElementById('carOwnerError').innerHTML = 'Car owner required.'; valid = false; }
  }

  let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (email === "" || !emailPattern.test(email)) {
    document.getElementById("emailError").innerHTML = "Enter a valid email address.";
    valid = false;
  }

  if (password.length < 6) {
    document.getElementById("passwordError").innerHTML = "Password must be at least 6 characters.";
    valid = false;
  }

  if (confirm !== password) {
    document.getElementById("confirmError").innerHTML = "Passwords do not match.";
    valid = false;
  }

  if (!valid) {
    document.getElementById("formError").innerHTML = "Please fix the above errors.";
    return false;
  }

  return true;
}


