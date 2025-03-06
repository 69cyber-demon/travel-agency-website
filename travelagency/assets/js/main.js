document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.main_form');
    const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    const submitButton = form.querySelector('input[type="submit"]');

    // Form validation
    form.addEventListener('submit', function(event) {
        let valid = true;
        inputs.forEach(input => {
            if (input.value.trim() === '') {
                valid = false;
                input.style.borderColor = 'red';
                input.nextElementSibling.textContent = 'This field is required';
            } else {
                input.style.borderColor = '#ccc';
                input.nextElementSibling.textContent = '';
            }
        });

        if (!valid) {
            event.preventDefault();
        }
    });

    // Clear error message on input
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (input.value.trim() !== '') {
                input.style.borderColor = '#ccc';
                input.nextElementSibling.textContent = '';
            }
        });
    });
});