<?php
require_once __DIR__ . '/config/connection.php';
require_once 'includes/functions.php';
require_once __DIR__ . '/includes/db/albums.php';

$album_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($album_id <= 0) {
    die("Invalid album ID.");
}

$album = get_album_by_id($db, $album_id);

if (!$album) {
    die("Album not found.");
}

$title = htmlspecialchars($album['album_name']) . " | D'Leverage Photography";

require_once 'includes/header.php';
?>
<div class="page-spacer"></div>

<main>
    <div class="container">
        <div class="heading">
            <a href="photos-category.php?category=<?= urlencode($album['album_category']) ?>" class="category-crumb">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                Back to <?= htmlspecialchars(category_label($album['album_category'])) ?>
            </a>
            <h3><?= htmlspecialchars($album['album_name']) ?></h3>
        </div>

        <div class="album-container mt-5">
            <div class="row">
                <?php
                $images = get_album_images($db, $album_id);

                if (!empty($images)):
                    foreach ($images as $img):
                ?>
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <img src="<?= htmlspecialchars(url_encode_path($img['img'])) ?>" class="card-img-top" alt="Album Image" loading="lazy" decoding="async">
                            </div>
                        </div>
                <?php
                    endforeach;
                else:
                    echo "<p class='text-muted text-center'>No images found for this album.</p>";
                endif;
                ?>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
