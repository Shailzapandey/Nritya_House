<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<div class="container" style="max-width: 1200px; margin: 40px auto;">

    <div style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: 2.5rem; margin-bottom: 10px;">Upcoming Events</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Join our masterclasses, showcases, and community workshops.</p>
    </div>

    <div style="display: flex; flex-direction: column; gap: 30px; max-width: 1000px; margin: 0 auto 80px auto;">
        <?php if (empty($upcoming_events)): ?>
            <div style="text-align: center; padding: 60px; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
                <i class="fa-regular fa-calendar-xmark" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                <h3 style="color: var(--text-dark);">No upcoming events</h3>
                <p style="color: var(--text-muted);">Check back later for new workshops and performances.</p>
            </div>
        <?php else: ?>
            <?php foreach ($upcoming_events as $event): ?>
                <?php
                $dateObj = new DateTime($event['event_date']);
                $month = $dateObj->format('M');
                $day = $dateObj->format('d');
                $time = $dateObj->format('h:i A');
                $fullDate = $dateObj->format('l, F j, Y');

                // Construct the secure image path
                $thumbPath = !empty($event['image_url']) ? 'assets/images/' . $event['image_url'] : 'assets/images/default_thumb.jpg';
                ?>
                <div style="display: flex; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); overflow: hidden; border: 1px solid #f1f5f9; transition: transform 0.2s;">

                    <div style="width: 250px; flex-shrink: 0; background: #000;">
                        <img src="<?php echo BASE_URL . '/' . htmlspecialchars($thumbPath); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.9;">
                    </div>

                    <div style="background: var(--primary-gradient); color: white; padding: 30px 20px; min-width: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                        <span style="font-size: 1.2rem; text-transform: uppercase; font-weight: bold; letter-spacing: 2px;"><?php echo $month; ?></span>
                        <span style="font-size: 2.5rem; font-weight: 900; line-height: 1; margin: 5px 0;"><?php echo $day; ?></span>
                    </div>

                    <div style="padding: 25px; flex-grow: 1; display: flex; flex-direction: column; justify-content: center;">
                        <h2 style="margin: 0 0 10px 0; font-size: 1.5rem; color: var(--text-dark);"><?php echo htmlspecialchars($event['title']); ?></h2>

                        <div style="display: flex; gap: 20px; margin-bottom: 15px; color: var(--primary); font-size: 0.9rem; font-weight: bold;">
                            <span><i class="fa-regular fa-clock"></i> <?php echo $fullDate . ' at ' . $time; ?></span>
                            <span><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($event['location']); ?></span>
                        </div>

                        <p style="color: var(--text-muted); line-height: 1.6; margin: 0; font-size: 0.95rem;">
                            <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($past_events)): ?>
        <div style="border-top: 2px solid #eee; padding-top: 60px;">
            <div style="text-align: center; margin-bottom: 40px;">
                <h2 style="font-size: 2rem; margin-bottom: 10px;">Our Legacy</h2>
                <p style="color: var(--text-muted); font-size: 1rem;">Moments from our past showcases and battles.</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                <?php foreach ($past_events as $past): ?>
                    <?php
                    $thumbPath = !empty($past['image_url']) ? 'assets/images/' . $past['image_url'] : 'assets/images/default_thumb.jpg';
                    $pastDate = (new DateTime($past['event_date']))->format('F Y');
                    ?>
                    <div style="position: relative; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);  aspect-ratio: 4/3; background: #000;">

                        <img src="<?php echo BASE_URL . '/' . htmlspecialchars($thumbPath); ?>" alt="<?php echo htmlspecialchars($past['title']); ?>"
                            style="width: 100%; height: 100%; object-fit: cover; opacity: 0.8; transition: transform 0.3s ease, opacity 0.3s ease;"
                            onmouseover="this.style.transform='scale(1.05)'; this.style.opacity='0.5';"
                            onmouseout="this.style.transform='scale(1)'; this.style.opacity='0.8';">

                        <div style="position: absolute; bottom: 0; left: 0; width: 100%; padding: 20px; background: linear-gradient(transparent, rgba(0,0,0,0.9)); color: white; pointer-events: none;">
                            <h4 style="margin: 0 0 5px 0; font-size: 1.1rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);"><?php echo htmlspecialchars($past['title']); ?></h4>
                            <p style="margin: 0; font-size: 0.85rem; color: #cbd5e1;"><i class="fa-regular fa-calendar"></i> <?php echo $pastDate; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>