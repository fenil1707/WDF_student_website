document.getElementById('loginForm').addEventListener('submit', function (event) {
  event.preventDefault();

  var studentId = document.getElementById('studentId').value;
  var password = document.getElementById('password').value;

  localStorage.removeItem('loginError');

  if (email === '' || password=== '') {
    localStorage.setItem('loginError', 'Please fill in all the details!');
    localStorage.removeItem('studentId');
    localStorage.removeItem('password');
    window.location.href = 'profile.html';
    return;
  }

  localStorage.setItem('studentEmail', email);
  localStorage.setItem('password', password);

  window.location.href = 'profile.html';
});

if (window.location.pathname.endsWith('profile.html')) {
  var error = localStorage.getItem('loginError');
  var errorBox = document.getElementById('showError');

  if (error) {
    errorBox.style.display = 'block';
    errorBox.textContent = error;
    document.getElementById('showId').textContent = '';
    document.getElementById('showPassword').textContent = '';
  } else {
    errorBox.style.display = 'none';
    document.getElementById('showId').textContent = localStorage.getItem('studentId');
    document.getElementById('showPassword').textContent = localStorage.getItem('password');
  }
}