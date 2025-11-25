  <?php
  include 'admin/database.php'; // Caminho para o teu ficheiro de ligação

  if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
  }

  $where = [];
  if (!empty($_GET['q'])) {
    $q = $conn->real_escape_string($_GET['q']);
    $where[] = "(titulo_card LIKE '%$q%' OR categoria LIKE '%$q%')";
  }

  if (isset($_GET['ano']) && $_GET['ano'] !== '' && $_GET['ano'] !== 'all') {
    $ano = (int)$_GET['ano'];
    $where[] = "ano = $ano";
  }

  if (isset($_GET['categoria']) && $_GET['categoria'] !== '' && $_GET['categoria'] !== 'all') {
    $categoria = $conn->real_escape_string($_GET['categoria']);
    $where[] = "categoria = '$categoria'";
  }

  $whereSQL = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

  $sql = "SELECT id, titulo_card, `data`, img_card, categoria FROM galeriafotos $whereSQL";
  $resultGaleria  = $conn->query($sql);

  $sqlHeaderQuery = "SELECT tituloprincipal, descricao FROM galeriafotos LIMIT 1";
  $resultHeaderQuery = $conn->query($sqlHeaderQuery);

  $sqlHeader = "SELECT tituloprincipal, descricao FROM galeriafotos";
  $resultHeader = $conn->query($sqlHeader);
  $header = $resultHeader && $resultHeader->num_rows > 0 ? $resultHeader->fetch_assoc() : null;

  $sql1 = "SELECT id, logo, logo_titulo FROM logo";
  $res1 = mysqli_query($conn, $sql1);

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
    <title>Galeria de Fotos - Junta de Freguesia de Barreiro de Besteiros e Tourigo</title>
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
        padding-top: 200px;
        background-color: #F7FCFE;
      }

      .img_inicial {
        position: relative;
        background: url('IMAGENS/fundo_bodofesta.jpg') center center/cover no-repeat;
        height: 450px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-left: 0;
        margin-top: -190px;
        z-index: 0;
      }

      .img_inicial::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(9, 17, 29, 0.68);
        /* filtro azul escuro */
        z-index: 1;
      }

      .img_inicial .titulo {
        text-align: center;
        font-size: 39px;
        font-weight: bold;
        margin-top: 180px;
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
        <i class="bi bi-search" style="font-size:30px; margin-left:40px; cursor:pointer; position:absolute; bottom:36px;"></i>
      </div>
    </div>

    <section class="img_inicial">
      <div class="texto">
        <?php if ($resultHeaderQuery && $resultHeaderQuery->num_rows > 0): ?>
          <?php while ($headerRow = $resultHeaderQuery->fetch_assoc()): ?>
            <h1 class="tituloprincipal"><?= htmlspecialchars($headerRow['tituloprincipal']) ?></h1>
            <h2 class="descricao"><?= nl2br(htmlspecialchars($headerRow['descricao'])) ?></h2>
          <?php endwhile; ?>
        <?php else: ?>
          <p>Não existem imagens registadas.</p>
        <?php endif; ?>
      </div>
    </section>

    <main class="breadcrumbs">
      <a href="index.php">Início</a>
      <i class="fa-solid fa-angle-right"></i>
      <a href="galeria.php">
        <?= $header ? htmlspecialchars($header['tituloprincipal']) : 'Galeria' ?>
      </a>
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

    <footer class="footer-meruge" style="margin-top: 740px;">
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



    <!--------------SCRIPT ANO ------------------>
    <script>
      (function() {
        const select = document.getElementById('customSelectAno');
        if (!select) return;

        const selected = select.querySelector('.selected');
        const arrow = select.querySelector('.arrow');
        const options = select.querySelector('.options');
        const hiddenInput = select.querySelector('input[type="hidden"]');

        // Inicializar texto se já houver valor
        if (hiddenInput.value) {
          const initialOption = Array.from(options.children).find(opt => opt.dataset.value === hiddenInput.value);
          if (initialOption) selected.textContent = initialOption.textContent;
          if (hiddenInput.value === "all") selected.textContent = "Ano";
        }

        // Abrir/fechar lista (clicar no campo OU na seta)
        [selected, arrow].forEach(el => {
          el.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = select.classList.toggle('open');
            select.setAttribute('aria-expanded', isOpen);
          });
        });

        // Selecionar opção
        options.querySelectorAll('li').forEach(option => {
          option.addEventListener('click', () => {
            selected.textContent = option.textContent;
            hiddenInput.value = option.dataset.value !== 'all' ? option.dataset.value : '';
            select.classList.remove('open');
            select.setAttribute('aria-expanded', false);
          });
        });

        // Fechar dropdown se clicar fora
        document.addEventListener('click', e => {
          if (!select.contains(e.target)) {
            select.classList.remove('open');
            select.setAttribute('aria-expanded', false);
          }
        });

        // Acessibilidade por teclado
        select.addEventListener('keydown', e => {
          const focusable = Array.from(options.querySelectorAll('li'));
          let index = focusable.indexOf(document.activeElement);

          if (!select.classList.contains('open') && (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ')) {
            e.preventDefault();
            select.classList.add('open');
            select.setAttribute('aria-expanded', true);
            focusable[0].focus();
            return;
          }

          if (select.classList.contains('open')) {
            if (e.key === 'ArrowDown') {
              e.preventDefault();
              index = (index + 1) % focusable.length;
              focusable[index].focus();
            } else if (e.key === 'ArrowUp') {
              e.preventDefault();
              index = (index - 1 + focusable.length) % focusable.length;
              focusable[index].focus();
            } else if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              if (focusable.includes(document.activeElement)) document.activeElement.click();
            } else if (e.key === 'Escape') {
              e.preventDefault();
              select.classList.remove('open');
              select.setAttribute('aria-expanded', false);
              selected.focus();
            }
          }
        });
      })();
    </script>

    <!--------------SCRIPT ATUALIZAR FILTROS ------------------>
    <script>
      function atualizarFiltros() {
        const container = document.getElementById("filtrosSelecionados");
        container.innerHTML = "";
        const anoInput = document.querySelector("input[name='ano']");
        const categoriaInput = document.querySelector("input[name='categoria']");

        const categoria = categoriaInput.value;

        if (categoria) {
          const chip = criarChip("Categoria: " + categoria, () => {
            categoriaInput.value = "";
            // reset placeholder do mês
            document.querySelector("#customSelectCategoria .selected").textContent = "Categoria";
            atualizarFiltros();
          });
          container.appendChild(chip);
        }

        if (ano) {
          const chip = criarChip("Ano: " + ano, () => {
            anoInput.value = "";
            // reset placeholder do ano
            document.querySelector("#customSelectAno .selected").textContent = "Anos";
            atualizarFiltros();
          });
          container.appendChild(chip);
        }

        // se tiver filtros ativos, empurra o form um pouco
        const form = document.getElementById("filtrosForm");
        form.style.marginRight = container.children.length > 0 ? "60px" : "120px";
      }


      function criarChip(texto, onRemove) {
        const div = document.createElement("div");
        div.className = "filtro-ativo";
        div.innerHTML = `<span>${texto}</span>`;
        const btn = document.createElement("button");
        btn.innerHTML = "×";
        btn.onclick = onRemove;
        div.appendChild(btn);
        return div;
      }

      // atualizar quando selects mudarem
      document.querySelectorAll(".custom-select .options li").forEach(li => {
        li.addEventListener("click", () => {
          const parent = li.closest(".custom-select");
          const inputHidden = parent.querySelector("input[type='hidden']");
          inputHidden.value = li.dataset.value !== "all" ? li.dataset.value : "";
          atualizarFiltros();
        });
      });

      // ao carregar a página (se já tiver filtros ativos via GET)
      window.addEventListener("DOMContentLoaded", atualizarFiltros);
    </script>

    <!--------------SCRIPT MOVER FORM QUANDO HÁ FILTROS ATIVOS ------------------>
    <script>
      function updateMoveForm() {
        const anoVal = document.querySelector('#customSelectAno input').value;
        const mesVal = document.querySelector('#customSelectCategoria input').value;
        // Pode adicionar outros filtros se precisar

        const form = document.getElementById('filtrosForm');

        // Se pelo menos um filtro estiver selecionado
        if ((anoVal && anoVal !== 'all') || (mesVal && mesVal !== 'all')) {
          form.classList.add('move-form');
        } else {
          form.classList.remove('move-form');
        }
      }

      // Ligar à seleção dos filtros customizados
      document.querySelectorAll('.custom-select .options li').forEach(li => {
        li.addEventListener('click', () => {
          // Pequeno timeout para garantir input hidden foi atualizado
          setTimeout(updateMoveForm, 10);
        });
      });

      // Chamar na carga da página também (útil para filtros de GET)
      document.addEventListener('DOMContentLoaded', updateMoveForm);
    </script>

    <script>
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


        if (ano) {
          const chip = criarChip("Ano: " + ano, () => {
            anoInput.value = "";
            // reset placeholder do ano
            document.querySelector("#customSelectAno .selected").textContent = "Anos";
            atualizarFiltros();
          });
          container.appendChild(chip);
        }

        if (mes) {
          const chip = criarChip("Mês: " + mes, () => {
            mesInput.value = "";
            // reset placeholder do mês
            document.querySelector("#customSelectMes .selected").textContent = "Mês";
            atualizarFiltros();
          });
          container.appendChild(chip);
        }

        // se tiver filtros ativos, empurra o form um pouco
        const form = document.getElementById("filtrosForm");
        form.style.marginRight = container.children.length > 0 ? "60px" : "120px";
      }


      function criarChip(texto, onRemove) {
        const div = document.createElement("div");
        div.className = "filtro-ativo";
        div.innerHTML = `<span>${texto}</span>`;
        const btn = document.createElement("button");
        btn.innerHTML = "×";
        btn.onclick = onRemove;
        div.appendChild(btn);
        return div;
      }

      // atualizar quando selects mudarem
      document.querySelectorAll(".custom-select .options li").forEach(li => {
        li.addEventListener("click", () => {
          const parent = li.closest(".custom-select");
          const inputHidden = parent.querySelector("input[type='hidden']");
          inputHidden.value = li.dataset.value !== "all" ? li.dataset.value : "";
          atualizarFiltros();
        });
      });

      // ao carregar a página (se já tiver filtros ativos via GET)
      window.addEventListener("DOMContentLoaded", atualizarFiltros);
    </script>

    <!--------------SCRIPT FILTROS SELECIONADOS ------------------>
    <script>
      function atualizarFiltrosSelecionados() {
        const filtrosDiv = document.getElementById('filtrosSelecionados');
        const ano = document.querySelector('#customSelectAno input').value;
        const categoria = document.querySelector('#customSelectCategoria input').value;


        filtrosDiv.innerHTML = ''; // Limpa antes
        if (ano && ano !== 'all') {
          const chip = document.createElement('div');
          chip.className = 'filtro-chip';
          chip.innerHTML = `Ano: ${ano} <span class="close" title="Remover filtro">&times;</span>`;
          chip.querySelector('.close').onclick = function() {
            document.querySelector('#customSelectAno input').value = '';
            atualizarFiltrosSelecionados();
            // Opcional: reset visual do select custom!
            document.querySelector('#customSelectAno .selected').textContent = 'Anos';
          };
          filtrosDiv.appendChild(chip);
        }
        if (categoria && categoria !== 'all') {
          const chip = document.createElement('div');
          chip.className = 'filtro-chip';
          chip.innerHTML = `Categoria: ${categoria} <span class="close" title="Remover filtro">&times;</span>`;
          chip.querySelector('.close').onclick = function() {
            document.querySelector('#customSelectCategoria input').value = '';
            atualizarFiltrosSelecionados();
            // Reset visual do select custom
            document.querySelector('#customSelectCategoria .selected').textContent = 'Categoria';
          };
          filtrosDiv.appendChild(chip);
        }

      }
      document.querySelectorAll('.custom-select .options li').forEach(li => {
        li.addEventListener('click', () => {
          setTimeout(atualizarFiltrosSelecionados, 20);
        });
      });
      window.addEventListener('DOMContentLoaded', atualizarFiltrosSelecionados);
    </script>

    <!--------------SCRIPT CATEGORIA ------------------>
    <script>
      (function() {
        const select = document.getElementById('customSelectCategoria');
        if (!select) return;

        const selected = select.querySelector('.selected');
        const arrow = select.querySelector('.arrow');
        const options = select.querySelector('.options');
        const hiddenInput = select.querySelector('input[type="hidden"]');

        // Inicializar texto se já houver valor
        if (hiddenInput.value) {
          const initialOption = Array.from(options.children).find(opt => opt.dataset.value === hiddenInput.value);
          if (initialOption) selected.textContent = initialOption.textContent;
          if (hiddenInput.value === "all") selected.textContent = "Categoria";
        }

        // Abrir/fechar lista (clicar no campo OU na seta)
        [selected, arrow].forEach(el => {
          el.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = select.classList.toggle('open');
            select.setAttribute('aria-expanded', isOpen);
          });
        });

        // Selecionar opção
        options.querySelectorAll('li').forEach(option => {
          option.addEventListener('click', () => {
            selected.textContent = option.textContent;
            hiddenInput.value = option.dataset.value !== 'all' ? option.dataset.value : '';
            select.classList.remove('open');
            select.setAttribute('aria-expanded', false);
          });
        });

        // Fechar dropdown se clicar fora
        document.addEventListener('click', e => {
          if (!select.contains(e.target)) {
            select.classList.remove('open');
            select.setAttribute('aria-expanded', false);
          }
        });

        // Acessibilidade por teclado
        select.addEventListener('keydown', e => {
          const focusable = Array.from(options.querySelectorAll('li'));
          let index = focusable.indexOf(document.activeElement);

          if (!select.classList.contains('open') && (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ')) {
            e.preventDefault();
            select.classList.add('open');
            select.setAttribute('aria-expanded', true);
            focusable[0].focus();
            return;
          }

          if (select.classList.contains('open')) {
            if (e.key === 'ArrowDown') {
              e.preventDefault();
              index = (index + 1) % focusable.length;
              focusable[index].focus();
            } else if (e.key === 'ArrowUp') {
              e.preventDefault();
              index = (index - 1 + focusable.length) % focusable.length;
              focusable[index].focus();
            } else if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              if (focusable.includes(document.activeElement)) document.activeElement.click();
            } else if (e.key === 'Escape') {
              e.preventDefault();
              select.classList.remove('open');
              select.setAttribute('aria-expanded', false);
              selected.focus();
            }
          }
        });
      })();
    </script>

  </body>

  </html>