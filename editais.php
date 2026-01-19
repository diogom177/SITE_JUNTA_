<?php
include 'admin/database.php';

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

$sqlHeader = "SELECT titulo_pagina, descricao, img_banner FROM editais LIMIT 1";
$resultHeader = $conn->query($sqlHeader);

$imgBanner = 'junta.jpg';
if ($resultHeader && $resultHeader->num_rows > 0) {
    $row = $resultHeader->fetch_assoc();
    $imgBanner = $row['img_banner'];
}

if (!$res1 || !$resultado1 || !$result2 || !$resultHeader) {
    die("Erro numa das consultas: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editais - Junta de Freguesia de Barreiro de Besteiros</title>

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
    <link rel="stylesheet" href="CSS/estilo1.css">
    <link rel="stylesheet" href="CSS/noticia.css">
    <link rel="stylesheet" href="CSS/style.css">

    <style>
        .page-wrapper {
            opacity: 1;
            transform: scale(1);
            transition: opacity 0.65s ease, transform 0.55s ease;
        }

        .page-wrapper.fade-out {
            opacity: 0;
            transform: scale(0.998);
        }

        .img_inicial {
            position: relative !important;
            background: url('IMAGENS/<?= $imgBanner ?>') center / cover no-repeat;
            height: 430px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-left: 0;
            z-index: 0 !important;
        }

        .img_inicial::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(12, 23, 39, 0.68);
            z-index: -1 !important;
        }

        .texto {
            text-align: center !important;
            position: relative;
            z-index: 2000 !important;
            color: #eae1e1ff;
            margin-bottom: 68px;
            font-family: "Cabin", sans-serif;
            font-optical-sizing: auto;
            font-weight: 500;
        }

        .texto .tituloprincipal {
            text-align: center;
            font-size: 39px;
            font-weight: bold;
            margin-top: 30px;
        }

        .texto .descricao2 {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 0;
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
    </div> <!-- fecha navbar -->

    <section class="img_inicial">
        <div class="texto">
            <?php
            mysqli_data_seek($resultHeader, 0);
            if ($row_header = $resultHeader->fetch_assoc()):
            ?>
                <h1 class="tituloprincipal"><?= htmlspecialchars($row_header['titulo_pagina']) ?></h1>
                <h2 class="descricao2"><?= nl2br(htmlspecialchars($row_header['descricao'])) ?></h2>
            <?php else: ?>
                <h1 class="tituloprincipal">Atas</h1>
                <h2 class="descricao2">Aceda às atas das reuniões do executivo da Junta de Freguesia</h2>
            <?php endif; ?>
        </div>
    </section>
    <style>
        .breadcrumbs a {
            color: #fff;
            text-decoration: none;
            transition: color 0.8s ease;
        }

        .breadcrumbs {
            position: relative;
            bottom: 90px;
            text-align: center;
            font-family: "Poppins", serif;
            font-size: 19px;
            z-index: 2000 !important;
        }

        .breadcrumbs a:hover {
            color: #57c5b6ff;
            box-shadow: 0 4px 6px -2px rgba(0, 0, 0, 0.25);
        }

        .breadcrumbs i {
            font-size: 16px;
        }
    </style>

    <main class="breadcrumbs">
        <a href="index.php">Ínicio</a> <i class="fa-solid fa-angle-right"></i> <a href="editais.php"> Editais</a>
    </main>

    <section class="atas">
        <div class="atas-header">
            <h1>Editais</h1>
            <span class="atas-update">Última Atualização 14 fevereiro, 2024</span>
        </div>

        <!-- Bloco de um ano -->
        <div class="ano-atas">
            <button class="ano-toggle">
                <span>Atas 2025</span>
                <span class="icon">+</span>
            </button>

            <div class="ano-content">
                <div class="tabela-header">
                    <span>Nome do documento</span>
                    <span>Data</span>
                    <span></span>
                </div>

                <div class="linha-doc">
                    <span class="tipo">PDF</span>
                    <span class="nome">Ata instalação Assembleia Municipal mandato 2025-2029</span>
                    <span class="data">24 - 10 - 2025</span>
                    <a href="PDFS/ata1.pdf" target="_blank" class="btn-download">⬇</a>
                </div>

                <div class="linha-doc">
                    <span class="tipo">PDF</span>
                    <span class="nome">Ata Alteração de Membro</span>
                    <span class="data">08 - 09 - 2025</span>
                    <a href="PDFS/ata2.pdf" target="_blank" class="btn-download">⬇</a>
                </div>

                <div class="atualizacao-ano">Última Atualização 06 maio, 2025</div>
            </div>
        </div>

        <!-- Repete bloco .ano-atas para 2024, 2023, etc. -->
    </section>

    <style>
        .atas {
            max-width: 1100px;
            margin: 0 auto 60px auto;
            font-family: "Poppins", sans-serif;
            color: #222;
        }

        .atas-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 40px 0 20px;
        }

        .atas-header h1 {
            font-size: 36px;
            font-weight: 600;
        }

        .atas-update {
            font-size: 14px;
            color: #256128;
        }

        .ano-atas {
            border-top: 1px solid #ddd;
        }

        .ano-toggle {
            width: 100%;
            padding: 18px 20px;
            background: #fff;
            border: none;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            cursor: pointer;
        }

        .ano-toggle .icon {
            font-size: 22px;
            color: #256128;
        }

        .ano-atas.open .ano-toggle .icon {
            transform: rotate(45deg);
            /* + vira para - */
        }

        .ano-content {
            display: none;
            background: #f3f7f1;
            padding: 20px 24px 14px;
        }

        .ano-atas.open .ano-content {
            display: block;
        }

        .tabela-header {
            display: grid;
            grid-template-columns: 1.5fr 0.5fr 60px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6f7a6f;
            margin-bottom: 10px;
        }

        .linha-doc {
            display: grid;
            grid-template-columns: 70px 1.4fr 0.5fr 60px;
            align-items: center;
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

    <script src="JS/navbar.js"></script>
    <script src="JS/fade_body.js"></script>
    <script src="JS/barra_pesquisa.js"></script>
</body>

</html>