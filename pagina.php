<?php
include 'admin/database.php'; // Caminho para o teu ficheiro de ligação

if (!$conn) {
  die("Erro de conexão: " . mysqli_connect_error());
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

$isAjax = isset($_GET['ajax']) && $_GET['ajax'] == 1;

if ($isAjax) {
  $noticiasPorPagina = 9;
  $paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
  $totalNoticias = $conn->query("SELECT COUNT(*) as total FROM noticias")->fetch_assoc()['total'];
  $totalPaginas = ceil($totalNoticias / $noticiasPorPagina);
  if ($paginaAtual > $totalPaginas) $paginaAtual = $totalPaginas;
  $offset = ($paginaAtual - 1) * $noticiasPorPagina;

  $sql = "SELECT idnoticia, img, data, titulo, subtitulo FROM noticias ORDER BY data DESC LIMIT $noticiasPorPagina OFFSET $offset";
  $result = $conn->query($sql);
?>
  <div class="grade-noticias" id="noticias">
    <?php
    if ($result && $result->num_rows > 0):
      while ($noticia = $result->fetch_assoc()):
    ?>
        <article class="cartao-noticia">
          <img src="IMAGENS/<?= htmlspecialchars($noticia['img']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>">
          <p><?= formatarDataPt1($noticia['data']) ?></p>
          <h3><?= htmlspecialchars($noticia['titulo']) ?></h3>
          <p><?= htmlspecialchars($noticia['subtitulo']) ?></p>
          <a href="noticia.php?id=<?= urlencode($noticia['idnoticia']) ?>" class="btn-noticia">Ler Mais</a>
        </article>
    <?php
      endwhile;
    else:
      echo "<p>Não há notícias a mostrar.</p>";
    endif;
    ?>
  </div>

  <nav class="paginacao">
    <ul>
      <?php
      // código da paginação aqui (igual ao teu)
      ?>
    </ul>
  </nav>
<?php
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ínicio - Junta de Freguesia de Barreiro de Besteiros</title>

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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Joan&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Libertinus+Sans:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="CSS/estilo1.css">
  <link rel="stylesheet" href="CSS/locais.css">
  <link rel="stylesheet" href="CSS/rodape.css">
  <link rel="stylesheet" href="CSS/noticia.css">
  <style>
    html {
      scroll-behavior: smooth;
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


  <div class="page">
    <?php
    $noticiasPorPagina = 9;
    $paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;

    // Conta total de notícias
    $totalNoticias = $conn->query("SELECT COUNT(*) as total FROM noticias")->fetch_assoc()['total'];
    $totalPaginas = ceil($totalNoticias / $noticiasPorPagina);

    // Impede ir a páginas fora do intervalo
    if ($paginaAtual > $totalPaginas) $paginaAtual = $totalPaginas;

    $offset = ($paginaAtual - 1) * $noticiasPorPagina;

    $sql = "SELECT idnoticia, img, data, titulo, subtitulo FROM noticias ORDER BY data DESC LIMIT $noticiasPorPagina OFFSET $offset";
    $result = $conn->query($sql);

    // Função para o formato da data
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
      return "$dia DE $mes DE $ano";
    }
    ?>
    <div class="grade-noticias" id="grade_noticias">
      <?php
      if ($result && $result->num_rows > 0):
        while ($noticia = $result->fetch_assoc()):
      ?>
          <article class="cartao-noticia">
            <img src="IMAGENS/<?= htmlspecialchars($noticia['img']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>">
            <p><?= formatarDataPt1($noticia['data']) ?></p>
            <h3><?= htmlspecialchars($noticia['titulo']) ?></h3>
            <p><?= htmlspecialchars($noticia['subtitulo']) ?></p>
            <a href="noticia.php?id=<?= urlencode($noticia['idnoticia']) ?>" class="btn-noticia">Ler Mais</a>
          </article>
      <?php
        endwhile;
      else:
        echo "<p>Não há notícias a mostrar.</p>";
      endif;
      ?>
    </div>

    <!-- PAGINAÇÃO -->
    <nav class="paginacao">
      <ul>
        <?php
        // Botão primeira página
        for ($i = $inicio; $i <= $fim; $i++) {
          if ($i == $paginaAtual) {
            echo '<li><span class="active">' . $i . '</span></li>';
          } else {
            echo '<li><a href="?pagina=' . $i . '#noticias">' . $i . '</a></li>';
          }
        }

        // Botão anterior
        if ($paginaAtual > 1) {
          echo '<li><a href="?pagina=' . ($paginaAtual - 1) . '#noticias"><</a></li>';
        } else {
          echo '<li><span class="disabled"><</span></li>';
        }

        // Botão próximo
        if ($paginaAtual < $totalPaginas) {
          echo '<li><a href="?pagina=' . ($paginaAtual + 1) . '#noticias">></a></li>';
        } else {
          echo '<li><span class="disabled">></span></li>';
        }

        // Botão primeira página
        if ($paginaAtual > 1) {
          echo '<li><a href="?pagina=1#noticias">«</a></li>';
        } else {
          echo '<li><span class="disabled">«</span></li>';
        }

        // Botão última página
        if ($paginaAtual < $totalPaginas) {
          echo '<li><a href="?pagina=' . $totalPaginas . '#noticias">»</a></li>';
        } else {
          echo '<li><span class="disabled">»</span></li>';
        }

        ?>
      </ul>
    </nav>
  </div>

  <script src="JS/submenulateral.js"></script>
  <script src="JS/navbar.js"></script>
  <script src="JS/barra_pesquisa.js"></script>
</body>

</html>