<?php
require_once 'connection.php';
require_once 'includes/functions.php';

$album_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($album_id <= 0) {
    die("Invalid album ID.");
}

// Fetch album details
$album_stmt = $db->prepare("SELECT * FROM album WHERE id = ?");
$album_stmt->bind_param("i", $album_id);
$album_stmt->execute();
$album_result = $album_stmt->get_result();

if ($album_result->num_rows == 0) {
    die("Album not found.");
}

$album = $album_result->fetch_assoc();
// Needed before including the header, which prints it into <title> —
// previously header.php was required first thing, before $album (or $title)
// existed, which threw "Undefined variable $title" into the page title.
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
                // Fetch album images
                $img_stmt = $db->prepare("SELECT * FROM album_img WHERE album_id = ?");
                $img_stmt->bind_param("i", $album_id);
                $img_stmt->execute();
                $img_result = $img_stmt->get_result();

                if ($img_result->num_rows > 0):
                    while ($img = $img_result->fetch_assoc()):
                ?>
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <img src="<?= htmlspecialchars(url_encode_path($img['img'])) ?>" class="card-img-top" alt="Album Image" loading="lazy" decoding="async">
                            </div>
                        </div>
                <?php
                    endwhile;
                else:
                    echo "<p class='text-muted text-center'>No images found for this album.</p>";
                endif;
                ?>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
