<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'junta';

$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>


<!DOCTYPE html>
<html lang="pt-PT">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Backoffice de Administração</title>

  <link rel="icon" href="IMAGENS/LOGO_U_F_BB.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="CSS/backoffice.css">

<body>

  <!-- Navbar -->
  <div class="navbar">

    <div class="menu">
      <div class="dropdown">
        <a href="index.php">
          <icone class="bi bi-house-fill"></icone> Início
        </a>
      </div>


      <div class="dropdown">
        <a href="contactos.php">
          <span class="bi bi-telephone-outbound-fill"> Contactos</span>
        </a>
      </div>
    </div> <!-- fecha menu -->

  </div> <!-- fecha navbar -->

</body>

</html>