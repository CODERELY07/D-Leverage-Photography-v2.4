<?php

require_once 'connection.php';

$album_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($album_id <= 0) {
    die("Invalid album ID.");
}

// Fetch album details
$stmt = $db->prepare("SELECT * FROM album WHERE id = ?");
$stmt->bind_param("i", $album_id);
$stmt->execute();
$album = $stmt->get_result()->fetch_assoc();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .image-card {
        position: relative;
        overflow: hidden;
    }
    .delete-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        display: none;
    }
    .image-card:hover .delete-btn {
        display: block;
    }
</style>

<div class="container mt-5">
    <h2 class="text-center mb-4"><?= htmlspecialchars($album['album_name']) ?> Images</h2>

    <div class="row">
        <?php
        $img_stmt = $db->prepare("SELECT * FROM album_img WHERE album_id = ?");
        $img_stmt->bind_param("i", $album_id);
        $img_stmt->execute();
        $images = $img_stmt->get_result();

        if ($images->num_rows > 0):
            while ($img = $images->fetch_assoc()):
        ?>
        <div class="col-md-3 mb-4">
            <div class="card image-card">
                <img src="<?= htmlspecialchars($img['img']) ?>" class="card-img-top" alt="Album Image">

                <!-- Delete Icon (uses a form) -->
                <form action="delete_album_image.php" method="POST">
                    <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                    <input type="hidden" name="image_path" value="<?= $img['img'] ?>">
                    <button type="submit" class="btn btn-danger delete-btn" title="Delete Image">
                        &times;
                    </button>
                </form>
            </div>
        </div>
        <?php
            endwhile;
        else:
            echo "<p class='text-muted'>No images found in this album.</p>";
        endif;
        ?>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
