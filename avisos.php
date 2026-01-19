<?php
include 'admin/database.php'; // Caminho para o teu ficheiro de ligação

if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
}
$sql = "SELECT id, opcao, link, classe FROM submenu_freguesia ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql);

$sql = "SELECT id, `option`, `url`, classse, tipo, parent FROM submenu_autarquia ORDER BY id ASC";
$result2 = mysqli_query($conn, $sql);

if (!$result2) {
    die("Erro na consulta submenu_autarquia: " . mysqli_error($conn));
}

$sqlHeader = "SELECT titulo_pagina, descricao FROM avisos LIMIT 1";
$resultHeader = $conn->query($sqlHeader);

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

$sqlAvisos = "SELECT id_aviso, titulo_aviso, corpoaviso, data, img FROM avisos ORDER BY data DESC";
$resultAvisos = $conn->query($sqlAvisos);

$sql = "SELECT id, opcao_, url_, `classe_1` FROM submenu_contactos ORDER BY id ASC";
$resultado1 = mysqli_query($conn, $sql);

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
?>



<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avisos - Junta de Freguesia de Barreiro de Besteiros</title>
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
    <link rel="stylesheet" href="CSS/contactos.css">
    <link rel="stylesheet" href="CSS/rodape.css">

    <style>
        body {
            background-color: #f4f7fa;
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

        .lista-avisos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 50px;
        }

        /* Transitions for fade in/out if used */
        .lista-avisos,
        .paginacao {
            transition: opacity 0.32s ease, transform 0.32s ease;
        }

        .fade-out {
            opacity: 0;
            transform: translateY(8px);
        }

        .fade-in {
            opacity: 1;
            transform: translateY(0);
        }

        .cartao-aviso {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            padding: 20px;
            text-align: left;
            transition: transform 0.3s ease;
            min-height: 550px;
        }

        .cartao-aviso:hover {
            transform: translateY(-5px);
        }

        .cartao-aviso h3 {
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

        .cartao-aviso img {
            height: 220px;
            width: 100%;
            border-radius: 8px;
            margin-bottom: 15px;
            cursor: default;
            transition: transform 0.3s ease;
            object-fit: cover;
        }

        .cartao-aviso img:hover {
            transform: scale(1.05);
        }

        .cartao-aviso a:link,
        .cartao-aviso a:visited {
            color: #22649a;
            text-decoration: none;
        }

        .cartao-aviso p {
            font-size: 0.95em;
            color: #444;
            margin-bottom: 15px;
            text-overflow: ellipsis !important;
        }

        .cssbuttons-io-button {
            background: #49a3df;
            color: white !important;
            font-family: "Poppins", serif;
            padding: 6px;
            padding-left: 10px;
            font-size: 15px;
            font-weight: 500;
            border-radius: 14px;
            border: none;
            letter-spacing: 0.2px;
            display: flex;
            align-items: center;
            box-shadow: inset 0 0 26px -10px #429197;
            overflow: hidden;
            position: relative;
            height: 42px;
            padding-right: 23px;
            cursor: pointer;
            max-width: 140px;
        }

        .cssbuttons-io-button .icon {
            background: white;
            margin-left: 1em;
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 2.2em;
            width: 2.2em;
            border-radius: 0.7em;
            box-shadow: 0.1em 0.1em 0.6em 0.2em #4d96be;
            right: 0.3em;
            transition: all 0.3s;
        }

        .cssbuttons-io-button:hover .icon {
            width: calc(100% - 0.6em);
        }

        .cssbuttons-io-button .icon svg {
            width: 1.1em;
            transition: transform 0.3s;
            color: #1252ab;
        }

        .cssbuttons-io-button:hover .icon svg {
            transform: translateX(0.1em);
        }

        .cssbuttons-io-button:active .icon {
            transform: scale(0.95);
        }

        .cssbuttons-io-button a {
            text-decoration: none;
            color: #fefefe;
        }

        .lista-avisos .aviso-data {
            font-size: 15px;
            margin-left: 30px;
            color: #444444;
            margin-bottom: 10px;
        }

        .cartao-aviso .bi-calendar-event {
            font-size: 18px;
            margin-top: 252px;
            color: #444444 !important;
            margin-left: -338px;
            position: absolute;
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

    <style>
        .img_inicial {
            position: relative;
            background: url('IMAGENS/IMG_20250908_111827.jpg') center center/cover no-repeat;
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
            text-align: center !important;
            font-size: 24px !important;
            font-weight: bold !important;
            margin-top: 0 !important;
        }
    </style>

    <div class="page-wrapper fade-out">
        <section class="img_inicial">
            <div class="texto">
                <?php if ($resultHeader && $resultHeader->num_rows > 0): ?>
                    <?php while ($row = $resultHeader->fetch_assoc()): ?>
                        <h1 class="tituloprincipal"><?= htmlspecialchars($row['titulo_pagina']) ?></h1>
                        <h2 class="descricao2"><?= nl2br(htmlspecialchars($row['descricao'])) ?></h2>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Não existem imagens registadas.</p>
                <?php endif; ?>
            </div>
        </section>

        <main class="breadcrumbs" aria-label="breadcrumb">
            <a href="index.php">Início</a> <i class="bi bi-chevron-right"></i> <span>Avisos</span>
        </main>

        <div class="lista-avisos">
            <?php if ($resultAvisos && $resultAvisos->num_rows > 0): ?>
                <?php while ($aviso = $resultAvisos->fetch_assoc()): ?>
                    <article class="cartao-aviso">
                        <img src="IMAGENS/<?= htmlspecialchars($aviso['img']) ?>" alt="<?= htmlspecialchars($aviso['titulo_aviso']) ?>">
                        <i class="bi bi-calendar-event" style="color: #000;"></i>
                        <p class="aviso-data"><?= formatarDataPt1($aviso['data']) ?></p>
                        <a href="aviso.php?id=<?= urlencode($aviso['id_aviso']) ?>">
                            <h3><?= htmlspecialchars($aviso['titulo_aviso']) ?></h3>
                        </a>
                        <p><?= htmlspecialchars(mb_strimwidth($aviso['corpoaviso'], 0, 240, '...')) ?></p>
                        <a href="aviso.php?id=<?= urlencode($aviso['id_aviso']) ?>" class="cssbuttons-io-button">Ler Mais
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

    </div> <!-- fecha page-wrapper -->


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

    <script src="JS/navbar.js"></script>
    <script src="JS/submenulateral.js"></script>
    <script src="JS/barra_pesquisa.js"></script>

</body>

</html>