<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';

// සිංහල සටහන: cart data session එකේ තබාගනී, order submit වන විට database එකට යවයි.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
} else {
    $action = $_GET['action'] ?? '';
}

if ($action === 'add') {
    $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
    $qty = max(1, min(5, (int)($_POST['qty'] ?? 1)));
    $stmt = $pdo->prepare("SELECT id, title, price, image, slug, status FROM artworks WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $art = $stmt->fetch();
    if ($art && $art['status'] === 'Available') {
        $_SESSION['cart'][$id] = [
            'id' => $art['id'],
            'title' => $art['title'],
            'price' => $art['price'],
            'image' => $art['image'],
            'slug' => $art['slug'],
            'qty' => ($_SESSION['cart'][$id]['qty'] ?? 0) + $qty,
        ];
        $_SESSION['cart'][$id]['qty'] = min(5, $_SESSION['cart'][$id]['qty']);
        $_SESSION['flash_success'] = 'Artwork added to cart.';
    } else {
        $_SESSION['flash_error'] = 'This artwork is not available.';
    }
    redirect('cart.php');
}

if ($action === 'update') {
    foreach ($_POST['qty'] ?? [] as $id => $qty) {
        if (isset($_SESSION['cart'][$id])) {
            $qty = max(1, min(5, (int)$qty));
            $_SESSION['cart'][$id]['qty'] = $qty;
        }
    }
    $_SESSION['flash_success'] = 'Cart updated successfully.';
    redirect('cart.php');
}

if ($action === 'remove') {
    $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
    unset($_SESSION['cart'][$id]);
    $_SESSION['flash_success'] = 'Item removed from cart.';
    redirect('cart.php');
}

$pageTitle = 'Shopping Cart';
$cart = $_SESSION['cart'] ?? [];
$total = array_reduce($cart, fn($sum, $item) => $sum + ((float)$item['price'] * (int)$item['qty']), 0);
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container"><span class="eyebrow">Cart</span><h1>Shopping Cart</h1><p>Review selected artworks before placing your order.</p></div>
</section>
<div class="container section">
    <?php if (!$cart): ?>
        <div class="empty-state"><h3>Your cart is empty</h3><p>Browse the gallery and add available artworks.</p><a class="btn btn-dark" href="<?= url('gallery.php') ?>">Browse Artworks</a></div>
    <?php else: ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="update">
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Artwork</th><th>Price</th><th>Qty</th><th>Subtotal</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td><strong><?= e($item['title']) ?></strong><br><small><a href="<?= url('artwork.php?slug=' . urlencode($item['slug'])) ?>">View artwork</a></small></td>
                            <td><?= money((float)$item['price']) ?></td>
                            <td><input class="form-control" type="number" name="qty[<?= (int)$item['id'] ?>]" value="<?= (int)$item['qty'] ?>" min="1" max="5" style="max-width:90px"></td>
                            <td><?= money((float)$item['price'] * (int)$item['qty']) ?></td>
                            <td><a class="btn btn-light btn-small" href="<?= url('cart.php?action=remove&id=' . (int)$item['id']) ?>" data-confirm="Remove this item?">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="panel" style="margin-top:22px">
                <div class="panel-head"><h2>Total: <?= money((float)$total) ?></h2><div class="card-actions"><button class="btn btn-light" type="submit">Update Cart</button><a class="btn btn-dark" href="<?= url('checkout.php') ?>">Checkout</a></div></div>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
