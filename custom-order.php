<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $reference = upload_reference_image('reference_image');
        $subject = 'Custom Artwork Request';
        $message = trim($_POST['message'] ?? '');
        if ($message === '') { throw new RuntimeException('Please describe your custom artwork request.'); }
        $stmt = $pdo->prepare("INSERT INTO messages (user_id, name, email, subject, message, status) VALUES (?, ?, ?, ?, ?, 'Open')");
        $extra = $reference ? "\n\nReference image: {$reference}" : '';
        $stmt->execute([current_user()['id'], current_user()['name'], current_user()['email'], $subject, $message . $extra]);
        log_email(ADMIN_EMAIL, 'New Custom Artwork Request', current_user()['name'] . ' sent a custom artwork request.');
        $_SESSION['flash_success'] = 'Custom artwork request sent successfully.';
        redirect('messages.php');
    } catch (Throwable $e) {
        $_SESSION['flash_error'] = $e->getMessage();
    }
}
$pageTitle = 'Custom Artwork Request';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">Custom Art</span><h1>Upload Reference Image</h1><p>Send a custom artwork request to the gallery admin with your reference image and requirement details.</p></div></section>
<div class="container contact-grid">
    <div class="contact-info">
        <h2>How it works</h2>
        <p>Upload a JPG, PNG, or WEBP image below 3MB. The admin will review your request, reply through the messaging system, and confirm the next steps.</p>
        <div class="info-line"><?= icon_svg('shield') ?><span>Validated image uploads and secure customer login.</span></div>
        <div class="info-line"><?= icon_svg('mail') ?><span>Admin replies are visible in your message dashboard.</span></div>
    </div>
    <form class="form-card" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <div class="form-group"><label>Reference Image</label><input class="form-control" type="file" name="reference_image" accept="image/png,image/jpeg,image/webp"></div>
        <div class="form-group"><label>Artwork Requirement</label><textarea class="form-control" name="message" required placeholder="Describe size, style, colour, deadline, and other details..."></textarea></div>
        <button class="btn btn-dark btn-block" type="submit">Send Request</button>
    </form>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
