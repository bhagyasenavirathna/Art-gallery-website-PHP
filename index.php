<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Home';
$featured = $pdo->query("SELECT artworks.*, artists.name AS artist_name FROM artworks LEFT JOIN artists ON artworks.artist_id = artists.id WHERE featured = 1 ORDER BY artworks.created_at DESC LIMIT 3")->fetchAll();
$artists = $pdo->query("SELECT * FROM artists ORDER BY id LIMIT 4")->fetchAll();
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-content">
            <span class="eyebrow">Online Art Gallery</span>
            <h1>Inspiring the World Through Artistic Vision</h1>
            <p>Discover, purchase, and order modern, traditional, and hand-drawn artworks from a premium digital gallery built for collectors, artists, and art lovers.</p>
            <div class="hero-actions">
                <a class="btn btn-dark" href="<?= url('gallery.php') ?>">Explore Artworks <?= icon_svg('arrow') ?></a>
                <a class="btn btn-light" href="<?= url('custom-order.php') ?>">Request Custom Art</a>
            </div>
            <div class="hero-stats">
                <div class="stat-card"><strong>120+</strong><span>Curated Artworks</span></div>
                <div class="stat-card"><strong>25+</strong><span>Artist Profiles</span></div>
                <div class="stat-card"><strong>95%</strong><span>Reliable Availability</span></div>
            </div>
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="section-head center">
            <span class="eyebrow">Trending</span>
            <h2>Premium Collections</h2>
            <p>Browse selected artworks with clear details, price, category, artist, availability, and quick ordering features.</p>
        </div>
        <div class="grid-3">
            <?php foreach ($featured as $art): ?>
                <article class="card art-card">
                    <img src="<?= url($art['image']) ?>" alt="<?= e($art['title']) ?>">
                    <div class="art-card-body">
                        <div class="card-meta"><span><?= e($art['category']) ?></span><span class="badge <?= $art['status'] === 'Available' ? 'badge-success' : 'badge-warning' ?>"><?= e($art['status']) ?></span></div>
                        <h3><?= e($art['title']) ?></h3>
                        <div class="card-meta"><span><?= e($art['artist_name']) ?></span><strong class="price"><?= money((float)$art['price']) ?></strong></div>
                        <div class="card-actions">
                            <a class="btn btn-light" href="<?= url('artwork.php?slug=' . urlencode($art['slug'])) ?>">View Details</a>
                            <a class="btn btn-dark" href="<?= url('cart.php?action=add&id=' . $art['id']) ?>">Add Cart</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Creative Minds</span>
                <h2>Artists to Watch</h2>
                <p>Meet artists who redefine artistic boundaries with modern, traditional, and hand-drawn styles.</p>
            </div>
            <a class="btn btn-dark" href="<?= url('artists.php') ?>">View Artists <?= icon_svg('arrow') ?></a>
        </div>
        <div class="artist-strip">
            <?php foreach ($artists as $artist): ?>
                <article class="artist-card">
                    <img src="<?= url($artist['image']) ?>" alt="<?= e($artist['name']) ?>">
                    <h3><?= e($artist['name']) ?></h3>
                    <p><?= e($artist['location']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="section-head center">
            <span class="eyebrow">Latest Articles</span>
            <h2>Art Insights</h2>
            <p>Explore selected art knowledge, collecting tips, and modern gallery trends.</p>
        </div>
        <div class="grid-3">
            <article class="card article-card">
                <img src="<?= asset('images/article-review.jpg') ?>" alt="Article image">
                <div class="art-card-body"><div class="card-meta">Art Review</div><h3>Exploring Classic Techniques in Modern Art</h3><p>Learn how traditional techniques can improve modern digital gallery presentations.</p></div>
            </article>
            <article class="card article-card">
                <img src="<?= asset('images/article-therapy.jpg') ?>" alt="Article image">
                <div class="art-card-body"><div class="card-meta">Art Therapy</div><h3>How Art Supports Calm Living Spaces</h3><p>Discover why collectors select colours, textures, and themes for interior balance.</p></div>
            </article>
            <article class="card article-card">
                <img src="<?= asset('images/article-borders.jpg') ?>" alt="Article image">
                <div class="art-card-body"><div class="card-meta">Gallery</div><h3>Art Beyond Borders</h3><p>Online galleries help customers explore and order artworks from any location.</p></div>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container cta">
        <div>
            <h2>Ready to collect a premium artwork?</h2>
            <p>Create an account, add artwork to cart, place an order, and track the order status from your customer dashboard.</p>
        </div>
        <a class="btn" href="<?= url('register.php') ?>">Get Started <?= icon_svg('arrow') ?></a>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
