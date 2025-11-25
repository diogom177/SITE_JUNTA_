document.addEventListener('DOMContentLoaded', () => {
  const slider = document.querySelector('.links-slider ul');
  const container = document.querySelector('.links-slider');
  const items = Array.from(slider.children);

  // 1. Duplicar várias vezes para garantir sempre continuidade visual
  for (let i = 0; i < 3; i++) {
    items.forEach(item => slider.appendChild(item.cloneNode(true)));
  }

  // 2. Calcular largura do bloco original para reset invisível
  const gap = parseInt(getComputedStyle(slider).gap) || 0;
  let blockWidth = 0;
  items.forEach((li, i) => {
    blockWidth += li.offsetWidth;
    if (i < items.length - 1) blockWidth += gap;
  });

  let pos = 0;
  let speed = 0.5; // px por frame (ajusta para mais rápido/lento)
  let paused = false;
  let isDragging = false;
  let startX, startPos;

  // Loop de scroll infinito
  function loop() {
    if (!paused && !isDragging) {
      pos -= speed;
      if (Math.abs(pos) >= blockWidth) {
        pos = 0;
      }
      slider.style.transform = `translateX(${pos}px)`;
    }
    requestAnimationFrame(loop);
  }

  loop();

  // Pausa no hover
  container.addEventListener('mouseenter', () => paused = true);
  container.addEventListener('mouseleave', () => paused = false);

  // Arraste com rato
  container.addEventListener('mousedown', e => {
    isDragging = true;
    container.classList.add('grabbing');
    startX = e.pageX;
    startPos = pos;
  });

  document.addEventListener('mouseup', () => {
    if (isDragging) {
      isDragging = false;
      container.classList.remove('grabbing');
    }
  });

  container.addEventListener('mousemove', e => {
    if (!isDragging) return;
    e.preventDefault();
    const dx = e.pageX - startX;
    pos = startPos + dx;
    slider.style.transform = `translateX(${pos}px)`;
  });

  // Arraste em touch (mobile)
  container.addEventListener('touchstart', e => {
    isDragging = true;
    startX = e.touches[0].pageX;
    startPos = pos;
  });

  container.addEventListener('touchend', () => {
    isDragging = false;
  });

  container.addEventListener('touchmove', e => {
    if (!isDragging) return;
    const dx = e.touches[0].pageX - startX;
    pos = startPos + dx;
    slider.style.transform = `translateX(${pos}px)`;
  });
});