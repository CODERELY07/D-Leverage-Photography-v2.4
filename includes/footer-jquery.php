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
    <!-- bootrap -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Jquery -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
      integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
    <!-- Magnific pop up -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/jquery.magnific-popup.min.js"
      integrity="sha512-fCRpXk4VumjVNtE0j+OyOqzPxF1eZwacU3kN3SsznRPWHgMTSSo7INc8aY03KQDBWztuMo5KjKzCFXI/a5rVYQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
    <script>
      // Aos execute
      AOS.init();

      $(document).ready(function () {
        // Initially show all images and activate the 'all' filter
        $(".all").addClass("active");
        $(".image").show(400);

        // Initialize Magnific Popup for the gallery
        $(".gallery").magnificPopup({
          delegate: "a",
          type: "image",
          gallery: {
            enabled: true,
          },
        });

        // Click event handler for filter buttons
        $(".buttons").click(function () {
          // Remove 'active' class from all buttons and add it to the clicked button
          $(this).addClass("active").siblings().removeClass("active");

          // Get the data-filter value of the clicked button
          var filter = $(this).attr("data-filter");

          // Hide all images initially
          $(".image").hide();

          if (filter === "all") {
            // Show all images if 'all' filter is selected
            $(".image").show(400);
          } else {
            // Show only images matching the current filter
            $(".image." + filter).show(400);
          }
          // Update Magnific Popup with filtered images
          $(".gallery").magnificPopup({
            delegate: "a." + filter,
            type: "image",
            gallery: {
              enabled: true,
            },
          });
        });
      });
    </script>
  </body>
</html>
