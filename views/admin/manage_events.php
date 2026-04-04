<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 1200px; margin: 40px auto;">

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'event_added'): ?>
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            <i class="fa-solid fa-circle-check"></i> Event successfully scheduled.
        </div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'event_deleted'): ?>
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            <i class="fa-solid fa-trash-can"></i> Event permanently deleted.
        </div>
    <?php endif; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="font-size: 2rem; color: var(--text-dark);">Manage Events</h2>
        <div style="display: flex; gap: 10px;">
            <a href="<?php echo BASE_URL; ?>/admin/courses" class="btn btn-outline"><i class="fa-solid fa-book"></i> Catalog</a>
            <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-outline"><i class="fa-solid fa-users"></i> Users</a>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <a href="<?php echo BASE_URL; ?>/admin/addEvent" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Schedule New Event</a>
    </div>

    <div style="overflow-x: auto; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                <th style="padding: 15px;">Date & Time</th>
                <th style="padding: 15px;">Title</th>
                <th style="padding: 15px;">Location</th>
                <th style="padding: 15px;">Status</th>
                <th style="padding: 15px;">Actions</th>
            </tr>

            <?php foreach ($events as $event): ?>
                <?php
                $eventTime = strtotime($event['event_date']);
                $isPast = $eventTime < time();
                ?>
                <tr style="border-bottom: 1px solid #e2e8f0; <?php echo $isPast ? 'opacity: 0.7; background: #fafafa;' : ''; ?>">
                    <td style="padding: 15px; font-weight: bold;">
                        <?php echo date('M d, Y', $eventTime); ?><br>
                        <span style="color: var(--text-muted); font-size: 0.85rem; font-weight: normal;"><?php echo date('h:i A', $eventTime); ?></span>
                    </td>
                    <td style="padding: 15px;"><strong><?php echo htmlspecialchars($event['title']); ?></strong></td>
                    <td style="padding: 15px; color: var(--text-muted);"><?php echo htmlspecialchars($event['location']); ?></td>
                    <td style="padding: 15px;">
                        <?php if ($isPast): ?>
                            <span class="badge" style="background: #e2e8f0; color: #64748b;">Past (Gallery)</span>
                        <?php else: ?>
                            <span class="badge" style="background: #dcfce7; color: #166534;">Upcoming</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 15px;">
                        <form action="<?php echo BASE_URL; ?>/admin/deleteEvent" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');" style="margin: 0;">
                            <?php echo \App\Core\Security::csrfField(); ?>
                            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                            <button type="submit" class="btn btn-outline" style="padding: 5px 10px; color: #ef4444; border-color: #ef4444; cursor: pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>