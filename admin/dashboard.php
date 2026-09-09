<?php
    $title = "Admin Dashboard";
    require_once __DIR__ . '/../includes/admin-head.php';
    require_once __DIR__ . '/../includes/admin-header.php';
    require_once __DIR__ . '/../includes/db/messages.php';
    require_once __DIR__ . '/../includes/db/images.php';
    require_once __DIR__ . '/../includes/db/albums.php';

    $unreadMessageCount = count_messages_by_status($db, 'unread');
    $imageCount = get_image_count($db);
    $albumCount = get_album_count($db);

?>
    <main class="py-5">
        <div class="container">
            <div class="page-heading">
                <div>
                    <span class="eyebrow">Overview</span>
                    <h1 class="h2 mb-0">Admin Dashboard</h1>
                </div>
                <div class="text-muted"><?php echo date('F j, Y'); ?></div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="admin/portfolio.php" class="admin-box-link" aria-label="Manage portfolio images">
                        <div class="card border-0 shadow-sm h-100 admin-box">
                            <div class="card-body">
                                <div class="stat-icon gold">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="admin-box-text">
                                    <h4>Portfolio Images</h4>
                                    <p class="text-muted mb-0">Portfolio Photos</p>
                                </div>
                                <p class="admin-box-count">
                                    <?php echo $imageCount; ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="admin/albums.php" class="admin-box-link" aria-label="Manage album images">
                        <div class="card border-0 shadow-sm h-100 admin-box">
                            <div class="card-body">
                                <div class="stat-icon success">
                                    <i class="fas fa-images"></i>
                                </div>
                                <div class="admin-box-text">
                                    <h4>Album Images</h4>
                                    <p class="text-muted mb-0">Album Photos</p>
                                </div>
                                <p class="admin-box-count">
                                    <?php echo $albumCount; ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="admin/inbox.php" class="admin-box-link" aria-label="Open inbox">
                        <div class="card border-0 shadow-sm h-100 admin-box">
                            <div class="card-body">
                                <div class="stat-icon warning">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="admin-box-text">
                                    <h4>New Messages</h4>
                                    <p class="text-muted mb-0">Unread Messages</p>
                                </div>
                                <p class="admin-box-count">
                                    <?php echo $unreadMessageCount; ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>
<?php
    require_once __DIR__ . '/../includes/admin-footer.php';
?>
