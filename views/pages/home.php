<?php include_once __DIR__ . '/../../includes/header.php'; ?>

<section style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white; padding: 100px 0; overflow: hidden; position: relative;">
    <div class="container" style="display: flex; align-items: center; flex-wrap: wrap; gap: 40px;">
        <div style="flex: 1; min-width: 300px;">
            <h1 style="font-size: 3.5rem; margin: 0 0 20px 0; line-height: 1.1; font-weight: 800;">
                Master the Art of <span style="color: var(--primary);">Movement</span>.
            </h1>
            <p style="font-size: 1.25rem; color: #94a3b8; margin-bottom: 40px; line-height: 1.6;">
                Access world-class dance instruction and a global community of artists. High-fidelity video syllabus designed for your growth.
            </p>
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <a href="<?php echo BASE_URL; ?>/course" class="btn btn-primary" style="padding: 16px 40px; font-size: 1.1rem;">Explore All Courses</a>
                <a href="<?php echo BASE_URL; ?>/live" class="btn btn-outline" style="color: white; border-color: rgba(255,255,255,0.3); background: rgba(255,255,255,0.05);">Upcoming Live Classes</a>
            </div>
        </div>
        <div style="flex: 1; min-width: 300px; text-align: center;">
            <div style="position: relative; z-index: 2; background: rgba(255,255,255,0.05); padding: 50px; border-radius: 30px; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(12px);">
                <div style="font-size: 3.5rem; font-weight: 800; color: var(--primary);">500+</div>
                <div style="color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">Active Learners</div>
            </div>
        </div>
    </div>
</section>

<section style="padding: 80px 0; background: #ffffff;">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;">
            <div>
                <h2 style="font-size: 2rem; margin-bottom: 10px; font-weight: 700;">Featured Learning Paths</h2>
                <p style="color: var(--text-muted);">Curated selections for every skill level.</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/course" style="color: var(--primary); font-weight: bold; text-decoration: none;">View Catalog <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div style="display: flex; gap: 25px; overflow-x: auto; padding: 10px 5px 30px; scroll-snap-type: x mandatory; -ms-overflow-style: none; scrollbar-width: none;">
            <?php if (!empty($featured_courses)): ?>
                <?php foreach ($featured_courses as $course): ?>
                    <div style="flex: 0 0 320px; scroll-snap-align: start; background: white; border-radius: 16px; box-shadow: var(--shadow-sm); overflow: hidden; border: 1px solid #f1f5f9;">
                        <img src="<?php echo BASE_URL; ?>/assets/images/default_thumb.jpg" style="width: 100%; aspect-ratio: 16/9; object-fit: cover;">
                        <div style="padding: 24px;">
                            <span class="badge badge-beginner"><?php echo htmlspecialchars($course['difficulty_level']); ?></span>
                            <h4 style="margin: 15px 0; font-size: 1.15rem; font-weight: 700;"><?php echo htmlspecialchars($course['title']); ?></h4>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                                <span style="font-weight: 800; font-size: 1.25rem;">Rs. 1500</span>
                                <a href="<?php echo BASE_URL; ?>/course/show?id=<?php echo $course['class_id']; ?>" class="btn btn-outline" style="padding: 6px 16px; font-size: 0.85rem;">Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section style="padding: 80px 0; background: #f8fafc;">
    <div class="container" style="display: flex; align-items: center; flex-wrap: wrap; gap: 60px;">
        <div style="flex: 1; min-width: 300px; position: relative;">
            <div style="width: 100%; height: 400px; background: #e2e8f0; border-radius: 24px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-user-tie" style="font-size: 8rem; color: #cbd5e1;"></i>
            </div>
            <div style="position: absolute; bottom: -20px; right: -20px; background: white; padding: 20px; border-radius: 16px; box-shadow: var(--shadow-md); border: 1px solid #f1f5f9;">
                <strong>Top Instructor</strong>
                <p style="margin:0; font-size: 0.8rem; color: var(--text-muted);">Master in Contemporary</p>
            </div>
        </div>
        <div style="flex: 1; min-width: 300px;">
            <h2 style="font-size: 2.5rem; margin-bottom: 20px;">Learn from the <span class="text-gradient">Elite</span>.</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 30px;">
                Our instructors aren't just teachers; they are performers who have graced world stages. Get exclusive insights into technique, performance, and stage presence.
            </p>
            <a href="<?php echo BASE_URL; ?>/instructor" class="btn btn-primary">Meet Our Masters</a>
        </div>
    </div>
</section>

<section style="padding: 80px 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 40px;">
            <div style="background: white; padding: 40px; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: var(--shadow-sm);">
                <i class="fa-solid fa-people-group" style="font-size: 2rem; color: var(--primary); margin-bottom: 20px;"></i>
                <h3>Join the Collective</h3>
                <p style="color: var(--text-muted); margin: 15px 0 25px;">Share your dance milestones, ask for feedback, and stay motivated with peers from around the globe.</p>
                <a href="<?php echo BASE_URL; ?>/community" style="color: var(--primary); font-weight: bold; text-decoration: none;">Explore Community <i class="fa-solid fa-chevron-right" style="font-size: 0.8rem;"></i></a>
            </div>
            <div style="background: white; padding: 40px; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: var(--shadow-sm);">
                <i class="fa-solid fa-calendar-star" style="font-size: 2rem; color: var(--secondary); margin-bottom: 20px;"></i>
                <h3>Live Workshops</h3>
                <p style="color: var(--text-muted); margin: 15px 0 25px;">Stay updated with our seasonal showcases, choreography battles, and upcoming offline workshops.</p>
                <a href="<?php echo BASE_URL; ?>/event" style="color: var(--secondary); font-weight: bold; text-decoration: none;">Upcoming Events <i class="fa-solid fa-chevron-right" style="font-size: 0.8rem;"></i></a>
            </div>
        </div>
    </div>
</section>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>