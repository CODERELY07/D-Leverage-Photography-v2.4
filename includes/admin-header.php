<header class="sticky-top bg-white shadow-sm ">

    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
        
            <div class="logo">
                <a href="admin.php" class="d-block">
                    <img src="image/static-img/logo.png" width="150" alt="D'Leverage Logo" class="img-fluid">
                </a>
            </div>

            <div class="user-icon-con position-relative">
                <div 
                    class="user-icon rounded-circle bg-light p-2 shadow-sm d-flex justify-content-center align-items-center" 
                    id="user" 
                    style="width: 40px; height: 40px; cursor: pointer;"
                >
                    <i class="fa-solid fa-user text-secondary" style="font-size: 1.1rem;"></i>
                </div>
                    <div 
                        class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2" 
                        style="width: 200px; display: none;" 
                        aria-labelledby="user"
                    >
                    <a href="upload-portfolio.php" class="dropdown-item d-flex align-items-center py-2">
                        <i class="fas fa-image me-2 text-primary"></i>
                        <span>Portfolio Images</span>
                    </a>
                    <a href="albumImages.php" class="dropdown-item d-flex align-items-center py-2">
                        <i class="fas fa-images me-2 text-success"></i>
                        <span>Album Images</span>
                    </a>
                    <a href="inbox.php" class="dropdown-item d-flex align-items-center py-2">
                        <i class="fas fa-inbox me-2 text-warning"></i>
                        <span>Inbox</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="logout.php" class="dropdown-item d-flex align-items-center py-2 text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        <span>Logout</span>
                    </a>
                </div>
                    <div class="hide dropdown absolute card shadow-sm px-3" >
                    <a href="upload-portfolio.php" class="d-block py-1 text-decoration-none">Portfolio Images</a>
                    <a href="albumImages.php" class="d-block py-1 text-decoration-none">Album Images</a>
                    <a href="inbox.php" class="d-block py-1 text-decoration-none">Inbox</a>
                    <a href="logout.php" class="d-block py-1 text-decoration-none text-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>