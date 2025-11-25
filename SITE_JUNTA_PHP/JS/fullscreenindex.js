const fullscreenBtn = document.getElementById("fullscreen-btn");
const fullscreenIcon = document.getElementById("fullscreen-icon");
const modal = document.getElementById("modal-imagem-index");

// Clicar no botão
fullscreenBtn.addEventListener("click", () => {
  if (!document.fullscreenElement) {
    modal.requestFullscreen().catch(err => {
      console.log(`Erro ao entrar em fullscreen: ${err.message}`);
    });
  } else {
    document.exitFullscreen();
  }
});

// Trocar o ícone quando muda o fullscreen
document.addEventListener("fullscreenchange", () => {
  if (document.fullscreenElement) {
    fullscreenIcon.classList.remove("bi-fullscreen");
    fullscreenIcon.classList.add("bi-fullscreen-exit");
  } else {
    fullscreenIcon.classList.remove("bi-fullscreen-exit");
    fullscreenIcon.classList.add("bi-fullscreen");
  }
});
