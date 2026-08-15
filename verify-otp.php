<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/otp.php';

if (empty($_SESSION['pending_login'])) {
    $_SESSION['flash_error'] = 'Please enter your login details first.';
    redirect('login.php');
}

$pendingUser = $_SESSION['pending_login'];
$emailMasked = mask_email($pendingUser['email']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? 'verify';

    if ($action === 'resend') {
        // සිංහල සටහන: user ඉල්ලුවොත් අලුත් OTP එකක් නැවත email කරයි.
        $otp = create_login_otp($pendingUser);
        if (send_login_otp($pendingUser, $otp)) {
            $_SESSION['flash_success'] = 'A new OTP code was sent to ' . $emailMasked . '.';
        } else {
            $_SESSION['flash_error'] = 'OTP email could not be sent. Please check SMTP settings.';
        }
        redirect('verify-otp.php');
    }

    $otpCode = preg_replace('/\D/', '', $_POST['otp'] ?? '');
    $_SESSION['login_otp_attempts'] = (int)($_SESSION['login_otp_attempts'] ?? 0) + 1;

    if (time() > (int)($_SESSION['login_otp_expires'] ?? 0)) {
        clear_login_otp();
        $_SESSION['flash_error'] = 'OTP code expired. Please login again.';
        redirect('login.php');
    }

    if ($_SESSION['login_otp_attempts'] > OTP_MAX_ATTEMPTS) {
        clear_login_otp();
        $_SESSION['flash_error'] = 'Too many incorrect OTP attempts. Please login again.';
        redirect('login.php');
    }

    if (strlen($otpCode) === 6 && password_verify($otpCode, $_SESSION['login_otp_hash'] ?? '')) {
        // සිංහල සටහන: OTP එක නිවැරදි නම් user session එක create කර login කරයි.
        $_SESSION['user'] = $pendingUser;
        clear_login_otp();
        $_SESSION['flash_success'] = 'Welcome back, ' . $pendingUser['name'] . '.';
        redirect($pendingUser['role'] === 'admin' ? 'admin/dashboard.php' : 'dashboard.php');
    }

    $_SESSION['flash_error'] = 'Invalid OTP code. Please try again.';
}

$pageTitle = 'Verify OTP';
require_once __DIR__ . '/includes/header.php';
?>
<section class="auth-wrap">
    <div class="form-card auth-card otp-card">
        <span class="eyebrow">Email Verification</span>
        <h1>Enter OTP Code</h1>
        <p>We sent a 6-digit login code to <strong><?= e($emailMasked) ?></strong>. The code expires in <?= OTP_EXPIRY_MINUTES ?> minutes.</p>
        <form method="post" class="otp-form">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="verify">
            <div class="form-group">
                <label>OTP Code</label>
                <input class="form-control otp-input" type="text" name="otp" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="000000" required autofocus>
            </div>
            <button class="btn btn-dark btn-block" type="submit">Verify & Login</button>
        </form>
        <form method="post" style="margin-top:14px">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="resend">
            <button class="btn btn-light btn-block" type="submit">Resend OTP</button>
        </form>
        <p style="font-size:.88rem;margin-top:16px">Did not receive the email? Check SMTP settings in <code>config/mail.php</code> or check <code>storage/logs/email.log</code> during local testing.</p>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
