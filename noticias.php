<?php
include 'admin/database.php';

if (!$conn) {
  die("Erro de conexão: " . mysqli_connect_error());
}

$where = [];
if (!empty($_GET['q'])) {
  $q = $conn->real_escape_string($_GET['q']);
  $where[] = "(titulo_noticia LIKE '%$q%' OR subtitulo LIKE '%$q%' OR descricao2 LIKE '%$q%')";
}
if (isset($_GET['ano']) && $_GET['ano'] !== '' && $_GET['ano'] !== 'all') {
  $ano = (int)$_GET['ano'];
  $where[] = "YEAR(data) = $ano";
}
if (isset($_GET['mes']) && $_GET['mes'] !== '' && $_GET['mes'] !== 'all') {
  $mes = (int)$_GET['mes'];
  $where[] = "MONTH(data) = $mes";
}

$whereSQL = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$noticiasPorPagina = 9;
$paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;

$totalNoticias = $conn->query("SELECT COUNT(*) as total FROM noticias $whereSQL")->fetch_assoc()['total'];
$totalPaginas = ceil($totalNoticias / $noticiasPorPagina);

if ($paginaAtual > $totalPaginas) {
  $paginaAtual = $totalPaginas > 0 ? $totalPaginas : 1;
}

$offset = ($paginaAtual - 1) * $noticiasPorPagina;

$sqlNoticias = "SELECT idnoticia, img_fundo, data_publicacao, titulo_noticia, subtitulo 
                FROM noticias $whereSQL 
                ORDER BY data_publicacao DESC 
                LIMIT $noticiasPorPagina OFFSET $offset";
$resultNoticias = $conn->query($sqlNoticias);


// Responder requisições AJAX retornando apenas o bloco de resultados + paginação
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
  ob_start();
?>
  <div id="ajax-results">
    <?php if ($resultNoticias && $resultNoticias->num_rows > 0): ?>
      <?php while ($noticia = $resultNoticias->fetch_assoc()): ?>
        <article class="cartao-noticias">
          <img src="IMAGENS/<?= htmlspecialchars($noticia['img_fundo']) ?>" alt="<?= htmlspecialchars($noticia['titulo_noticia']) ?>">
          <i class="bi bi-calendar-event" style="color: #000;"></i>
          <p class="date"><?= formatarDataPt1($noticia['data_publicacao']) ?></p>
          <a href="noticia.php?id=<?= urlencode($noticia['idnoticia']) ?>">
            <h3><?= htmlspecialchars($noticia['titulo_noticia']) ?></h3>
          </a>
          <p><?= htmlspecialchars(mb_strimwidth($noticia['subtitulo'], 0, 240, '...')) ?></p>
          <a href="noticia.php?id=<?= urlencode($noticia['idnoticia']) ?>" class="cssbuttons-io-button">Ler Mais
            <div class="icon">
              <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0h24v24H0z" fill="none"></path>
                <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z" fill="currentColor"></path>
              </svg>
            </div>
          </a>
        </article>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="semnada1">
        <img src="IMAGENS/notfound.png" alt="Sem notícias" class="notfound_img">
        <div class="title">Sem notícias disponíveis nesta data!</div>
        <div class="corpo">Por favor selecione outras datas!</div>
      </div>
    <?php endif; ?>
  </div>

  <div id="ajax-pagination">
    <div class="paginacao" style="padding-bottom: 20px; ">
      <ul>
        <?php
        $queryString = $_GET;
        // Botão anterior
        if ($paginaAtual > 1) {
          $queryString['pagina'] = $paginaAtual - 1;
          echo '<li><a href="?' . http_build_query($queryString) . '"><</a></li>';
        } else {
          echo '<li><span class="disabled"><</span></li>';
        }

        $maxPaginasVisiveis = 7;
        $meio = floor($maxPaginasVisiveis / 2);
        $inicio = max(1, $paginaAtual - $meio);
        $fim = min($totalPaginas, $inicio + $maxPaginasVisiveis - 1);
        if ($fim - $inicio + 1 < $maxPaginasVisiveis) {
          $inicio = max(1, $fim - $maxPaginasVisiveis + 1);
        }

        for ($i = $inicio; $i <= $fim; $i++) {
          $queryString['pagina'] = $i;
          if ($i == $paginaAtual) {
            echo '<li><span class="active">' . $i . '</span></li>';
          } else {
            echo '<li><a href="?' . http_build_query($queryString) . '">' . $i . '</a></li>';
          }
        }

        // Botão seguinte
        if ($paginaAtual < $totalPaginas) {
          $queryString['pagina'] = $paginaAtual + 1;
          echo '<li><a href="?' . http_build_query($queryString) . '">></a></li>';
        } else {
          echo '<li><span class="disabled">></span></li>';
        }
        ?>
      </ul>
    </div>
  </div>

