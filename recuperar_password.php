<?php
session_start();
$flash_reset = $_SESSION['aviso_email'] ?? '';
$flash_error = $_SESSION['flash_error'] ?? '';
unset($_SESSION['aviso_email']);
unset($_SESSION['flash_error']);
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
  <meta charset="UTF-8">
  <title>Recuperar palavra-passe</title>
  <link rel="stylesheet" href="CSS/login.css">
  <link rel="icon" type="image/png" href="IMAGENS/LOGO_U_F_BB.png" />

</head>

<body>
  <!-- Mensagem de reset (azul, específica desta página) -->
  <?php if ($flash_reset): ?>
    <div class="aviso-email">
      <div class="popup-icon info-icon">
        <svg aria-hidden="true" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="info-svg">
          <path clip-rule="evenodd" fill-rule="evenodd"
            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z">
          </path>
        </svg>
      </div>

      <div class="info-message">
        <?= htmlspecialchars($flash_reset) ?>
      </div>

      <div class="popup-icon close-icon" onclick="this.parentElement.classList.add('removendo')">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="close-svg">
          <path d="m15.8333 5.34166-1.175-1.175-4.6583 4.65834-4.65833-4.65834-1.175 1.175 4.65833 4.65834-4.65833 4.6583 1.175 1.175 4.65833-4.6583 4.6583 4.6583 1.175-1.175-4.6583-4.6583z" class="close-path"></path>
        </svg>
      </div>
    </div>
  <?php endif; ?>

  <!-- Mensagem de erro genérica (vermelha) -->
  <?php if ($flash_error): ?>
    <div class="erro-mensagem">
      <?= htmlspecialchars($flash_error) ?>
    </div>
  <?php endif; ?>

  <div class="container" style="margin-top: 100px;">
    <div class="heading">Recuperar palavra-passe</div>
    <p class="recover-subtitle">
      Introduza o email associado à sua conta para receber o link de <br> redefinição de palavra-passe.
    </p>
    <img src="IMAGENS/logo_fb1.png" alt="Logo" class="img_login">

    <form class="form" method="post" action="enviar_reset.php">

      <div class="form__group field">
        <input
          type="email"
          class="form__field"
          placeholder="Email"
          name="email"
          id="email"
          required
          autocomplete="off"
          autofocus 
          style="font-size: 18px; letter-spacing: 0.6px;">
        <label for="email" class="form__label">Email</label>
      </div>

      <div class="btn-container">
        <button type="submit" class="btn1">Enviar link</button>
        <a href="login.php" class="btn1">Voltar ao login</a>
      </div>

    </form>
  </div>

  <style>
    .img_login {
      display: block;
      margin: 0 auto 20px;
      width: 93px;
      height: auto;
    }

    .recover-subtitle {
      font-size: 15px;
      color: #4a4949ff;
      text-align: center;
      margin: 5px 0 20px;
      line-height: 18.4px;
    }

    .btn1 { 
      font-size: 16px;
      padding: 8px 10px;
      border-radius: 0.5rem;
      background: linear-gradient(135deg, #0034de, #006eff);
      border: 2px solid rgb(50, 50, 50);
      border-bottom: 4px solid rgb(50, 50, 50);
      box-shadow: 0px 1px 6px 0px #002cbb;
      cursor: pointer;
      transition: 0.2s;
      transition-timing-function: linear;
      text-decoration: none;
      color: white;
      display: inline-block;
      margin: 5px;
    }

    .btn1:focus {
      font-size: 17px;
      padding: 10px 25px;
      border-radius: 0.7rem;
      background: linear-gradient(135deg, #365ad3ff, #006eff);
      border: 2px solid rgb(50, 50, 50);
      border-bottom: 5px solid rgb(50, 50, 50);
      box-shadow: 0px 1px 6px 0px #617dd9ff;
      transform: translate(0, -3px);
      cursor: pointer;
      transition: 0.2s;
      transition-timing-function: linear;
    }

    .btn1:active {
      transform: translate(0, 0);
      border-bottom: 2px solid rgb(50, 50, 50);
    }

    /* “skin” de info, igual ao snippet */
    .info-popup {
      background-color: #eff6ff;
      border: 2px solid #1d4ed8;
    }

    .info-icon path {
      fill: #1d4ed8;
    }

    .info-message {
      color: #1d4ed8;
      flex: 1;
    }

    /* ícones e botão fechar */
    .popup-icon svg {
      width: 28px;
      height: 28px;
    }

    .close-icon {
      margin-left: auto;
    }

    .close-svg {
      cursor: pointer;
    }

    .close-path {
      fill: grey;
    }
  </style>

  <script>
    setTimeout(function() {
      const aviso = document.querySelector('.aviso-email');
      if (aviso) {
        aviso.classList.add('removendo');
        setTimeout(() => aviso.remove(), 500);
      }
    }, 3200);
  </script>

</body>

</html>