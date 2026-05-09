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

    // ---------- Confirmation Modal ----------
    const saveBtn       = document.getElementById('saveChangesBtn');
    const confirmOverlay = document.getElementById('confirmOverlay');
    const confirmOk     = document.getElementById('confirmOk');
    const confirmCancel = document.getElementById('confirmCancel');
    const profileForm   = document.getElementById('profileForm');

    if (saveBtn && confirmOverlay) {
        saveBtn.addEventListener('click', () => {
            confirmOverlay.classList.add('active');
        });

        confirmCancel.addEventListener('click', () => {
            confirmOverlay.classList.remove('active');
        });

        // Close on overlay background click
        confirmOverlay.addEventListener('click', (e) => {
            if (e.target === confirmOverlay) confirmOverlay.classList.remove('active');
        });

        confirmOk.addEventListener('click', () => {
            confirmOverlay.classList.remove('active');
            // Fire the submit event so the AJAX handler runs (not a native POST)
            form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
        });
    }

    // ---------- Password visibility toggles ----------
    document.querySelectorAll('.toggle-pw').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            if (!input) return;
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.innerHTML = isHidden ? '&#128064;' : '&#128065;';
        });
    });

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
        msgBox.innerHTML = `
            <div class="msg-inner">
                <div class="msg-icon">${isError ? '✕' : '✓'}</div>
                <div class="msg-text">${html}</div>
                <button class="msg-close" id="dismissMsg">&times;</button>
            </div>
        `;
        msgBox.className = isError ? 'ajax-toast toast-error' : 'ajax-toast toast-success';
        msgBox.style.display = 'block';

        // Animate in
        requestAnimationFrame(() => msgBox.classList.add('toast-visible'));

        document.getElementById('dismissMsg').addEventListener('click', () => {
            msgBox.classList.remove('toast-visible');
            setTimeout(() => {
                msgBox.style.display = 'none';
                if (!isError) window.location.reload();
            }, 300);
        });

        // Auto-dismiss success after 4s
        if (!isError) {
            setTimeout(() => {
                if (msgBox.style.display !== 'none') {
                    msgBox.classList.remove('toast-visible');
                    setTimeout(() => {
                        msgBox.style.display = 'none';
                        window.location.reload();
                    }, 300);
                }
            }, 4000);
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
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