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

$sql1 = "SELECT id, logo, logo_titulo FROM logo";
$res1 = mysqli_query($conn, $sql1);

$sql = "SELECT id, opcao_, url_, `classe_1` FROM submenu_contactos ORDER BY id ASC";
$resultado1 = mysqli_query($conn, $sql);

$sqlAvisos = "SELECT id_aviso, titulo, titulo_aviso, corpoaviso, data FROM avisos";
$resultAvisos = $conn->query($sqlAvisos);

// Garantir que a variável $aviso exista para a página de detalhe
$aviso = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_aviso = intval($_GET['id']);
    $sqlAviso = "SELECT id_aviso, titulo, titulo_aviso, corpoaviso, data, img FROM avisos WHERE id_aviso = " . $id_aviso . " LIMIT 1";
    $resAviso = $conn->query($sqlAviso);
    if ($resAviso && $resAviso->num_rows > 0) {
        $aviso = $resAviso->fetch_assoc();
    }
}

// Se não veio id ou não encontrou, usa o primeiro aviso disponível como fallback
if (!$aviso) {
    if ($resultAvisos && $resultAvisos->num_rows > 0) {
        $aviso = $resultAvisos->fetch_assoc();
    } else {
        // fallback mínimo para evitar erros no template
        $aviso = [
            'id_aviso' => 0,
            'titulo' => '',
            'titulo_aviso' => '',
            'corpoaviso' => '',
            'data' => date('Y-m-d'),
            'img' => ''
        ];
    }
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
    <title>Avisos - Junta de Freguesia de Barreiro de Besteiros e Tourigo</title>
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
            padding-bottom: 200px;
        }

        .grade-avisos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 130px;
        }

        /* Transições para fade in/out ao atualizar resultados */
        .grade-avisos,
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
            min-height: 340px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .cartao-aviso:hover {
            transform: translateY(-5px);
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

        .cartao-aviso .aviso-link:link,
        .cartao-aviso .aviso-link:visited {
            color: #22649a;
            text-decoration: none;
        }

        .cartao-aviso h3 {
            margin-bottom: 6px;
            font-size: 1.19em;
            color: #22649a;
            font-family: "Poppins", serif;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .cartao-aviso .aviso-titulo-secundario {
            font-size: 1.05em;
            color: #466787;
            margin: 0 0 10px 0;
        }

        .cartao-aviso p {
            font-size: 0.95em;
            color: #444;
            margin-bottom: 15px;
            text-overflow: ellipsis !important;
            overflow: hidden;
        }

        .aviso-data {
            font-size: 0.97em;
            color: #888;
            margin-bottom: 8px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .cartao-aviso .bi-calendar-event {
            margin-right: 5px;
            color: #5b76a6 !important;
        }

        /* Mantém o botão anterior */
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

        /* container se usares ver mais avisos, opcional */
        .vermais-container-avisos {
            margin-top: 40px;
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
        </div> <!-- fecha menu -->

        <div class="navbar_canto">
            <a href="login.php">
                <img src="IMAGENS/image-removebg-preview (38).png" alt="Login">
            </a>
            <i class="bi bi-search" style="font-size:30px; margin-left:40px; cursor:pointer; position:absolute; bottom:36px;"></i>
        </div>
    </div>

    <main class="grade-avisos">
        <article class="cartao-aviso">
            <?php if (!empty($aviso['img'])): ?>
            <img src="IMAGENS/<?= htmlspecialchars($aviso['img']) ?>" alt="<?= htmlspecialchars($aviso['titulo_aviso']) ?>">
            <?php endif; ?>
            <i class="bi bi-calendar-event" style="color: #000;"></i>
            <p class="aviso-data"><?= date('d/m/Y', strtotime($aviso['data'])) ?></p>
            <a href="aviso.php?id=<?= urlencode($aviso['id_aviso']) ?>" class="aviso-link">
                <h3><?= htmlspecialchars($aviso['titulo_aviso']) ?></h3>
            </a>
            <h4 class="aviso-titulo-secundario"><?= htmlspecialchars($aviso['titulo']) ?></h4>
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
    </main>


    <footer class="footer-meruge" style="margin-top: -30px;">
        <div class="footer-meruge-top">
            <div class="footer-meruge-brand">
                <img src="IMAGENS/logo_fb-removebg-preview (1).png" class="footer-meruge-logo" alt="Logo Freguesia" href="index.php">
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