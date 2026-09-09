const user = document.getElementById('user');
const dropdown = document.getElementById('adminDropdown');

const filtered = document.getElementById('filtered');
const filterHide = document.getElementById('filter-hide');

function hideElem(element) {
    if (!element) return;
    element.classList.remove('active');
    if (element === dropdown && user) user.setAttribute('aria-expanded', 'false');
}
function toggleElem(element) {
    if (!element) return;
    const opening = !element.classList.contains('active');
    element.classList.toggle('active', opening);
    if (element === dropdown && user) user.setAttribute('aria-expanded', String(opening));
}

window.addEventListener("click", () => {
    hideElem(filterHide);
    hideElem(dropdown);
});

if (user) {
    user.addEventListener("click", (e) => {
        e.stopPropagation();
        toggleElem(dropdown);
    });
}

if (filtered) {
    filtered.addEventListener("click", (e) => {
        e.stopPropagation();
        toggleElem(filterHide);
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const albumModalEl = document.getElementById('albumModal');
    const albumForm = document.getElementById('albumForm');
    const albumFormTitle = document.getElementById('albumFormTitle');
    const albumFormSub = document.getElementById('albumFormSub');
    const albumModalIcon = document.getElementById('albumModalIcon');
    const submitBtnLabel = document.getElementById('submitBtnLabel');
    const submitBtnIcon = document.getElementById('submitBtnIcon');
    const imageInput = document.getElementById('upload-album');
    const imageHint = document.getElementById('imageHint');
    const CREATE_SUB = "Sets the album's name, category, link and cover photo.";
    const CREATE_HINT = "JPG or PNG. This becomes the album's cover photo.";
    const EDIT_HINT = "Leave empty to keep the current cover photo.";

    function clearEditedRow() {
        document.querySelectorAll('#albumTableBody tr.row-editing').forEach(tr => tr.classList.remove('row-editing'));
    }

    function enterEditMode(button) {
        document.getElementById('album-id').value = button.dataset.id;
        document.getElementById('upload-name').value = button.dataset.name;
        document.getElementById('album-category').value = button.dataset.category;
        document.getElementById('upload-link').value = button.dataset.link;

        if (albumFormTitle) albumFormTitle.textContent = 'Edit Album';
        if (albumFormSub) albumFormSub.textContent = `Editing "${button.dataset.name}".`;
        if (albumModalIcon) { albumModalIcon.classList.remove('fa-folder-plus'); albumModalIcon.classList.add('fa-pen'); }
        if (submitBtnLabel) submitBtnLabel.textContent = 'Save Changes';
        if (submitBtnIcon) { submitBtnIcon.classList.remove('fa-plus'); submitBtnIcon.classList.add('fa-check'); }
        if (imageInput) imageInput.removeAttribute('required');
        if (imageHint) imageHint.textContent = EDIT_HINT;

        clearEditedRow();
        const row = button.closest('tr');
        if (row) row.classList.add('row-editing');
    }

    function resetAlbumForm() {
        if (!albumForm) return;
        albumForm.reset();
        document.getElementById('album-id').value = '';

        if (albumFormTitle) albumFormTitle.textContent = 'Create New Album';
        if (albumFormSub) albumFormSub.textContent = CREATE_SUB;
        if (albumModalIcon) { albumModalIcon.classList.remove('fa-pen'); albumModalIcon.classList.add('fa-folder-plus'); }
        if (submitBtnLabel) submitBtnLabel.textContent = 'Create Album';
        if (submitBtnIcon) { submitBtnIcon.classList.remove('fa-check'); submitBtnIcon.classList.add('fa-plus'); }
        if (imageInput) imageInput.setAttribute('required', 'required');
        if (imageHint) imageHint.textContent = CREATE_HINT;

        clearEditedRow();
    }

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () { enterEditMode(this); });
    });

    if (albumModalEl) {
        albumModalEl.addEventListener('hidden.bs.modal', resetAlbumForm);
    }

    if (albumForm) {
        albumForm.addEventListener('submit', () => {
            const btn = document.getElementById('albumSubmitBtn');
            setTimeout(() => { if (btn) btn.disabled = true; }, 0);
        });
    }

    const albumSearch = document.getElementById('albumSearch');
    const albumTableBody = document.getElementById('albumTableBody');
    const noAlbumResults = document.getElementById('noAlbumResults');
    if (albumSearch && albumTableBody) {
        albumSearch.addEventListener('input', () => {
            const query = albumSearch.value.trim().toLowerCase();
            let visibleCount = 0;
            albumTableBody.querySelectorAll('tr[data-album-name]').forEach(row => {
                const match = row.dataset.albumName.includes(query);
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });
            if (noAlbumResults) noAlbumResults.classList.toggle('d-none', visibleCount !== 0);
        });
    }

    const addImageModalEl = document.getElementById('addImageModal');
    const addToAlbumForm = document.getElementById('addToAlbumForm');
    if (addToAlbumForm) {
        const addImageSubmit = document.getElementById('addImageSubmit');
        const responseBox = document.getElementById('response');

        $(addToAlbumForm).on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const originalLabel = addImageSubmit ? addImageSubmit.innerHTML : '';

            if (addImageSubmit) {
                addImageSubmit.disabled = true;
                addImageSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Uploading...';
            }

            $.ajax({
                url: 'actions/upload_album_img.php',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    const ok = /successfully/i.test(response);
                    if (responseBox) {
                        responseBox.innerHTML = `<div class="alert ${ok ? 'alert-success' : 'alert-danger'} mb-0">${response}</div>`;
                    }
                    if (ok) addToAlbumForm.reset();
                },
                error: function () {
                    if (responseBox) {
                        responseBox.innerHTML = '<div class="alert alert-danger mb-0">Something went wrong. Please try again.</div>';
                    }
                },
                complete: function () {
                    if (addImageSubmit) {
                        addImageSubmit.disabled = false;
                        addImageSubmit.innerHTML = originalLabel;
                    }
                }
            });
        });

        if (addImageModalEl) {
            addImageModalEl.addEventListener('hidden.bs.modal', () => {
                addToAlbumForm.reset();
                if (responseBox) responseBox.innerHTML = '';
            });
        }
    }
});
