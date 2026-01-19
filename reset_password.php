<?php
session_start();
require 'admin/database.php';

$token = $_GET['token'] ?? '';

$sql = "SELECT id_admin FROM admin
        WHERE reset_key = ? AND reset_expires_at > NOW()";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
  die('Link de redefinição inválido ou expirado.');
}
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
  <meta charset="UTF-8">
  <title>Nova palavra-passe</title>
  <link rel="stylesheet" href="CSS/login.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="icon" type="image/png" href="IMAGENS/LOGO_U_F_BB.png" />
</head>

<body>
  <div class="container" style="margin-top: 80px;">
    <div class="heading">Definir nova palavra-passe</div>
    <p class="reset-subtitle">Introduza uma nova palavra-passe para a sua conta.</p>
    <img src="IMAGENS/logo_fb.png" alt="Logo" class="img_login">

    <form id="resetForm" class="form" method="post" action="process_reset.php" style="gap: 10px;">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

      <div class="form__group field" style="position:relative;">
        <input type="password" name="password" id="password" class="form__field" placeholder="Nova palavra-passe" style="width: 310px;" required>
        <label for="password" class="form__label">Nova palavra-passe</label>
        <button type="button" class="toggle-pass" data-target="password" aria-label="Mostrar palavra-passe" title="Mostrar/Ocultar">
          <i class="bi bi-eye"></i>
        </button>
      </div>

      <div class="form__group field" style="position:relative;">
        <input type="password" name="password_confirm" id="password_confirm" class="form__field" placeholder="Confirmar palavra-passe" style="width: 310px;" required>
        <label for="password_confirm" class="form__label" style="width: 220px; text-align: left;">Confirmar palavra-passe</label>
        <button type="button" class="toggle-pass" data-target="password_confirm" aria-label="Mostrar confirmar palavra-passe" title="Mostrar/Ocultar">
          <i class="bi bi-eye"></i>
        </button>
      </div>

      <div class="btn-container">
        <button type="submit" class="btn">Redefinir</button>
        <a href="reset_cancel.php?token=<?= rawurlencode($token) ?>" class="btn">Voltar atrás</a>
      </div>

    </form>
  </div>

  <div id="resetError" class="aviso-reset erro-mensagem" role="alert" aria-live="assertive" style="display:none;"></div>

  <style>
    .form__field[type="password"],
    .form__field[type="text"] {
      min-width: 290px;
      color: #111;
      letter-spacing: 1.5px;
    }

    .form__field[type="text"] {
      color: #000 !important;
    }

    .img_login {
      display: block;
      margin: 0 auto 20px;
      width: 90px;
      height: auto;
    }

    .reset-subtitle {
      font-size: 0.9rem;
      text-align: center;
      color: #666;
      margin-bottom: 20px;
    }

    .form__group {
      position: relative;
      padding: 20px 0 0;
      width: 100%;
      max-width: 220px;
      overflow: visible;
    }

    .form__field {
      font-family: "Raleway", Arial, sans-serif;
      width: 100%;
      border: none;
      border-bottom: 2px solid #9b9b9b;
      outline: 0;
      font-size: 17px;
      color: #fff;
      padding: 7px 12px 7px 0;
      background: transparent;
      transition: border-color 0.4s;
      box-sizing: border-box;
      display: block;
    }

    .form__group .toggle-pass {
      background: transparent !important;
      border: 0 !important;
      cursor: pointer;
      position: absolute;
      right: -86px; /*mexer os olhos para os lados*/ 
      top: 50%;
      transform: translateY(-50%);
      color: #666;
      font-size: 18px;
      width: 28px;
      height: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 !important;
      margin: 0 !important;
      line-height: 1;
      pointer-events: auto;
      flex-shrink: 0;
      z-index: 10;
    }

    .form__group .toggle-pass:hover {
      color: #113599ff;
    }

    .form__group .toggle-pass:focus {
      outline: none;
    }

    .form__field::placeholder {
      color: transparent;
    }

    .form__field:placeholder-shown~.form__label {
      font-size: 17px;
      cursor: text;
      top: 20px;
    }

    .form__label {
      position: absolute;
      top: 0;
      display: block;
      transition: 0.4s;
      font-size: 17px;
      color: #9b9b9b;
      pointer-events: none;
    }

    .form__field:focus {
      padding: 7px 10px 6px 0;
      font-weight: 300;
      border-width: 3px;
      border-image: linear-gradient(to right, #113599ff, #3869efff);
      border-image-slice: 1;
    }

    .form__field:focus~.form__label {
      position: absolute;
      top: 0;
      display: block;
      transition: 0.2s;
      font-size: 17px;
      color: #113599ff;
      font-weight: 300;
    }

    /* reset input */
    .form__field:required,
    .form__field:invalid {
      box-shadow: none;
    }
    .reset-error {
      position: fixed;
      left: 50%;
      transform: translateX(-50%);
      bottom: 30px;
      background: linear-gradient(90deg,#ff6b6b,#ff8e53);
      color: #fff;
      padding: 12px 18px;
      border-radius: 8px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.15);
      font-size: 0.95rem;
      z-index: 9999;
      opacity: 0;
      transition: opacity 220ms, transform 220ms;
    }

    .reset-error.show {
      display: block;
      opacity: 1;
      transform: translateX(-50%) translateY(-6px);
    }

    #resetForm{
      font-family: "Raleway", Arial, sans-serif;
    }
  </style>

  <script>
    (function(){
      var form = document.getElementById('resetForm');
      var pw = document.getElementById('password');
      var pwc = document.getElementById('password_confirm');
      var err = document.getElementById('resetError');

      function showError(msg){
        if (!err) return;
        // Construir a mesma estrutura usada por .erro-mensagem
        err.innerHTML = '<span class="erro-icone"><i class="bi bi-exclamation-circle-fill"></i></span>' +
                        '<span class="erro-texto">' + String(msg) + '</span>' +
                        '<button class="erro-fechar" type="button" aria-label="Fechar">×</button>';
        // Mostrar como flex (a classe .erro-mensagem usa display:flex)
        err.style.display = 'flex';
        // Remover qualquer classe de remoção anterior
        err.classList.remove('removendo');

        // Adicionar listener ao botão de fechar
        var closeBtn = err.querySelector('.erro-fechar');
        if (closeBtn) {
          closeBtn.addEventListener('click', function(){
            err.classList.add('removendo');
            setTimeout(function(){ err.style.display = 'none'; }, 500);
          });
        }

        // Auto fechar após 3.2s com mesma animação que noutras páginas
        setTimeout(function(){
          if (!err) return;
          err.classList.add('removendo');
          setTimeout(function(){ if (err) err.style.display = 'none'; }, 500);
        }, 3200);
      }
      if (!form) return;
      try {
        form.addEventListener('submit', function(e){
          var p = pw && pw.value ? pw.value : '';
          var c = pwc && pwc.value ? pwc.value : '';
          if (p.length < 8) {
            e.preventDefault();
            showError('A palavra-passe deve ter pelo menos 8 caracteres.');
            if (pw) pw.focus();
            return;
          }
          if (p !== c) {
            e.preventDefault();
            showError('As palavras-passe não coincidem.');
            if (pwc) pwc.focus();
            return;
          }
        });
      } catch (ex) {
        console && console.error && console.error('Reset form error', ex);
      }
    })();
  </script>

  <script>
    // Toggle password visibility for reset form
    (function(){
      function toggleHandler(e){
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
      document.querySelectorAll('.toggle-pass').forEach(function(b){ b.addEventListener('click', toggleHandler); });
    })();
  </script>
</body>

</html>