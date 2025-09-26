window.onload = function () {
  const rememberMeCheckbox = document.getElementById('rememberMe');
  const emailInput = document.getElementById('email');
  const form = document.querySelector('form');



  if (form) {
    form.addEventListener('submit', function (e) {
      
      var email = document.getElementById('email').value.toLowerCase().trim();
      var password = document.getElementById('password').value;
      var emailError = document.getElementById('emailError');
      var passwordError = document.getElementById('passwordError');
      var formError = document.getElementById('formError');
      emailError.innerHTML = '';
      passwordError.innerHTML = '';
      formError.innerHTML = '';
      var valid = true;
      if (email === '') { emailError.innerHTML = 'Email is required.'; valid = false; }
      if (password === '') { passwordError.innerHTML = 'Password is required.'; valid = false; }
      if (!valid) { e.preventDefault(); formError.innerHTML = 'Please fix the errors above.'; return; }
     
    });
  }
};


