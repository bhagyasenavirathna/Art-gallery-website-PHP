<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        $_SESSION['flash_error'] = 'Please enter valid details. Password must be at least 6 characters.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['flash_error'] = 'This email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, address, password, role) VALUES (?, ?, ?, ?, ?, 'customer')");
            $stmt->execute([$name, $email, $phone, $address, $hash]);
            $_SESSION['flash_success'] = 'Registration successful. Please login.';
            redirect('login.php');
        }
    }
}
$pageTitle = 'Register';
require_once __DIR__ . '/includes/header.php';
?>
<section class="auth-wrap">
    <div class="form-card auth-card">
        <span class="eyebrow">Create Account</span>
        <h1>Join Arts Gallery</h1>
        <p>Register to place orders, upload reference images, and track your artwork requests.</p>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <div class="form-group"><label>Name</label><input class="form-control" name="name" required></div>
            <div class="form-group"><label>Email</label><input class="form-control" type="email" name="email" required></div>
            <div class="form-grid">
                <div class="form-group"><label>Phone</label><input class="form-control" name="phone"></div>
                <div class="form-group"><label>Password</label><input class="form-control" type="password" name="password" minlength="6" required></div>
            </div>
            <div class="form-group"><label>Address</label><textarea class="form-control" name="address"></textarea></div>
            <button class="btn btn-dark btn-block" type="submit">Create Account</button>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
