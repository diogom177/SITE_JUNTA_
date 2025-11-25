<?php
// Conexão à base de dados
include 'admin/database.php';

// Verifica se a ligação foi bem-sucedida
if ($conn->connect_error) {
  die("Erro na ligação à base de dados: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  $sql = "SELECT idnoticia, img, data, titulo, subtitulo, corponoticia, img_noticia, img_banner, sidebar_titulo 
            FROM noticias 
            WHERE idnoticia = $id LIMIT 1";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    $noticia = $result->fetch_assoc();
  } else {
    die("Notícia não encontrada.");
  }
} else {
  die("ID da notícia inválido.");
}


$idAtual = intval($_GET['id']);

// Buscar notícia anterior (maior ID menor que o atual)
$sqlAnterior = "SELECT idnoticia FROM noticias WHERE idnoticia < $idAtual ORDER BY idnoticia DESC LIMIT 1";
$resAnterior = $conn->query($sqlAnterior);
$idAnterior = ($resAnterior && $resAnterior->num_rows > 0) ? $resAnterior->fetch_assoc()['idnoticia'] : null;

// Buscar notícia seguinte (menor ID maior que o atual)
$sqlSeguinte = "SELECT idnoticia FROM noticias WHERE idnoticia > $idAtual ORDER BY idnoticia ASC LIMIT 1";
$resSeguinte = $conn->query($sqlSeguinte);
$idSeguinte = ($resSeguinte && $resSeguinte->num_rows > 0) ? $resSeguinte->fetch_assoc()['idnoticia'] : null;
function formatarDataPt($data_iso)
{
  $meses = [
    1 => 'JANEIRO',
    2 => 'FEVEREIRO',
    3 => 'MARÇO',
    4 => 'ABRIL',
    5 => 'MAIO',
    6 => 'JUNHO',
    7 => 'JULHO',
    8 => 'AGOSTO',
    9 => 'SETEMBRO',
    10 => 'OUTUBRO',
    11 => 'NOVEMBRO',
    12 => 'DEZEMBRO'
  ];
  $timestamp = strtotime($data_iso);
  $dia = date('j', $timestamp);
  $mes = $meses[(int)date('n', $timestamp)];
  $ano = date('Y', $timestamp);
  return "$dia DE $mes, $ano";
}

$sql_logo = "SELECT id, logo, logo_titulo FROM logo";
$res1 = mysqli_query($conn, $sql_logo);

$sql_freguesia = "SELECT id, opcao, link, classe FROM submenu_freguesia ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql_freguesia);

$sql_contactos = "SELECT id, opcao_, url_, classe_1 FROM submenu_contactos ORDER BY id ASC";
$resultado1 = mysqli_query($conn, $sql_contactos);

$sql_autarquia = "SELECT id, `option`, `url`, classse, tipo, parent FROM submenu_autarquia ORDER BY id ASC";
$result2 = mysqli_query($conn, $sql_autarquia);


if ($row1 = mysqli_fetch_assoc($res1)) {
  $logo = $row1['logo'];
  $logo_titulo = $row1['logo_titulo'];
}

// Buscar título e imagem da notícia anterior e seguinte para preview nos botões
$prevData = null;
if (!empty($idAnterior)) {
  $resPrev = $conn->query("SELECT titulo, img FROM noticias WHERE idnoticia = $idAnterior LIMIT 1");
  if ($resPrev && $resPrev->num_rows > 0) {
    $prevData = $resPrev->fetch_assoc();
  }
}

