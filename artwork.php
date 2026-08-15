<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT artworks.*, artists.name AS artist_name, artists.location AS artist_location FROM artworks LEFT JOIN artists ON artworks.artist_id = artists.id WHERE artworks.slug = ? LIMIT 1");
$stmt->execute([$slug]);
$art = $stmt->fetch();
if (!$art) {
    $_SESSION['flash_error'] = 'Artwork not found.';
    redirect('gallery.php');
}
$pageTitle = $art['title'];
require_once __DIR__ . '/includes/header.php';
?>
<div class="container detail-grid">
    <div class="detail-img">
        <img src="<?= url($art['image']) ?>" alt="<?= e($art['title']) ?>">
    </div>
    <div class="detail-content">
        <span class="eyebrow"><?= e($art['category']) ?></span>
        <h1><?= e($art['title']) ?></h1>
        <span class="badge <?= $art['status'] === 'Available' ? 'badge-success' : ($art['status'] === 'Sold' ? 'badge-danger' : 'badge-warning') ?>"><?= e($art['status']) ?></span>
        <p><?= e($art['description']) ?></p>
        <div class="detail-list">
            <div class="detail-item"><span>Artist</span><strong><?= e($art['artist_name'] ?? 'Unknown') ?></strong></div>
            <div class="detail-item"><span>Location</span><strong><?= e($art['artist_location'] ?? 'Gallery') ?></strong></div>
            <div class="detail-item"><span>Medium</span><strong><?= e($art['medium']) ?></strong></div>
            <div class="detail-item"><span>Size</span><strong><?= e($art['size']) ?></strong></div>
        </div>
        <h2><?= money((float)$art['price']) ?></h2>
        <?php if ($art['status'] === 'Available'): ?>
            <form class="qty-row" method="post" action="<?= url('cart.php') ?>">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id" value="<?= (int)$art['id'] ?>">
                <input class="form-control" type="number" min="1" max="5" name="qty" value="1" aria-label="Quantity">
                <button class="btn btn-dark" type="submit">Add to Cart <?= icon_svg('cart') ?></button>
                <a class="btn btn-light" href="<?= url('custom-order.php') ?>">Custom Request</a>
            </form>
        <?php else: ?>
            <a class="btn btn-light" href="<?= url('messages.php') ?>">Ask About Availability</a>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
