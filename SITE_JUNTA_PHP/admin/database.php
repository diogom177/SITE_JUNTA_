<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "junta";

// criar conexao
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexao
if ($conn->connect_error) {
  die("Erro de conexão: " . $conn->connect_error);
}
?>