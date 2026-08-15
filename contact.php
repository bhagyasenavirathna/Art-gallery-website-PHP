<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $subject === '' || $message === '') {
        $_SESSION['flash_error'] = 'Please fill all fields correctly.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO messages (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([current_user()['id'] ?? null, $name, $email, $subject, $message]);
        log_email(ADMIN_EMAIL, 'New Contact Message', $name . ' sent a message: ' . $subject);
        $_SESSION['flash_success'] = 'Your inquiry was sent successfully.';
        redirect('contact.php');
    }
}
$pageTitle = 'Contact';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">Contact</span><h1>Contact Gallery</h1><p>Send inquiries about artworks, orders, availability, or custom artwork requests.</p></div></section>
<div class="container contact-grid">
    <div class="contact-info">
        <h2>Gallery Support</h2>
        <p>The contact system stores messages in the admin dashboard and writes email notification logs for SMTP-ready workflow.</p>
        <div class="info-line"><?= icon_svg('mail') ?><span>admin@artgallery.lk</span></div>
        <div class="info-line"><?= icon_svg('box') ?><span>Colombo Art Gallery, Sri Lanka</span></div>
        <div class="info-line"><?= icon_svg('shield') ?><span>Secure inquiry management</span></div>
    </div>
    <form class="form-card" method="post">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <div class="form-grid">
            <div class="form-group"><label>Name</label><input class="form-control" name="name" value="<?= e(current_user()['name'] ?? '') ?>" required></div>
            <div class="form-group"><label>Email</label><input class="form-control" type="email" name="email" value="<?= e(current_user()['email'] ?? '') ?>" required></div>
        </div>
        <div class="form-group"><label>Subject</label><input class="form-control" name="subject" required></div>
        <div class="form-group"><label>Message</label><textarea class="form-control" name="message" required></textarea></div>
        <button class="btn btn-dark" type="submit">Send Inquiry</button>
    </form>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
