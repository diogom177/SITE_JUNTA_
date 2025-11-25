// Anima fade-in ao ENTRAR (só ao carregar, não ao refresh)
window.addEventListener("load", () => {
 document.body.classList.add("fade-in");
});

// Ao clicar num link
document.querySelectorAll("a").forEach(link => {
 link.addEventListener("click", function(e) {
  if (this.target === "_blank" || this.hasAttribute("download")) return;

  // Só faz fade-out se o link vai mesmo navegar
  e.preventDefault();
  document.body.classList.remove("fade-in");
  document.body.classList.add("fade-out");

  // Quando acaba a animação, muda de página
  setTimeout(() => {
   window.location = this.href;
  }, 800);
 });
});
