document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("modal-imagem-index");
  if (!modal) {
    console.log("modal-index: não encontrado — script parado");
    return;
  }

  let imagens = Array.from(document.querySelectorAll(".imagens-atracoes img, .thumbnail"));
  if (imagens.length === 0) console.warn("modal-index: não encontrei miniaturas (.imagens-atracoes img, .thumbnail)");

  let imgGrande = document.getElementById("imagem-modal-conteudo-index");
  let modalTitulo = document.getElementById("descricao-modal-index");
  const fecharBtn = document.getElementById("fechar-modal-index");
  const anteriorBtn = document.getElementById("seta-esquerda-index");
  const seguinteBtn = document.getElementById("seta-direita-index");
  const contador = document.getElementById("contador-modal-index");


  // Se faltar o imgGrande/cria um fallback (mais tolerante)
  if (!imgGrande) {
    imgGrande = document.createElement("img");
    imgGrande.id = "imagem-modal-conteudo-index";
    imgGrande.className = "imagem-modal-conteudo";
    modal.appendChild(imgGrande);
    console.warn("modal-index: imagem principal não existe");
  }
  if (!modalTitulo) {
    modalTitulo = document.createElement("div");
    modalTitulo.id = "descricao-modal-index";
    modalTitulo.className = "descricao-modal";
    modal.appendChild(modalTitulo);
  }

  let indexAtual = 0;
  let animando = false;

  function abrirModal(index) {
    if (!imagens[index]) return;
    indexAtual = index;
    imgGrande.src = imagens[index].src;
    modalTitulo.textContent = imagens[index].alt || "Imagem";
    modal.classList.add("aberto");
    document.body.style.overflow = "hidden";
    atualizarContador();

    const btnDownload = document.getElementById("btn-download-index");
    btnDownload.href = imagens[index].src;
    btnDownload.download = imagens[index].src.split('/').pop();

  }

  function fecharModal() {
    modal.classList.remove("aberto");
    document.body.style.overflow = "";

    if (document.fullscreenElement) {
      document.exitFullscreen();
    }

    const fullscreenIcon = document.getElementById("fullscreen-icon");
    if (fullscreenIcon) {
      fullscreenIcon.classList.remove("bi-fullscreen-exit");
      fullscreenIcon.classList.add("bi-fullscreen");
    }

  }

  function mostrarImagemAnterior() {
    if (!animando) slideImagem(-1);
  }

  function mostrarImagemSeguinte() {
    if (!animando) slideImagem(1);
  }

  function atualizarContador() {
    if (contador) {
      contador.textContent = `Imagem ${indexAtual + 1} de ${imagens.length}`;
    }
  }


  function slideImagem(direcao) {
    if (imagens.length <= 1) return;
    animando = true;

    const novaIndex = (indexAtual + direcao + imagens.length) % imagens.length;
    const novaImagem = imagens[novaIndex];

    const sairClasse = direcao === 1 ? "slide-out-esq" : "slide-out-dir";
    const entrarClasse = direcao === 1 ? "slide-in-dir" : "slide-in-esq";

    // clone da imagem atual para animar saída
    const imagemSaida = imgGrande.cloneNode(true);
    imagemSaida.classList.add(sairClasse);
    imagemSaida.style.position = "absolute";
    imagemSaida.style.top = imgGrande.style.top || "50%";
    imagemSaida.style.left = imgGrande.style.left || "50%";
    imagemSaida.style.transform = imgGrande.style.transform || "translate(-50%, -50%)";

    // container para imagens animadas
    let container = modal.querySelector(".modal-conteudo");
    if (!container) {
      container = document.createElement("div");
      container.className = "modal-conteudo";
      modal.appendChild(container);
    }
    container.appendChild(imagemSaida);

    // força reflow para reiniciar animações se necessário
    imgGrande.classList.remove(entrarClasse);
    void imgGrande.offsetWidth;

    // troca e anima a entrada
    imgGrande.src = novaImagem.src;
    modalTitulo.textContent = novaImagem.alt || "Imagem";
    imgGrande.classList.add(entrarClasse);

    const btnDownload = document.getElementById("btn-download-index");
    btnDownload.href = novaImagem.src;
    btnDownload.download = novaImagem.src.split('/').pop();
    

    // cleanup: espera animationend ou timeout fallback
    let finished = false;
    const onEnd = () => {
      if (finished) return;
      finished = true;
      imgGrande.classList.remove(entrarClasse);
      if (imagemSaida && imagemSaida.parentNode) imagemSaida.remove();
      indexAtual = novaIndex;
      animando = false;
      clearTimeout(timeoutFallback);
    };

    imgGrande.addEventListener("animationend", () => {
      imgGrande.classList.remove(entrarClasse);
      if (imagemSaida && imagemSaida.parentNode) imagemSaida.remove();
      indexAtual = novaIndex;
      animando = false;
      atualizarContador(); // <--- contador atualizado
    }, { once: true });


    imgGrande.addEventListener("animationend", onEnd, { once: true });

    const timeoutFallback = setTimeout(() => {
      if (!finished) onEnd();
    }, 600); // um pouco maior que a animação (0.3s), para segurança
  }

  imagens.forEach((img, i) => {
    img.addEventListener("click", () => abrirModal(i));
  });

  if (fecharBtn) fecharBtn.addEventListener("click", fecharModal);
  if (anteriorBtn) anteriorBtn.addEventListener("click", (e) => { e.stopPropagation(); mostrarImagemAnterior(); });
  if (seguinteBtn) seguinteBtn.addEventListener("click", (e) => { e.stopPropagation(); mostrarImagemSeguinte(); });

  modal.addEventListener("click", (e) => {
    if (e.target === modal) fecharModal();
  });

  // teclado: usa window para garantir captura
  window.addEventListener("keydown", (e) => {
    if (!modal.classList.contains("aberto")) return;
    if (e.key === "Escape") fecharModal();
    if (e.key === "ArrowLeft") mostrarImagemAnterior();
    if (e.key === "ArrowRight") mostrarImagemSeguinte();
  });
});
