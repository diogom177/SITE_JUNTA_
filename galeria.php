  <?php
  include 'admin/database.php';

  if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
  }

  $sql = "SELECT id, titulo_card, `data`, img_card FROM galeria_fotos";
  $resultGaleria  = $conn->query($sql);

  $sqlHeaderQuery = "SELECT tituloprincipal, descricao, img_banner FROM galeria_fotos LIMIT 1";
  $resultHeaderQuery = $conn->query($sqlHeaderQuery);
  $headerBanner = $resultHeaderQuery ? $resultHeaderQuery->fetch_assoc() : null;

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

  $sqlFreguesia = "SELECT id, opcao, link, classe FROM submenu_freguesia ORDER BY id ASC";
  $resultFreguesia = mysqli_query($conn, $sqlFreguesia);

  $sql = "SELECT id, `option`, `url`, classse, tipo, parent FROM submenu_autarquia ORDER BY id ASC";
  $result2 = mysqli_query($conn, $sql);

  $sql_contactos = "SELECT id, opcao_, url_, classe_1 FROM submenu_contactos ORDER BY id ASC";
  $resultado1 = mysqli_query($conn, $sql_contactos);

  if (!$result2) {
    die("Erro na consulta submenu_autarquia: " . mysqli_error($conn));
  }

  if (!$resultado1) {
    die("Erro na consulta submenu_freguesia: " . mysqli_error($conn));
  }
  ?>

  <!DOCTYPE html>
  <html lang="pt-PT">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria de Fotos - Junta de Freguesia de Barreiro de Besteiros</title>
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
    <link rel="stylesheet" href="CSS/galeria.css">
    <link rel="stylesheet" href="CSS/rodape.css">

    <style>
      body {
        background-color: #F7FCFE;
      }

      .img_inicial {
        position: relative;
        background: url('IMAGENS/<?= htmlspecialchars($headerBanner['img_banner']) ?>') center center/cover no-repeat;
        height: 430px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-left: 0;
        margin-top: -1px;
        /*nao tirar isto, senao o banner vai p cima*/
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

      .texto .descricao {
        text-align: center !important;
        font-size: 24px !important;
        font-weight: bold !important;
        margin-top: 0 !important;
      }

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
        'horario' => 'Barreiro: Terça-Feira das 19h00 - 20h30 Tourigo: Sexta-Feira das 16h-18h',
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
          <img src="IMAGENS/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($titulo_junta); ?>">
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
            <?php while ($row = $resultFreguesia->fetch_assoc()) { ?>
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

    <section class="img_inicial">
      <div class="texto">
        <?php if ($headerBanner && !empty($headerBanner['tituloprincipal'])): ?>
          <h1 class="tituloprincipal"><?= htmlspecialchars($headerBanner['tituloprincipal']) ?></h1>
          <h2 class="descricao"><?= nl2br(htmlspecialchars($headerBanner['descricao'])) ?></h2>
        <?php else: ?>
          <h1 class="tituloprincipal">Galeria de Imagens</h1>
          <h2 class="descricao">Encontre aqui a coleção de imagens da nossa comunidade.</h2>
        <?php endif; ?>
      </div>

    </section>
    <div class="breadcrumbs">
      <a href="index.php">Início</a> <i class="bi bi-chevron-right"></i>
      <a href="galeria.php">Galeria de Fotos</a>
    </div>

    <main class="grade-galeria">
      <?php if ($resultGaleria && $resultGaleria->num_rows > 0): ?>
        <?php while ($galeria = $resultGaleria->fetch_assoc()): ?>
          <article class="cartao-galeria">
            <img src="IMAGENS/<?= htmlspecialchars($galeria['img_card']) ?>" alt="<?= htmlspecialchars($galeria['titulo_card']) ?>">
            <a href="galeriaa.php?id=<?= urlencode($galeria['id']) ?>">
              <h3><?= htmlspecialchars($galeria['titulo_card']) ?></h3>
            </a>
            <p><?= htmlspecialchars($galeria['data']) ?></p>
            <a href="fotodetalhe.php?id=<?= urlencode($galeria['id']) ?>" class="button1">
              <span>Ver Mais</span>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 74 74" height="34" width="34">
                <circle stroke-width="3" stroke="black" r="35.5" cy="37" cx="37"></circle>
                <path
                  fill="black"
                  d="M25 35.5C24.1716 35.5 23.5 36.1716 23.5 37C23.5 37.8284 24.1716 38.5 25 38.5V35.5ZM49.0607 38.0607C49.6464 37.4749 49.6464 36.5251 49.0607 35.9393L39.5147 26.3934C38.9289 25.8076 37.9792 25.8076 37.3934 26.3934C36.8076 26.9792 36.8076 27.9289 37.3934 28.5147L45.8787 37L37.3934 45.4853C36.8076 46.0711 36.8076 47.0208 37.3934 47.6066C37.9792 48.1924 38.9289 48.1924 39.5147 47.6066L49.0607 38.0607ZM25 38.5L48 38.5V35.5L25 35.5V38.5Z">
                </path>
              </svg>
            </a>
          </article>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="semnada">
          <p class="maior">Sem imagens disponíveis na galeria!</p>
          <p class="menor"> Por favor escolha uma categoria ou ano diferente!</p>
        </div>
      <?php endif; ?>
    </main>

    <style>
      .semnada {
        text-align: center;
        color: black;
        padding-top: 80px;
        font-family: "Raleway", sans-serif !important;
        font-weight: 500;
        font-size: 20px;
        line-height: 1.7;
      }

      .semnada .maior {
        font-size: 25px;
        display: block;
      }

      .semnada .menor {
        font-size: 16px;
        display: block;
        margin-top: -20px;
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

    <script src="JS/navbar.js"></script>
    <script src="JS/submenulateral.js"></script>
    <script src="JS/barra_pesquisa.js"></script>
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

  </body>

  </html>