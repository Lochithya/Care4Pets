document.addEventListener('DOMContentLoaded', function () {
    const tabs = Array.from(document.querySelectorAll('.tab'));
    const contents = Array.from(document.querySelectorAll('.tab-content'));

    function activateTab(tabEl) {
        // remove active from all
        tabs.forEach(t => t.classList.remove('active'));
        contents.forEach(c => {
            c.classList.remove('active');
            c.setAttribute('aria-hidden', 'true');
        });

        tabEl.classList.add('active');
        const target = tabEl.dataset.target;
        const targetEl = document.getElementById(target);
        if (targetEl) {
            targetEl.classList.add('active');
            targetEl.setAttribute('aria-hidden', 'false');
        }

        // optionally update URL param without reload
        try {
            const url = new URL(window.location);
            url.searchParams.set('tab', target);
            window.history.replaceState({}, '', url);
        } catch (e) { /* ignore if no URL support */ }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function () { activateTab(tab); });
    });

    // open tab from query param if provided
    const params = new URLSearchParams(window.location.search);
    const tabParam = params.get('tab');
    if (tabParam) {
        const t = tabs.find(x => x.dataset.target === tabParam);
        if (t) activateTab(t);
    }

    const form = document.querySelector('.profile-form');
    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const phone = document.getElementById('phone');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password') ;
    const avatarInput = document.getElementById('avatar');
    const msgBox = document.getElementById('ajaxMessage');

    // Show message
    const showMsg = (html, isError = true) => {
        msgBox.innerHTML = html + ' <span id="dismissMsg" style="cursor:pointer;font-weight:bold">&nbsp;&nbsp;✕</span>';
        msgBox.style.display = 'block';
        msgBox.style.backgroundColor = isError ? '#e9cac7ff' : '#c0e9c2ff';
        msgBox.style.padding = '10px';
        msgBox.style.borderRadius = '8px';
        msgBox.style.textAlign = 'center';
        document.getElementById('dismissMsg').addEventListener('click', () => { 
            msgBox.style.display='none'; 
            if(!isError){
                window.location.reload();
            }
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });                     // auto scrolling
    };

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
            if (!data.success) {
                showMsg('Username is already taken ', true);
            }
        }).catch(err => console.error(err));
    }

    function clearMsg() {
        msgBox.innerHTML = '';                           // clearing the message box in the form submit
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        clearMsg();

        // Client-side validation
        const errors = [];
        
        const pwd = password.value.trim();
        const confirmPwd = confirmPassword.value.trim();

        if (pwd === '' && confirmPwd === '') {
                                                                // do nothing → user didn’t change password
        } else if (pwd === '' || confirmPwd === '') {
                                                                                        // only one filled
             errors.push('Both password fields must be filled to change your password.');
        } else {
                                                                                    // both filled → validate strength + match
            const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            if (!pwdRegex.test(pwd)) {
                errors.push('Password must be ≥8 chars and include uppercase, lowercase, number and special char.');
            }
            if (pwd !== confirmPwd) {
                errors.push('Passwords do not match.');
            }
        }

        if (!/^[\w.\-]{3,30}$/.test(username.value.trim())) errors.push('Username must be 3–30 chars (letters, numbers, ., _, -).');
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) errors.push('Invalid email format.');
        
        if (avatarInput.files.length) {
            const file = avatarInput.files[0];
            if (!file.type.startsWith('image/')) errors.push('Avatar must be an image.');
            if (file.size > 2*1024*1024) errors.push('Avatar must be ≤2MB.');
        }

        if (errors.length) { showMsg(errors.join('<br>')); return; }

        // Send form via AJAX
        const formData = new FormData(form);
        fetch('../includes/dashboard_handler.php', { 
            method: 'POST', 
            body: formData 
        })
        .then(res => res.json())
        .then(json => {
            if (json.success) {
            showMsg('Profile updated successfully ', false);
            
            } else {
            showMsg((json.errors || [json.message]).join('<br>'));
            }
        })
        .catch(err => { console.error(err); showMsg('Server error'); });
    });

});