<?php
include 'admin/database.php'; // Caminho para o teu ficheiro de ligação

if (!$conn) {
  die("Erro de conexão: " . mysqli_connect_error());
}

?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galeria de Fotos - Junta de Freguesia de Barreiro de Besteiros e Tourigo</title>
  <link rel="icon" href="IMAGENS/LOGO_U_F_BB.png" type="image/x-icon">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
      font-family: Arial, sans-serif;
      background: #fff;
      margin: 0;
      padding: 0;
      line-height: 1.6;
    }

    .imagem-principal {
      width: 100%;
      max-height: 270px;
      object-fit: cover;
      display: block;
      margin-bottom: 30px;
    }

    .conteudo-artigo {
      max-width: 950px;
      margin: 0 auto 24px auto;
      padding: 0 36px;
    }

    .meta-info {
      font-size: 14px;
      color: #666;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .meta-info .data {
      font-weight: bold;
    }

    .meta-info span:not(:last-child):after {
      content: "·";
      margin: 0 9px;
      color: #bbb;
      font-weight: normal;
    }

    .titulo-principal {
      font-size: 2em;
      font-weight: 600;
      color: #1a1a1a;
      margin-bottom: 18px;
      line-height: 1.2;
      letter-spacing: -1px;
    }

    .texto-corpo {
      font-size: 1.06em;
      color: #353535;
      margin-bottom: 24px;
      text-align: justify;
    }

    .galeria-imagens {
      display: flex;
      gap: 18px;
      margin-bottom: 22px;
    }

    .imagem-pequena {
      width: 250px;
      height: 120px;
      object-fit: cover;
      border-radius: 8px;
      flex-shrink: 0;
    }

    .secao-partilhar {
      display: flex;
      align-items: center;
      gap: 13px;
      margin-bottom: 34px;
    }

    .secao-partilhar span {
      font-weight: 500;
      color: #333;
      font-size: 16px;
      margin-right: 3px;
    }

    .botao-social {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      color: #fff;
      font-size: 16px;
      margin-right: 4px;
      transition: filter 0.15s;
    }

    .facebook {
      background: #1877f2;
    }

    .twitter {
      background: #1da1f2;
    }

    .google {
      background: #ea4335;
    }

    .linkedin {
      background: #0a66c2;
    }

    .botao-social:hover {
      filter: brightness(1.1);
    }

    .navegacao {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 24px;
    }

    .botao-nav {
      width: 38px;
      height: 38px;
      border: 1.5px solid #bbb;
      background: transparent;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: #555;
      font-size: 18px;
      transition: border-color 0.2s, color 0.2s;
    }

    .botao-nav:hover {
      border-color: #0066cc;
      color: #0066cc;
    }

    .anterior {
      transform: rotate(180deg);
    }

    @media (max-width: 900px) {
      .conteudo-artigo {
        padding: 0 10px;
      }

      .galeria-imagens {
        flex-direction: column;
        align-items: center;
      }

      .imagem-pequena {
        width: 97vw;
        max-width: 300px;
      }
    }
  </style>


</head>

<body>


  <!-- Navbar -->
  <div class="navbar">
    <div class="logo">
      <a href="index.php">
        <img src="IMAGENS/logo_fb-removebg-preview.ico" alt="Brasão da Junta de Freguesia de Barreiro de Besteiros e Tourigo">
      </a>
      <div class="titulo">
        <strong>
          <p><a href="index.php"> Junta de Freguesia de Barreiro <br> de Besteiros e Tourigo </a></p>
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



  <div class="container">
    <img src="IMAGENS/rua_tresval_principal.jpg" alt="Rua de Tresval" class="imagem-principal">

    <div class="conteudo-artigo">
      <div class="meta-info">
        <span class="data">04/10/2022</span>
        <span class="categoria">Em Eventos</span>
        <span class="categoria">Por Gião</span>
      </div>

      <h1 class="titulo-principal">ALARGAMENTO DA RUA DE TRESVAL CONCLUÍDO</h1>

      <div class="texto-corpo">
        <p>No seguimento da conclusão do alargamento da Rua de Santo Estevão encontra-se agora concluído o alargamento da Rua de Tresval, em Gião. Estas obras foram executadas pela Câmara Municipal e reclamadas pela Junta de Freguesia e permitiram o desengargalamento e o trânsito nestes arruamentos.</p>

        <br>

        <p>Na obra da Rua de Tresval foram executados os seguintes trabalhos: construção de muros de pedra, passeios, repavimentação da via, expansão da rede de águas pluviais e ainda a instalação de duas lombas de baixo perfil com o objetivo de reduzir a velocidade nesta rua. Foram também colocados dois contentores de lixo para facilitar o serviço de recolha dos resíduos urbanos, melhorando a capacidade estrutural desta vila histórica com as medidas locais e melhorias mencionadas até aqui. Foram também instalados tubos através das edificações permitindo estacionamento facilitado.</p>

        <br>

        <p>A Junta de Freguesia de Gião endereça um agradecimento especial aos proprietários dos terrenos pela cedência gratuita do domínio público que permitiu este importante desengargalamento da via, reconhecimento da boa colaboração na boa execução, à 3ª anterior foi Póvoa e o 3º abaixo Uma estrutura. A Câmara Municipal de Vila de Conde apoiou assim ainda a conclusão da obra e contribuiu determinantemente melhorias nos arruamentos indicados.</p>
      </div>

      <div class="galeria-imagens">
        <img src="IMAGENS/rua_tresval1.jpg" alt="Detalhes da obra" class="imagem-pequena">
        <img src="IMAGENS/rua_tresval2.jpg" alt="Rua finalizada" class="imagem-pequena">
        <img src="IMAGENS/rua_tresval3.jpg" alt="Vista geral" class="imagem-pequena">
      </div>

      <div class="secao-partilhar">
        <span class="partilhar-texto">Partilhar</span>
        <a href="#" class="botao-social facebook">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#" class="botao-social twitter">
          <i class="fab fa-twitter"></i>
        </a>
        <a href="#" class="botao-social google">
          <i class="fab fa-google-plus-g"></i>
        </a>
        <a href="#" class="botao-social linkedin">
          <i class="fab fa-linkedin-in"></i>
        </a>
      </div>

      <div class="navegacao">
        <button class="botao-nav anterior">
          <i class="fas fa-arrow-right"></i>
        </button>
        <button class="botao-nav seguinte">
          <i class="fas fa-arrow-right"></i>
        </button>
      </div>
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