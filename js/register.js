document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');
  const username = document.getElementById('username');
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('confirm_password');
  const email = document.getElementById('email');
  const phoneInput = document.getElementById('phone');
  const msgBox = document.getElementById('ajaxMessage');
  const avatarInput = document.getElementById('avatar');

  let timer;
  username.addEventListener("input", () => {
    clearTimeout(timer);
    timer = setTimeout(checkUsername, 450);
  });

  function checkUsername() {
    const val = username.value.trim();
    if (!val) return;

    fetch('../includes/check_username.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'username=' + encodeURIComponent(val)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showMsg('Username is Available ✓', false);
      } else {
        showMsg('Username is already taken ✕', true);
      }
    })
    .catch(err => console.error(err));
  }

  const showMsg = (html, isError = true) => {
    msgBox.innerHTML = html;
    msgBox.style.backgroundColor = isError ? '#eeb6b0ff' : '#99d69cff';
    msgBox.style.display = 'block';
    msgBox.style.padding = '10px';
    msgBox.style.marginBottom = '15px';
    msgBox.style.borderRadius = '8px';
    msgBox.style.fontSize = '16px';
    msgBox.style.textAlign = 'center';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  function clearMsg() {
    msgBox.innerHTML = '';
  }

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    clearMsg();

    const phone = phoneInput.value.trim(); // <-- for removal of the outer values
    const clientErrors = validateClientSide(phone);
    if (clientErrors.length) {
      showMsg(clientErrors.join('<br>'), true);
      return;
    }

    const formData = new FormData(form);

    fetch('../includes/register_handler.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(json => {
      if (json.success) {
        showMsg(json.message || 'Registered successfully!', false);
        form.reset();
      } else {
        const errHtml = (json.errors && json.errors.join('<br>')) || (json.message) || 'Registration failed';
        showMsg(errHtml, true);
      }
    })
    .catch(err => {
      console.error(err);
      showMsg('Server error. Check console.', true);
    });
  });

  function validateClientSide(phone) {
    const errors = [];
    const pwd = password.value;
    const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    if (!pwdRegex.test(pwd)) errors.push('Password must be ≥8 chars and include uppercase, lowercase, number and special char.');
    if (pwd !== confirmPassword.value) errors.push('Passwords do not match.');
    if (!/^[\w.\-]{3,30}$/.test(username.value.trim())) errors.push('Username must be 3–30 chars (letters, numbers, ., _, -).');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) errors.push('Invalid email format.');


    if (avatarInput.files.length) {
        const file = avatarInput.files[0];
        const maxSizeMB = 2; // max 2MB
        if (!file.type.startsWith('image/')) errors.push('Avatar must be an image.');
        if (file.size > maxSizeMB * 1024 * 1024) errors.push(`Avatar must be ≤ ${maxSizeMB}MB.`);
    }
    return errors;
  }
});
