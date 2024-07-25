<footer>
    <div class="footer-wrapper">
    <div class="socials">
        <a href="https://www.instagram.com/dleveragephoto" target="_blank">
        <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="https://www.facebook.com/dleveragephoto" target="_blank">
        <i class="fa-brands fa-facebook"></i>
        </a>
    </div>
    <div class="menu">
        <ul>
            <li class="mb-3"><h5>Menu</h5></li>
            <li><a href="portfolio.html">Portfolio</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="photos.html">Photos</a></li>
        </ul>
    </div>
    <div class="about">
        <h5>About</h5>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur quasi explicabo iure amet, ipsum dolores eos quam magni atque ea exercitationem, optio unde, ipsam repellat. Alias, et minima nihil esse est laboriosam suscipit facilis excepturi sed dolor ipsam praesentium delectus consequuntur vero, similique accusamus tempora natus veniam ut voluptates omnis.</p>
    </div>
    <div class="footer-contact">
        <p>For more Inquiry <br>Please contact us</p>
        <a href="contact.php">
            <button type="button">Contact</button>
        </a>
    </div>
    </div>
    <p class="text-center text-white m-0 p-3">@2024 | ALL RIGHTS RESERVED | D’LEVERAGE</p>
</footer>
<script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="js/script.js"></script>
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