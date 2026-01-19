<?php
include 'admin/database.php';  // O teu ficheiro de conexão

if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
}

if ($_POST) {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $assunto = mysqli_real_escape_string($conn, $_POST['assunto']);
    $mensagem = mysqli_real_escape_string($conn, $_POST['mensagem']);
    
    // Data/hora atual
    $data_envio = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO formulario_contacto (nome, email, assunto, mensagem) 
            VALUES ('$nome', '$email', '$assunto', '$mensagem')";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect back to the contact page with a success status
        header('Location: Contactos.php?status=success');
        exit;
    } else {
        // Redirect back with an error status (do not expose raw DB error to users)
        header('Location: Contactos.php?status=error');
        exit;
    }
}

mysqli_close($conn);
?>
