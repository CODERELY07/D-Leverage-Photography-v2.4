<?php

?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h5 class="mb-0">Message Summary</h5>
    <div class="d-flex">
        <div class="messageNotif-container me-3">
            <a href="admin/inbox.php" class="text-decoration-none">
                <span class="badge bg-danger rounded-pill"><?= $unreadCount ?></span>
                <i class="fa-solid fa-envelope ms-1"></i> Unread
            </a>
        </div>
        <div class="messageNotif-container">
            <a href="admin/inbox-read.php" class="text-decoration-none">
                <span class="badge bg-secondary rounded-pill"><?= $readCount ?></span>
                <i class="fa-solid fa-envelope-open ms-1"></i> Read
            </a>
        </div>
    </div>
</div>
