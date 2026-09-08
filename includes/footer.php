    <footer>
        <div class="footer-top">
            <div class="footer-brand">
                <a class="logo" href="index.php">
                    <img src="image/static-img/logo-trim.png" alt="D'Leverage Logo" />
                </a>
                <p>A Filipino wedding, events, and portrait photographer.<br>
                    Based in Montreal, QC, and available worldwide.</p>
            </div>
            <nav class="footer-links" aria-label="Footer">
                <h5>Explore</h5>
                <ul>
                    <li><a href="portfolio.php">Portfolio</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="photos.php">Photos</a></li>
                    <li><a href="contact.php">Book Now</a></li>
                </ul>
            </nav>
            <div class="footer-connect">
                <h5>Let's Connect</h5>
                <p>Follow along for new sessions and behind-the-scenes moments, or send a message to start planning yours.</p>
                <a class="btn btn-gold btn-sm footer-cta" href="contact.php">Book a Session</a>
                <div class="socials">
                    <a href="https://www.instagram.com/dleveragephoto" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="social-ig">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="https://www.facebook.com/dleveragephoto" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="social-fb">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                </div>
            </div>
        </div>
        <p class="copyright m-0 p-3">&copy; <?= date('Y') ?> | ALL RIGHTS RESERVED | D'LEVERAGE</p>
    </footer>
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
    <script src="js/script.js?v=<?php echo time(); ?>"></script>
    <script src="js/magnificpopup.js?v=<?php echo time(); ?>"></script>
    <script>
      AOS.init();
    </script>
</body>
</html>