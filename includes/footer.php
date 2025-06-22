<footer>
     <div class="logo">
        <a href="index.php"
            >
            <img src="image/static-img/logo.png" alt="D'Leverage Logo"
            />
        </a>
    </div>
    <div class="footer-wrapper">
        <div>
            
            <h5>About</h5>
            <p>Dleverage Photography <br><br>
                A Filipino wedding, events, and portrait photographer. <br>
                Based in Montreal, QC,<br>
                and available worldwide.</p>
        </div>
       
        <div>
            <h5>For more Inquiry </h5>
            <p> Please contact us</p>
            <a href="https://www.instagram.com/dleveragephoto" target="_blank">
                <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="https://www.facebook.com/dleveragephoto" target="_blank">
                <i class="fa-brands fa-facebook"></i>
            </a>
           
        </div>
    </div>
    <p class="text-center m-0 p-3">@2024 | ALL RIGHTS RESERVED | D’LEVERAGE</p>
</footer>
<script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/script.js?v=<?php echo time(); ?>"></script>
    <script>
      AOS.init();
        // Select all .layout containers
        // Select all .layout containers
        const layouts = document.querySelectorAll('.layout');

        // Iterate over each .layout container
        layouts.forEach(layout => {
            // Select all images inside the current .layout container
            const imgs = layout.querySelectorAll('img');
            
            // Iterate over each image
            imgs.forEach(img => {
                // Create a new <div> element
                let div = document.createElement('div');
                div.classList.add('image-container');

                // Construct the URL for the background image
                const smallImageUrl = `image/small/${getImageFilename(img.src)}`;
                div.style.backgroundImage = `url('${smallImageUrl}')`;
                
                // Append the image to the <div> element
                div.appendChild(img);
                
                // Append the <div> element (with the image inside) back to the .layout container
                layout.appendChild(div);
                
                // Add event listener for image load
                img.addEventListener('load', function() {
                    // Function to remove .image-container once image is loaded
                    function removeImageContainer() {
                        // Check if img still exists and has parent .image-container
                        if (img.parentNode && img.parentNode.classList.contains('image-container')) {
                            // Remove the .image-container parent
                            img.parentNode.parentNode.replaceChild(img, img.parentNode);
                        }
                        // Remove event listener after execution
                        img.removeEventListener('load', removeImageContainer);
                    }
                    
                    // Check if image is already loaded
                    if (img.complete) {
                        removeImageContainer();
                    } else {
                        // If image is not yet loaded, wait for the load event
                        img.addEventListener('load', removeImageContainer);
                    }
                });
            });
        });

    // Helper function to extract image filename from URL
    function getImageFilename(url) {
    return url.substring(url.lastIndexOf('/') + 1);
    }
    </script>
  </body>
</html>