<?php
  $out = ob_get_clean();
  echo $out;
  exit;
}

// Query para header (título/descrição inicial)
$sqlHeader = "SELECT tituloprincipal, descricao2 FROM noticias LIMIT 1";
$resultHeader = $conn->query($sqlHeader);

// Função para formatar a data
function formatarDataPt1($data_iso)
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

$sql = "SELECT id, opcao, link, classe FROM submenu_freguesia ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql);

$sql = "SELECT id, `option`, `url`, classse, tipo, parent FROM submenu_autarquia ORDER BY id ASC";
$result2 = mysqli_query($conn, $sql);

$sql = "SELECT id, opcao_, url_, `classe_1` FROM submenu_contactos ORDER BY id ASC";
$resultado1 = mysqli_query($conn, $sql);

if (!$result2) {
  die("Erro na consulta submenu_autarquia: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notícias - Junta de Freguesia de Barreiro de Besteiros</title>
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
  <link rel="stylesheet" href="CSS/rodape.css">
  <link rel="stylesheet" href="CSS/noticia.css">

  <style>
    body {
      background-color: #F7F7F7 !important;
    }

    .page-wrapper {
      opacity: 1;
      transform: scale(1);
      transition: opacity 0.75s ease, transform 1.25s ease;
    }

    .page-wrapper.fade-out {
      opacity: 0;
      transform: scale(0.998);
    }

    .paginacao ul {
      font-family: "Poppins", serif;
      background: #F7F7F7;
      display: flex;
      justify-content: center;
      align-items: center;
      list-style: none;
      padding: 10px 0;
      margin: 10px 10px 10px 10px;
      gap: 3px;
    }

    .paginacao li {
      margin: 0 2px;
    }

    .paginacao a,
    .paginacao span {
      display: block;
      padding: 19px 20px;
      border-radius: 8px;
      color: #444;
      background-color: #F7FCFE;
      text-decoration: none;
      font-weight: 400;
      font-size: 16px;
      transition: background .2s, color .2s;
      cursor: pointer;
      box-shadow: 0 2px 5px rgba(0.13, 0, 0, 0.15);
    }

    .paginacao a.active,
    .paginacao span.active {
      background: #1e82cfff;
      color: #fff;
      font-weight: 700;
      pointer-events: none;
    }

    .paginacao a:hover:not(.active):not(.disabled) {
      background: #9ab9e1ff;
      color: #000;
    }

    .paginacao .disabled,
    .paginacao a.disabled {
      color: #b7bcc3;
      background: #e3e5e9;
      pointer-events: auto;
      cursor: not-allowed !important;
      /* mostra cursor bloqueado */
    }

    .img_inicial {
      position: relative !important;
      background: url('IMAGENS/fundo.jpg') center center/cover no-repeat;
      height: 430px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding-left: 0;
      z-index: 0;
    }

    .img_inicial::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(12, 23, 39, 0.68);
      /* filtro azul escuro */
      z-index: 1;
    }

    .texto {
      text-align: center !important;
      position: relative;
      z-index: 2;
      color: #eae1e1ff;
      margin-bottom: 48px;
      font-family: "Cabin", sans-serif;
      font-optical-sizing: auto;
      font-weight: 500;
    }

    .texto .tituloprincipal {
      text-align: center;
      font-size: 39px;
      font-weight: bold;
      margin-top: 180px;
    }

    .texto .descricao2 {
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      margin-top: 0;
    }

    .submenu-autarquia {
      margin-left: 15px !important;
    }

    .submenu-freguesia {
      margin-left: 15px !important;
    }

    @media (max-width: 700px) {
      .img_inicial {
        height: 120px;
      }

      .texto {
        margin-left: 15px;
      }

      .img_inicial h1 {
        font-size: 1.5em;
      }
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

  <!-- Navbar -->
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
    </div> <!-- fecha menu -->

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

  <div class="page-wrapper fade-out">

    <section class="img_inicial">
      <div class="texto">
        <?php if ($resultHeader && $resultHeader->num_rows > 0): ?>
          <?php while ($row = $resultHeader->fetch_assoc()): ?>
            <h1 class="tituloprincipal"><?= htmlspecialchars($row['tituloprincipal']) ?></h1>
            <h2 class="descricao2"><?= nl2br(htmlspecialchars($row['descricao2'])) ?></h2>
          <?php endwhile; ?>
        <?php else: ?>
          <p>Não existem imagens registadas.</p>
        <?php endif; ?>
      </div>
    </section>

    <main class="breadcrumbs">
      <a href="index.php">Ínicio</a> <i class="fa-solid fa-angle-right"></i> <a href="noticias.php"> Notícias</a></a>
    </main>

    <!--BREADCRUMBS-->
    <style>
      .breadcrumbs {
        position: relative;
        bottom: 70px;
        text-align: center;
        font-family: "Poppins", serif;
        font-size: 19px;
        color: #fff;
      }

      .breadcrumbs a {
        color: #fff;
        text-decoration: none;
        transition: color 0.8s ease;
      }

      .breadcrumbs a:hover {
        color: #cedcdbff;
        box-shadow: 0 4px 6px -2px rgba(223, 219, 219, 0.25);
      }

      .breadcrumbs i {
        font-size: 16px;
      }
    </style>

    <!---------------------------------------------    filtros  tem q ter o materialize senao nao aparecem   ------------------------------------------>

    <main style="display: flex; gap: 18px; align-items: center; position: absolute; margin-left: 180px; padding-top: 60px;" id="inicio-noticias">
      <form id="filtrosForm" method="get" style="display: flex; gap: 18px; align-items: center;">

        <div class="form2">
          <input
            class="input"
            id="filtroTxt"
            placeholder="Pesquisar..."
            type="text"
            name="q"
            value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
          <span class="input-border"></span>
        </div>

        <!-- Select Ano -->
        <div class="custom-select" id="customSelectAno">
          <div class="selected">Anos</div>
          <div class="arrow"></div>
          <ul class="options">
            <li data-value="all">Todos</li>
            <?php
            $anos = $conn->query("SELECT DISTINCT YEAR(data_publicacao) as ano FROM noticias WHERE data_publicacao IS NOT NULL ORDER BY ano DESC");
            if ($anos && $anos->num_rows > 0) {
              while ($row = $anos->fetch_assoc()) {
                echo "<li data-value='{$row['ano']}'>{$row['ano']}</li>";
              }
            }
            ?>

          </ul>
          <input type="hidden" name="ano" value="<?= (isset($_GET['ano']) && $_GET['ano'] !== 'all') ? htmlspecialchars($_GET['ano']) : '' ?>">
        </div>

        <!-- Select Mês -->
        <div class="custom-select" id="customSelectMes">
          <div class="selected">Mês</div>
          <div class="arrow"></div>
          <ul class="options">
            <li data-value="all">Todos</li>
            <?php
            $meses = [
              1 => 'Janeiro',
              2 => 'Fevereiro',
              3 => 'Março',
              4 => 'Abril',
              5 => 'Maio',
              6 => 'Junho',
              7 => 'Julho',
              8 => 'Agosto',
              9 => 'Setembro',
              10 => 'Outubro',
              11 => 'Novembro',
              12 => 'Dezembro'
            ];
            foreach ($meses as $num => $nome) {
              echo "<li data-value='$num'>$nome</li>";
            }
            ?>
          </ul>
          <input type="hidden" name="mes" value="<?= (isset($_GET['mes']) && $_GET['mes'] !== 'all') ? htmlspecialchars($_GET['mes']) : '' ?>">
        </div>

        <div id="filtrosSelecionados" style="display:flex; gap:5px; flex-wrap:wrap; align-items:center;"></div>
      </form>
    </main>

    <div class="grade-noticias-noticias">
      <?php if ($resultNoticias && $resultNoticias->num_rows > 0): ?>
        <?php while ($noticia = $resultNoticias->fetch_assoc()): ?>
          <article class="cartao-noticias">
            <img src="IMAGENS/<?= htmlspecialchars($noticia['img_fundo']) ?>" alt="<?= htmlspecialchars($noticia['titulo_noticia']) ?>">
            <i class="bi bi-calendar-event" style="color: #000;"></i>
            <p class="date"><?= formatarDataPt1($noticia['data_publicacao']) ?></p>
            <a href="noticia.php?id=<?= urlencode($noticia['idnoticia']) ?>">
              <h3><?= htmlspecialchars($noticia['titulo_noticia']) ?></h3>
            </a>
            <p><?= htmlspecialchars(mb_strimwidth($noticia['subtitulo'], 0, 240, '...')) ?></p>
            <a href="noticia.php?id=<?= urlencode($noticia['idnoticia']) ?>" class="cssbuttons-io-button">Ler Mais
              <div class="icon">
                <svg
                  height="24"
                  width="24"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg">
                  <path d="M0 0h24v24H0z" fill="none"></path>
                  <path
                    d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"
                    fill="currentColor"></path>
                </svg>
              </div>
            </a>
          </article>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="semnada1">
          <img src="IMAGENS/notfound.png" alt="Sem notícias" class="notfound_img">
          <div class="title">Sem notícias disponíveis nesta data!</div>
          <div class="corpo">Por favor selecione outras datas!</div>
        </div>
      <?php endif; ?>
    </div>

    <style>
      .grade-noticias-noticias .bi-calendar-event {
        font-size: 18px;
        margin-top: 252px;
        color: #444444 !important;
        margin-left: -338px;
        position: absolute;
      }

      .semnada1 {
        font-family: "Poppins", serif;
        grid-column: 1 / -1;
        justify-self: center;
        align-self: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 350px;
        margin-top: 50px;
      }

      .semnada1 .notfound_img {
        width: 200px;
        height: auto;
        margin-bottom: 20px;
      }

      .semnada1 .title {
        font-size: 19px;
        font-weight: normal;
        color: #444444;
        margin-bottom: 10px;
        text-align: center;
      }

      .semnada1 .corpo {
        font-size: 16px;
        font-weight: normal;
        color: #444444;
        margin-bottom: -10px;
      }

      .cartao-noticias h3 {
        font-size: 21px;
        letter-spacing: 0.2px;
        bottom: 10px;
        position: relative;
        color: #466787;
        font-family: "TikTok Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 500;
        letter-spacing: 0.4px;
        line-height: 1.1;
        text-align: center;
        width: 360px;
        margin-left: -10px;
        cursor: pointer;
        text-decoration: none;
      }
    </style>

    <!-- Paginação -->
    <div class="paginacao" style="padding-bottom: 20px; ">
      <ul>
        <?php
        $queryString = $_GET;

        // Botão anterior
        if ($paginaAtual > 1) {
          $queryString['pagina'] = $paginaAtual - 1;
          echo '<li><a href="?' . http_build_query($queryString) . '"><</a></li>';
        } else {
          echo '<li><span class="disabled"><</span></li>';
        }

        // Intervalo de páginas
        $maxPaginasVisiveis = 7;
        $meio = floor($maxPaginasVisiveis / 2);
        $inicio = max(1, $paginaAtual - $meio);
        $fim = min($totalPaginas, $inicio + $maxPaginasVisiveis - 1);

        if ($fim - $inicio + 1 < $maxPaginasVisiveis) {
          $inicio = max(1, $fim - $maxPaginasVisiveis + 1);
        }

        for ($i = $inicio; $i <= $fim; $i++) {
          $queryString['pagina'] = $i;
          if ($i == $paginaAtual) {
            echo '<li><span class="active">' . $i . '</span></li>';
          } else {
            echo '<li><a href="?' . http_build_query($queryString) . '">' . $i . '</a></li>';
          }
        }

        // Botão seguinte
        if ($paginaAtual < $totalPaginas) {
          $queryString['pagina'] = $paginaAtual + 1;
          echo '<li><a href="?' . http_build_query($queryString) . '">></a></li>';
        } else {
          echo '<li><span class="disabled">></span></li>';
        }
        ?>
      </ul>
    </div>

    <div class="espacamento" style="padding-top: 40px;">
    </div>

  </div>

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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script src="JS/btntopo.js"></script>
  <script src="JS/navbar.js"></script>
  <script src="JS/select_ano.js"></script>
  <script src="JS/select_mes.js"></script>
  <script src="JS/atualizarform.js"></script>

  <!-- animação de fade-in da página -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const wrapper = document.querySelector(".page-wrapper");
      if (!wrapper) return;

      // Usa um frame para aplicar o fade depois do layout estabilizar
      requestAnimationFrame(() => {
        wrapper.classList.remove("fade-out");
      });
    });
  </script>

  <script>
    // Criar chip de filtro ativo
    function criarChip(texto, onRemove) {
      const div = document.createElement("div");
      div.className = "filtro-ativo";
      div.innerHTML = `<span>${texto}</span>`;
      const btn = document.createElement("button");
      btn.type = "button";
      btn.innerHTML = "×";
      btn.onclick = onRemove;
      div.appendChild(btn);
      return div;
    }

    // Atualiza os chips visuais
    function atualizarFiltros() {
      const container = document.getElementById("filtrosSelecionados");
      container.innerHTML = "";

      const anoInput = document.querySelector("input[name='ano']");
      const mesInput = document.querySelector("input[name='mes']");

      const ano = anoInput.value;
      const mes = mesInput.value;
      const nomesMeses = {
        1: "Janeiro",
        2: "Fevereiro",
        3: "Março",
        4: "Abril",
        5: "Maio",
        6: "Junho",
        7: "Julho",
        8: "Agosto",
        9: "Setembro",
        10: "Outubro",
        11: "Novembro",
        12: "Dezembro"
      };

      // normalizar valores ativos (considere '' e 'all' como sem seleção)
      const anoAtivo = ano && ano !== '' && ano !== 'all';
      const mesAtivo = mes && mes !== '' && mes !== 'all';

      if (anoAtivo) {
        const chip = criarChip("Ano: " + ano, () => {
          anoInput.value = ""; // desmarcar
          document.querySelector("#customSelectAno .selected").textContent = "Anos";
          atualizarFiltros();
          anoInput.dispatchEvent(new Event('change', {
            bubbles: true
          }));
          if (typeof doFetch === "function") doFetch();
        });
        container.appendChild(chip);
      }

      if (mesAtivo) {
        const chip = criarChip("Mês: " + (nomesMeses[mes] || mes), () => {
          mesInput.value = ""; // desmarcar
          document.querySelector("#customSelectMes .selected").textContent = "Mês";
          atualizarFiltros();
          mesInput.dispatchEvent(new Event('change', {
            bubbles: true
          }));
          if (typeof doFetch === "function") doFetch();
        });
        container.appendChild(chip);
      }

      // Não alterar margem do form (chips posicionados por CSS)
      // Mantemos o form fixo; os chips serão posicionados com CSS para não empurrar o layout.
    }

    // Atualiza chips quando select muda
    document.querySelectorAll(".custom-select .options li").forEach(li => {
      li.addEventListener("click", () => {
        const parent = li.closest(".custom-select");
        const inputHidden = parent.querySelector("input[type='hidden']");
        inputHidden.value = li.dataset.value === "all" ? "" : li.dataset.value;
        // atualizar UI e disparar change para fetch
        atualizarFiltros();
        inputHidden.dispatchEvent(new Event('change', {
          bubbles: true
        }));
      });
    });

    // Atualiza chips no carregamento da página
    window.addEventListener("DOMContentLoaded", atualizarFiltros);
  </script>

  <script>
    (function() {
      const form = document.getElementById('filtrosForm');
      const resultsContainer = document.querySelector('.grade-noticias-noticias');
      const paginationContainer = document.querySelector('.paginacao');

      if (!form || !resultsContainer) return;

      let debounceTimer = null;

      window.doFetch = function(e, forcePage) {
        if (e && e.preventDefault) e.preventDefault();
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
          const params = new URLSearchParams(new FormData(form));
          params.set('ajax', '1');
          if (typeof forcePage === 'number') {
            params.set('pagina', String(forcePage));
          } else {
            params.set('pagina', '1');
          }

          // animação: fade out dos resultados e paginação
          const fadeDuration = 320; // deve coincidir com CSS transition
          if (resultsContainer) {
            resultsContainer.classList.remove('fade-in');
            resultsContainer.classList.add('fade-out');
          }
          if (paginationContainer) {
            paginationContainer.classList.remove('fade-in');
            paginationContainer.classList.add('fade-out');
          }

          // aguardar a animação de saída antes de fazer o fetch (cria sensação mais suave)
          setTimeout(() => {
            fetch(window.location.pathname + '?' + params.toString(), {
                method: 'GET'
              })
              .then(r => r.text())
              .then(html => {
                const tmp = document.createElement('div');
                tmp.innerHTML = html;
                const newResults = tmp.querySelector('#ajax-results');
                const newPagination = tmp.querySelector('#ajax-pagination');
                if (newResults && resultsContainer) resultsContainer.innerHTML = newResults.innerHTML;
                if (newPagination && paginationContainer) paginationContainer.innerHTML = newPagination.innerHTML;


                // forçar reflow e animar entrada
                if (resultsContainer) {
                  // remover classe fade-out e adicionar fade-in
                  resultsContainer.classList.remove('fade-out');
                  // trigger reflow
                  void resultsContainer.offsetWidth;
                  resultsContainer.classList.add('fade-in');
                }
                if (paginationContainer) {
                  paginationContainer.classList.remove('fade-out');
                  void paginationContainer.offsetWidth;
                  paginationContainer.classList.add('fade-in');
                }

                bindPaginationLinks();
              })
              .catch(err => console.error('Erro ao buscar notícias:', err));
          }, fadeDuration);
        }, 220);
      };

      // Intercepta envio do formulário para usar AJAX
      form.addEventListener('submit', doFetch);

      // Atualiza quando digita na pesquisa
      const input = form.querySelector('input[name="q"]');
      if (input) {
        input.addEventListener('input', doFetch);
        input.addEventListener('change', doFetch);
      }

      // Atualiza quando hidden inputs mudam
      const anoHidden = form.querySelector('input[name="ano"]');
      const mesHidden = form.querySelector('input[name="mes"]');
      if (anoHidden) anoHidden.addEventListener('change', doFetch);
      if (mesHidden) mesHidden.addEventListener('change', doFetch);

      // Atualiza ao clicar nas opções custom dos selects
      document.querySelectorAll('.custom-select .options li').forEach(li => {
        li.addEventListener('click', () => {
          setTimeout(doFetch, 80);
        });
      });

      // Paginação ajax
      function bindPaginationLinks() {
        const pagLinks = document.querySelectorAll('.paginacao a');
        pagLinks.forEach(link => {
          link.addEventListener('click', function(ev) {
            ev.preventDefault();
            const url = new URL(link.href);
            const page = url.searchParams.get('pagina');
            doFetch(null, Number(page));
            scrollToTopEase();
          });
        });
      }
      bindPaginationLinks();

      // Atualiza resultados ao remover chips
      const filtrosSelecionados = document.getElementById('filtrosSelecionados');
      if (filtrosSelecionados) {
        filtrosSelecionados.addEventListener('click', function(e) {
          if (e.target.tagName === 'BUTTON') {
            setTimeout(doFetch, 80);
          }
        });
      }
    })();
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var elems = document.querySelectorAll('custom-select');
      M.FormSelect.init(elems);
    });
  </script>

  <script>
    function scrollToTopEase() {
      const target = document.getElementById('inicio-noticias');
      if (!target) return;

      const headerOffset = 110;
      const targetPos = target.getBoundingClientRect().top + window.pageYOffset - headerOffset;

      const start = window.scrollY || window.pageYOffset;
      const distance = targetPos - start;
      const duration = 1200;
      const startTime = performance.now();

      function easeOutCubic(t) {
        return 1 - Math.pow(1 - t, 3);
      }

      function animate(now) {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const eased = easeOutCubic(progress);
        const current = start + distance * eased;
        window.scrollTo(0, current);
        if (progress < 1) requestAnimationFrame(animate);
      }

      requestAnimationFrame(animate);
    }
  </script>
  <script src="JS/barra_pesquisa.js"></script>
</body>

</html>