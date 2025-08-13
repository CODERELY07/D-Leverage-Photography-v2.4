<?php
  require_once 'connection.php';
  $title = "Portfolio | D'Leverage Photography";
  require_once 'includes/header.php';
?>
    <div style="margin-top: 200px"></div>
    <main>
      <div class="gallery">
        <ul class="controls">          
          <li class="buttons active" data-filter="all">All</li>
          <?php
            $query = "SELECT DISTINCT category FROM image";
            $result = $db->query($query);

            while($data = $result->fetch_assoc()):
            ?>
              <li class="buttons" data-filter="<?php echo $data['category'];?>"><?php echo $data['category']; ?> Photos</li>
            <?php
              endwhile;
            ?>
        </ul>
        <div class="image-container">
         <?php
            $query = "SELECT * FROM image";
            $result = $db->query($query);

            while($data = $result->fetch_assoc()):
          ?>
            <a href="./image/<?php echo $data['filename']; ?>" class="image <?php echo $data['category']; ?> img">
              <img src="./image/<?php echo $data['filename']; ?>" alt="<?php echo $data['filename']; ?>">
            </a>
          <?php
            endwhile;
          ?>
        </div>
      </div>
    </main>
<?php
  require_once 'includes/footer.php';
?>