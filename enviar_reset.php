<?php

session_start();
require 'admin/database.php';

$email = trim($_POST['email'] ?? '');

if ($email === '') {
    $_SESSION['flash_error'] = 'Indique o email no campo abaixo.';
    header('Location: recuperar_password.php');
    exit();
}

// 1) Ver se existe admin com esse email
$sql  = "SELECT id_admin FROM admin WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    // 2) Gerar token e data de expiração
    $token   = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600);

    $stmt->free_result();
    $sql2 = "UPDATE admin SET reset_key = ?, reset_expires_at = ? WHERE email = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('sss', $token, $expires, $email);
    $stmt2->execute();

    // 3) Construir link para redefinir
    $link = "http://localhost/SITE_JUNTA_PHP/reset_password.php?token=" . urlencode($token);

    $subject = 'Redefinição de palavra-passe - Junta de Freguesia';
    $message = "Olá,\n\nClique neste link para redefinir a sua palavra-passe:\n$link\n\nEste link expira em 1 hora.\n\nObrigado,\nJunta de Freguesia Barreiro de Besteiros";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n";
    $headers .= "From: Junta de Freguesia <juntafreguesiabarreiro@gmail.com>\r\n";
    $headers .= "Reply-To: juntafreguesiabarreiro@gmail.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    mail($email, $subject, $message, $headers);
}

// Mensagem genérica
$_SESSION['aviso_email'] = 'Foi enviado um link de redefinição de palavra-passe para ' . $email . '';
header('Location: recuperar_password.php');
exit();
