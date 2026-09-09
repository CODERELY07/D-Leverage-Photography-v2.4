<?php
    $title = "Inbox | Admin";
    require_once __DIR__ . '/../includes/admin-head.php';
    require_once __DIR__ . '/../includes/admin-header.php';
    require_once __DIR__ . '/../includes/db/messages.php';
    require_once __DIR__ . '/../includes/flash.php';
    require_once __DIR__ . '/../includes/csrf.php';

    $unreadCount = count_messages_by_status($db, 'unread');
    $readCount = count_messages_by_status($db, 'read');
    $messages = get_messages_by_status($db, 'unread');
?>
        <main class="container py-4">
            <div class="page-heading">
                <div>
                    <span class="eyebrow">Inbox</span>
                    <h1 class="h3 mb-0">Your Clients Messages</h1>
                </div>
                <div class="text-muted"><?php echo date('F j, Y'); ?></div>
            </div>

            <?php display_flash(); ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <?php require __DIR__ . '/../includes/views/messages/summary-badges.php'; ?>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($messages)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4">No unread messages found</td>
                                    </tr>
                                <?php else: foreach ($messages as $row): ?>
                                    <tr>
                                        <td data-label="From"><?php echo htmlspecialchars($row['fullname']); ?></td>
                                        <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td data-label="Actions">
                                            <button type="button" class="icon-btn icon-view" data-tooltip="View details" aria-label="View message from <?php echo htmlspecialchars($row['fullname']); ?>" data-bs-toggle="modal" data-bs-target="#messageModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <div class="modal fade" id="messageModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="messageModalLabel">Message Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?php require __DIR__ . '/../includes/views/messages/message-details.php'; ?>

                                                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="btn btn-sm btn-outline-secondary">
                                                                    <i class="fas fa-envelope me-1"></i> Reply
                                                                </a>

                                                                <form action="actions/inbox-markAsRead.php" method="POST" class="mb-0">
                                                                    <?php csrf_field(); ?>
                                                                    <input type="hidden" name="rowId" value="<?= $row['id']?>">
                                                                    <button type="submit" name="markReadBtn" class="btn btn-sm btn-primary">
                                                                        <i class="fas fa-check me-1"></i> Mark as read
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
<?php
    require_once __DIR__ . '/../includes/admin-footer.php';
?>
