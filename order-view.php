<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if (is_admin()) {
    $stmt = $pdo->prepare("SELECT orders.*, users.name, users.email FROM orders JOIN users ON orders.user_id = users.id WHERE orders.id = ? LIMIT 1");
    $stmt->execute([$id]);
} else {
    $stmt = $pdo->prepare("SELECT orders.*, users.name, users.email FROM orders JOIN users ON orders.user_id = users.id WHERE orders.id = ? AND orders.user_id = ? LIMIT 1");
    $stmt->execute([$id, current_user()['id']]);
}
$order = $stmt->fetch();
if (!$order) { $_SESSION['flash_error'] = 'Order not found.'; redirect(is_admin() ? 'admin/orders.php' : 'dashboard.php'); }
$itemStmt = $pdo->prepare("SELECT order_items.*, artworks.title, artworks.slug, artworks.image FROM order_items JOIN artworks ON order_items.artwork_id = artworks.id WHERE order_id = ?");
$itemStmt->execute([$id]);
$items = $itemStmt->fetchAll();
$pageTitle = 'Order ' . $order['order_number'];
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">Order Details</span><h1><?= e($order['order_number']) ?></h1><p>Status: <span class="badge"><?= e($order['status']) ?></span></p></div></section>
<div class="container section">
    <div class="panel">
        <div class="form-grid">
            <div><strong>Customer</strong><p><?= e($order['name']) ?><br><?= e($order['email']) ?></p></div>
            <div><strong>Address</strong><p><?= e($order['delivery_address']) ?></p></div>
        </div>
        <?php if ($order['customer_note']): ?><p><strong>Note:</strong> <?= e($order['customer_note']) ?></p><?php endif; ?>
        <?php if ($order['reference_image']): ?><p><strong>Reference Image:</strong> <a href="<?= url($order['reference_image']) ?>" target="_blank">Open uploaded image</a></p><?php endif; ?>
    </div>
    <div class="table-wrap">
        <table><thead><tr><th>Artwork</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead><tbody>
            <?php foreach ($items as $item): ?>
                <tr><td><strong><?= e($item['title']) ?></strong><br><a href="<?= url('artwork.php?slug=' . urlencode($item['slug'])) ?>">View Artwork</a></td><td><?= money((float)$item['price']) ?></td><td><?= (int)$item['quantity'] ?></td><td><?= money((float)$item['price'] * (int)$item['quantity']) ?></td></tr>
            <?php endforeach; ?>
        </tbody></table>
    </div>
    <div class="panel" style="margin-top:22px"><h2>Total: <?= money((float)$order['total']) ?></h2><a class="btn btn-light" href="<?= is_admin() ? url('admin/orders.php') : url('dashboard.php') ?>">Back</a></div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
