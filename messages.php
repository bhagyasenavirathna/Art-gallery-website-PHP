<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';
require_login();
if (is_admin()) { redirect('admin/messages.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($subject === '' || $message === '') {
        $_SESSION['flash_error'] = 'Please enter subject and message.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO messages (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([current_user()['id'], current_user()['name'], current_user()['email'], $subject, $message]);
        log_email(ADMIN_EMAIL, 'New Customer Message', current_user()['name'] . ' sent a message: ' . $subject);
        $_SESSION['flash_success'] = 'Message sent successfully.';
        redirect('messages.php');
    }
}

$stmt = $pdo->prepare("SELECT * FROM messages WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([current_user()['id']]);
$messages = $stmt->fetchAll();
$replies = [];
if ($messages) {
    $ids = implode(',', array_map('intval', array_column($messages, 'id')));
    $replyRows = $pdo->query("SELECT message_replies.*, users.name AS admin_name FROM message_replies JOIN users ON message_replies.admin_id = users.id WHERE message_id IN ($ids) ORDER BY created_at ASC")->fetchAll();
    foreach ($replyRows as $row) { $replies[$row['message_id']][] = $row; }
}
$pageTitle = 'Messages';
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">Messaging</span><h1>Customer Messages</h1><p>Send inquiries and view admin replies.</p></div></section>
<div class="container dashboard">
    <aside class="sidebar">
        <a href="<?= url('dashboard.php') ?>"><?= icon_svg('box') ?> My Orders</a>
        <a class="active" href="<?= url('messages.php') ?>"><?= icon_svg('mail') ?> Messages</a>
        <a href="<?= url('custom-order.php') ?>"><?= icon_svg('edit') ?> Custom Art</a>
    </aside>
    <section>
        <form class="panel" method="post">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <h2>New Message</h2>
            <div class="form-group"><label>Subject</label><input class="form-control" name="subject" required></div>
            <div class="form-group"><label>Message</label><textarea class="form-control" name="message" required></textarea></div>
            <button class="btn btn-dark" type="submit">Send Message</button>
        </form>
        <div class="panel">
            <h2>Message History</h2>
            <?php foreach ($messages as $msg): ?>
                <div class="message-box">
                    <strong><?= e($msg['subject']) ?></strong> <span class="badge"><?= e($msg['status']) ?></span>
                    <br><small><?= e($msg['created_at']) ?></small>
                    <p><?= nl2br(e($msg['message'])) ?></p>
                    <?php foreach ($replies[$msg['id']] ?? [] as $reply): ?>
                        <div class="message-box" style="background:#fbfaf8"><strong>Admin Reply</strong><br><small><?= e($reply['created_at']) ?></small><p><?= nl2br(e($reply['reply'])) ?></p></div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <?php if (!$messages): ?><p>No messages yet.</p><?php endif; ?>
        </div>
    </section>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
