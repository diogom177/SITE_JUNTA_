(function() {
  document.addEventListener("DOMContentLoaded", function() {
  let lastScrollTop = 0;
  const navbar = document.querySelector('.navbar');
  const topBar = document.querySelector('.top-bar');
  const navbarHeight = navbar.offsetHeight;
  const topBarHeight = topBar.offsetHeight;
  

  // Inicializa
  navbar.style.top = topBarHeight + 'px';
  topBar.style.top = '0';

  window.addEventListener('scroll', function() {
    const st = window.pageYOffset || document.documentElement.scrollTop;
    const scrollingDown = st > lastScrollTop;

    if (st <= 10) {
      // Está no topo da página
      topBar.style.top = '0';
      navbar.style.top = topBarHeight + 'px';
    } else {
      // Esconde topBar
      topBar.style.top = '-60px';

      if (scrollingDown) {
        navbar.style.transition = "top 1.0s ease"; //esconde a navbar
        navbar.style.top = '-'+ (navbarHeight + 0) +'px';
      } else {
        navbar.style.transition = "top 0.8s ease"; //aparece a navbar
        navbar.style.top = '0'; 
      }
    }
    lastScrollTop = st <= 0 ? 0 : st;
  }, false);
});
})();
