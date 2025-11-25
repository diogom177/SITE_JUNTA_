<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'junta';

$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql1 = "SELECT id, logo, logo_titulo FROM logo";
$res1 = mysqli_query($conn, $sql1);

$sql = "SELECT id, opcao, link, classe FROM submenu_freguesia ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql);

$sql = "SELECT id, `option`, `url`, classse, tipo, parent FROM submenu_autarquia ORDER BY id ASC";
$result2 = mysqli_query($conn, $sql);

$sql_contactos = "SELECT id, opcao_, url_, classe_1 FROM submenu_contactos ORDER BY id ASC";
$resultado1 = mysqli_query($conn, $sql_contactos);


if (!$result2) {
  die("Erro na consulta: " . mysqli_error($conn));
}

$sql = "SELECT id, img, titulo FROM `login`";
$result3 = mysqli_query($conn, $sql);

if ($result3 = mysqli_fetch_assoc($result3)) {
  $logo = $result3['img'];
  $logo_titulo = $result3['titulo'];
}

if ($row1 = mysqli_fetch_assoc($res1)) {
  $logo = $row1['logo'];
  $logo_titulo = $row1['logo_titulo'];
}

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $Username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $admin_query = "SELECT * FROM admin WHERE email = '$Username' AND password = '$password'";
  $admin_result = mysqli_query($conn, $admin_query);

  if (mysqli_num_rows($admin_result) == 1) {
    $_SESSION['admin_username'] = $Username;
    header('Location: admin/backoffice.php');
    exit();
  } else {
    echo '<script>alert("Credenciais Inválidas. Por favor verifique os dados inseridos!");</script>';
  }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Login - Junta de Freguesia de Barreiro de Besteiros e Tourigo</title>
  <link rel="icon" href="IMAGENS/LOGO_U_F_BB.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <link rel="stylesheet" href="CSS/estilo1.css">
  <link rel="stylesheet" href="CSS/login.css">
  <link rel="stylesheet" href="CSS/rodape.css">


</head>

