<?php
    $title = "Portfolio Images | Admin";
    require_once __DIR__ . '/../includes/admin-head.php';
    require_once __DIR__ . '/../includes/admin-header.php';
    require_once __DIR__ . '/../includes/db/images.php';
    require_once __DIR__ . '/../includes/flash.php';

    $imageCount = get_image_count($db);
?>
        <main class="container pb-5">
            <div class="page-heading mt-5">
                <div>
                    <span class="eyebrow">Manage</span>
                    <h1 class="h3 mb-0">Portfolio Images</h1>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-muted"><?php echo $imageCount; ?> image<?php echo $imageCount === 1 ? '' : 's'; ?> total</div>
                    <div class="album-actions">
                        <button type="button" class="action-fab gold" data-tooltip="Upload Portfolio Images" aria-label="Upload Portfolio Images" data-bs-toggle="modal" data-bs-target="#uploadImagesModal">
                            <i class="fas fa-cloud-arrow-up"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php display_flash(); ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-toolbar">
                        <div class="section-head mb-0">
                            <div class="icon-chip success"><i class="fas fa-layer-group"></i></div>
                            <div>
                                <h5 class="card-title mb-0">All Images</h5>
                                <p class="section-sub mb-0" id="imageCountLabel"><?php echo $imageCount; ?> total</p>
                            </div>
                        </div>
                        <div class="hidden-parent filter">
                            <button class='btn-filter' id="filtered" type="button">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <div id="filter-hide">
                                <ul id="filter-list">
                                    <li class="active" data-filter="all">All</li>
                                    <li data-filter="wedding">Wedding/Prenuptial</li>
                                    <li data-filter="birthday">Birthday</li>
                                    <li data-filter="others">Others</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="image_table table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Image Name</th>
                                    <th>Image Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <div class="modal fade" id="uploadImagesModal" tabindex="-1" aria-labelledby="uploadImagesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="section-head mb-0">
                            <div class="icon-chip gold"><i class="fas fa-cloud-arrow-up"></i></div>
                            <div>
                                <h5 class="modal-title mb-0" id="uploadImagesModalLabel">Upload Portfolio Images</h5>
                                <p class="section-sub mb-0">Add one or more photos to a category.</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="actions/upload.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="category" class="form-label">Category</label>
                                    <select name="category" id="category" class="form-control" required>
                                        <option value="" selected disabled>Choose a category</option>
                                        <option value="wedding">Wedding/Prenuptial</option>
                                        <option value="birthday">Birthday</option>
                                        <option value="others">Others</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="uploadImg" class="form-label">Images</label>
                                    <input type="file" accept="image/*" name="uploadImg[]" id="uploadImg" class="form-control" multiple required>
                                    <small class="field-hint">You can select multiple files at once.</small>
                                </div>
                                <div class="col-12 pt-2">
                                    <button type="submit" id="uploadBtn" class="btn btn-primary w-100" name="upload">
                                        <i class="fas fa-upload me-2"></i><span>Upload Files</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
        const filterList = document.getElementById('filter-list');
        const tableBody = document.querySelector('.image_table tbody');
        const imageCountLabel = document.getElementById('imageCountLabel');

        filterList.addEventListener('click', (event) => {
            const filter = event.target.getAttribute('data-filter');
            if (filter) {
                filterList.querySelectorAll('li').forEach(li => li.classList.remove('active'));
                event.target.classList.add('active');
                fetchImages(filter);
            }
        });

        function fetchImages(filter) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `actions/table-portfolio.php?filter=${filter}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    let html = '';
                    let response;
                    if (this.responseText == "no") {
                        html = '<tr><td colspan="4" class="text-center text-muted py-4">No images found</td></tr>';
                        tableBody.innerHTML = html;
                        if (imageCountLabel) imageCountLabel.textContent = '0 shown';
                    } else {
                        try {
                            response = JSON.parse(this.responseText);
                            response.forEach(img => {
                                html += `
                                    <tr>
                                        <td style="display:none" class="img_id">${img.id}</td>
                                        <td data-label="Image">
                                            <img src="image/${img.filename}" alt="${img.filename}" class="editable-image"
                                            data-id="${img.id}" title="Click to replace image">
                                            <input type="file" accept="image/*" class="d-none upload-input" data-id="${img.id}" />
                                        </td>
                                        <td data-label="Name">${img.filename}</td>
                                        <td data-label="Category">
                                        <select class="form-control category-select" data-id="${img.id}">
                                                <option value="wedding" ${img.category === 'wedding' ? 'selected' : ''}>Wedding/Prenuptial</option>
                                                <option value="birthday" ${img.category === 'birthday' ? 'selected' : ''}>Birthday</option>
                                                <option value="others" ${img.category === 'others' ? 'selected' : ''}>Others</option>
                                            </select>
                                        </td>
                                        <td data-label="Action">
                                            <button type="button" class="icon-btn icon-danger delete" data-tooltip="Delete" aria-label="Delete image" data-filename="${img.filename}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                            tableBody.innerHTML = html;
                            if (imageCountLabel) imageCountLabel.textContent = `${response.length} shown`;
                        } catch (error) {
                            console.error("Error Parsing JSON: ", error);
                        }
                    }
                } else {
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4">Error loading images.</td></tr>';
                }
            };
            xhr.send();
        }

        fetchImages('all');
        tableBody.addEventListener('click', (event) => {
            const deleteBtn = event.target.closest('.delete');
            if (deleteBtn) {
                const filename = deleteBtn.getAttribute('data-filename');
                confirmDelete(() => deleteImage(deleteBtn, filename));
            }
        });
        function deleteImage(target, filename) {
            const row = target.closest('tr');
            const imgIdElement = row.querySelector('.img_id');
            const img_id = imgIdElement ? imgIdElement.textContent : 'ID not found';

            const xhr = new XMLHttpRequest();

            xhr.open("POST", "actions/deleteImage.php",true);
            xhr.setRequestHeader("Content-type", 'application/x-www-form-urlencoded');

            xhr.onload = function(){
                if(xhr.status === 200){
                    fetchImages('all');
                }else{
                    console.error('Error deleting image:', xhr.statusText);
                }
            }
            xhr.onerror = function() {
                console.error('Error deleting image:', xhr.statusText);
            };

            const data = `click_delete_btn=true&img_id=${encodeURIComponent(img_id)}&filename=${encodeURIComponent(filename)}`;

            xhr.send(data);
        }

            tableBody.addEventListener('click', function(e) {
                if (e.target.classList.contains('editable-image')) {
                    const id = e.target.getAttribute('data-id');
                    const input = document.querySelector(`.upload-input[data-id="${id}"]`);
                    input.click();
                }
            });

            tableBody.addEventListener('change', function(e) {
                if (e.target.classList.contains('upload-input')) {
                    const id = e.target.getAttribute('data-id');
                    const file = e.target.files[0];
                    if (!file) return;

                    const formData = new FormData();
                    formData.append('new_image', file);
                    formData.append('img_id', id);

                    fetch('actions/updateImage.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => res.text())
                    .then(response => {
                        fetchImages('all');
                    }).catch(err => console.error(err));
                }
            });

            tableBody.addEventListener('change', function(e) {
                if (e.target.classList.contains('category-select')) {
                    const id = e.target.getAttribute('data-id');
                    const newCategory = e.target.value;

                    const formData = new FormData();
                    formData.append('img_id', id);
                    formData.append('new_category', newCategory);

                    fetch('actions/updateCategory.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => res.text())
                    .catch(err => console.error(err));
                }
            });

            const uploadForm = document.getElementById('uploadForm');
            if (uploadForm) {
                uploadForm.addEventListener('submit', () => {
                    const btn = document.getElementById('uploadBtn');
                    setTimeout(() => { if (btn) btn.disabled = true; }, 0);
                });
            }

        </script>
<?php
    require_once __DIR__ . '/../includes/admin-footer.php';
?>
