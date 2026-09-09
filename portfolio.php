<?php
  require_once __DIR__ . '/config/connection.php';
  require_once __DIR__ . '/includes/db/images.php';
  $title = "Portfolio | D'Leverage Photography";
  require_once 'includes/header.php';

  $images = get_all_images($db);
  $categoryResult = $db->query("SELECT DISTINCT category FROM image");
?>
    <div class="page-spacer"></div>
    <main>
      <div class="gallery">
        <ul class="controls">
          <li class="buttons active" data-filter="all">All</li>
          <?php while($data = $categoryResult->fetch_assoc()): ?>
              <li class="buttons" data-filter="<?php echo htmlspecialchars($data['category']); ?>"><?php echo htmlspecialchars($data['category']); ?> Photos</li>
          <?php endwhile; ?>
        </ul>
        <div class="image-container">
          <?php foreach ($images as $data): ?>
            <a href="./image/<?php echo rawurlencode($data['filename']); ?>" class="image <?php echo htmlspecialchars($data['category']); ?> img">
              <img src="./image/<?php echo rawurlencode($data['filename']); ?>" alt="<?php echo htmlspecialchars($data['filename']); ?>" loading="lazy" decoding="async">
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </main>
<?php
  require_once 'includes/footer.php';
?>