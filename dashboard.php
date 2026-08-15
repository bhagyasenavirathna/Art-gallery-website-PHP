<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';
require_login();
if (is_admin()) { redirect('admin/dashboard.php'); }

$userId = current_user()['id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
$messageStmt = $pdo->prepare("SELECT * FROM messages WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$messageStmt->execute([$userId]);
$messages = $messageStmt->fetchAll();
$pageTitle = 'Customer Dashboard';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container"><span class="eyebrow">Dashboard</span><h1>Hello, <?= e(current_user()['name']) ?></h1><p>Track your artwork orders, messages, and account details.</p></div>
</section>
<div class="container dashboard">
    <aside class="sidebar">
        <a class="active" href="<?= url('dashboard.php') ?>"><?= icon_svg('box') ?> My Orders</a>
        <a href="<?= url('messages.php') ?>"><?= icon_svg('mail') ?> Messages</a>
        <a href="<?= url('gallery.php') ?>"><?= icon_svg('search') ?> Browse Gallery</a>
        <a href="<?= url('logout.php') ?>"><?= icon_svg('user') ?> Logout</a>
    </aside>
    <section>
        <div class="stats-grid">
            <div class="stat-card"><strong><?= count($orders) ?></strong><span>Total Orders</span></div>
            <div class="stat-card"><strong><?= count(array_filter($orders, fn($o) => $o['status'] === 'Pending')) ?></strong><span>Pending</span></div>
            <div class="stat-card"><strong><?= count(array_filter($orders, fn($o) => $o['status'] === 'Completed')) ?></strong><span>Completed</span></div>
            <div class="stat-card"><strong><?= money((float)array_sum(array_column($orders, 'total'))) ?></strong><span>Total Value</span></div>
        </div>
        <div class="panel">
            <div class="panel-head"><h2>Order Tracking</h2><a class="btn btn-dark btn-small" href="<?= url('gallery.php') ?>">New Order</a></div>
            <?php if (!$orders): ?>
                <div class="empty-state"><h3>No orders yet</h3><p>Your orders will appear here after checkout.</p></div>
            <?php else: ?>
                <div class="table-wrap">
                    <table><thead><tr><th>Order No</th><th>Total</th><th>Status</th><th>Date</th><th>Details</th></tr></thead><tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr><td><strong><?= e($order['order_number']) ?></strong></td><td><?= money((float)$order['total']) ?></td><td><span class="badge"><?= e($order['status']) ?></span></td><td><?= e(date('M d, Y', strtotime($order['created_at']))) ?></td><td><a href="<?= url('order-view.php?id=' . (int)$order['id']) ?>">View</a></td></tr>
                    <?php endforeach; ?>
                    </tbody></table>
                </div>
            <?php endif; ?>
        </div>
        <div class="panel">
            <div class="panel-head"><h2>Recent Messages</h2><a class="btn btn-light btn-small" href="<?= url('messages.php') ?>">Open Messages</a></div>
            <?php foreach ($messages as $msg): ?>
                <div class="message-box"><strong><?= e($msg['subject']) ?></strong><br><small><?= e($msg['created_at']) ?> - <?= e($msg['status']) ?></small><p><?= e($msg['message']) ?></p></div>
            <?php endforeach; ?>
            <?php if (!$messages): ?><p>No messages yet.</p><?php endif; ?>
        </div>
    </section>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
