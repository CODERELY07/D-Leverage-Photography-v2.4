<?php
    require_once 'includes/header.php';
    require_once 'connection.php';
?>
<div style="margin-top: 200px;"></div>
<main>
    <div class="container">
        <h3 class="display-5 mb-5 text-center">Wedding</h3>
        <div class="album-container mt-5">
            <div class="album-flex">
                <?php
                    $query = "SELECT * FROM album WHERE album_category = 'wedding'";
                    $result = $db->query($query) or die("Connection Failed " . $db->error);

                    if($result->num_rows > 0):
                ?>
                 <?php 
                        while($row = $result->fetch_assoc()):
                ?>
                <a href="<?php echo $row['album_link']?>" target="_blank">
                    <div>
                        <div class="album">
                            
                                <img src="image/upload-album/<?php echo $row['album_img'] ?>" alt="<?php echo $row['album_name'] ?> Image">
                                <p class="album_name"><?= $row['album_name'] ?></p>
                        
                        </div>
                    </div>
                </a>
                <?php
                        endwhile;
                   ?>
                <?php
                    endif;
                ?>
            </div>
        </div>
    </div>
    <br><br><br>
    <h6>Check Also:</h6>
    <ul>
        <li><a href="photos-birthday.php" class="link">Birthday Photos</a></li>
        <li><a href="photos-others.php" class="link">Others Photos</a></li>
    </ul>
</main>
<?php
    require_once 'includes/footer.php';
?>
