document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("modal-imagem-locais");
    if (!modal) {
        console.log("modal-locais não encontrado");
        return;
    }

    let imagens = Array.from(document.querySelectorAll(".imagens-atracoes img, .thumbnail"));
    if (imagens.length === 0) console.warn("modal-locais: não encontrei miniaturas (.galeria-locais img, .thumbnail)");

    let imgGrande = document.getElementById("imagem-modal-conteudo-locais");
    let modalTitulo = document.getElementById("descricao-modal-locais");
    const fecharBtn = document.getElementById("fechar-modal-locais");
    const anteriorBtn = document.getElementById("seta-esquerda-locais");
    const seguinteBtn = document.getElementById("seta-direita-locais");
    const contador = document.getElementById("contador-modal-locais");


    if (!imgGrande) {
        imgGrande = document.createElement("img");
        imgGrande.id = "imagem-modal-conteudo-locais";
        imgGrande.className = "imagem-modal-conteudo";
        modal.appendChild(imgGrande);
        console.warn("modal-locais: imagem principal não existia — criei uma automaticamente");
    }
    if (!modalTitulo) {
        modalTitulo = document.createElement("div");
        modalTitulo.id = "descricao-modal-locais";
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
        atualizarContador(); // <--- contador atualizado

        const btnDownload = document.getElementById("btn-download");
        btnDownload.href = imagens[index].src;
        btnDownload.download = imagens[index].src.split('/').pop();

    }

    function atualizarContador() {
        if (contador) {
            contador.textContent = `Imagem ${indexAtual + 1} de ${imagens.length}`;
        }
    }

    function fecharModal() {
        modal.classList.remove("aberto");
        document.body.style.overflow = "";

        if (document.fullscreenElement) {
            document.exitFullscreen();
        }

        modal.classList.remove("aberto");
        document.body.style.overflow = "";
    }

    function mostrarImagemAnterior() {
        if (!animando) slideImagem(-1);
    }

    function mostrarImagemSeguinte() {
        if (!animando) slideImagem(1);
    }

    function slideImagem(direcao) {
        if (imagens.length <= 1) return;
        animando = true;

        const novaIndex = (indexAtual + direcao + imagens.length) % imagens.length;
        const novaImagem = imagens[novaIndex];

        const sairClasse = direcao === 1 ? "slide-out-esq" : "slide-out-dir";
        const entrarClasse = direcao === 1 ? "slide-in-dir" : "slide-in-esq";

        const imagemSaida = imgGrande.cloneNode(true);
        imagemSaida.classList.add(sairClasse);
        imagemSaida.style.position = "absolute";

        let container = modal.querySelector(".modal-conteudo");
        if (!container) {
            container = document.createElement("div");
            container.className = "modal-conteudo";
            modal.appendChild(container);
        }
        container.appendChild(imagemSaida);

        imgGrande.classList.remove(entrarClasse);
        void imgGrande.offsetWidth;

        imgGrande.src = novaImagem.src;
        modalTitulo.textContent = novaImagem.alt || "Imagem";
        imgGrande.classList.add(entrarClasse);

        const btnDownload = document.getElementById("btn-download");
        btnDownload.href = novaImagem.src;
        btnDownload.download = novaImagem.src.split('/').pop();

        imgGrande.addEventListener("animationend", () => {
            imgGrande.classList.remove(entrarClasse);
            if (imagemSaida && imagemSaida.parentNode) imagemSaida.remove();
            indexAtual = novaIndex;
            animando = false;
            atualizarContador(); // <--- contador atualizado
        }, { once: true });

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

        imgGrande.addEventListener("animationend", onEnd, { once: true });

        const timeoutFallback = setTimeout(() => {
            if (!finished) onEnd();
        }, 600);
    }

    imagens.forEach((img, i) => {
        img.addEventListener("click", () => abrirModal(i));
    });

    if (fecharBtn) fecharBtn.addEventListener("click", fecharModal);
    if (anteriorBtn) anteriorBtn.addEventListener("click", (e) => { e.stopPropagation(); mostrarImagemAnterior(); });
    if (seguinteBtn) seguinteBtn.addEventListener("click", (e) => { e.stopPropagation(); mostrarImagemSeguinte(); });

    modal.addEventListener("click", (e) => {
        if (e.target === e.currentTarget) fecharModal();
    });


    window.addEventListener("keydown", (e) => {
        if (!modal.classList.contains("aberto")) return;
        if (e.key === "Escape") fecharModal();
        if (e.key === "ArrowLeft") mostrarImagemAnterior();
        if (e.key === "ArrowRight") mostrarImagemSeguinte();
    });
});
