

(function () {
  var form = document.querySelector('form');
  if (!form) return; // no form on the page

  form.addEventListener('submit', function (event) {
    // read inputs
    var emailInput = document.getElementById('email');
    var passwordInput = document.getElementById('password');
    var remember = document.getElementById('remember');

    var email = emailInput ? String(emailInput.value).trim() : '';
    var password = passwordInput ? String(passwordInput.value) : '';

    // basic validation
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

    // store when requested
    try {
      if (remember && remember.checked) {
        localStorage.setItem('studentEmail', email);
      } else {
        localStorage.removeItem('studentEmail');
      }
    } catch (e) {
      // localStorage may be unavailable in some contexts; ignore errors
      console.warn('localStorage unavailable', e);
    }

    // allow the form to submit to home.html (native form action)
    // optionally you could redirect via JS: window.location.href = 'home.html';
  });
})();
