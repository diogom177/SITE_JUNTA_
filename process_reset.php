<?php
session_start();
require 'admin/database.php';

$token    = $_POST['token'] ?? '';
$password = trim($_POST['password'] ?? '');

if ($token === '' || $password === '') {
    $_SESSION['flash_error'] = 'Dados em falta.';
    header('Location: login.php');
    exit();
}

// Validação servidor: mínimo 8 caracteres
if (mb_strlen($password) < 8) {
    $_SESSION['flash_error'] = 'A palavra-passe deve ter pelo menos 8 caracteres.';
    header('Location: reset_password.php?token=' . urlencode($token));
    exit();
}

// Validar token
$sql = "SELECT id_admin FROM admin 
        WHERE reset_key = ? AND reset_expires_at > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $id_admin = (int)$row['id_admin'];

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql2 = "UPDATE admin
             SET password = ?, reset_key = NULL, reset_expires_at = NULL
             WHERE id_admin = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('si', $hash, $id_admin);
    $stmt2->execute();

    $_SESSION['flash_success'] = 'Password redefinida com sucesso! Já pode iniciar sessão.';
    header('Location: login.php');
    exit();
} else {
    $_SESSION['flash_error'] = 'Link de redefinição inválido ou expirado.';
    header('Location: login.php');
    exit();
}