<?php
include 'admin/database.php'; // Caminho para o teu ficheiro de ligação

if (!$conn) {
  die("Erro de conexão: " . mysqli_connect_error());
}
$sql = "SELECT id, opcao, link, classe FROM submenu_freguesia ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql);

$sql = "SELECT id, `option`, `url`, classse, tipo, parent FROM submenu_autarquia ORDER BY id ASC";
$result2 = mysqli_query($conn, $sql);

if (!$result2) {
  die("Erro na consulta submenu_autarquia: " . mysqli_error($conn));
}

$sql1 = "SELECT id, logo, logo_titulo FROM logo";
$res1 = mysqli_query($conn, $sql1);


if ($row1 = mysqli_fetch_assoc($res1)) {
  $logo = $row1['logo'];
  $logo_titulo = $row1['logo_titulo'];
}
?>


<!DOCTYPE html>
<html lang="pt-PT">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Assembleia - Junta de Freguesia de Barreiro de Besteiros e Tourigo</title>
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
  <link rel="stylesheet" href="CSS/contactos.css">
  <link rel="stylesheet" href="CSS/rodape.css">

</head>

<body>
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
  </div>


  <footer class="footer-meruge" style="margin-top: -30px;">
    <div class="footer-meruge-top">
      <div class="footer-meruge-brand">
        <img src="IMAGENS/logo_fb-removebg-preview (1).png" class="footer-meruge-logo" alt="Logo Meruge" href="index.php">
        <h1 class="footer-meruge-title">Junta de Freguesia de Barreiro <br>de Besteiros</h1>
        <div class="footer-meruge-info" style="margin-top:30px;">
          <p>
            <span style="display:inline-block; min-width: 220px; font-size: 18px;">
              <span class="footer-meruge-icon" style="color:#ffa000;"><i class="bi bi-geo-alt-fill"></i></span>
              Rua Nossa Senhora dos Aflitos, 121
            </span>
            <br>
            <span style="margin-left: 26px; font-size: 18px;">
              3465–012 Barreiro de Besteiros
            </span>
          </p>
          <p>
            <span class="footer-meruge-icon">&#9993; <a href="mailto:junta.barreiro.tourigo@gmail.com">junta.barreiro.tourigo@gmail.com</a> </span>
          </p>
          <p>
            <span class="footer-meruge-icon">&#9742; <a href="tel:232 871 137">232 871 137</a><small> *Chamada para a rede fixa nacional</small> </span>
          </p>
          <p>
            <span class="footer-meruge-icon"><i class="bi bi-telephone"></i> <a href="tel:966754989 ">966 754 989 </a><small>*Chamada para a rede móvel nacional</small> </span>
          </p>
          <p>
            <span class="footer-meruge-icon"><i class="bi bi-calendar4"></i></span>Horário de Funcionamento:
          </p>
          <p>
            <span style="margin-left: 20px;">Terças-feiras e Sextas-feiras: <br></span>
            <span style="margin-left: 20px;">19h00 - 20h30</span>
          </p>
        </div>
      </div>
      <div class="footer-meruge-section" style="margin-bottom: 20px;">
        <h4>Freguesia</h4>
        <ul>
          <li><a href="história.php">História</a></li>
          <li><a href="sobre.php">Sobre a Freguesia</a></li>
          <li><a href="locais.php">Locais de Interesse</a></li>
          <li><a href="galeria.php">Galeria de Fotos</a></li>
          <li><a href="noticias.php">Notícias</a></li>
        </ul>
      </div>
      <div class="footer-meruge-section">
        <h4>Autarquia</h4>
        <ul>
          <li><a style="color: #444444;" href="documentos.php">Pedido de Documentos</a></li>
          <li><a href="executivo.php">Executivo</a></li>
          <li><a href="assembleia.php">Assembleia</a></li>
        </ul>
      </div>
      <div class="footer-meruge-section">
        <h4>Executivo</h4>
        <ul>
          <li><a href="#">Membros do Executivo</a></li>
          <li><a href="#">Informações</a></li>
          <li><a href="#">Avisos</a></li>
          <li><a href="#">Editais</a></li>
          <li><a href="#">Atas</a></li>
        </ul>
      </div>
      <div class="footer-meruge-section">
        <h4>Contactos</h4>
        <ul>
          <li><a href="história.php">Contactos da Freguesia</a></li>
          <li><a href="sobre.php">Contactos de Interesse Geral</a></li>
          <li><a href="galeria.php">Formulário de Contacto</a></li>
          <li><a href="locais.php">Localização</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-meruge-bottom">
      <p>@2026 Junta de Freguesia de Barreiro de Besteiros</p>
    </div>
  </footer>


  <script src="JS/navbar.js"></script>
  <script src="JS/submenulateral.js"></script>



</body>

</html>