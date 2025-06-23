<?php
   require_once 'connection.php';

    // Get category from URL, default to 'others' if not set
    $category = isset($_GET['category']) ? strtolower($_GET['category']) : 'others';

    // Sanitize allowed values
    $allowed_categories = ['wedding', 'birthday', 'others'];
    if (!in_array($category, $allowed_categories)) {
        die("Invalid category selected.");
    }
    $title = ucfirst($category) . " Album | D'Leverage Photography";    
   
    require_once 'includes/header.php';
 
    
?>
<div style="margin-top: 200px;"></div>
<main>
    
    <div class="container">
        <h3 class="display-5 mb-5 text-center"><?= ucfirst($category) ?></h3>
        <div class="album-container mt-5">
            <div class="album-flex">
                <?php
                    $query = "SELECT * FROM album WHERE album_category = ?";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("s", $category);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if($result->num_rows > 0):
                        while($row = $result->fetch_assoc()):
                ?>
                <a href="view-album.php?id=<?= $row['id'] ?>">
                    <div class="album">
                        <img src="image/upload-album/<?= $row['album_img'] ?>" alt="<?= $row['album_name'] ?> Image">
                        <p class="album_name"><?= $row['album_name'] ?></p>
                    </div>
                </a>
                <?php
                        endwhile;
                    else:
                        echo "<p>No albums found for this category.</p>";
                    endif;
                ?>
            </div>
        </div>
        <br><br><br>
        <h6>Check Also:</h6>
        <ul>
            <?php foreach ($allowed_categories as $cat): ?>
                <?php if ($cat !== $category): ?>
                    <li><a href="photos-category.php?category=<?= $cat ?>" class="link"><?= ucfirst($cat) ?> Photos</a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</main>
<?php
    require_once 'includes/footer.php';
?>
