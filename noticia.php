<?php
// Conexão à base de dados
include 'admin/database.php';

// Verifica se a ligação foi bem-sucedida
if ($conn->connect_error) {
  die("Erro na ligação à base de dados: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  $sql = "SELECT idnoticia, img_fundo, data_publicacao, titulo_noticia, subtitulo, corponoticia, galeria_imgs, descricao_imgs, img_banner, sidebar_titulo 
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

$ficheiros  = array_filter(array_map('trim', explode(',', $noticia['galeria_imgs'])));
$descricoes = array_map('trim', explode('|', $noticia['descricao_imgs']));

$imagens = [];
foreach ($ficheiros as $i => $ficheiro) {
  $imagens[] = [
    'ficheiro'  => $ficheiro,
    'descricao' => $descricoes[$i] ?? ''
  ];
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


$sql_logo = "SELECT logo, titulo_junta FROM `admin` LIMIT 1";
$res1 = mysqli_query($conn, $sql_logo);

if ($res1 && mysqli_num_rows($res1) > 0) {
  $row = mysqli_fetch_assoc($res1);
  $logo = $row['logo'] ?? 'default-logo.png';
  $titulo_junta = $row['titulo_junta'] ?? 'Junta de Freguesia';
} else {
  $logo = 'default-logo.png';
  $titulo_junta = 'Junta de Freguesia Barreiro de Besteiros';
}

$sql_freguesia = "SELECT id, opcao, link, classe FROM submenu_freguesia ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql_freguesia);

$sql_contactos = "SELECT id, opcao_, url_, classe_1 FROM submenu_contactos ORDER BY id ASC";
$resultado1 = mysqli_query($conn, $sql_contactos);

$sql_autarquia = "SELECT id, `option`, `url`, classse, tipo, parent FROM submenu_autarquia ORDER BY id ASC";
$result2 = mysqli_query($conn, $sql_autarquia);

// Buscar título e imagem da notícia anterior e seguinte para preview nos botões
$prevData = null;
if (!empty($idAnterior)) {
  $resPrev = $conn->query("SELECT titulo_noticia, img_fundo FROM noticias WHERE idnoticia = $idAnterior LIMIT 1");
  if ($resPrev && $resPrev->num_rows > 0) {
    $prevData = $resPrev->fetch_assoc();
  }
}

$nextData = null;
if (!empty($idSeguinte)) {
  $resNext = $conn->query("SELECT titulo_noticia, img_fundo FROM noticias WHERE idnoticia = $idSeguinte LIMIT 1");
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
  <title><?= htmlspecialchars($noticia['titulo_noticia']) ?></title>
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

    .fade-wrapper.fade-out {
      opacity: 0;
      transition: opacity 1.7s ease;
    }

    .fade-wrapper.fade-in {
      opacity: 1;
      transition: opacity 1.52s ease;
    }

    .content {
      position: relative !important;
      height: 440px;
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
        <img src="IMAGENS/<?= htmlspecialchars($logo) ?>" alt="<?= ($titulo_junta) ?>">
      </a>
      <div class="titulo">
        <strong>
          <p><a href="index.php"><?= nl2br(htmlspecialchars($titulo_junta)) ?></a></p>
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
      <span class="jf-search-toggle">
        <i class="bi bi-search jf-search-icon"
          style="font-size:30px; margin-left:40px; cursor:pointer; position:absolute; bottom:-5px;"></i>
      </span>

      <div class="jf-search-bar">
        <form method="GET" action="">
          <input class="jf-search-input" type="search" name="q"
            placeholder="Pesquisar por termo..."
            value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
          <button type="submit" class="jf-search-close"
            style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:none; border:none; font-size:18px; cursor:pointer; color:#999; z-index:10;">
            &times;
          </button>
        </form>
      </div>

    </div>
  </div>

  <div class="fade-wrapper"> <!--FADE ANIMAÇAO--NOTICIA COMPLETA -->

    <main class="main-content">
      <div class="content">
        <div class="texto">
          <h2><?= htmlspecialchars($noticia['titulo_noticia']) ?></h2>
        </div>
      </div>
    </main>
    <!--------------------   BREADCRUMBS STYLE  ------------------>
    <style>
      .breadcrumbs a {
        color: #fff !important;
        text-decoration: none;
        transition: color 0.8s ease;
      }

      .breadcrumbs {
        position: relative;
        bottom: 280px;
        text-align: center;
        font-family: "Poppins", serif;
        font-size: 19px;
        z-index: 2;
        margin-top: 16px;
        color: #fff;
        text-decoration-color: #fff;
      }

      .breadcrumbs a:hover {
        color: #57c5b6ff;
        box-shadow: 0 4px 6px -2px rgba(0, 0, 0, 0.25);
      }

      .breadcrumbs i {
        font-size: 16px;
      }

      .breadcrumbs .fa-angle-right {
        color: #fff !important;
      }
    </style>

    <div class="fade-wrapper">
      <main class="sidebar-recentes">
        <h4 class="sidebar-titulo"><?= htmlspecialchars($noticia['sidebar_titulo']) ?></h4>
        <?php
        $idAtual = intval($_GET['id']);

        $sql = "SELECT idnoticia, img_fundo, titulo_noticia, data_publicacao
        FROM noticias
        WHERE idnoticia != $idAtual
        ORDER BY data_publicacao DESC
        LIMIT 5";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
        ?>
            <div class="sidebar-noticia" id="fade-wrapper">
              <a href="noticia.php?id=<?= $row['idnoticia'] ?>">
                <img src="IMAGENS/<?= htmlspecialchars($row['img_fundo']) ?>" class="sidebar-img" alt="<?= htmlspecialchars($row['titulo_noticia']) ?>">
                <div class="sidebar-titulo-noticia"><?= htmlspecialchars($row['titulo_noticia']) ?></div>
                <div class="sidebar-data"><?= formatarDataPt($row['data_publicacao']) ?></div>
              </a>
            </div>
          <?php endwhile;
        else: ?>
          <p>Nenhuma notícia encontrada.</p>
        <?php endif; ?>
      </main>
    </div>
    <!-------------------   SIDEBAR NOTICIAS  ------------------>
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
      <?= htmlspecialchars($noticia['titulo_noticia']) ?></a>
    </main>
    <style>
      .noticia-bloco {
        margin-top: -120px !important;
        position: relative;
        right: 160px;
        align-items: center;
      }
    </style>

    <div class="noticia-bloco">
      <img class="noticia-imagem"
        src="IMAGENS/<?= htmlspecialchars($noticia['img_fundo']) ?>"
        alt="<?= htmlspecialchars($noticia['titulo_noticia']) ?>">

      <div class="noticia-data"><?= formatarDataPt($noticia['data_publicacao']) ?></div>

      <i class="bi bi-calendar"
        style="width: 30px; height:30px; margin-left: 314px; position:relative; font-size:19px;"></i>

      <div class="noticia-titulo"
        style="position:relative; left: 110px;">
        <?= htmlspecialchars($noticia['titulo_noticia']) ?>
      </div>
    </div>

    <!-- TEXTO -->
    <div class="noticia-conteudo">
      <div class="noticia-lead">
        <?= nl2br($noticia['corponoticia']) ?>
      </div>
    </div>

    <!--SLIDER -->
    <?php
    $total    = count($imagens);
    $porVista = 3;
    $paginas  = (int) ceil($total / $porVista);
    ?>

    <?php if ($total > 0): ?>
      <div class="noticia-galeria">
        <button class="noticia-prev" type="button" aria-label="Anterior">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <polyline points="14 5 8 12 14 19"
              fill="none"
              stroke="#ffffff"
              stroke-width="2.5"
              stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
        </button>
        <div class="noticia-slider" id="slider-<?= (int)$noticia['idnoticia'] ?>">

          <div class="noticia-slides">
            <?php foreach ($imagens as $img): ?>
              <div class="noticia-slide">
                <img src="IMAGENS/<?= htmlspecialchars($img['ficheiro']) ?>"
                  class="noticia-img noticia-thumb thumbnail"
                  alt="<?= htmlspecialchars($img['descricao']) ?>">
              </div>
            <?php endforeach; ?>
          </div>

          <?php if ($paginas > 1): ?>
            <div class="noticia-indicadores"
              data-por-vista="<?= $porVista ?>"
              data-total="<?= $total ?>">
              <?php for ($i = 0; $i < $paginas; $i++): ?>
                <button type="button"
                  class="indicador <?= $i === 0 ? 'ativo' : '' ?>"
                  data-indice="<?= $i ?>"></button>
              <?php endfor; ?>
            </div>
          <?php endif; ?>
        </div>
        <button class="noticia-next" type="button" aria-label="Seguinte">
          <svg xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24">
            <polyline points="10 5 16 12 10 19"
              fill="none"
              stroke="#ffffff"
              stroke-width="2.5"
              stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>

        </button>
      </div>
    <?php endif; ?>

    <style>
      .noticia-prev,
      .noticia-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: rgba(176, 176, 173, 0.85);
        display: flex;
        /* centra conteúdo */
        outline: none;
        align-items: center;
        /* vertical */
        justify-content: center;
        transition: transform 0.3s ease, background 0.3s ease;
        /* horizontal */
        cursor: pointer;
        z-index: 10;
      }

      .noticia-prev img,
      .noticia-next img {
        width: 20px;
        /* um pouco menor que 40px */
        height: 20px;
        object-fit: contain;
        display: block;
        margin: 0;
      }

      .noticia-prev {
        left: -50px;
      }

      .noticia-next {
        right: -54px;
      }

      .noticia-prev:hover,
      .noticia-next:hover {
        background: #252a29ff;
      }

      .noticia-prev:focus,
      .noticia-prev:active,
      .noticia-prev:focus-visible,
      .noticia-next:focus,
      .noticia-next:active,
      .noticia-next:focus-visible {
        outline: none !important;
        box-shadow: none !important;
      }

      .noticia-galeria {
        position: relative;
        max-width: 1250px;
        margin: 70px auto 0 auto;
        width: 100%;
      }

      .noticia-slider {
        position: relative;
        width: 100%;
        height: 430px;
        margin: 40px auto 0 auto;
        z-index: 1;
        overflow: hidden;
        padding: 0 8px;
        box-sizing: border-box;
      }

      .noticia-slides {
        display: flex;
        flex-wrap: nowrap;
        align-items: stretch;
        height: 100%;
        transition: transform 0.9s ease-in-out;
        /* transição suave do slide */
        z-index: 1;
      }

      /* 3 imagens por “vista”, estilo gallery */
      .noticia-slide {
        flex: 0 0 calc(100% / 3);
        /* exatamente 1/3 da largura */
        padding: 0 10px;
        /* espaço entre as imagens */
        box-sizing: border-box;
        transition: filter 0.4s ease;
        cursor: pointer;
      }

      /* imagem ocupa o bloco todo, formato retângulo */
      .noticia-slider .noticia-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        border-radius: 10px;
      }

      .noticia-indicadores {
        position: relative;
        z-index: 2;
        display: none;
        justify-content: center;
        margin-top: 12px;
        gap: 4px;
      }

      .noticia-indicadores .indicador {
        width: 32px;
        height: 40px;
        border-radius: 50px;
        border: none;
        background-color: #cfcfcf;
        cursor: pointer;
      }

      .noticia-indicadores .indicador.ativo {
        background-color: #2f5494;
      }

      /* responsivo: em mobile podes mostrar 1 ou 2 por vista, aqui 2 */
      @media (max-width: 780px) {
        .noticia-slider {
          max-width: 100%;
          height: 210px;
        }

        .noticia-slide {
          flex: 0 0 50%;
          /* 2 imagens visíveis em mobile */
          margin: 0 6px;
        }
      }

      .noticia-titulo {
        font-size: 30px;
        font-weight: 700;
        margin-left: 194px;
        margin-bottom: 20px;
        margin-top: 20px;
        font-family: "Funnel Display", sans-serif;
        font-optical-sizing: auto;
        font-weight: 500;
        font-style: normal;
        color: #232a23;
        letter-spacing: 0.4px;
        max-width: 54vw;
      }

      .noticia-data {
        color: #4c4747ff;
        font-size: 16px;
        left: 350px;
        bottom: -27px;
        position: relative;
        font-weight: 600;
        letter-spacing: 0.3px;
      }

      .noticia-lead {
        max-width: 830px;
        width: 90vw;
        margin: 0 auto 34px auto;
        font-size: 15px;
        color: #232323;
        text-align: left;
        font-size: 18px;
        font-family: "Poppins", sans-serif;
        font-weight: 300;
        position: relative;
        list-style-position: inside;
        line-height: 1.5;
        letter-spacing: 0.4px;
        right: 27px;
      }

      .noticia-footer {
        width: 90vw;
        max-width: 900px;
        margin: 40px auto 30px auto;
        /* espaço normal abaixo da galeria */
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        position: relative;
        z-index: 1;
        /* opcional, só para garantir que fica acima do fundo */
      }

      .partilhar-label {
        color: #888;
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 5px;
        letter-spacing: 0.1em;
      }

      .partilhar-botoes {
        display: flex;
        gap: 18px;
        justify-content: center;
        margin-bottom: 10px;
      }

      .botao-partilhar {
        width: 38px;
        height: 38px;
        border: 1.3px solid #598149;
        background: none;
        color: #598149;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25em;
        text-decoration: none;
        transition: all 0.14s;
      }

      .botao-partilhar:hover {
        background: #598149;
        color: #fff;
      }


      @media (max-width: 780px) {

        .noticia-imagem,
        .noticia-lead,
        .noticia-footer,
        .noticia-footer hr {
          max-width: 99vw;
          width: 99vw;
          padding: 0;
        }

        .categoria,
        .noticia-titulo,
        .noticia-data {
          padding-left: 5vw;
          margin-left: 0;
          max-width: 99vw;
        }
      }
    </style>

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
                <img src="IMAGENS/<?= htmlspecialchars($prevData['img_fundo']) ?>" alt="<?= htmlspecialchars($prevData['titulo_noticia']) ?>">
                <div>
                  <div class="preview-title"><?= htmlspecialchars($prevData['titulo_noticia']) ?></div>
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
                <img src="IMAGENS/<?= htmlspecialchars($nextData['img_fundo']) ?>" alt="<?= htmlspecialchars($nextData['titulo_noticia']) ?>">
                <div>
                  <div class="preview-title"><?= htmlspecialchars($nextData['titulo_noticia']) ?></div>
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
        padding-bottom: 54px;
      }
    </style>

    <?php
    include_once 'admin/database.php';
    $sql = "SELECT r.titulo_junta AS titulo_junta, c.morada, c.email, c.telefone_fixo, c.telemovel, c.horario_funcionamento, r.logo 
        FROM rodape r 
        LEFT JOIN contactos c ON r.id_contacto = c.id 
        LIMIT 1";
    $result = $conn->query($sql);
    $rodape = ($result && $result->num_rows > 0) ? $result->fetch_assoc() : [];
    ?>

    <footer class="footer-meruge">
      <div class="footer-meruge-top">
        <div class="footer-meruge-brand">
          <?php if (!empty($rodape['logo'])): ?>
            <img src="IMAGENS/<?= htmlspecialchars($rodape['logo']) ?>" class="footer-meruge-logo" alt="Logo Junta" href="index.php">
          <?php endif; ?>
          <h1 class="footer-meruge-title"><?= nl2br(($rodape['titulo_junta'] ?? 'Junta de Freguesia de Barreiro <br> de Besteiros')) ?></h1>

          <div class="footer-meruge-info" style="margin-top:30px;">
            <?php
            $morada_parts = explode(',', $rodape['morada'] ?? 'Rua Nossa Senhora dos Aflitos, 121, 3465-012 Barreiro de Besteiros', 2);
            $parte1 = trim($morada_parts[0]);
            $parte2 = trim($morada_parts[1] ?? '');
            ?>

            <p>
              <span style="display:inline-block; min-width: 220px; font-size: 18px;">
                <span class="footer-meruge-icon" style="color:#ffa000;"><i class="bi bi-geo-alt-fill"></i></span>
                <?= htmlspecialchars($parte1) ?>
              </span>
              <br>
              <span style="margin-left: 26px; font-size: 18px;">
                <?= htmlspecialchars($parte2) ?>
              </span>
            </p>

            <!-- Email -->
            <?php if (!empty($rodape['email'])): ?>
              <p><span class="footer-meruge-icon">&#9993; <a href="mailto:<?= htmlspecialchars($rodape['email']) ?>"><?= htmlspecialchars($rodape['email']) ?></a></span></p>
            <?php endif; ?>

            <!-- Telefone fixo -->
            <?php if (!empty($rodape['telefone_fixo'])): ?>
              <p>
                <span class="footer-meruge-icon">&#9742;
                  <a href="tel:<?= preg_replace('/[^0-9]/', '', $rodape['telefone_fixo']) ?>">
                    <?= htmlspecialchars($rodape['telefone_fixo']) ?>
                  </a>
                  <small> *Rede fixa nacional</small>
                </span>
              </p>
            <?php endif; ?>

            <!-- Telemóvel -->
            <?php if (!empty($rodape['telemovel'])): ?>
              <p>
                <span class="footer-meruge-icon"><i class="bi bi-telephone"></i>
                  <a href="tel:<?= preg_replace('/[^0-9]/', '', $rodape['telemovel']) ?>">
                    <?= htmlspecialchars($rodape['telemovel']) ?>
                  </a>
                  <small>*Rede móvel nacional</small>
                </span>
              </p>
            <?php endif; ?>
            <?php
            $horario_parts = explode("\n", trim($rodape['horario_funcionamento']), 2);
            $horario_linha1 = trim($horario_parts[0] ?? 'Terças-feiras e Sextas-feiras:');
            $horario_linha2 = trim($horario_parts[1] ?? '19h00 - 20h30');
            ?>
            <p>
              <span style="display:inline-block; min-width: 220px; font-size: 18px;">
                <span class="footer-meruge-icon" style="color:#ffa000;"><i class="bi bi-calendar4"></i></span>
                <?= htmlspecialchars($horario_linha1) ?>
              </span>
              <br>
              <span style="margin-left: 26px; font-size: 18px;">
                <?= htmlspecialchars($horario_linha2) ?>
              </span>
            </p>

          </div>

        </div>

        <!-- Menus estáticos mantidos -->
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
            <li><a href="executivo.php">Executivo</a></li>
            <li><a href="assembleia.php">Assembleia</a></li>
            <li><a href="documentos.php">Pedido de Documentos</a></li>
          </ul>
        </div>

        <div class="footer-meruge-section">
          <h4>Executivo</h4>
          <ul>
            <li><a href="executivo.php">Membros do Executivo</a></li>
            <li><a href="informacoes.php">Informações</a></li>
            <li><a href="avisos.php">Avisos</a></li>
            <li><a href="editais.php">Editais</a></li>
            <li><a href="atas.php">Atas</a></li>
          </ul>
        </div>

        <div class="footer-meruge-section">
          <h4>Contactos</h4>
          <ul>
            <li><a href="contactos.php#contactos">Contactos da Freguesia</a></li>
            <li><a href="sobre.php">Contactos de Interesse Geral</a></li>
            <li><a href="locais.php">Localização</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-meruge-bottom">
        <p>
          @2026 <?= htmlspecialchars($rodape['titulo_junta'] ?? 'Junta de Freguesia de Barreiro de Besteiros') ?>.
          Todos os direitos reservados | Termos e Condições |
          *Chamada para a rede/móvel fixa nacional
        </p>
      </div>
    </footer>
  </div>

  <!-- MODAL IMAGENS NOTICIA COMPLETA -->
  <div id="modal-imagem-index" class="modal-imagem">
    <span class="fechar-modal" id="fechar-modal-index">&times;</span>
    <a id="btn-download-index" class="btn-download" style="height: 30px; width:30px;">
      <i class="bi bi-download"></i>
    </a>

    <a id="fullscreen-btn" style="color:#fff; cursor:pointer;">
      <i id="fullscreen-icon" class="bi bi-fullscreen"></i>
    </a>
    <img class="imagem-modal-conteudo imagem-modal-conteudo-index" id="imagem-modal-conteudo-index" />

    <div class="info-modal-index">
      <div id="contador-modal-index" class="contador-modal-index"></div>
      <div id="descricao-modal-index" class="descricao-modal-index"></div>
    </div>
    <!-- Setas de navegação -->
    <span class="seta seta-esquerda" id="seta-esquerda-index">
      <img src="IMAGENS/seta-esquerda.png" style="height: 30px; width:30px;">
    </span>
    <span class="seta seta-direita" id="seta-direita-index">
      <img src="IMAGENS/seta-direita (2).png" style="height: 30px; width:30px;">
    </span>
  </div>


  <!-- css modal-noticias -->
  <script src="JS/fullscreenindex.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const modal = document.getElementById("modal-imagem-index");
      if (!modal) {
        console.log("modal-index: não encontrado — script parado");
        return;
      }

      let imagens = Array.from(document.querySelectorAll(".imagens-atracoes img, .thumbnail"));
      if (imagens.length === 0) console.warn("modal-index: não encontrei miniaturas (.imagens-atracoes img, .thumbnail)");

      let imgGrande = document.getElementById("imagem-modal-conteudo-index");
      let modalTitulo = document.getElementById("descricao-modal-index");
      const fecharBtn = document.getElementById("fechar-modal-index");
      const anteriorBtn = document.getElementById("seta-esquerda-index");
      const seguinteBtn = document.getElementById("seta-direita-index");
      const contador = document.getElementById("contador-modal-index");

      // Se faltar o imgGrande/cria um fallback (mais tolerante)
      if (!imgGrande) {
        imgGrande = document.createElement("img");
        imgGrande.id = "imagem-modal-conteudo-index";
        imgGrande.className = "imagem-modal-conteudo";
        modal.appendChild(imgGrande);
        console.warn("modal-index: imagem principal não existe");
      }
      if (!modalTitulo) {
        modalTitulo = document.createElement("div");
        modalTitulo.id = "descricao-modal-index";
        modalTitulo.className = "descricao-modal";
        modal.appendChild(modalTitulo);
      }

      let indexAtual = 0;
      let animando = false;

      function abrirModal(index) {
        if (!imagens[index]) return;
        indexAtual = index;
        imgGrande.src = imagens[index].src;
        modalTitulo.textContent = imagens[index].alt || "";
        //modalTitulo.textContent = imagens[index].alt || "Imagem";
        modal.classList.add("aberto");
        document.body.style.overflow = "hidden";
        atualizarContador();

        const btnDownload = document.getElementById("btn-download-index");
        btnDownload.href = imagens[index].src;
        btnDownload.download = imagens[index].src.split('/').pop();

      }

      function fecharModal() {
        modal.classList.remove("aberto");
        document.body.style.overflow = "";

        if (document.fullscreenElement) {
          document.exitFullscreen();
        }

        const fullscreenIcon = document.getElementById("fullscreen-icon");
        if (fullscreenIcon) {
          fullscreenIcon.classList.remove("bi-fullscreen-exit");
          fullscreenIcon.classList.add("bi-fullscreen");
        }

      }

      function mostrarImagemAnterior() {
        if (!animando) slideImagem(-1);
      }

      function mostrarImagemSeguinte() {
        if (!animando) slideImagem(1);
      }

      function atualizarContador() {
        if (contador) {
          contador.textContent = `${indexAtual + 1} / ${imagens.length}`;
        }
      }


      function slideImagem(direcao) {
        if (imagens.length <= 1) return;
        animando = true;

        const novaIndex = (indexAtual + direcao + imagens.length) % imagens.length;
        const novaImagem = imagens[novaIndex];

        const sairClasse = direcao === 1 ? "slide-out-esq" : "slide-out-dir";
        const entrarClasse = direcao === 1 ? "slide-in-dir" : "slide-in-esq";

        // clone da imagem atual para animar saída
        const imagemSaida = imgGrande.cloneNode(true);
        imagemSaida.classList.add(sairClasse);
        imagemSaida.style.position = "absolute";
        imagemSaida.style.top = imgGrande.style.top || "50%";
        imagemSaida.style.left = imgGrande.style.left || "50%";
        imagemSaida.style.transform = imgGrande.style.transform || "translate(-50%, -50%)";

        // container para imagens animadas
        let container = modal.querySelector(".modal-conteudo");
        if (!container) {
          container = document.createElement("div");
          container.className = "modal-conteudo";
          modal.appendChild(container);
        }
        container.appendChild(imagemSaida);

        // força reflow para reiniciar animações se necessário
        imgGrande.classList.remove(entrarClasse);
        void imgGrande.offsetWidth;

        // troca e anima a entrada
        imgGrande.src = novaImagem.src;
        modalTitulo.textContent = novaImagem.alt || "";
        //modalTitulo.textContent = novaImagem.alt || "Imagem";
        imgGrande.classList.add(entrarClasse);

        const btnDownload = document.getElementById("btn-download-index");
        btnDownload.href = novaImagem.src;
        btnDownload.download = novaImagem.src.split('/').pop();


        // cleanup: espera animationend ou timeout fallback
        let finished = false;
        const onEnd = () => {
          if (finished) return;
          finished = true;
          imgGrande.classList.remove(entrarClasse);
          if (imagemSaida && imagemSaida.parentNode) imagemSaida.remove();
          indexAtual = novaIndex;
          animando = false;
          clearTimeout(timeoutFallback);
        };

        imgGrande.addEventListener("animationend", () => {
          imgGrande.classList.remove(entrarClasse);
          if (imagemSaida && imagemSaida.parentNode) imagemSaida.remove();
          indexAtual = novaIndex;
          animando = false;
          atualizarContador(); // <--- contador atualizado
        }, {
          once: true
        });


        imgGrande.addEventListener("animationend", onEnd, {
          once: true
        });

        const timeoutFallback = setTimeout(() => {
          if (!finished) onEnd();
        }, 600);
      }

      imagens.forEach((img, i) => {
        img.addEventListener("click", () => abrirModal(i));
      });

      if (fecharBtn) fecharBtn.addEventListener("click", fecharModal);
      if (anteriorBtn) anteriorBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        mostrarImagemAnterior();
      });
      if (seguinteBtn) seguinteBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        mostrarImagemSeguinte();
      });

      modal.addEventListener("click", (e) => {
        if (e.target === modal) fecharModal();
      });

      // teclado: usa window para garantir captura
      window.addEventListener("keydown", (e) => {
        if (!modal.classList.contains("aberto")) return;
        if (e.key === "Escape") fecharModal();
        if (e.key === "ArrowLeft") mostrarImagemAnterior();
        if (e.key === "ArrowRight") mostrarImagemSeguinte();
      });
    });
  </script>
  <!-- FADE IN E OUT ANIMAÇAO NOTICIA COMPLETA -->
  <script>
    const wrapper = document.querySelector(".fade-wrapper");

    // Inicializa invisível
    wrapper.classList.remove("fade-in");
    wrapper.classList.add("fade-out");

    // Fade-in quando página carrega
    window.addEventListener("pageshow", (event) => {
      if (event.persisted || document.visibilityState === "visible") {
        wrapper.classList.remove("fade-out");
        wrapper.classList.add("fade-in");
      }
    });

    // Aplica fade-out só aos links para noticia.php
    document.querySelectorAll('a[href^="noticia.php"]').forEach(link => {
      link.addEventListener("click", function(event) {
        event.preventDefault();
        wrapper.classList.remove("fade-in");
        wrapper.classList.add("fade-out");
        setTimeout(() => {
          window.location = this.href;
        }, 300);
      });
    });
  </script>

  <!-- ZOOM IMAGEM MODAL NOTICIA COMPLETA -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const img = document.getElementById('imagem-modal-conteudo-index');
      if (!img) return;

      let zoomed = false;
      const scale = 2;
      let naturalWidth = 0;
      let naturalHeight = 0;

      function guardarDimensoes() {
        naturalWidth = img.naturalWidth;
        naturalHeight = img.naturalHeight;
      }

      // quando a imagem carrega (ou muda src)
      img.addEventListener('load', () => {
        guardarDimensoes();
        resetZoom();
      });

      function clamp(v, min, max) {
        return Math.min(Math.max(v, min), max);
      }

      function aplicarZoomNaPosicao(e) {
        if (!zoomed) return;

        const modalRect = img.parentElement.getBoundingClientRect();
        const displayWidth = img.clientWidth;
        const displayHeight = img.clientHeight;

        const x = e.clientX - modalRect.left;
        const y = e.clientY - modalRect.top;

        const zoomedWidth = displayWidth * scale;
        const zoomedHeight = displayHeight * scale;

        const extraX = zoomedWidth - displayWidth;
        const extraY = zoomedHeight - displayHeight;

        let offsetX = -(x * (scale - 1));
        let offsetY = -(y * (scale - 1));

        const minX = -extraX;
        const maxX = 0;
        const minY = -extraY;
        const maxY = 0;

        offsetX = clamp(offsetX, minX, maxX);
        offsetY = clamp(offsetY, minY, maxY);

        img.style.transform = `translate(${offsetX}px, ${offsetY}px) scale(${scale})`;
      }

      img.addEventListener('click', (e) => {
        zoomed = !zoomed;

        if (!zoomed) {
          resetZoom();
          return;
        }

        img.style.cursor = 'zoom-out';
        aplicarZoomNaPosicao(e);
      });

      img.addEventListener('mousemove', aplicarZoomNaPosicao);

      function resetZoom() {
        zoomed = false;
        img.style.transform = 'scale(1)';
        img.style.cursor = 'zoom-in';
      }

      const setaEsq = document.getElementById('seta-esquerda-index');
      const setaDir = document.getElementById('seta-direita-index');
      if (setaEsq) setaEsq.addEventListener('click', resetZoom);
      if (setaDir) setaDir.addEventListener('click', resetZoom);

      // se já abres o modal com uma imagem carregada
      if (img.complete) {
        guardarDimensoes();
      }
    });
  </script>




  <!-- SLIDER SETAS -->
  <script>
    document.querySelectorAll('.noticia-galeria').forEach(wrapper => {
      const slider = wrapper.querySelector('.noticia-slider');
      const faixa = slider.querySelector('.noticia-slides');
      const slides = Array.from(slider.querySelectorAll('.noticia-slide'));
      const prev = wrapper.querySelector('.noticia-prev'); // fora do slider
      const next = wrapper.querySelector('.noticia-next'); // fora do slider

      const porVista = 3;
      const total = slides.length;
      let indiceBase = 0;

      if (total <= porVista) {
        if (prev) prev.style.display = 'none';
        if (next) next.style.display = 'none';
        return;
      }

      function larguraSlideReal() {
        if (!slides[0]) return 0;
        return slides[0].offsetWidth;
      }

      function atualizar() {
        const largura = larguraSlideReal();
        const maxIndice = Math.max(0, total - porVista);
        if (indiceBase < 0) indiceBase = 0;
        if (indiceBase > maxIndice) indiceBase = maxIndice;
        faixa.style.transform = `translateX(-${indiceBase * largura}px)`;
      }

      if (next) {
        next.addEventListener('click', () => {
          const maxIndice = Math.max(0, total - porVista);
          if (indiceBase < maxIndice) {
            indiceBase += 1;
            atualizar();
          }
          next.blur(); // tira o foco/active do botão
        });
      }

      if (prev) {
        prev.addEventListener('click', () => {
          if (indiceBase > 0) {
            indiceBase -= 1;
            atualizar();
          }
          prev.blur(); // tira o foco/active do botão
        });
      }


      atualizar();
      window.addEventListener('resize', atualizar);
    });
  </script>
  <script src="JS/navbar.js"></script>
  <script src="JS/barra_pesquisa.js"></script>
</body>

</html>