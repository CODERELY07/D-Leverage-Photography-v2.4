<?php
    require_once __DIR__ . '/../config/connection.php';
    $title = "Admin Image";
    require_once __DIR__ . '/../includes/admin-head.php';
    require_once __DIR__ . '/../includes/flash.php';

    $albums = [];
    $albumsResult = $db->query("SELECT * FROM album ORDER BY id DESC");
    if ($albumsResult) {
        while ($row = $albumsResult->fetch_assoc()) {
            $albums[] = $row;
        }
    }
    $albumCount = count($albums);

     require_once __DIR__ . '/../includes/admin-header.php';
?>

    <main class="container py-4">
        <div class="page-heading">
            <div>
                <span class="eyebrow">Manage</span>
                <h1 class="h3 mb-0">Album Images</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-muted"><?php echo $albumCount; ?> album<?php echo $albumCount === 1 ? '' : 's'; ?> total</div>
                <div class="album-actions">
                    <button type="button" class="action-fab gold" data-tooltip="Create New Album" aria-label="Create New Album" data-bs-toggle="modal" data-bs-target="#albumModal" id="openCreateAlbumBtn">
                        <i class="fas fa-folder-plus"></i>
                    </button>
                    <button type="button" class="action-fab info" data-tooltip="Add Images to Existing Album" aria-label="Add Images to Existing Album" data-bs-toggle="modal" data-bs-target="#addImageModal">
                        <i class="fas fa-images"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <?php display_flash(); ?>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-toolbar">
                    <div class="section-head mb-0">
                        <div class="icon-chip success"><i class="fas fa-layer-group"></i></div>
                        <div>
                            <h5 class="card-title mb-0">All Albums</h5>
                            <p class="section-sub mb-0"><?php echo $albumCount; ?> total</p>
                        </div>
                    </div>
                    <div class="table-search">
                        <i class="fas fa-search"></i>
                        <input type="search" id="albumSearch" class="form-control" placeholder="Search albums...">
                    </div>
                </div>

                <div id="noAlbumResults" class="text-muted small mb-3 d-none">No albums match your search.</div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Cover</th>
                                <th>Album</th>
                                <th>Link</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="albumTableBody">
                            <?php
                            if ($albumCount > 0) {
                                foreach ($albums as $row) {
                                    $safeName = htmlspecialchars($row['album_name']);
                                    $safeCategory = htmlspecialchars($row['album_category']);
                                    $safeLink = htmlspecialchars($row['album_link']);
                                    $safeImg = htmlspecialchars($row['album_img']);
                                    $imgUrl = rawurlencode($row['album_img']);
                                    $categoryClass = in_array($row['album_category'], ['wedding', 'birthday']) ? $row['album_category'] : 'others';
                                ?>
                                    <tr id="<?php echo (int) $row['id']; ?>" data-album-name="<?php echo strtolower($safeName); ?>">
                                        <td data-label="Cover">
                                            <img src="image/upload-album/<?php echo $imgUrl; ?>" alt="<?php echo $safeName; ?> cover" class="img-thumbnail" width="100">
                                        </td>

                                        <td data-label="Album">
                                            <div class="fw-semibold"><?php echo $safeName; ?></div>
                                            <span class="category-badge <?php echo $categoryClass; ?>"><?php echo ucfirst($safeCategory); ?></span>
                                        </td>

                                        <td data-label="Link">
                                            <a href="<?php echo $safeLink; ?>" target="_blank" rel="noopener" class="link-chip" title="<?php echo $safeLink; ?>">
                                                <i class="fas fa-arrow-up-right-from-square"></i><span><?php echo $safeLink; ?></span>
                                            </a>
                                        </td>

                                        <td data-label="Actions">
                                            <div class="icon-btn-group">
                                                <a href="admin/album-view.php?id=<?php echo (int) $row['id']; ?>" class="icon-btn icon-view" data-tooltip="View" aria-label="View album">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button
                                                    type="button"
                                                    class="icon-btn icon-edit edit-btn"
                                                    data-tooltip="Edit"
                                                    aria-label="Edit album"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#albumModal"
                                                    data-id="<?php echo (int) $row['id']; ?>"
                                                    data-name="<?php echo $safeName; ?>"
                                                    data-category="<?php echo $safeCategory; ?>"
                                                    data-link="<?php echo $safeLink; ?>"
                                                    data-img="<?php echo $safeImg; ?>"
                                                ><i class="fas fa-pen"></i></button>
                                                <form action="actions/delete_album.php" class="delete-form d-inline" method="POST">
                                                    <input type="hidden" name="delete_id" value="<?php echo (int) $row['id']; ?>">
                                                    <input type="hidden" name="delete_img" value="<?php echo $safeImg; ?>">
                                                    <button type="submit" class="icon-btn icon-danger" name="delete_img_btn" data-tooltip="Delete" aria-label="Delete album">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center text-muted py-5'><i class=\"fas fa-images fa-2x d-block mb-2\" style=\"opacity:.35\"></i>No albums yet &mdash; use the + button above to create one.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="albumModal" tabindex="-1" aria-labelledby="albumFormTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="section-head mb-0">
                        <div class="icon-chip gold"><i class="fas fa-folder-plus" id="albumModalIcon"></i></div>
                        <div>
                            <h5 class="modal-title mb-0" id="albumFormTitle">Create New Album</h5>
                            <p class="section-sub mb-0" id="albumFormSub">Sets the album's name, category, link and cover photo.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="actions/uploadAlbum.php" enctype="multipart/form-data" method="POST" id="albumForm">
                        <input type="hidden" name="album-id" id="album-id">
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label for="upload-name" class="form-label">Album Name</label>
                                <input type="text" name="album-name" id="upload-name" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label for="album-category" class="form-label">Category</label>
                                <select name="album-category" id="album-category" class="form-control" required>
                                    <option value="" selected disabled>Choose a category</option>
                                    <option value="wedding">Wedding</option>
                                    <option value="birthday">Birthday</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="upload-link" class="form-label">Album Link</label>
                                <input type="text" name="album-link" id="upload-link" class="form-control" placeholder="https://..." required>
                            </div>
                            <div class="col-12">
                                <label for="upload-album" class="form-label">Cover Image</label>
                                <input type="file" accept="image/*" name="upload-album" id="upload-album" class="form-control" required>
                                <small class="field-hint" id="imageHint">JPG or PNG. This becomes the album's cover photo.</small>
                            </div>
                            <div class="col-12 pt-2">
                                <button type="submit" name="albumSubmit" id="albumSubmitBtn" class="btn btn-primary w-100">
                                    <i class="fas fa-plus me-2" id="submitBtnIcon"></i><span id="submitBtnLabel">Create Album</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addImageModal" tabindex="-1" aria-labelledby="addImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="section-head mb-0">
                        <div class="icon-chip info"><i class="fas fa-images"></i></div>
                        <div>
                            <h5 class="modal-title mb-0" id="addImageModalLabel">Add Images to Existing Album</h5>
                            <p class="section-sub mb-0">Adds an extra photo to an album's gallery, not its cover.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addToAlbumForm" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Album</label>
                                <select name="album_id" required class="form-control">
                                    <option value="" selected disabled>Choose an album</option>
                                    <?php foreach ($albums as $row): ?>
                                        <option value="<?php echo (int) $row['id']; ?>"><?php echo htmlspecialchars($row['album_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Image</label>
                                <input class="form-control" type="file" name="img" accept="image/*" required>
                            </div>
                            <div class="col-12 pt-2">
                                <button type="submit" id="addImageSubmit" class="btn btn-primary w-100">
                                    <i class="fas fa-upload me-2"></i><span>Add Image</span>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div id="response" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
<?php
    require_once __DIR__ . '/../includes/admin-footer.php';
?>
