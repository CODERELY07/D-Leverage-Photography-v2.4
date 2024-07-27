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
    <?php
        // Define the SQL query to select all images from the 'image' table
        $query = "SELECT * FROM image";

        // Execute the query and store the result in $result
        $result = $db->query($query);

        // Check if the query returned any rows
        if ($result->num_rows > 0) {
            // Calculate the number of images to display per div
            // This ensures that the images are evenly distributed across multiple divs
            $images_per_div = ceil($result->num_rows / 3); 
            
            $image_count = 0;  // Initialize the image counter
            $div_count = 0;    // Initialize the div counter

            // Loop through the result set
            while($data = $result->fetch_assoc()){
                // Check if it's time to start a new div
                if ($image_count % $images_per_div == 0) {
                    // Close the previous div if it exists (not the first div)
                    if ($image_count > 0) {
                        echo '</div>'; 
                    }
                    // Start a new div with specific attributes for animation and layout
                    echo '<div data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1000" class="layout">';
                    $div_count++;
                }
                // Output the image tag with the source set to the filename from the database
                echo '<img src="./image/' . $data['filename'] . '" alt="" loading="lazy">';
                $image_count++;
            }
            // Close the last div after the loop completes
            echo '</div>';
        }

        // Close the database connection
        $db->close();
        ?>


    </div>
    </div>
</main>
<?php
    require_once 'includes/footer.php';
?>