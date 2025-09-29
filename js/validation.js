

function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function validatePassword(password) {
    return password.length >= 6;
}

function validatePhone(phone) {
    const phoneRegex = /^[0-9+\-\s()]+$/;
    return phoneRegex.test(phone) && phone.length >= 10;
}

function validateForm(formId) {
    const form = document.getElementById(formId);
    let isValid = true;
    let errorMessage = '';

    
    const errorDiv = document.getElementById('error-message');
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }

   
    const fields = form.querySelectorAll('input, select, textarea');
    
    fields.forEach(field => {
        
        field.style.borderColor = '#ddd';
        
       
        if (field.hasAttribute('required') && !field.value.trim()) {
            field.style.borderColor = '#dc3545';
            errorMessage += `${field.getAttribute('data-label') || field.name} is required.\n`;
            isValid = false;
        }
        
       
        if (field.type === 'email' && field.value && !validateEmail(field.value)) {
            field.style.borderColor = '#dc3545';
            errorMessage += 'Please enter a valid email address.\n';
            isValid = false;
        }
        
        
        if (field.type === 'password' && field.value && !validatePassword(field.value)) {
            field.style.borderColor = '#dc3545';
            errorMessage += 'Password must be at least 6 characters long.\n';
            isValid = false;
        }
        
        
        if (field.name === 'phone' && field.value && !validatePhone(field.value)) {
            field.style.borderColor = '#dc3545';
            errorMessage += 'Please enter a valid phone number.\n';
            isValid = false;
        }
    });

    
    const password = form.querySelector('input[name="password"]');
    const confirmPassword = form.querySelector('input[name="confirm_password"]');
    
    if (password && confirmPassword && password.value !== confirmPassword.value) {
        password.style.borderColor = '#dc3545';
        confirmPassword.style.borderColor = '#dc3545';
        errorMessage += 'Passwords do not match.\n';
        isValid = false;
    }

  
    if (!isValid && errorDiv) {
        errorDiv.innerHTML = errorMessage.replace(/\n/g, '<br>');
        errorDiv.style.display = 'block';
        errorDiv.scrollIntoView({ behavior: 'smooth' });
    }

    return isValid;
}


document.addEventListener('DOMContentLoaded', function() {
    
    const emailFields = document.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (this.value && !validateEmail(this.value)) {
                this.style.borderColor = '#dc3545';
                showFieldError(this, 'Please enter a valid email address');
            } else {
                this.style.borderColor = '#28a745';
                hideFieldError(this);
            }
        });
    });

    
    const passwordFields = document.querySelectorAll('input[type="password"]');
    passwordFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (this.value && !validatePassword(this.value)) {
                this.style.borderColor = '#dc3545';
                showFieldError(this, 'Password must be at least 6 characters long');
            } else if (this.value) {
                this.style.borderColor = '#28a745';
                hideFieldError(this);
            }
        });
    });
});

function showFieldError(field, message) {
    let errorSpan = field.parentNode.querySelector('.field-error');
    if (!errorSpan) {
        errorSpan = document.createElement('span');
        errorSpan.className = 'field-error';
        errorSpan.style.color = '#dc3545';
        errorSpan.style.fontSize = '12px';
        errorSpan.style.marginTop = '5px';
        errorSpan.style.display = 'block';
        field.parentNode.appendChild(errorSpan);
    }
    errorSpan.textContent = message;
}

function hideFieldError(field) {
    const errorSpan = field.parentNode.querySelector('.field-error');
    if (errorSpan) {
        errorSpan.remove();
    }
}
