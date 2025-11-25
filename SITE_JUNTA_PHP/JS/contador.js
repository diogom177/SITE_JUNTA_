const imagens = document.querySelectorAll('.imagens-atracoes img');
let modal = document.getElementById("modal-imagem");
let contador = document.getElementById("contador");
let indiceAtual = 0;

function openModal(n) {
  indiceAtual = n;
  modal.style.display = "block";
  mostrarImagem();
}

function closeModal() {
  modal.style.display = "none"
}

function changeSlide(n) {
  indiceAtual = (indiceAtual + n + imagens.length) % imagens.length;
  mostrarImagem();
}

function mostrarImagem() {
  let img = imagens[indiceAtual];
  modalImg.src = img.src;
  contador.innerHTML = `Imagem ${indiceAtual + 1} de ${imagens.length}`;
}
