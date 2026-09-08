<header class="admin-topbar sticky-top">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">

            <a href="admin.php" class="d-flex align-items-center gap-2 logo text-decoration-none">
                <img src="image/static-img/logo-trim.png" alt="D'Leverage Logo">
                <span class="brand-name d-none d-sm-inline">D'Leverage Admin</span>
            </a>

            <div class="user-icon-con">
                <button type="button" class="user-icon" id="user" aria-haspopup="true" aria-expanded="false" aria-label="Account menu">
                    <i class="fa-solid fa-user"></i>
                </button>

                <nav class="admin-dropdown" id="adminDropdown" aria-label="Admin menu">
                    <a href="upload-portfolio.php">
                        <i class="fas fa-image"></i>
                        <span>Portfolio Images</span>
                    </a>
                    <a href="albumImages.php">
                        <i class="fas fa-images"></i>
                        <span>Album Images</span>
                    </a>
                    <a href="inbox.php">
                        <i class="fas fa-inbox"></i>
                        <span>Inbox</span>
                    </a>
                    <hr>
                    <a href="logout.php" class="text-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</header>
