<?php
    require_once 'includes/header.php';
    require_once 'connection.php';
?>
<div style="margin-top: 200px;"></div>
<main>
    <div class="container">
    <div class="heading">
        <h3>Photos</h3>
    </div>
    <div class="box">
        <!-- <div data-aos="fade-down"
        data-aos-easing="linear"
        data-aos-duration="1000" class="layout">
            <img src="image/model16.JPG" alt="" loading="lazy">
            <img src="image/model10.JPG" alt="" loading="lazy">
            <img src="image/birthday1.JPG" alt="" loading="lazy">
            <img src="image/birthday2.JPG" alt="" loading="lazy">
            <img src="image/birthday3.JPG" alt="" loading="lazy">
        </div>
        <div data-aos="fade-down"
        data-aos-easing="linear"
        data-aos-duration="1400" class="layout">
            <img src="image/birthday4.JPG" alt="" loading="lazy">
            <img src="image/img1.1.JPG" alt="" loading="lazy">
            <img src="image/model17.PNG" alt="" loading="lazy">
            <img src="image/model11.JPG" alt="" loading="lazy">
            <img src="image/model12.JPG" alt="" loading="lazy">
        </div>
        <div data-aos="fade-down"
        data-aos-easing="linear"
        data-aos-duration="1800" class="layout">
            <img src="image/model13.JPG" alt="" loading="lazy">
            <img src="image/model14.JPG" alt="" loading="lazy">
            <img src="image/model15.JPG" alt="" loading="lazy">
            <img  src="image/birthday6.JPG" alt="" loading="lazy">
            <img src="image/birthday7.JPG" alt="" loading="lazy">
        </div> -->

        <?php
            $query = "SELECT * FROM image";
            $result = $db->query($query);

            if($result->num_rows > 0){

                $images_per_div = ceil($result->num_rows / 3); 
                
                $image_count = 0;
                $div_count = 0;

                while($data = $result->fetch_assoc()){
                    if ($image_count % $images_per_div == 0) {
                        if ($image_count > 0) {
                            echo '</div>'; 
                        }
                        echo '<div data-aos="fade-down" data-aos-easing="linear"  data-aos-duration="1000" class="layout">';
                        $div_count++;
                    }
                    echo '<img src="./image/' . $data['filename'] . '" alt="" loading="lazy">';
                    $image_count++;
                }
                echo '</div>';
            }

            $db->close();
        ?>

    </div>
    </div>
</main>
<?php
    require_once 'includes/footer.php';
?>