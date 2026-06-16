document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById('logoutBtn');
    const backBtn = document.getElementById('backBtn');
    const generateForm = document.getElementById('generateForm');
    const resultArea = document.getElementById('resultArea');

    if(logoutBtn) logoutBtn.addEventListener('click', logout);
    
    if(backBtn) {
        backBtn.addEventListener('click', () => {
            resultArea.classList.add('hidden');
            generateForm.classList.remove('hidden');
            document.getElementById('jsonInput').value = ''; 
            document.getElementById('javadocBtn').classList.add('hidden');
        });
    }

    if(generateForm) {
        generateForm.addEventListener('submit', (e) => {
            e.preventDefault();
            generateDocs();
        });
    }
});

function showError(elementId, message) {
    const el = document.getElementById(elementId);
    el.textContent = message;
    el.className = 'ui-message ui-error';
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

async function generateDocs() {
    const title = document.getElementById('projTitle').value;
    const structure = document.getElementById('jsonInput').value;
    const generateBtn = document.getElementById('generateBtn');
    
    const originalBtnText = generateBtn.textContent;
    generateBtn.textContent = "Generating...";
    generateBtn.disabled = true;
    
    try {
        const res = await fetch('api/generate.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({title, structure})
        });
        
        const data = await res.json();
        
        if (data.success) {
            document.getElementById('generateForm').classList.add('hidden');
            document.getElementById('resultArea').classList.remove('hidden');
            document.getElementById('resultBtn').href = data.docs_url;

            if (data.javadoc_url) {
                const javadocBtn = document.getElementById('javadocBtn');
                javadocBtn.href = data.javadoc_url;
                javadocBtn.classList.remove('hidden');
            }
        } else {
            showError('genMessage', "Error: " + data.message);
        }
    } catch (err) {
        showError('genMessage', 'Generation error occurred.');
    } finally {
        generateBtn.textContent = originalBtnText;
        generateBtn.disabled = false;
    }
}