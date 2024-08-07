<?php
    $title = "Photos | D'Leverage Photography";
    require_once 'includes/header.php';
    require_once 'connection.php';
?>
<div style="margin-top: 200px;"></div>
<main>
    <div class="container">
    <div class="heading">
        <h3>Photos</h3>
    </div>
    <div class="container album">
        <a class="card" href="photos-wedding.php">
            <div>
                <div class="card-bg">
                    <img src="image/static-img/wedding.jpg" alt="">
                </div>
                <div class="cat">
                    <h2>WEDDING / PRENUPTIAL</h2>
                </div>
                <hr>
            </div>
        </a>
        <a class="card" href="photos-birthday.php">
            <div>
                <div class="card-bg">
                    <img src="image/static-img/birthday.JPG" alt="">
                </div>
                <div class="cat">
                    <h2>BIRTHDAY</h2>
                </div>
                <hr>
            </div>
        </a>
        <a class="card" href="photos-others.php">
            <div>
                <div class="card-bg">
                    <img src="image/static-img/others.jpg" alt="">
                </div>
                <div class="cat">
                    <h2>OTHERS</h2>
                </div>
                <hr>
            </div>
        </a>
       
    </div>
    <div class="box">


    <?php
        // $query = "SELECT * FROM image";
        // $result = $db->query($query);
       
        // if ($result->num_rows > 0) {
        //     $images_per_div = ceil($result->num_rows / 3); 
            
        //     $image_count = 0; 
        //     $div_count = 0;   

           
        //     while($data = $result->fetch_assoc()){
               
        //         if ($image_count % $images_per_div == 0) {
                 
        //             if ($image_count > 0) {
        //                 echo '</div>'; 
        //             }
                   
        //             echo '<div data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1000" class="layout">';
        //             $div_count++;
        //         }
              
        //         echo '<img src="./image/' . $data['filename'] . '" alt="" loading="lazy">';
        //         $image_count++;
        //     }
           
        //     echo '</div>';
        // }
        // $db->close();
        ?>


    </div>
    </div>
</main>
<?php
    require_once 'includes/footer.php';
?>