document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById('logoutBtn');
    const backBtn = document.getElementById('backBtn');
    const backArrowBtn = document.getElementById('backArrowBtn');
    const generateForm = document.getElementById('generateForm');
    const resultArea = document.getElementById('resultArea');
    const importJsonBtn = document.getElementById('importJsonBtn');
    const jsonInput = document.getElementById('jsonInput');

    if (importJsonBtn && jsonInput) {
        importJsonBtn.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) {
                return;
            }

            const reader = new FileReader();
            
            reader.onload = (event) => {
                try {
                    const parsedData = JSON.parse(event.target.result);
                    jsonInput.value = JSON.stringify(parsedData, null, 4);
                } catch (error) {
                    showError('genMessage', 'Error: The selected file is not a valid JSON.');
                    importJsonBtn.value = '';
                }
            };

            reader.readAsText(file);
        });
    }

    if (logoutBtn) logoutBtn.addEventListener('click', logout);
    
    const resetToForm = () => {
        resultArea.classList.add('hidden');
        generateForm.classList.remove('hidden');
        document.getElementById('jsonInput').value = ''; 
        document.getElementById('projTitle').value = '';
        document.getElementById('javadocBtn').classList.add('hidden');
    };

    if (backBtn) backBtn.addEventListener('click', resetToForm);
    if (backArrowBtn) backArrowBtn.addEventListener('click', resetToForm);

    if (generateForm) {
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
        const res = await fetch('api/auth.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'logout' })
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = 'index.php';
        }
    } catch (err) {
        console.error("Logout Error:", err);
    }
}

async function generateDocs() {
    const title = document.getElementById('projTitle').value;
    const structure = document.getElementById('jsonInput').value;
    const generateBtn = document.getElementById('generateBtn');
    
    if (!title || !structure) {
        showError('genMessage', 'Error: Please fill in all required fields.');
        return;
    }
    
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