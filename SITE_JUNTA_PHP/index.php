<?php
include 'admin/database.php';

if (!$conn) {
  die("Erro de conexão: " . mysqli_connect_error());
}

//--- CONSULTAS ---
$sql_paginainicial = "SELECT id, titulo_h3, frase, subtitulo_h2, texto, opcoes, fotos, caption FROM paginainicial";
$res = mysqli_query($conn, $sql_paginainicial);

$sql_logo = "SELECT id, logo, logo_titulo FROM logo";
$res1 = mysqli_query($conn, $sql_logo);

$sql_freguesia = "SELECT id, opcao, link, classe FROM submenu_freguesia ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql_freguesia);

$sql_contactos = "SELECT id, opcao_, url_, classe_1 FROM submenu_contactos ORDER BY id ASC";
$resultado1 = mysqli_query($conn, $sql_contactos);

$sql_autarquia = "SELECT id, `option`, `url`, classse, tipo, parent FROM submenu_autarquia ORDER BY id ASC";
$result2 = mysqli_query($conn, $sql_autarquia);

$sql_boasvindas = "SELECT id_msg, titulo_pagina, foto_presidente, nome_presidente, cargo, mensagem FROM boas_vindas";
$result4 = mysqli_query($conn, $sql_boasvindas);

$sql_freguesia = "SELECT id, opcao, link, classe FROM submenu_freguesia ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql_freguesia);

$sql_servicos = "SELECT id_servico, titulo, descricao, titulo_servico, descricao_servico, img_servico ,link FROM servicos ORDER BY id_servico ASC";
$result3 = mysqli_query($conn, $sql_servicos);

$sql_avisos = "SELECT id_aviso, titulo, titulo_aviso, data FROM avisos ORDER BY data DESC LIMIT 5";
$res_avisos = mysqli_query($conn, $sql_avisos);

// Buscar o título de um aviso específico (ex: o mais recente)
$sql_titulo = "SELECT titulo FROM avisos LIMIT 1";
$res_titulo = mysqli_query($conn, $sql_titulo);
$titulo_aviso = '';
if ($row = mysqli_fetch_assoc($res_titulo)) {
  $titulo_aviso = $row['titulo'];
}

if (!$res || !$res1 || !$resultado || !$resultado1 || !$result2 || !$result3 || !$result4 || !$res_avisos) {
  die("Erro numa das consultas: " . mysqli_error($conn));
}

if ($result4 && mysqli_num_rows($result4) > 0) {
  // Supondo que só há uma mensagem principal
  $row = mysqli_fetch_assoc($result4);
  $titulo = htmlspecialchars($row['titulo_pagina']);
  $foto_presidente = htmlspecialchars($row['foto_presidente']);
  $nome_presidente = htmlspecialchars($row['nome_presidente']);
  $cargo = htmlspecialchars($row['cargo']);
  $mensagem = nl2br(htmlspecialchars($row['mensagem']));
} else {
  $titulo = "Mensagem de Boas-vindas";
  $foto_presidente = "IMAGENS/image-removebg-preview (49).png";
  $nome_presidente = "Alberto Matos";
  $cargo = "Presidente da Junta de Freguesia";
  $mensagem = "Ainda não há mensagem disponível.";
}

// --- LOGO ---
if ($row1 = mysqli_fetch_assoc($res1)) {
  $logo = $row1['logo'];
  $logo_titulo = $row1['logo_titulo'];
}

// --- PAGINA INICIAL ---
if ($row = mysqli_fetch_assoc($res)) {
  $titulo_h3 = $row['titulo_h3'];
  $frase = $row['frase'];
  $subtitulo_h2 = $row['subtitulo_h2'];
  $texto = $row['texto'];
  $opcoes = $row['opcoes'];
  $fotos = array_map('trim', explode(',', $row['fotos']));
  $caption = array_map('trim', explode(',', $row['caption']));
}
?>


