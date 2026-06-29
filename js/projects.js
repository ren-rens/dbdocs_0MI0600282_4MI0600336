const modal = document.getElementById('deleteModal');
const confirmBtn = document.getElementById('confirmDelete');

document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById('logoutBtn');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', logout);
    }
    loadProjects();

    document.getElementById('cancelDelete').addEventListener('click', () => {
        modal.classList.add('hidden');
    });
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

async function loadProjects() {
    const tableBody = document.getElementById('projectsTableBody');
    const table = document.getElementById('projectsTable');
    const emptyState = document.getElementById('emptyState');

    try {
        const res = await fetch('api/projects.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'list' })
        });

        const data = await res.json();

        if (!data.success) {
            showMessage('projectsMessage', data.message);
            return;
        }

        if (data.projects.length === 0) {
            table.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }

        tableBody.innerHTML = '';
        data.projects.forEach(project => {
            tableBody.appendChild(buildProjectRow(project));
        });
    } catch (err) {
        showMessage('projectsMessage', 'Could not load your schemas.');
    }
}

function buildProjectRow(project) {
    const row = document.createElement('tr');
    row.dataset.id = project.id;

    const createdDate = new Date(project.created_at);
    const formattedDate = createdDate.toLocaleDateString(undefined, {
        year: 'numeric', month: 'short', day: 'numeric'
    });

    const titleCell = document.createElement('td');
    titleCell.textContent = project.title;

    const dateCell = document.createElement('td');
    dateCell.textContent = formattedDate;

    const actionsCell = document.createElement('td');
    actionsCell.className = 'actions-cell-wrapper';

    const topRow = document.createElement('div');
    topRow.className = 'action-group';
    
    const viewLink = document.createElement('a');
    viewLink.href = project.docs_url;
    viewLink.target = '_blank';
    viewLink.className = 'btn-view';
    viewLink.textContent = 'View Custom Docs';
    topRow.appendChild(viewLink);

    if (project.javadoc_url) {
        const javadocLink = document.createElement('a');
        javadocLink.href = project.javadoc_url;
        javadocLink.target = '_blank';
        javadocLink.className = 'btn-view';
        javadocLink.textContent = 'View Javadoc';
        topRow.appendChild(javadocLink);
    }
    actionsCell.appendChild(topRow);

    const folderName = project.docs_url.split('/')[1];
    const midRow = document.createElement('div');
    midRow.className = 'action-group';

    const zipAll = document.createElement('a');
    zipAll.href = `api/download_zip.php?folder=${folderName}&type=all`;
    zipAll.className = 'btn-dl';
    zipAll.textContent = 'Full Project ZIP';
    midRow.appendChild(zipAll);

    const zipDocs = document.createElement('a');
    zipDocs.href = `api/download_zip.php?folder=${folderName}&type=docs`;
    zipDocs.className = 'btn-dl';
    zipDocs.textContent = 'Custom Docs ZIP';
    midRow.appendChild(zipDocs);

    const zipSrc = document.createElement('a');
    zipSrc.href = `api/download_zip.php?folder=${folderName}&type=src`;
    zipSrc.className = 'btn-dl';
    zipSrc.textContent = 'Java Classes ZIP';
    midRow.appendChild(zipSrc);

    actionsCell.appendChild(midRow);

    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.className = 'btn-del';
    deleteBtn.textContent = 'Delete';
    deleteBtn.addEventListener('click', () => deleteProject(project.id, row));
    actionsCell.appendChild(deleteBtn);

    row.appendChild(titleCell);
    row.appendChild(dateCell);
    row.appendChild(actionsCell);

    return row;
}

function deleteProject(id, row) {
    modal.classList.remove('hidden');

    confirmBtn.onclick = async () => {
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Deleting...';

        modal.classList.add('hidden');

        try {
            const res = await fetch('api/projects.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ action: 'delete', id: id })
            });
            const data = await res.json();

            if (data.success) {
                row.remove();
                
                const tableBody = document.getElementById('projectsTableBody');
                if (tableBody.children.length === 0) {
                    document.getElementById('projectsTable').classList.add('hidden');
                    document.getElementById('emptyState').classList.remove('hidden');
                }
            } else {
                showMessage('projectsMessage', data.message);
            }
        } catch (err) {
            showMessage('projectsMessage', 'Error deleting project.');
        } finally {
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Delete';
        }
    };
}
