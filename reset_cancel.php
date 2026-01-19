<?php
session_start();
require 'admin/database.php';

$token = $_GET['token'] ?? '';

if ($token === '') {
    $_SESSION['flash_error'] = 'Token inválido.';
    header('Location: login.php');
    exit();
}

$sql = "UPDATE admin SET reset_key = NULL, reset_expires_at = NULL WHERE reset_key = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['flash_error'] = 'Erro na base de dados.';
    header('Location: login.php');
    exit();
}

$stmt->bind_param('s', $token);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Mensagem e classe específica para cancelamento
    $_SESSION['flash_error'] = 'Redefinição cancelada.';
    $_SESSION['flash_error_class'] = 'reset-cancelado';
} else {
    $_SESSION['flash_error'] = 'Token não encontrado ou já expirado.';
    $_SESSION['flash_error_class'] = 'reset-erro';
}

header('Location: login.php');
exit();
