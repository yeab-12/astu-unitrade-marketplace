document.addEventListener('DOMContentLoaded', () => {
    // Basic form validation for signup
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const telegram = document.getElementById('telegram_username').value;
            const phone = document.getElementById('phone').value;
            const ugr = document.getElementById('ugr_id').value;

            if (password.length < 8 || password.length > 10) {
                e.preventDefault();
                alert('Password must be between 8 and 10 characters.');
                return;
            }

            if (!telegram.startsWith('@')) {
                e.preventDefault();
                alert('Telegram username must start with @');
                return;
            }

            const phonePattern = /^\+2519\d{8}$/;
            if (!phonePattern.test(phone)) {
                e.preventDefault();
                alert('Phone number must be in format +2519XXXXXXXX');
                return;
            }

            const ugrPattern = /^UGR\/\d{5}\/\d{2}$/;
            if (!ugrPattern.test(ugr)) {
                e.preventDefault();
                alert('UGR ID must be in format UGR/XXXXX/XX');
                return;
            }
        });
    }

    // Image upload preview (sell.php)
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
