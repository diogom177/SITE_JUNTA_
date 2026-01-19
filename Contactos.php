<?php
include 'admin/database.php'; // Caminho para o teu ficheiro de ligação

if (!$conn) {
  die("Erro de conexão: " . mysqli_connect_error());
}

$sqlHeader = "SELECT titulo, descricao FROM contactos LIMIT 1";
$resultHeader = $conn->query($sqlHeader);

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

$map_url = "https://www.google.com/maps/embed?pb=!4v1768064490882!6m8!1m7!1sSRx52bO0OAM15jJnMROGPw!2m2!1d40.50971305152652!2d-8.177693941046174!3f350.82563621336504!4f-3.2077732154803726!5f0.7820865974627469";  // Default vazio

$sql12 = "SELECT localizacao_url FROM contactos LIMIT 1";
$result = $conn->query($sql12);

if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $map_url = $row['localizacao_url'];
}

$sql_contactos = "SELECT id, opcao_, url_, classe_1 FROM submenu_contactos ORDER BY id ASC";
$resultado1 = mysqli_query($conn, $sql_contactos);

if (!$result2) {
  die("Erro na consulta submenu_autarquia: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contactos - Junta de Freguesia de Barreiro de Besteiros</title>
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&family=Domine:wght@400..700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="CSS/estilo1.css">
  <link rel="stylesheet" href="CSS/contactos.css">
  <link rel="stylesheet" href="CSS/rodape.css">
  <link rel="stylesheet" href="CSS/style1.css">

  <style>
    html {
      scroll-behavior: smooth !important;
    }

    #contactos {
      scroll-margin-top: 40px !important;
    }

    body {
      background: #f5f7fb;
      padding: 0px 0;
      color: #2b2b2b;
    }

    .page-wrapper {
      margin: 0 auto;
      padding: 0 30px;
    }

    /* Hero Section */
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

    .texto .titulo {
      text-align: center;
      font-size: 39px;
      font-weight: bold;
      margin-top: 180px;
    }

    .texto .descricao {
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      margin-top: 0;
    }

    /* Breadcrumbs */
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
      'horario' => 'Barreiro: Terças das 19h00 - 20h30 Tourigo: Sextas Feiras das 13h-14h',
      'email' => 'junta.barreiro.tourigo@gmail.com',
      'sede' => 'R. Nossa Senhora dos Aflitos, 121, Barreiro de Besteiros',
    ];
  }
  ?>
  <?php
  // Mostrar mensagem flash se vier do envio — usa classes existentes em login.css
  if (isset($_GET['status'])) {
    $status = $_GET['status'];
    if ($status === 'success') {
      echo '<div id="flash-message" class="sucesso-mensagem"><span class="sucesso-icone"><i class="bi bi-check-circle-fill"></i></span><span class="sucesso-texto">Mensagem enviada com sucesso</span><button type="button" class="flash-close" aria-label="Fechar aviso">×</button></div>';
    } else {
      echo '<div id="flash-message" class="erro-mensagem"><span class="erro-icone"><i class="bi bi-exclamation-circle-fill"></i></span><span class="erro-texto">Ocorreu um erro ao enviar a mensagem</span><button type="button" class="flash-close" aria-label="Fechar aviso">×</button></div>';
    }
  }
  ?>

  <style>
    /* Mensagens flash (mesmo visual do login) */
    .sucesso-mensagem,
    .erro-mensagem {
      position: fixed;
      top: 220px;
      left: 50%;
      transform: translate(-50%, 0);
      margin: 0;
      z-index: 9999;
      box-sizing: border-box;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      padding: 10px 16px;
      font-weight: 700;
      font-size: 15px;
      border-radius: 6px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
      animation: slideDown 0.45s ease-out;
      max-width: 90%;
      width: 360px;
      text-align: center;
    }

    .sucesso-mensagem {
      background-color: #d4edda;
      color: #155724;
      border: 2px solid #c3e6cb;
    }

    .erro-mensagem {
      background-color: #f8d7da;
      color: #721c24;
      border: 2px solid #f5c6cb;
    }

    /* Ícones e texto dentro da mensagem */
    .sucesso-icone,
    .erro-icone {
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }

    .sucesso-icone i {
      color: #155724;
    }

    .erro-icone i {
      color: #c82333;
    }

    .sucesso-texto,
    .erro-texto {
      flex: 1;
      text-align: left;
      padding-left: 8px;
      font-weight: 700;
    }

    /* Botão fechar (X) no lado direito */
    .flash-close {
      border: none;
      background: transparent;
      color: #777;
      font-size: 20px;
      line-height: 1;
      cursor: pointer;
      padding: 2px 6px;
      border-radius: 4px;
      transition: color .18s ease, background-color .18s ease;
    }

    .flash-close:hover {
      color: #e07a7a;
      /* vermelho claro no hover */
      background: rgba(224, 122, 122, 0.06);
    }

    .sucesso-mensagem.removendo,
    .erro-mensagem.removendo {
      animation: slideUp 0.45s ease-in forwards;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translate(-50%, -40px);
      }

      to {
        opacity: 1;
        transform: translate(-50%, 0);
      }
    }

    @keyframes slideUp {
      from {
        opacity: 1;
        transform: translate(-50%, 0);
      }

      to {
        opacity: 0;
        transform: translate(-50%, -40px);
      }
    }
  </style>

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
    </div><!-- fecha menu -->

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

  <style>
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

    .texto .titulo {
      text-align: center;
      font-size: 39px;
      font-weight: bold;
      margin-top: 180px;
    }

    .texto .descricao {
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      margin-top: 0;
    }
  </style>
  <!----------------------------------------------------------------------------------------------------------------------------------->
  <section class="img_inicial">
    <div class="texto">
      <?php if ($resultHeader && $resultHeader->num_rows > 0): ?>
        <?php while ($row = $resultHeader->fetch_assoc()): ?>
          <h1 class="titulo"><?= htmlspecialchars($row['titulo']) ?></h1>
          <h2 class="descricao"><?= nl2br(htmlspecialchars($row['descricao'])) ?></h2>
        <?php endwhile; ?>
      <?php else: ?>
        <p>Não existem imagens registadas.</p>
      <?php endif; ?>
    </div>
  </section>

  <main class="breadcrumbs">
    <a href="index.php">Ínicio</a> <i class="fa-solid fa-angle-right"></i> Contactos
  </main>

  <style>
    .breadcrumbs a {
      color: #fff;
      text-decoration: none;
      transition: color 0.8s ease;
    }

    .breadcrumbs {
      position: relative;
      bottom: 70px;
      text-align: center;
      font-family: "Poppins", serif;
      font-size: 19px;
      color: #fff;
    }

    .breadcrumbs a:hover {
      color: #cedcdbff;
      box-shadow: 0 4px 6px -2px rgba(223, 219, 219, 0.25);
    }

    .breadcrumbs i {
      font-size: 16px;
    }
  </style>

  <?php
  include_once 'admin/database.php';
  $sql = "SELECT r.titulo_junta, c.morada, c.email, c.telefone_fixo, c.telemovel, c.horario_funcionamento 
        FROM rodape r 
        LEFT JOIN contactos c ON r.id_contacto = c.id 
        LIMIT 1";
  $result = $conn->query($sql);
  $contacto = ($result && $result->num_rows > 0) ? $result->fetch_assoc() : [];
  ?>

  <div class="page-wrapper">
    <section class="contact-section">
      <div class="contact-container">
        <div class="contact-info-box">
          <h1><?= htmlspecialchars($contacto['titulo_junta'] ?? 'Junta de Freguesia de Barreiro de Besteiros') ?></h1>
          <p class="contact-subtitle">Informações sobre nossos contactos</p>

          <div class="info-item">
            <div class="icon-circle"><i class="bi bi-geo-alt"></i></div>
            <div>
              <h3>Morada</h3>
              <p><?= nl2br(htmlspecialchars($contacto['morada'])) ?></p>
            </div>
          </div>

          <div class="info-item">
            <div class="icon-circle"><i class="bi bi-telephone"></i></div>
            <div>
              <h3>Telefone</h3>
              <?php if (!empty($contacto['telefone_fixo'])): ?>
                <p><?= htmlspecialchars($contacto['telefone_fixo']) ?></p>
              <?php endif; ?>
              <?php if (!empty($contacto['telemovel'])): ?>
                <p><?= htmlspecialchars($contacto['telemovel']) ?></p>
              <?php endif; ?>
              <p class="note">*Chamada para a rede fixa nacional</p>
              <p class="note">*Chamada para a rede móvel nacional</p>
            </div>
          </div>

          <div class="info-item">
            <div class="icon-circle"><i class="bi bi-envelope"></i></div>
            <div>
              <h3>Email</h3>
              <a href="mailto:<?= htmlspecialchars($contacto['email']) ?>"><?= htmlspecialchars($contacto['email']) ?></a>
            </div>
          </div>

          <div class="info-item">
            <div class="icon-circle"><i class="bi bi-clock"></i></div>
            <div>
              <h3>Horário</h3>
              <?= nl2br(htmlspecialchars($contacto['horario_funcionamento'])) ?>
            </div>
          </div>
        </div>

        <div class="contact-form-box">
          <h2>Formulário de Contacto</h2>
          <form id="formContacto" method="post" action="enviar_dados.php">
            <!-- Nome + Email -->
            <div class="row-2">
              <div class="field">
                <input type="text" id="nome" name="nome" required placeholder="">
                <label for="nome" class="label">Nome</label>
                <div class="underline"></div>
                <span class="error-message" id="error-nome"></span>
              </div>
              <div class="field">
                <input type="email" id="email" name="email" required placeholder="">
                <label for="email" class="label">Email</label>
                <div class="underline"></div>
                <span class="error-message" id="error-email"></span>
              </div>
            </div>

            <!-- Assunto -->
            <div class="field">
              <input type="text" id="assunto" name="assunto" required placeholder="">
              <label for="assunto" class="label">Assunto</label>
              <div class="underline"></div>
              <span class="error-message" id="error-assunto"></span>
            </div>

            <!-- Mensagem -->
            <div class="field field-mensagem">
              <textarea id="mensagem" name="mensagem" rows="5" placeholder=""></textarea>
              <label for="mensagem" class="label-mensagem-cinzenta" style="font-size: 18px !important; color: #777;">Mensagem</label>
              <span class="error-message" id="error-mensagem"></span>
            </div>

            <button class="send-btn" type="submit">Enviar Mensagem <i class="bi bi-chevron-double-right"></i></button>
          </form>
        </div>
      </div>
    </section>
  </div>

  <style>
    .map-wrapper {
      display: flex;
      justify-content: center;
      /* centro horizontal */
      align-items: center;
      /* centro vertical */
      min-height: 100vh;
      margin-top: -60px;
    }

    .map-wrapper iframe {
      width: 860px;
      max-width: 100%;
      /* para não rebentar em ecrãs pequenos */
      height: 450px;
      /* ajusta se quiseres mais alto/baixo */
      display: block;
      border-radius: 10px;
    }
  </style>

  <style>
    /* LABEL MENSAGEM - CINZENTA INICIAL, VERDE QUANDO TEM TEXTO */
    .label-mensagem-cinzenta {
      position: absolute !important;
      top: -13px !important;
      left: 0 !important;
      color: #777 !important;
      pointer-events: none !important;
      z-index: 10 !important;
      transition: color 0.3s ease !important;
    }

    .field-mensagem.has-content .label-mensagem-cinzenta {
      color: #054f18 !important;
    }

    .field-mensagem textarea:focus~.label-mensagem-cinzenta {
      color: #054f18 !important;
    }

    .field-mensagem {
      position: relative !important;
      padding-top: 17px !important;
    }

    .field-mensagem textarea {
      border: 2px solid #ccc !important;
      transition: all 0.3s ease !important;
      margin-top: 10px !important;
    }

    .field-mensagem {
      margin-top: 26px !important;
    }


    .field-mensagem textarea:focus {
      border-color: #054f18 !important;
      box-shadow: 0 0 12px rgba(5, 79, 24, 0.5) !important;
    }

    .field-mensagem::before {
      display: none !important;
    }

    /* Estilos para mensagens de erro - DEBAIXO DA LINHA */
    /* A mensagem aparece logo abaixo da linha (underline), sem afetar o input ou linha */
    .error-message {
      display: block;
      color: #d32f2f;
      font-size: 13px;
      margin-top: 0;
      margin-bottom: 0;
      padding-left: 0;
      padding-top: 4px;
      font-family: "Poppins", sans-serif;
      opacity: 0;
      transition: opacity 0.4s ease, transform 0.4s ease;
      transform: translateY(-5px);
      min-height: 18px;
      position: absolute;
      bottom: -22px;
      left: 0;
      width: 100%;
      line-height: 1.4;
      z-index: 1;
    }

    .error-message.show {
      opacity: 1;
      transform: translateY(0);
    }

    /* O input e a linha (underline) mantêm SEMPRE a mesma posição - SEM padding que afete */
    .field.has-error:not(.field-mensagem) {
      margin-bottom: 22px;
      overflow: visible;
    }

    /* Garantir que o input NUNCA muda de posição ou padding, mesmo quando há erro */
    .field.has-error input {
      padding: 8px 0 2px 0 !important;
      margin-bottom: 0 !important;
    }

    /* Garantir que o textarea também tem espaço para a mensagem de erro DEBAIXO */
    .field-mensagem.has-error {
      margin-bottom: 22px;
      padding-bottom: 0;
    }

    .field-mensagem .error-message {
      position: relative;
      bottom: auto;
      margin-top: 6px;
      padding-top: 0;
    }

    /* Quando NÃO há erro, não há espaço extra */
    .field:not(.has-error) {
      padding-bottom: 0;
    }

    /* Garantir que o underline e a linha de fundo SEMPRE ficam na mesma posição, independente de erro */
    .field .underline {
      bottom: 0 !important;
    }

    .field::before {
      bottom: 0 !important;
    }

    /* Criar espaço para a mensagem aparecer abaixo sem afetar o input - usar min-height em vez de padding */
    .field.has-error:not(.field-mensagem) {
      min-height: calc(100% + 22px);
    }

    .field input.error,
    .field textarea.error {
      border-color: #d32f2f !important;
    }

    .field input.error~.underline,
    .field textarea.error~.label {
      background: #d32f2f !important;
      color: #d32f2f !important;
    }

    .field textarea.error {
      border: 2px solid #d32f2f !important;
      box-shadow: 0 0 8px rgba(211, 47, 47, 0.3) !important;
    }

    .field input.valid,
    .field textarea.valid {
      border-color: #054f18 !important;
    }

    .field input.valid~.underline {
      background: #054f18 !important;
    }
  </style>

  <div class="map-wrapper" id="localizacao">
    <?php if ($map_url): ?>
      <iframe src="<?php echo htmlspecialchars($map_url); ?>"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    <?php else: ?>
      <p>Localização não configurada.</p>
    <?php endif; ?>
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

  <script src="JS/navbar.js"></script>
  <script src="JS/barra_pesquisa.js"></script>
  <script src="JS/dados.js"></script>

  <script>
    // Detectar quando textarea tem conteúdo
    const textarea = document.getElementById('mensagem');
    const fieldMensagem = document.querySelector('.field-mensagem');

    function updateLabelColor() {
      if (textarea.value.trim() !== '') {
        fieldMensagem.classList.add('has-content');
      } else {
        fieldMensagem.classList.remove('has-content');
      }
    }

    // Verificar ao carregar (caso já tenha conteúdo)
    updateLabelColor();

    // Verificar ao digitar
    textarea.addEventListener('input', updateLabelColor);

    // Verificar ao perder o foco
    textarea.addEventListener('blur', updateLabelColor);

    // Auto-hide flash message after 5s — use existing animation (.removendo) from login.css
    const flash = document.getElementById('flash-message');
    if (flash) {
      // Remove o parâmetro status da URL para evitar re-submissão no refresh
      if (window.history && window.history.replaceState) {
        const cleanUrl = window.location.pathname;
        window.history.replaceState(null, '', cleanUrl);
      }

      setTimeout(() => {
        flash.classList.add('removendo');
        setTimeout(() => {
          if (flash && flash.parentNode) flash.parentNode.removeChild(flash);
        }, 500);
      }, 5000);
    }

    // Fechar ao clicar no X: anima e remove
    document.querySelectorAll('.flash-close').forEach(btn => {
      btn.addEventListener('click', function(e) {
        const msg = e.currentTarget.closest('.sucesso-mensagem, .erro-mensagem');
        if (!msg) return;
        msg.classList.add('removendo');
        setTimeout(() => {
          if (msg && msg.parentNode) msg.parentNode.removeChild(msg);
        }, 500);
      });
    });
  </script>

</html>