<body>

  <style>
    body {
      padding-top: 130px;
      background-color: #FFFFFF;
    }
  </style>

  <?php
  $sql = "SELECT telefone, horario, email, sede FROM topbar";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    $dados = $result->fetch_assoc();
  } else {
    $dados = [
      'telefone' => '232 871 137',
      'horario' => 'Barreiro: Terças das 19h00 - 20h30 Tourigo: Sextas Feiras das 13h-14h',
      'email' => 'junta.barreiro.tourigo@gmail.com',
      'sede' => 'R. Nossa Senhora dos Aflitos, 121, Barreiro de Besteiros',
    ];
  }
  ?>
  <!-- Top Bar -->
  <div class="top-bar">
    <div class="carrosel">
      <span>
        <i class="bi bi-telephone-forward-fill">
          <a href="tel:<?= htmlspecialchars($dados['telefone']) ?>"><?= htmlspecialchars($dados['telefone']) ?></a>
        </i>
      </span>
      <span>🕒 Horário de Atendimento: <?= htmlspecialchars($dados['horario']) ?></span>
      <span>📧 Email:<a href="mailto:<?= htmlspecialchars($dados['email']) ?>"><?= htmlspecialchars($dados['email']) ?></a></span>
      <span>📍 Sede: <?= htmlspecialchars($dados['sede']) ?></span>
    </div>
  </div>

  <!-- Navbar -->
  <div class="navbar">
    <div class="logo">
      <a href="index.php">
        <img src="IMAGENS/<?= htmlspecialchars($logo) ?>" alt="<?= ($logo_titulo) ?>">
      </a>
      <div class="titulo">
        <strong>
          <p><a href="index.php"><?= ($logo_titulo) ?></a></p>
        </strong>
      </div>
    </div>

    <div class="menu">
      <div class="dropdown">
        <a href="index.php">
          <icone class="bi bi-house-fill"></icone> Início
        </a>
      </div>

      <div class="dropdown">
        <a href="index.php#boasvindas">
          <svg xmlns="http://www.w3.org/2000/svg" fill="#150d0dff" viewBox="0 0 24 24" height="24" width="24" style="margin-bottom: -5px;">
            <path d="M12 2 1 7v2h22V7L12 2zM3 10v10h2v-8h2v8h2v-8h2v8h2v-8h2v8h2v-8h2v8h2V10H3z" />
          </svg> Boas-Vindas
        </a>
      </div>

      <div class="dropdown">
        <a href="#">
          <icone class="bi bi-compass"></icone> Freguesia <i class="bi bi-caret-down"></i>
        </a>
        <div class="submenu-freguesia">
          <?php while ($row = $resultado->fetch_assoc()) { ?>
            <a class="<?= htmlspecialchars($row['classe']) ?>" href="<?= htmlspecialchars($row['link']) ?>">
              <?= htmlspecialchars($row['opcao']) ?>
            </a>
          <?php }
          ?>
        </div>
      </div>

      <div class="dropdown">
        <a href="#">
          <span class="bi bi-building"></span> Autarquia <i class="bi bi-caret-down"></i>
        </a>

        <div class="submenu-autarquia">
          <?php
          // Carrega todos os menus da base de dados (resultado da query submenu_autarquia)
          $menus = [];

          while ($row_autarquia = mysqli_fetch_assoc($result2)) {
            // Usa os NOME das colunas exatamente como estão na BD
            $id = $row_autarquia['id'];
            $opcao = $row_autarquia['option']; // <-- campo correto da BD
            $url = $row_autarquia['url'];
            $classe = $row_autarquia['classse']; // <-- campo correto da BD
            $parent = isset($row_autarquia['parent']) ? $row_autarquia['parent'] : 0;

            // Agrupa os menus pelo seu "parent"
            $menus[$parent][] = [
              'id' => $id,
              'opcao' => $opcao,
              'url' => $url,
              'classe' => $classe
            ];
          }

          // Agora percorre os menus de topo (parent = 0)
          if (!empty($menus[0])) {
            foreach ($menus[0] as $menu) {
              // Menu “Executivo” com submenus à direita
              if ($menu['opcao'] === "Executivo") {
                echo '<div class="executivo-dropdown">';
                echo '<a class="' . htmlspecialchars($menu['classe']) . '" href="' . htmlspecialchars($menu['url']) . '">' . htmlspecialchars($menu['opcao']) . '</a>';

                // Submenus do Executivo
                if (!empty($menus[$menu['id']])) {
                  echo '<div class="submenu-right">';
                  foreach ($menus[$menu['id']] as $submenu) {
                    echo '<a class="' . htmlspecialchars($submenu['classe']) . '" href="' . htmlspecialchars($submenu['url']) . '">' . htmlspecialchars($submenu['opcao']) . '</a>';
                  }
                  echo '</div>';
                }

                echo '</div>';
              }
              // Menus normais
              else {
                echo '<a class="' . htmlspecialchars($menu['classe']) . '" href="' . htmlspecialchars($menu['url']) . '">' . htmlspecialchars($menu['opcao']) . '</a>';
              }
            }
          } else {
            echo '<p>Nenhum item de menu encontrado.</p>';
          }
          ?>
        </div>
      </div>


      <div class="dropdown">
        <a href="contactos.php">
          <span class="bi bi-telephone-outbound-fill"> Contactos <i class="bi bi-caret-down"></i></span>
        </a>
        <div class="submenu-contactos">
          <?php while ($row = $resultado1->fetch_assoc()) { ?>
            <a class="<?= htmlspecialchars($row['classe_1']) ?>" href="<?= htmlspecialchars($row['url_']) ?>">
              <?= htmlspecialchars($row['opcao_']) ?>
            </a>
          <?php }
          ?>
        </div>
      </div>
    </div> <!-- fecha menu -->

    <div class="navbar_canto">
      <a href="login.php">
        <img src="IMAGENS/image-removebg-preview (38).png" alt="Login">
      </a>
      <i class="bi bi-search" style="font-size:30px; margin-left:40px; cursor:pointer; position:absolute; bottom:36px;"></i>
    </div>
  </div> <!-- fecha navbar -->

  <!--FORM DE LOGIN-->
  <div class="container">
    <img src="IMAGENS/<?= htmlspecialchars($result3['img']) ?>" class="logo">
    <div class="heading"><?= ($result3['titulo']) ?></div>

    <form class="form" method="POST" action="login.php">
      <div class="input-field">
        <i class="bi person bi-person-fill"></i>
        <input
          required
          autocomplete="off"
          type="text"
          name="username"
          id="username" 
          autofocus/>
        <label for="username">Utilizador</label>
      </div>

      <div class="input-field">
        <i class="lock bi bi-lock-fill"></i>
        <input
          required
          autocomplete="off"
          type="password"
          name="password"
          id="password" />
        <label for="password">Palavra-passe</label>
      </div>

      <div class="btn-container">
        <button type="submit" class="btn">Entrar</button>
      </div>
    </form>
  </div>



  <script src="JS/navbar.js"></script>
  <script src="JS/submenulateral.js"></script>

</body>

</html>