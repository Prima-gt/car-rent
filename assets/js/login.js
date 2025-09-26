window.onload = function () {

  function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    const rememberMeCheckbox = document.getElementById('rememberMe');
    const emailInput = document.getElementById('email');
    const rememberedUser = getCookie('remembered_user');

    if (rememberedUser) {
        emailInput.value = rememberedUser;
        rememberMeCheckbox.checked = true;
    } else {
        rememberMeCheckbox.checked = false;
        emailInput.value = '';
    }
  

  document.getElementById("loginBtn").onclick = function () {
    var email = document.getElementById("email").value.toLowerCase().trim();
    var password = document.getElementById("password").value;

    var emailError = document.getElementById("emailError");
    var passwordError = document.getElementById("passwordError");
    var formError = document.getElementById("formError");

    emailError.innerHTML = "";
    passwordError.innerHTML = "";
    formError.innerHTML = "";

    var valid = true;

    if (email === "") {
      emailError.innerHTML = "Email is required.";
      valid = false;
    }
``
    if (password === "") {
      passwordError.innerHTML = "Password is required.";
      valid = false;
    }

    if (!valid) {
      formError.innerHTML = "Please fix the errors above.";
      return false;
    }

    return true;
  };
};
