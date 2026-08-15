<?php
require_once __DIR__ . '/includes/helpers.php';
// සිංහල සටහන: user session එක ඉවත් කර logout කරයි.
session_destroy();
session_start();
$_SESSION['flash_success'] = 'You have been logged out.';
redirect('index.php');
