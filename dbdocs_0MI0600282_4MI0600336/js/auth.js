document.addEventListener('DOMContentLoaded', () => {
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const toRegisterBtn = document.getElementById('toRegisterBtn');
    const toLoginBtn = document.getElementById('toLoginBtn');
    
    const loginSection = document.getElementById('loginSection');
    const registerSection = document.getElementById('registerSection');

    if (toRegisterBtn) {
        toRegisterBtn.addEventListener('click', () => {
            loginSection.classList.add('hidden');
            registerSection.classList.remove('hidden');
        });
    }

    if (toLoginBtn) {
        toLoginBtn.addEventListener('click', () => {
            registerSection.classList.add('hidden');
            loginSection.classList.remove('hidden');
        });
    }

    if (loginBtn) {
        loginBtn.addEventListener('click', () => authenticate('login'));
    }
    if (registerBtn) {
        registerBtn.addEventListener('click', () => authenticate('register'));
    }
});

function showError(elementId, message) {
    const el = document.getElementById(elementId);
    el.textContent = message;
    el.className = 'ui-message ui-error';
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 4000);
}

async function authenticate(action) {
    const isLogin = action === 'login';
    const emailInput = document.getElementById(isLogin ? 'loginEmail' : 'registerEmail');
    const passwordInput = document.getElementById(isLogin ? 'loginPassword' : 'registerPassword');
    const msgId = isLogin ? 'loginMessage' : 'registerMessage';

    if (!emailInput.value || !passwordInput.value) {
        showError(msgId, 'Please fill in both email and password.');
        return;
    }

    try {
        const res = await fetch('api/auth.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: action, email: emailInput.value, password: passwordInput.value })
        });
        
        const data = await res.json();
        
        if (data.success) {
            window.location.href = 'dashboard.php';
        } else {
            showError(msgId, data.message);
        }
    } catch (err) {
        showError(msgId, 'A server connection error occurred.');
    }
}