<!DOCTYPE html>
<html lang="pt-PT">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ínicio - Junta de Freguesia de Barreiro de Besteiros e Tourigo</title>

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

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Domine:wght@400..700&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="CSS/rodape.css">
  <link rel="stylesheet" href="CSS/locais.css">
  <link rel="stylesheet" href="CSS/estilo1.css">
  <link rel="stylesheet" href="CSS/noticia.css">
  <link rel="stylesheet" href="CSS/style.css">

  <style>
    body {
      overflow-x: hidden;
      min-height: 100vh;
    }

    html {
      scroll-behavior: smooth;
    }

    #boasvindas {
      scroll-margin-top: -30px !important;
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
        <i class="bi bi-telephone-forward-fill"></i>
        <a href="tel:<?= htmlspecialchars($dados['telefone']) ?>"><?= htmlspecialchars($dados['telefone']) ?></a>
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
    </div>

    <div class="navbar_canto">
      <a href="login.php">
        <img src="IMAGENS/image-removebg-preview (38).png" alt="Login">
      </a>
      <i class="bi bi-search" style="font-size:30px; margin-left:40px; cursor:pointer; position:absolute; bottom: 36px;"></i>
    </div>
  </div> <!-- fecha navbar -->

  <?php
  $sql = "SELECT tituloprincipal, subtitulo, imgs FROM inicio";
  $res = mysqli_query($conn, $sql);

  if ($row = mysqli_fetch_assoc($res)) {
    $tituloprincipal = $row['tituloprincipal'];
    $subtitulo       = $row['subtitulo'];
    $imgs            = $row['imgs'];

  ?>
    <div class="paginainicial">
      <span class="seta-slider seta-esquerda1" id="seta-esquerda">
        <img src="IMAGENS/seta-esquerda.png" style="height: 20px; width:20px;">
      </span>
      <span class="seta-slider seta-direita1" id="seta-direita">
        <img src="IMAGENS/seta-direita (2).png" style="height: 20px; width:20px;">
      </span>

      <div class="slider_imgs">
        <?php
        $imgsArr = array_map('trim', explode(',', $imgs));
        foreach ($imgsArr as $index => $img) {
          if ($img !== '') {
            echo '<img src="IMAGENS/' . htmlspecialchars($img) . '" alt=""' . ($index === 0 ? ' class="active"' : '') . '>';
          }
        }
        ?>

        <style>
          .slider_imgs {
            position: relative;
          }

          .seta-slider {
            z-index: 1000;
          }

          .paginainicial .seta-slider {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 1000;
            /* garante que fica por cima de tudo */
            background: none;
          }

          .paginainicial .seta-slider img {
            width: 30px !important;
            height: 30px !important;
            display: block;
            transition: filter 0.9s ease;
            filter: grayscale(0%) brightness(1) drop-shadow(0 0 5px black);
            transition: filter 0.3s ease, transform 0.3s ease;
          }

          .paginainicial .seta-slider:hover img {
            filter: grayscale(100%) brightness(0.6) drop-shadow(2px 2px 5px rgba(0, 0, 0, 0.5));
            transform: scale(1.0);
            /* efeito de “crescer” ao hover */
          }

          .seta-esquerda1 {
            left: 15px;
          }

          .seta-direita1 {
            right: 30px;
          }
        </style>
      </div>

      <div class="aviso-quadro" id="avisoQuadro">
        <div class="aviso-topo">
          <h2 class="aviso-titulo"><?= htmlspecialchars($titulo_aviso) ?></h2>
          <button id="botaoMinimizar" class="botao-minimizar" onclick="minimizarQuadro()">
            <span id="iconeMinimizar">&#8722;</span>
          </button>
        </div>
        <div class="aviso-conteudo" id="avisoConteudo">
          <ul class="aviso-lista">
            <?php while ($row = mysqli_fetch_assoc($res_avisos)) { ?>
              <li>
                <a class="aviso-msg" href="aviso.php?id=<?= urlencode($row['id_aviso']) ?>">
                  <?= htmlspecialchars($row['titulo_aviso']) ?>
                </a>
                <span class="aviso-data-item"><?= date('d/m/Y', strtotime($row['data'])) ?></span>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>

      <div class="slider_dots">
        <?php
        for ($i = 0; $i < count($imgsArr); $i++) {
          // Coloca a primeira "dot" como ativa
          echo '<span class="dot' . ($i === 0 ? ' active' : '') . '"></span>';
        }
        ?>
      </div>

      <div class="slider_texto">
        <h1><?php echo htmlspecialchars($tituloprincipal); ?></h1>
        <p><?php echo htmlspecialchars($subtitulo); ?></p>
        <a href="#atracoes" class="botaoinicial">Ver mais</a>

      </div>
    </div>
  <?php
  } else {
    echo "<p>Não existem dados para mostrar no slider.</p>";
  }
  ?>

  <script>
    function minimizarQuadro() {
      var quadro = document.getElementById('avisoQuadro');
      quadro.classList.toggle('minimizado');

      var icone = document.getElementById('iconeMinimizar');
      if (quadro.classList.contains('minimizado')) {
        icone.innerHTML = '&#43;'; // Plus icon
      } else {
        icone.innerHTML = '&#8722;'; // Minus icon
      }
    }
  </script>

  <style>
    .botao-minimizar {
      background-color: transparent;
      border: none;
      color: #fff;
      font-size: 2em;
      margin-top: -8px;
    }

    /* tirar fundo no clique e foco */
    .botao-minimizar:focus,
    .botao-minimizar:active {
      background-color: transparent;
      outline: none;
      box-shadow: none;
    }

    .aviso-quadro {
      position: absolute;
      top: 200px;
      right: 130px;
      width: 350px;
      background: rgba(143, 149, 160, 0.69);
      color: #fff;
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.36);
      font-family: 'Raleway', 'Montserrat', Arial, sans-serif;
      z-index: 99;
      overflow: hidden;
      min-height: 64px;
      transition:
        box-shadow 2.36s cubic-bezier(.12, .89, .87, 1.05),
        filter 0.45s;
      filter: blur(0);
    }

    .aviso-quadro.minimizado {
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.16);
      filter: blur(.1px);
    }

    .aviso-topo {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 22px 30px 0 30px;
    }

    .aviso-titulo {
      margin: 0;
      font-size: 2em;
      letter-spacing: .5px;
      font-weight: 700;
      margin-top: -10px;
    }

    .aviso-lista li {
      margin-bottom: 16px;
      padding: 4px 0 7px 0;
      border-bottom: 3px solid rgba(224, 227, 233, 0.28);
    }

    .aviso-data-item {
      display: block;
      font-size: 0.95em;
      color: #cce3ff;
      margin-bottom: 2px;
    }

    .aviso-msg {
      display: block;
      color: #fff;
      font-weight: 700;
      font-size: 1.15em;
      margin-bottom: 1px;
      line-height: 1.1;
      text-decoration: none;
      transition: color 0.2s, text-decoration 0.2s;
    }

    .aviso-msg:hover {
      background: rgba(41, 62, 57, 0.18);
      border-radius: 5px;
      color: #fff !important;
      transition: background 0.2s, color 0.2s;
    }

    .botao-minimizar {
      background: none;
      border: none;
      color: #fff;
      font-size: 2em;
      cursor: pointer;
      margin-left: 12px;
      transition: color 0.2s;
    }

    .botao-minimizar:hover {
      color: #aecfff;
    }

    .aviso-conteudo {
      padding: 11px 30px 24px 30px;
      opacity: 1;
      max-height: 500px;
      overflow: hidden;
      pointer-events: auto;
      will-change: max-height, opacity, padding;
      transition:
        max-height 0.48s cubic-bezier(.52, .09, .34, 1.03),
        opacity 0.3s cubic-bezier(.52, .09, .34, 1.03),
        padding 0.43s cubic-bezier(.23, .96, .79, .97);
    }

    .aviso-lista {
      margin: 0;
      padding-left: 16px;
      list-style-type: disc;
      font-size: 1.11em;
    }

    .aviso-lista li {
      margin: 7px 0;
    }

    .aviso-lista a:visited {
      /*alterar aqui a cor das opçoes da navbar*/
      color: #fdffffff;
      text-decoration: none;
    }

    /* Minimizado */
    .aviso-quadro.minimizado .aviso-conteudo {
      max-height: 0;
      opacity: 0;
      padding-top: 0;
      padding-bottom: 0;
      pointer-events: none;
      transition:
        max-height 1.35s cubic-bezier(.84, 0, .28, 1),
        opacity 0.2s cubic-bezier(.84, 0, .28, 1),
        padding 0.19s linear;
    }

    .aviso-lista li {
      margin: 7px 0;
    }

    /* Minimizado */
    .aviso-quadro.minimizado .aviso-conteudo {
      max-height: 0;
      opacity: 0;
      visibility: hidden;
      padding-top: 0;
      padding-bottom: 0;
      transition:
        max-height 0.35s cubic-bezier(.6, 0, .26, 1),
        opacity 0.2s;
    }

    .aviso-quadro {
      height: auto;
      min-height: 68px;
    }

    .aviso-quadro:not(.minimizado) {
      /* Mais largo quando maximizado */
      min-height: 350px;
      /* Ajuste a altura máxima se necessário */
      /* height: auto;  normalmente height:auto permite crescer conforme o conteúdo */
    }

    .paginainicial>img.active {
      display: block;
      /* Mostra só a imagem ativa */
    }

    .paginainicial {
      position: relative;
      width: 100%;
      height: 800px;
      overflow: hidden;
    }

    .paginainicial img {
      width: 100%;
      height: 800px;
      object-fit: cover;
      margin-top: 30px;
    }

    .slider_imgs {
      position: relative;
      width: 100%;
      height: 100%;
      transform: translateY(0px);
      image-rendering: pixelated !important;
    }

    .slider_imgs img {
      position: absolute;
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0;
      transition: opacity 1s ease;
      z-index: 1;
      pointer-events: auto;
    }

    .slider_imgs span {
      position: relative;
    }

    .slider_imgs img.active {
      opacity: 1;
      z-index: 1;
      pointer-events: auto;
    }

    .slider_dots {
      position: absolute;
      bottom: 27px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 10px;
      z-index: 15;
    }

    .slider_dots .dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #e3e3e3;
      transition: background 0.3s;
      border: 1px solid #0d0505ff;
      cursor: pointer;
    }

    .slider_dots .dot.active {
      background: #111;
      border-color: #111;
    }

    .slider_texto h1 {
      bottom: 450px;
      color: aliceblue;
      position: absolute;
      left: 80px;
      font-family: "Urbanist", sans-serif;
      font-optical-sizing: auto;
      font-weight: 500;
      font-size: 46px;
      gap: 40px;
      text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.8);
      max-width: 900px;
      line-height: 1.4;
    }

    .slider_texto p {
      top: 380px;
      /*quanto mais pixeis, desce*/
      color: aliceblue;
      position: absolute;
      left: 80px;
      /*---PARA MOVER PARA A DIREITA---*/
      font-family: "Figtree", sans-serif;
      font-size: 20px;
      text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.8);
    }

    .botaoinicial {
      background-color: #7ebcd6;
      color: white;
      padding: 10px 25px;
      font-size: 20px;
      border: none;
      border-radius: 11px;
      text-decoration: none;
      font-family: "Nunito Sans", sans-serif;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
      left: 79px;
      position: absolute;
      top: 500px;
      transition: transform 0.4s ease;
    }

    .botaoinicial:hover {
      background-color: #94aa9f;
      transform: scale(1.02);
      /* Aumenta ligeiramente */

      color: hsla(180, 100%, 100%, 1.00);
    }
  </style>


  <section class="atracoes" id="atracoes">
    <h2 class="fade-up"><?php echo htmlspecialchars($titulo_h3); ?></h2>
    <h4 class="fade-up"><?php echo htmlspecialchars($frase); ?></h4>

    <div class="linha-azul fade-up"></div>
    <div class="conteudo-atracoes fade-up">

      <div class="texto-atracoes">
        <h3 class="fade-up"><?php echo htmlspecialchars($subtitulo_h2); ?></h3>
        <p class="fade-up"><?php echo nl2br(htmlspecialchars($texto)); ?></p>


        <ul>
          <?php
          // Separar as opções (pode ser \n ou ;)
          $lista = preg_split('/\r\n|\r|\n|;/', $opcoes);
          foreach ($lista as $op) {
            $op = trim($op);
            if ($op !== '') {
              echo '<li class="fade-up">➜ <strong>' . htmlspecialchars($op) . '</strong></li>';
            }
          }
          ?>
        </ul>
        <div class="fade-up">
          <a href="locais.php" class="ver-tudo">Ver o melhor da Freguesia</a>
        </div>
      </div>
      <div class="imagens-atracoes fade-up">
        <?php
        // Garantir que temos arrays mesmo que venham em formato string
        $fotosArr    = is_array($fotos) ? $fotos : array_map('trim', explode(',', $fotos));
        $captionsArr = is_array($caption) ? $caption : array_map('trim', explode(',', $caption));

        // Loop nas fotos
        foreach ($fotosArr as $i => $foto) {
          if ($foto !== '') {
            // Legenda sempre do campo caption da BD
            $legenda = isset($captionsArr[$i]) ? $captionsArr[$i] : '';

            echo '<div class="imagem-container">';
            echo '  <img src="IMAGENS/' . htmlspecialchars($foto) . '" alt="' . htmlspecialchars($legenda) . '">';
            echo '  <div class="caption">' . htmlspecialchars($legenda) . '</div>';
            echo '</div>';
          }
        }
        ?>
      </div>
    </div>
  </section>


  <!-------------------------------------------------------------SECÇAO DE BOAS VINDAS--------------------------------------------------------------->

  <section class="bienvenida-container" id="boasvindas" style="background: linear-gradient(135deg, #f2f4f7ff 10%, #f4f7faff 30%, #f7f9fbff 100%);">
    <div class="titulo" style="text-align:center; width:100%;">
      <h2><?php echo htmlspecialchars($titulo); ?></h2>
    </div>
    <div class="presidente-box">
      <img src="IMAGENS/<?php echo htmlspecialchars($foto_presidente); ?>" alt="Foto do Presidente" class="presidente-foto">
      <span class="presidente-nome"><?php echo htmlspecialchars($nome_presidente); ?></span>
      <span class="presidente-cargo"><?php echo htmlspecialchars($cargo); ?></span>
    </div>
    <div class="mensagem-box">
      <p style="line-height: 1.65;"><?php echo ($mensagem); ?></p>
      <p class="presidente-nome">
        <?php echo htmlspecialchars($nome_presidente); ?><br><?php echo htmlspecialchars($cargo); ?>
      </p>
    </div>
  </section>

  <!-------------------------------------------------------------SECÇAO DE SERVIÇOS--------------------------------------------------------------->
  <style>
    h2 {
      text-align: center;
      font-weight: 700;
      font-size: 34px;
      margin-bottom: 30px;
      font-family: "Figtree", sans-serif;
      font-optical-sizing: auto;
      font-weight: 500;
      font-style: normal;
    }

    .services-container {
      display: grid;
      grid-template-columns: repeat(3, minmax(280px, 1fr));
      /* força 3 colunas na 1ª linha */
      gap: 30px 30px;
      justify-items: center !important;
      max-width: 1200px;
      margin: 0 auto !important;
    }

    /* Segunda linha: wrapper que centra dois cards - usa grid para espaçamento consistente */
    .services-row-2 {
      grid-column: 1 / -1;
      /* ocupa toda a largura do grid */
      display: grid;
      grid-template-columns: repeat(2, minmax(280px, 1fr));
      gap: 30px 40px;
      /* mesmo gap do container */
      justify-content: center;
      /* centra a grade de 2 colunas dentro do container */
      max-width: calc(2 * 350px + 40px);
      /* com base no max-width dos cards + gap */
      margin: 0 auto;
    }

    /* Responsividade */
    @media (max-width: 900px) {
      .services-container {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 600px) {
      .services-container {
        grid-template-columns: 2fr;
      }
    }

    /* CARD */
    .service-box {
      max-width: 350px;
      width: 100%;
      /* ocupa toda a célula do grid para espaçamento uniforme */
      /* limita a largura do card */
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgb(0 0 0 / 0.05);
      padding: 35px 25px 25px 110px;
      position: relative;
      border-bottom: 3px solid #6b8fa4;
      box-sizing: border-box;
      transition: transform 0.48s cubic-bezier(.2, .9, .2, 1), box-shadow 0.28s ease, border-bottom-color 0.28s ease;
      will-change: transform;
    }

    /* efeito hover: eleva o cartão e aplica sombra alinhada à linha inferior */
    .service-box::after {
      content: '';
      position: absolute;
      left: 0;
      right: 0;
      bottom: -6px;
      height: 8px;
      background: transparent;
      border-radius: 4px;
      filter: blur(6px);
      opacity: 0;
      transition: opacity 0.28s ease, background 0.28s ease;
      pointer-events: none;
    }

    .service-box:hover {
      transform: translateY(-8px);
      box-shadow: 0 18px 40px rgba(11, 38, 63, 0.12), 0 6px 12px rgba(0, 0, 0, 0.06);
      border-bottom-color: #3b6e8a;
    }

    .service-box:hover::after {
      background: rgba(59, 110, 138, 0.18);
      /* pequena 'sombra' colorida sob a linha */
      opacity: 1;
    }

    .icon-bg {
      position: absolute;
      top: 35px;
      left: 20px;
      width: 65px;
      height: 65px;
      background: #f0f2f4;
      border-radius: 5px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .icon svg {
      fill: #6b8fa4;
      width: 23px;
      height: 23px;
    }

    .service-title {
      font-weight: 700;
      font-size: 16px;
      margin-bottom: 10px;
    }

    .service-text {
      font-size: 13px;
      line-height: 1.5em;
      color: #767676;
      margin-bottom: 10px;
    }

    .arrow {
      font-size: 16px;
      color: #6b8fa4;
      font-weight: 700;
      user-select: none;
    }

    .descricao {
      text-align: center;
      font-size: 20px;
      color: #302e2eff;
      padding-bottom: -20px;
      margin-bottom: 10px;
      font-family: "Figtree", sans-serif;
      font-optical-sizing: auto;
      font-weight: 300;
      font-style: normal;
    }

    .divider {
      width: 60px;
      height: 4px;
      background-color: #2f5494ff;
      margin: 20px auto 50px auto;
      border-radius: 2px;
    }

    .vermais {
      text-decoration: none;
      color: #2f5494ff;
      font-weight: 600;
      font-size: 14px;
      bottom: -10px;
      margin-left: -3px;
      display: inline-block;
      margin-top: 10px;
    }
  </style>

  <main class="servicos-grid" style="background-color: #f6f6f6ff; padding: 30px 20px;">
    <h2>Serviços da Junta de Freguesia</h2>
    <p class="descricao">A Junta de Freguesia coloca ao dispor da população um conjunto de serviços essenciais para todos os fregueses!</p>

    <div class="divider"></div>
    <div class="services-container">
      <?php
      if ($result3 && mysqli_num_rows($result3) > 0) {
        while ($row = mysqli_fetch_assoc($result3)) {
          echo '<div class="service-box">';
          echo '    <div class="icon-bg icon">';
          echo '        <img src="IMAGENS/' . htmlspecialchars($row['img_servico']) . '" alt="' . htmlspecialchars($row['titulo_servico']) . '" style="width:50px; height:50px;" title="' . htmlspecialchars($row['titulo_servico']) . '">';
          echo '    </div>';
          echo '    <div class="service-title">' . htmlspecialchars($row['titulo_servico']) . '</div>';
          echo '    <div class="service-text">' . htmlspecialchars($row['descricao_servico']) . '</div>';
          echo '    <a class="vermais" href="' . htmlspecialchars($row['link']) . '">Pedir </a>';
          echo '</div>';
        }
      } else {
        echo '<p>Nenhum serviço encontrado.</p>';
      }
      ?>
    </div>
  </main>



  <!-------------------------------------------------------------SECÇAO DE NOTICIAS--------------------------------------------------------------->

  <section class="secao-noticias">
    <?php
    $sql = "SELECT tituloprincipal, descricao FROM noticias";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $tituloSecao = $row['tituloprincipal'];
      $descricaoSecao = $row['descricao'];
    } else {
      // Se nao houver nada na Base de dados
      $tituloSecao = "Últimas Atualizações";
      $descricaoSecao = "Acompanhe todas as atualizações importantes, desde comunicados oficiais, eventos locais, avisos à população, até às iniciativas e projetos em curso.";
    }

    // Função helper
    function e($str)
    {
      return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
    ?>

    <h2 class="titulo-noticias"><?= e($tituloSecao) ?></h2>
    <p class="texto-descricao"><?= e($descricaoSecao) ?></p>


    <style>
      .texto-descricao {
        font-family: "Montserrat", sans-serif;
        font-optical-sizing: auto;
        font-weight: 390;
        font-style: normal;
        font-size: 19px;
        color: #080606ff;
        box-shadow: 20px auto 10px auto;
        max-width: 900px;
        margin: 10px auto 40px auto;
        /* centra e dá espaçamento, no 10px, aumenta */
        text-align: center;
      }
    </style>

    <div class="grade-noticias">
      <?php
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

      $sql = "SELECT idnoticia, img, data, titulo, subtitulo FROM noticias ORDER BY data DESC LIMIT 6";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0):
        while ($noticia = $result->fetch_assoc()):
      ?>
          <article class="cartao-noticia">
            <img src="IMAGENS/<?= htmlspecialchars($noticia['img']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>">
            <p><i class="bi bi-calendar-event"></i><?= formatarDataPt($noticia['data']) ?></p>
            <h3><a href="noticia.php?id=<?= urlencode($noticia['idnoticia']) ?>"><?= htmlspecialchars($noticia['titulo']) ?></a></h3>
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
      <?php
        endwhile;
      else:
        echo "<p>Não há notícias a mostrar.</p>";
      endif;
      ?>

    </div>

    <style>
      .cartao-noticia h3 {
        font-size: 22px;
        margin-bottom: 34px;
        position: relative;
        color: #466787;
        font-family: "TikTok Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: 500;
        letter-spacing: 0.4px;
        line-height: 1.1;
        font-style: normal;
        text-align: center;
        width: 100%;
        /* ocupa a largura do cartão */
        margin-left: 0;
        /* não força deslocamento */
        cursor: pointer;
        text-decoration: none;
      }
    </style>

    <div class="vermais-container-noticias">
      <a href="noticias.php" class="btn-vermais-noticias">Ver todas as notícias</a>
    </div>
  </section>
  <!---------------------LINKS UTEIS-------------->
  <?php
  $sql_titulo = "SELECT titulo, texto FROM links LIMIT 1";
  $res_titulo = mysqli_query($conn, $sql_titulo);
  $titulo_link = "Links Úteis"; // valor por defeito caso não haja nada

  if ($row_titulo = mysqli_fetch_assoc($res_titulo)) {
    $titulo_link = $row_titulo['titulo'];
    $textoSecao = $row_titulo['texto'];
  }
  ?>

  <section class="secao-links-uteis">
    <div class="container-links">
      <h2><i class="bi bi-link-45deg"></i><?php echo htmlspecialchars($titulo_link); ?></h2>
      <h1><?php echo htmlspecialchars($textoSecao); ?></h1>
      <div class="links-slider">

        <ul>
          <?php
          $sql = "SELECT img, texto, link, alt_img FROM links ORDER BY idlink DESC";
          $res = mysqli_query($conn, $sql);

          if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
              $link = htmlspecialchars($row['link']);
              $img = htmlspecialchars($row['img']);
              $alt = htmlspecialchars($row['alt_img']);

              echo '<li>';
              echo '  <a href="' . $link . '" target="_blank" title="' . $alt . '">';
              echo '    <img src="IMAGENS/' . $img . '" alt="' . $alt . '" title="' . $alt . '">';
              echo '  </a>';
              echo '</li>';
            }
          } else {
            echo '<li>Sem links úteis registados.</li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </section>

  <!-----------------------------------------RODAPE-------------------------------------------->

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
          <li><a href="documentos.php">Pedido de Documentos</a></li>
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
          <li><a href="contactos.php#contactos">Contactos da Freguesia</a></li>
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
  <!---------------------------MODAL INDEX--------------------------->

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
  <style>
    .imagem-modal-conteudo-index {
      margin-top: 60px;
    }
  </style>

  <script src="JS/links.js"></script>
  <script src="JS/navbar.js"></script>
  <script src="JS/smoth.js"></script>
  <script src="JS/modal.js"></script> <!--modal script-->
  <script src="JS/dots.js"></script>
  <script src="JS/submenulateral.js"></script>
  <script src="JS/animacao.js"></script>
  <script src="JS/btntopo.js"></script>
  <script src="JS/fullscreenindex.js"></script>
  <script src="JS/add.js"></script>

  <!-- Script: agrupa automaticamente os últimos 2 cards quando necessário -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const container = document.querySelector('.services-container');
      if (!container) return;

      const getBoxes = () => Array.from(container.children).filter(c => c.classList && c.classList.contains('service-box'));

      const unwrapRow2 = () => {
        const existing = container.querySelector('.services-row-2');
        if (!existing) return;
        const children = Array.from(existing.children);
        children.forEach(ch => container.insertBefore(ch, existing));
        existing.remove();
      };

      const arrange = () => {
        unwrapRow2();
        const boxes = getBoxes();
        const n = boxes.length;
        if (n % 3 === 2) {
          const wrapper = document.createElement('div');
          wrapper.className = 'services-row-2';
          const lastTwo = boxes.slice(-2);
          lastTwo.forEach(b => wrapper.appendChild(b));
          container.appendChild(wrapper);
        }
      };

      arrange();

      const obs = new MutationObserver(() => {
        clearTimeout(window.__servicesArrangeTimer);
        window.__servicesArrangeTimer = setTimeout(arrange, 60);
      });
      obs.observe(container, {
        childList: true,
        subtree: false
      });
    });
  </script>

  <script>
    const imagens = <?php echo json_encode($imagens); ?>;
    const img = document.getElementById("imagem-modal-conteudo-index");
    const btn = document.getElementById("btn-download-index");
    let indiceAtual = 0;
    // setas
    document.getElementById("seta-direita-index").addEventListener("click", () => {
      indiceAtual = (indiceAtual + 1) % imagens.length;
      mostrarImagem(indiceAtual);
    });
    document.getElementById("seta-esquerda-index").addEventListener("click", () => {
      indiceAtual = (indiceAtual - 1 + imagens.length) % imagens.length;
      mostrarImagem(indiceAtual);
    });
    // inicializar
    mostrarImagem(indiceAtual);
  </script>
</body>

</html>