document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById('logoutBtn');
    const changePasswordForm = document.getElementById('changePasswordForm');

    if (logoutBtn) logoutBtn.addEventListener('click', logout);

    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', (e) => {
            e.preventDefault();
            changePassword();
        });
    }

    loadProfile();
});

function showMessage(elementId, message, isError = true) {
    const el = document.getElementById(elementId);
    el.textContent = message;
    el.className = 'ui-message ' + (isError ? 'ui-error' : 'ui-success');
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 4000);
}

async function logout() {
    try {
        await fetch('api/auth.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'logout' })
        });

        window.location.href = 'index.php';
    } catch (err) {
        console.error("Logout Error:", err);
    }
}

async function loadProfile() {
    try {
        const res = await fetch('api/profile.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'get_profile' })
        });

        const data = await res.json();

        if (data.success) {
            document.getElementById('profileEmail').textContent = data.email;
            const createdDate = new Date(data.created_at);
            document.getElementById('profileCreatedAt').textContent = createdDate.toLocaleDateString(undefined, {
                year: 'numeric', month: 'long', day: 'numeric'
            });
        } else {
            showMessage('profileMessage', data.message);
        }
    } catch (err) {
        showMessage('profileMessage', 'Could not load profile data.');
    }
}

async function changePassword() {
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const submitBtn = document.getElementById('changePasswordBtn');

    if (!currentPassword || !newPassword) {
        showMessage('passwordMessage', 'Please fill in both password fields.');
        return;
    }

    const originalBtnText = submitBtn.textContent;
    submitBtn.textContent = 'Updating...';
    submitBtn.disabled = true;

    try {
        const res = await fetch('api/profile.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                action: 'change_password',
                current_password: currentPassword,
                new_password: newPassword
            })
        });

        const data = await res.json();

        if (data.success) {
            showMessage('passwordMessage', data.message, false);
            document.getElementById('changePasswordForm').reset();
        } else {
            showMessage('passwordMessage', data.message);
        }
    } catch (err) {
        showMessage('passwordMessage', 'A server connection error occurred.');
    } finally {
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
    }
}
