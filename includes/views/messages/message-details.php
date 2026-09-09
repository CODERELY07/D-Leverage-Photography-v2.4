<?php

?>
<div class="mb-3">
    <h6>Contact Information</h6>
    <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($row['fullname']); ?></p>
    <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
    <p class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($row['phonenumber']); ?></p>
</div>

<div class="mb-3">
    <h6>Shoot Details</h6>
    <p class="mb-1"><strong>Date:</strong> <?php echo htmlspecialchars($row['shootdate']); ?></p>
    <p class="mb-1"><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
    <p class="mb-1"><strong>Service:</strong> <?php echo htmlspecialchars($row['service']); ?></p>
    <p class="mb-1"><strong>Session:</strong> <?php echo htmlspecialchars($row['session']); ?></p>
</div>

<div class="mb-3">
    <h6>Message</h6>
    <p><?php echo htmlspecialchars($row['message']); ?></p>
</div>
