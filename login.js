

(function () {
  var form = document.querySelector('form');
  if (!form) return; // no form on the page

  form.addEventListener('submit', function (event) {
    var emailInput = document.getElementById('email');
    var passwordInput = document.getElementById('password');
    var remember = document.getElementById('remember');

    var email = emailInput ? String(emailInput.value).trim() : '';
    var password = passwordInput ? String(passwordInput.value) : '';

    if (!email || !password) {
      event.preventDefault();
      alert('Please enter both email and password.');
      return;
    }

    if (password.length < 8) {
      event.preventDefault();
      alert('Password must be at least 8 characters long.');
      return;
    }

    try {
      if (remember && remember.checked) {
        localStorage.setItem('studentEmail', email);
      } else {
        localStorage.removeItem('studentEmail');
      }
    } catch (e) {
      console.warn('localStorage unavailable', e);
    }

  });
})();
