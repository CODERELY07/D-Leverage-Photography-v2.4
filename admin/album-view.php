<?php

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db/albums.php';

$album_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($album_id <= 0) {
    die("Invalid album ID.");
}

$album = get_album_by_id($db, $album_id);

if (!$album) {
    die("Album not found.");
}

$title = htmlspecialchars($album['album_name']) . " | Admin";
require_once __DIR__ . '/../includes/admin-head.php';
require_once __DIR__ . '/../includes/admin-header.php';
?>
<style>
    .image-card {
        position: relative;
        overflow: hidden;
    }
    .image-card .icon-btn{
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>

<main class="container py-5">
    <div class="page-heading">
        <div>
            <a href="admin/albums.php" class="text-muted text-decoration-none d-inline-flex align-items-center gap-2 mb-2">
                <i class="fas fa-arrow-left"></i> Back to Albums
            </a>
            <span class="eyebrow">Album</span>
            <h1 class="h3 mb-0"><?= htmlspecialchars($album['album_name']) ?></h1>
        </div>
    </div>

    <div class="row">
        <?php
        $images = get_album_images($db, $album_id);

        if (!empty($images)):
            foreach ($images as $img):
        ?>
        <div class="col-6 col-md-3 mb-4">
            <div class="card image-card shadow-sm">
                <img src="<?= htmlspecialchars(url_encode_path($img['img'])) ?>" class="card-img-top" alt="Album Image" loading="lazy" decoding="async">

                <form action="actions/delete_album_image.php" method="POST" class="delete-form">
                    <input type="hidden" name="image_id" value="<?= (int) $img['id'] ?>">
                    <input type="hidden" name="image_path" value="<?= htmlspecialchars($img['img']) ?>">
                    <button type="submit" class="icon-btn icon-danger" data-tooltip="Delete" aria-label="Delete image">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        <?php
            endforeach;
        else:
            echo "<p class='text-muted'>No images found in this album.</p>";
        endif;
        ?>
    </div>

</main>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
