document.querySelector('.botaoinicial').addEventListener('click', function(e) {
  e.preventDefault();
  const destino = document.querySelector('#atracoes');
  const targetY = destino.getBoundingClientRect().top + window.scrollY + 20;

  const startY = window.scrollY;
  const distance = targetY - startY;
  const duration = 120; // aumentar para ficar mmais lenta
  let startTime = null;

  function animationScroll(currentTime) {
    if (startTime === null) startTime = currentTime;
    const elapsed = currentTime - startTime;

    // função de easing (easeInOutCubic para suavidade extra)
    const progress = Math.min(elapsed / duration, 1);
    const ease = progress < 0.5
      ? 4 * progress * progress * progress
      : 1 - Math.pow(-2 * progress + 2, 3) / 2;

    window.scrollTo(0, startY + distance * ease);

    if (elapsed < duration) {
      requestAnimationFrame(animationScroll);
    }
  }

  requestAnimationFrame(animationScroll);
});

