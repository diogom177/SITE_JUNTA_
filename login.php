<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'junta';

$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql_logo = "SELECT logo, titulo_junta FROM `admin` LIMIT 1";
$res1 = mysqli_query($conn, $sql_logo);

if ($res1 && mysqli_num_rows($res1) > 0) {
  $row = mysqli_fetch_assoc($res1);
  $logo = $row['logo'] ?? 'default-logo.png';
  $titulo_junta = $row['titulo_junta'] ?? 'Junta de Freguesia';
} else {
  $logo = 'default-logo.png';
  $titulo_junta = 'Junta de Freguesia de Barreiro de Besteiros';
}

$sql = "SELECT id, opcao, link, classe FROM submenu_freguesia ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql);

$sql = "SELECT id, `option`, `url`, classse, tipo, parent FROM submenu_autarquia ORDER BY id ASC";
$result2 = mysqli_query($conn, $sql);

$sql_contactos = "SELECT id, opcao_, url_, classe_1 FROM submenu_contactos ORDER BY id ASC";
$resultado1 = mysqli_query($conn, $sql_contactos);

if (!$result2 || !$resultado || !$resultado1) {
  die("Erro na consulta: " . mysqli_error($conn));
}

$sql = "SELECT id, img, titulo FROM `login`";
$result3 = mysqli_query($conn, $sql);

if ($result3 = mysqli_fetch_assoc($result3)) {
  $logo = $result3['img'];
  $logo_titulo = $result3['titulo'];
}

session_start();
$flash_error = $_SESSION['flash_error'] ?? '';
$flash_success = $_SESSION['flash_success'] ?? '';
$flash_error_class = $_SESSION['flash_error_class'] ?? '';
unset($_SESSION['flash_error']);
unset($_SESSION['flash_success']);
unset($_SESSION['flash_error_class']);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $Username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($Username === '' || $password === '') {
    $_SESSION['flash_error'] = 'Preencha o utilizador/email e a palavra-passe.';
    header('Location: login.php');
    exit();
  } else {
    // Buscar o utilizador pelo email
    $sql = "SELECT id_admin, email, password FROM admin WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $Username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
      // Login OK
      $_SESSION['admin_username'] = $user['email'];
      $_SESSION['admin_id']       = $user['id_admin'];

      header('Location: admin/backoffice.php');
      exit();
    } else {
      // Falhou o login
      $_SESSION['flash_error'] = 'Credenciais inválidas. Por favor verifique os dados novamente!';
      header('Location: login.php');
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Junta de Freguesia de Barreiro de Besteiros</title>
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
  <style>
    .popup-mensagem {
      font-family: "Raleway", Arial, sans-serif;
      font-size: 15px;
      max-width: 640px;
      width: 80%;
      box-sizing: border-box;
      padding: 12px 16px;
      animation: slideDown 0.5s ease-out;
      z-index: 1000;
    }
  </style>

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
  </div> <!-- fecha navbar -->

  <?php if ($flash_error): ?>
    <div class="erro-mensagem <?php if ($flash_error_class) echo ' ' . htmlspecialchars($flash_error_class); ?>">
      <span class="erro-icone">
        <i class="bi bi-exclamation-circle-fill"></i>
      </span>

      <span class="erro-texto">
        <?= htmlspecialchars($flash_error) ?>
      </span>

      <button class="erro-fechar" type="button"
        onclick="this.parentElement.classList.add('removendo')">
        ×
      </button>
    </div>
  <?php endif; ?>

  <?php if ($flash_success): ?>
    <div class="sucesso-mensagem">
      <?= htmlspecialchars($flash_success) ?>
    </div>
  <?php endif; ?>

  <!--FORM DE LOGIN-->
  <div class="container">
    <div class="heading"><?= nl2br($result3['titulo']) ?></div>
    <img src="IMAGENS/<?= htmlspecialchars($result3['img']) ?>" class="logo">

    <form class="form" method="POST" action="login.php">

      <div class="input-field">
        <i class="bi person bi-person-fill"></i>
        <input
          required
          autocomplete="off"
          type="text"
          name="username"
          id="username"
          autofocus />
        <label for="username">Endereço de Email</label>
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
        <button type="button" class="toggle-pass" data-target="password" aria-label="Mostrar palavra-passe" title="Mostrar/Ocultar" style="background:transparent;border:0;cursor:pointer;position:absolute;right:18px;top:34%;transform:translateY(-50%);color:#666;font-size:18px">
          <i class="bi bi-eye"></i>
        </button>
      </div>

      <div class="ligacao">
        <a href="recuperar_password.php">Esqueceu-se da palavra-passe?</a>
      </div>

      <div class="btn-container">
        <button type="submit" class="btn">Entrar</button>
      </div>
    </form>
  </div>
  <!--FIM DO FORM DE LOGIN-->

  <script src="JS/navbar.js"></script>
  <script src="JS/submenulateral.js"></script>

  <!-- Toggle password visibility script -->
  <script>
    // Toggle password visibility (reutilizável)
    (function() {
      function toggleHandler(e) {
        var btn = e.currentTarget;
        var id = btn.getAttribute('data-target');
        var input = document.getElementById(id);
        if (!input) return;
        if (input.type === 'password') {
          input.type = 'text';
          btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
        } else {
          input.type = 'password';
          btn.innerHTML = '<i class="bi bi-eye"></i>';
        }
      }
      document.querySelectorAll('.toggle-pass').forEach(function(b) {
        b.addEventListener('click', toggleHandler);
      });
    })();
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Desaparece mensagens após 3.2 segundos
      setTimeout(function() {
        const mensagens = document.querySelectorAll('.erro-mensagem, .sucesso-mensagem');
        mensagens.forEach(msg => {
          msg.classList.add('removendo');
          setTimeout(() => msg.remove(), 500); // espera a animação slideUp
        });
      }, 3200);
    });
  </script>
  <script src="JS/barra_pesquisa.js"></script>
</body>

</html>