$nextData = null;
if (!empty($idSeguinte)) {
  $resNext = $conn->query("SELECT titulo, img FROM noticias WHERE idnoticia = $idSeguinte LIMIT 1");
  if ($resNext && $resNext->num_rows > 0) {
    $nextData = $resNext->fetch_assoc();
  }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($noticia['titulo']) ?></title>
  <link rel="icon" href="IMAGENS/LOGO_U_F_BB.png" type="image/x-icon">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Funnel+Display:wght@300..800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <link rel="stylesheet" href="CSS/estilo1.css">
  <link rel="stylesheet" href="CSS/locais.css">
  <link rel="stylesheet" href="CSS/rodape.css">
  <link rel="stylesheet" href="CSS/noticia.css">
  <link rel="stylesheet" href="CSS/style.css">
  <style>
    body {
      overflow-x: hidden;
    }

    .content {
      position: relative !important;
      height: 450px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      overflow: hidden;
      background: url('IMAGENS/<?= htmlspecialchars($noticia['img_banner']) ?>') center / cover no-repeat;
      font-family: "Figtree", sans-serif;
      font-optical-sizing: auto;
      font-weight: 500;
      font-style: normal;
      background-size: cover;
      background-repeat: no-repeat;
      bottom: 180px;
    }

    .navbar {
      z-index: 99999 !important;
    }
  </style>
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
    </div>

    <div class="navbar_canto">
      <a href="login.php">
        <img src="IMAGENS/image-removebg-preview (38).png" alt="Login">
      </a>
      <i class="bi bi-search" style="font-size:30px; margin-left:40px; cursor:pointer; position:absolute; bottom:36px;"></i>
    </div>
  </div>

  <main class="main-content">
    <div class="content">
      <div class="texto">
        <h2><?= htmlspecialchars($noticia['titulo']) ?></h2>
      </div>
    </div>
  </main>

  <style>
    .breadcrumbs a {
      color: #fff;
      text-decoration: none;
      transition: color 0.8s ease;
    }

    .breadcrumbs {
      position: relative;
      bottom: 300px;
      text-align: center;
      font-family: "Poppins", serif;
      font-size: 19px;
    }

    .breadcrumbs a:hover {
      color: #57c5b6ff;
      box-shadow: 0 4px 6px -2px rgba(0, 0, 0, 0.25);
    }

    .breadcrumbs i {
      font-size: 16px;
    }
  </style>

  <main class="sidebar-recentes">
    <h4 class="sidebar-titulo"><?= htmlspecialchars($noticia['sidebar_titulo']) ?></h4>
    <?php
    $idAtual = intval($_GET['id']);

    $sql = "SELECT idnoticia, img, titulo, data
        FROM noticias
        WHERE idnoticia != $idAtual
        ORDER BY data DESC
        LIMIT 6";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0):
      while ($row = $result->fetch_assoc()):
    ?>
        <div class="sidebar-noticia">
          <a href="noticia.php?id=<?= $row['idnoticia'] ?>">
            <img src="IMAGENS/<?= htmlspecialchars($row['img']) ?>" class="sidebar-img" alt="<?= htmlspecialchars($row['titulo']) ?>">
            <div class="sidebar-titulo-noticia"><?= htmlspecialchars($row['titulo']) ?></div>
            <div class="sidebar-data"><?= formatarDataPt($row['data']) ?></div>
          </a>
        </div>
      <?php endwhile;
    else: ?>
      <p>Nenhuma notícia encontrada.</p>
    <?php endif; ?>
  </main>

  <style>
    .sidebar-recentes {
      position: absolute;
      top: 540px;
      right: 40px;
      width: 440px;
      background: none;
      border-radius: 10px;
      box-shadow: 0 3px 12px rgba(30, 40, 85, 0.08);
      padding: 12px 15px;
      z-index: 1000;
      box-sizing: border-box;
      font-family: "Nunito Sans", sans-serif;
    }

    .sidebar-noticia {
      display: flex;
      align-items: center;
      margin-bottom: 28px;
      border-bottom: 1px solid #ededed;
      padding-bottom: 18px;
    }

    .sidebar-noticia a {
      display: flex;
      align-items: center;
      text-decoration: none;
      width: 100%;
    }

    .sidebar-img {
      width: 90px;
      height: 90px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 15px;
      flex-shrink: 0;
    }

    .sidebar-texto {
      max-width: 450px;
      /* controla a largura */
      display: grid;
      flex-direction: column;
      justify-content: center;
    }

    .sidebar-titulo-noticia {
      /*titulo da noticia*/
      font-size: 16px;
      font-weight: 500;
      color: #151414ff;
      margin-bottom: 17px;
      overflow-wrap: break-word;
      word-wrap: break-word;
      white-space: normal;
      display: block;
      position: relative;
      bottom: 10px;
    }

    .sidebar-titulo {
      text-align: center;
      font-size: 22px;
      letter-spacing: 0.5px;
      color: #3aa2cfff;
      font-weight: 600;
      position: relative;
      bottom: 10px;
    }

    .sidebar-data {
      font-size: 15px;
      position: absolute;
      letter-spacing: 0.1px;
      left: 120px;
      margin-top: 40px;
      color: #201d1dff;
      white-space: nowrap;
    }
  </style>

  <!-------------------    noticia completa    ------------------>

  <main class="breadcrumbs">
    <a href="index.php">Ínicio</a> <i class="fa-solid fa-angle-right"></i> <a href="noticias.php"> Notícias</a> <i class="fa-solid fa-angle-right"></i>
    <?= htmlspecialchars($noticia['titulo']) ?></a>
  </main>

  <main style="bottom: 150px; position:relative; right:160px; align-items:center;">
    <img class="noticia-imagem" src="IMAGENS/<?= htmlspecialchars($noticia['img']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>">
    <div class="noticia-data"><?= formatarDataPt($noticia['data']) ?></div>
    <i class="bi bi-calendar" style="width: 30px; height:30px; margin-left: 314px; position:relative; font-size:19px;"></i>
    <div class="noticia-titulo" style="position:relative; left: 110px;"><?= htmlspecialchars($noticia['titulo']) ?></div>
    <div class="noticia-lead"><?= ($noticia['corponoticia']) ?> </div>
    <?php
    $imagens = explode(',', $noticia['img_noticia']);

    foreach ($imagens as $img) {
      $img = trim($img);
      if ($img !== '') {
    ?>
        <img class="noticia-img"
          src="IMAGENS/<?= htmlspecialchars($img) ?>"
          alt="<?= htmlspecialchars($noticia['titulo']) ?>">
    <?php
      }
    }
    ?>
  </main>

  <footer class="noticia-footer">
    <span class="partilhar-label">PARTILHAR</span>
    <div class="partilhar-botoes">
      <a href="#" class="botao-partilhar" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
      <a href="#" class="botao-partilhar" aria-label="Email"><i class="fas fa-envelope"></i></a>
      <a href="#" class="botao-partilhar" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
    </div>
    <hr>
  </footer>


  <div class="navegacao-container">
    <div class="nav-esquerda">
      <?php if ($idAnterior): ?>
        <a class="botao-nav" href="noticia.php?id=<?= $idAnterior ?>" title="Notícia anterior">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="12 4 6 9 12 14" />
          </svg>
          <?php if (!empty($prevData)): ?>
            <div class="preview" aria-hidden="true">
              <img src="IMAGENS/<?= htmlspecialchars($prevData['img']) ?>" alt="<?= htmlspecialchars($prevData['titulo']) ?>">
              <div>
                <div class="preview-title"><?= htmlspecialchars($prevData['titulo']) ?></div>
                <div class="preview-excerpt"><?= htmlspecialchars($prevData['excerpt'] ?? '') ?></div>
              </div>
            </div>
          <?php endif; ?>
        </a>
      <?php endif; ?>
    </div>

    <div class="nav-direita">
      <?php if ($idSeguinte): ?>
        <a class="botao-nav" href="noticia.php?id=<?= $idSeguinte ?>" title="Notícia seguinte">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="6 4 12 9 6 14" />
          </svg>
          <?php if (!empty($nextData)): ?>
            <div class="preview" aria-hidden="true">
              <img src="IMAGENS/<?= htmlspecialchars($nextData['img']) ?>" alt="<?= htmlspecialchars($nextData['titulo']) ?>">
              <div>
                <div class="preview-title"><?= htmlspecialchars($nextData['titulo']) ?></div>
                <div class="preview-excerpt"><?= htmlspecialchars($nextData['excerpt'] ?? '') ?></div>
              </div>
            </div>
          <?php endif; ?>
        </a>
      <?php endif; ?>
    </div>
  </div>


  <div class="linhahorizontal">
    <hr>
  </div>

  <style>
    .linhahorizontal hr {
      border: 0;
      border-top: 1px solid #1c4e90ff;
      width: 98vw;
      max-width: 900px;
      margin: 30px auto 0 auto;
    }
  </style>


  <footer class="footer-meruge" style="margin-top: 50px; position: relative;">
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

  <script src="JS/submenulateral.js"></script>
  <script src="JS/paginacao.js"></script>

  <script>
    (function() {
      document.addEventListener("DOMContentLoaded", function() {
        const navbar = document.querySelector('.navbar');
        const topBar = document.querySelector('.top-bar');
        if (!navbar) return;
        const navbarHeight = navbar.offsetHeight;
        const topBarHeight = topBar ? topBar.offsetHeight : 0;

        let lastScroll = window.scrollY;
        // Inicializa
        if (window.scrollY <= 10) {
          navbar.style.top = topBarHeight + 'px';
          if (topBar) topBar.style.top = '0';
        } else {
          navbar.style.top = '0'; // Já no topo do viewport, deve aparecer!
          if (topBar) topBar.style.top = '-' + topBarHeight + 'px';
        }

        window.addEventListener('scroll', function() {
          let currentScroll = window.scrollY;
          if (currentScroll <= 10) {
            navbar.style.top = topBarHeight + 'px';
            if (topBar) topBar.style.top = '0';
          } else if (currentScroll < lastScroll) {
            navbar.style.top = '0'; // Sempre visível ao subir scroll!
            if (topBar) topBar.style.top = '-' + topBarHeight + 'px';
          } else if (currentScroll > lastScroll) {
            navbar.style.top = '-' + navbarHeight + 'px'; // Some ao descer
            if (topBar) topBar.style.top = '-' + topBarHeight + 'px';
          }
          lastScroll = currentScroll;
        });
      });
    })();
  </script>


</body>

</html>