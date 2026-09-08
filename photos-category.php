<?php
   require_once 'connection.php';
   require_once 'includes/functions.php';

    // Get category from URL, default to 'others' if not set
    $category = isset($_GET['category']) ? strtolower($_GET['category']) : 'others';

    // Sanitize allowed values
    $allowed_categories = ['wedding', 'birthday', 'others'];
    if (!in_array($category, $allowed_categories)) {
        die("Invalid category selected.");
    }

    $category_label = category_label($category);

    $title = $category_label . " Album | D'Leverage Photography";

    require_once 'includes/header.php';

    $query = "SELECT * FROM album WHERE album_category = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    $albums = [];
    while ($row = $result->fetch_assoc()) {
        $albums[] = $row;
    }
    $albumCount = count($albums);
?>
<div class="page-spacer"></div>
<main>
    <div class="container">
        <div class="heading">
            <a href="photos.php" class="category-crumb">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                Back to Photos
            </a>
            <h3><?= htmlspecialchars($category_label) ?></h3>
            <span class="count"><?= $albumCount ?> album<?= $albumCount === 1 ? '' : 's' ?></span>
        </div>

        <div class="album-container mt-5">
            <?php if ($albumCount > 0): ?>
                <div class="album-flex">
                    <?php foreach ($albums as $row): ?>
                        <a href="view-album.php?id=<?= (int) $row['id'] ?>">
                            <div class="album">
                                <div class="album-thumb">
                                    <img src="image/upload-album/<?= rawurlencode($row['album_img']) ?>" alt="<?= htmlspecialchars($row['album_name']) ?>" loading="lazy" decoding="async">
                                </div>
                                <p class="album_name"><?= htmlspecialchars($row['album_name']) ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="album-empty">
                    <i class="fa-solid fa-images" aria-hidden="true"></i>
                    <p>No <?= htmlspecialchars(strtolower($category_label)) ?> albums are up yet — check back soon, or get in touch to book your own session.</p>
                    <a href="contact.php" class="btn btn-gold btn-sm">Book a Session</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="related-categories">
            <span class="eyebrow">Check Also</span>
            <ul class="chip-list">
                <?php foreach ($allowed_categories as $cat): ?>
                    <?php if ($cat !== $category): ?>
                        <li><a href="photos-category.php?category=<?= urlencode($cat) ?>" class="chip"><?= htmlspecialchars(category_label($cat)) ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</main>
<?php
    require_once 'includes/footer.php';
?>
