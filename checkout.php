<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';
require_login();

$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
    $_SESSION['flash_error'] = 'Your cart is empty.';
    redirect('gallery.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        // සිංහල සටහන: මෙය demo card payment simulation එකක් පමණි; real payment gateway එකක් නොවේ.
        $demoCardNumber = preg_replace('/\D+/', '', $_POST['demo_card_number'] ?? '');
        $demoCardName = trim($_POST['demo_card_name'] ?? '');
        $demoCardExpiry = trim($_POST['demo_card_expiry'] ?? '');
        $demoCardCvc = preg_replace('/\D+/', '', $_POST['demo_card_cvc'] ?? '');

        $isValidDemoPayment = $demoCardNumber === '4242424242424242'
            && $demoCardName !== ''
            && $demoCardExpiry === '12/30'
            && $demoCardCvc === '123';

        if (!$isValidDemoPayment) {
            throw new RuntimeException('Demo payment failed. Please use the demo card details shown on the checkout page.');
        }

        $total = array_reduce($cart, fn($sum, $item) => $sum + ((float)$item['price'] * (int)$item['qty']), 0);
        $address = trim($_POST['delivery_address'] ?? current_user()['address'] ?? '');
        $note = trim($_POST['customer_note'] ?? '');
        $reference = upload_reference_image('reference_image');
        $orderNumber = 'AG-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, order_number, total, status, customer_note, reference_image, delivery_address) VALUES (?, ?, ?, 'Pending', ?, ?, ?)");
        $stmt->execute([current_user()['id'], $orderNumber, $total, $note, $reference, $address]);
        $orderId = (int)$pdo->lastInsertId();

        $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, artwork_id, price, quantity) VALUES (?, ?, ?, ?)");
        $statusStmt = $pdo->prepare("UPDATE artworks SET status = 'Reserved' WHERE id = ? AND status = 'Available'");
        foreach ($cart as $item) {
            $itemStmt->execute([$orderId, $item['id'], $item['price'], $item['qty']]);
            $statusStmt->execute([$item['id']]);
        }
        $pdo->commit();

        log_email(current_user()['email'], 'Order Confirmation - ' . $orderNumber, "Your order has been received. Demo payment approved. Total: " . money((float)$total));
        unset($_SESSION['cart']);
        $_SESSION['flash_success'] = 'Demo payment approved. Order placed successfully. Order number: ' . $orderNumber;
        redirect('dashboard.php');
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) { $pdo->rollBack(); }
        $_SESSION['flash_error'] = $e->getMessage();
    }
}

$pageTitle = 'Checkout';
$total = array_reduce($cart, fn($sum, $item) => $sum + ((float)$item['price'] * (int)$item['qty']), 0);
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container"><span class="eyebrow">Checkout</span><h1>Place Artwork Order</h1><p>Use the demo card details below to complete a safe payment simulation. No real card payment is processed.</p></div>
</section>
<div class="container section">
    <div class="detail-grid" style="padding:0">
        <form class="form-card" method="post" enctype="multipart/form-data" data-demo-payment-form>
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <div class="form-group"><label>Delivery Address / Contact Address</label><textarea class="form-control" name="delivery_address" required><?= e(current_user()['address'] ?? '') ?></textarea></div>
            <div class="form-group"><label>Order Note</label><textarea class="form-control" name="customer_note" placeholder="Write any special requirement or custom request..."></textarea></div>
            <div class="form-group"><label>Reference Image Optional</label><input class="form-control" type="file" name="reference_image" accept="image/png,image/jpeg,image/webp"></div>

            <div class="demo-payment-box">
                <div class="demo-payment-head">
                    <div>
                        <span class="eyebrow">Demo Payment</span>
                        <h2>Card Details</h2>
                    </div>
                    <span class="badge badge-success">Test Mode</span>
                </div>
                <div class="demo-card">
                    <strong>Demo Card</strong>
                    <span>4242 4242 4242 4242</span>
                    <small>Expiry 12/30 · CVC 123</small>
                </div>
                <button class="btn btn-light btn-small" type="button" data-fill-demo-card>Use Demo Card Details</button>
                <div class="form-group"><label>Name on Card</label><input class="form-control" type="text" name="demo_card_name" placeholder="Demo Customer" required autocomplete="cc-name"></div>
                <div class="form-group"><label>Card Number</label><input class="form-control" type="text" name="demo_card_number" placeholder="4242 4242 4242 4242" required inputmode="numeric" autocomplete="cc-number" maxlength="19"></div>
                <div class="form-grid">
                    <div class="form-group"><label>Expiry</label><input class="form-control" type="text" name="demo_card_expiry" placeholder="12/30" required autocomplete="cc-exp" maxlength="5"></div>
                    <div class="form-group"><label>CVC</label><input class="form-control" type="password" name="demo_card_cvc" placeholder="123" required inputmode="numeric" autocomplete="cc-csc" maxlength="3"></div>
                </div>
                <p class="demo-payment-note">This is only a demo checkout process. Please do not enter real card details.</p>
                <div class="demo-processing" data-demo-processing>
                    <span class="demo-spinner"></span>
                    <strong>Processing demo payment...</strong>
                </div>
            </div>

            <button class="btn btn-dark btn-block" type="submit" data-demo-pay-button>Pay & Confirm Order</button>
        </form>
        <div class="panel">
            <h2>Order Summary</h2>
            <div class="table-wrap" style="margin-top:18px">
                <table>
                    <thead><tr><th>Artwork</th><th>Qty</th><th>Total</th></tr></thead>
                    <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr><td><?= e($item['title']) ?></td><td><?= (int)$item['qty'] ?></td><td><?= money((float)$item['price'] * (int)$item['qty']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <h2 style="margin-top:20px">Total: <?= money((float)$total) ?></h2>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('[data-demo-payment-form]');
    const fillButton = document.querySelector('[data-fill-demo-card]');
    const processingBox = document.querySelector('[data-demo-processing]');
    const payButton = document.querySelector('[data-demo-pay-button]');

    if (fillButton && form) {
        fillButton.addEventListener('click', function () {
            form.querySelector('[name="demo_card_name"]').value = 'Demo Customer';
            form.querySelector('[name="demo_card_number"]').value = '4242 4242 4242 4242';
            form.querySelector('[name="demo_card_expiry"]').value = '12/30';
            form.querySelector('[name="demo_card_cvc"]').value = '123';
        });
    }

    if (form && processingBox && payButton) {
        form.addEventListener('submit', function (event) {
            if (form.dataset.processing === '1') {
                return;
            }
            if (!form.checkValidity()) {
                return;
            }
            event.preventDefault();
            form.dataset.processing = '1';
            processingBox.classList.add('is-active');
            payButton.disabled = true;
            payButton.textContent = 'Processing...';
            setTimeout(function () {
                form.submit();
            }, 1200);
        });
    }
});
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
