<?php
require_once 'includes/header.php';
require_once 'connection.php';

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
?>
<div style="margin-top: 200px;"></div>

<div class="container mt-5">
    <h2 class="mb-4 text-center"><?= htmlspecialchars($album['album_name']) ?></h2>

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
                        <img src="<?= htmlspecialchars($img['img']) ?>" class="card-img-top" alt="Album Image">
                    </div>
                </div>
        <?php
            endwhile;
        else:
            echo "<p class='text-muted'>No images found for this album.</p>";
        endif;
        ?>
    </div>
    <a href="photos-category.php?category=<?= urlencode($album['album_category']) ?>" class="btn btn-secondary mt-4">← Back to <?= ucfirst($album['album_category']) ?> Albums</a>
</div>

<?php require_once 'includes/footer.php'; ?>
