<?php
session_start();

function requireLogin($requiredRole = null) {
    if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
        $_SESSION['redirect_after_login'] = basename($_SERVER['PHP_SELF']);
        $_SESSION['alert'] = [
            'message' => 'Silakan login dulu.',
            'type' => 'warning'
        ];
        header("Location: ../login.php");
        exit();
    }

    if ($requiredRole !== null && $_SESSION['role'] != $requiredRole) {
        $_SESSION['alert'] = [
            'message' => 'Akses ditolak.',
            'type' => 'danger'
        ];
        header("Location: ../login.php");
        exit();
    }
}
?>
