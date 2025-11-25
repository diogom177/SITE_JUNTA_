document.addEventListener('DOMContentLoaded', () => {
  const imgs = document.querySelectorAll('.slider_imgs img');
  const dots = document.querySelectorAll('.slider_dots .dot');
  const setaEsquerda = document.getElementById("seta-esquerda");
  const setaDireita = document.getElementById("seta-direita");
  let curr = 0;
  const total = imgs.length;
  let intervalId = null;

  function showSlide(idx) {
    imgs.forEach((img, i) => {
      img.classList.toggle('active', i === idx);
    });
    dots.forEach(dot => dot.classList.remove('active'));
    dots[idx].classList.add('active');
    curr = idx;
  }

  function nextSlide() {
    curr = (curr + 1) % total;
    showSlide(curr);
  }

  function prevSlide() {
    curr = (curr - 1 + total) % total;
    showSlide(curr);
  }

  function startAutoSlide() {
    intervalId = setInterval(nextSlide, 10000);
  }

  function stopAutoSlide() {
    if (intervalId) {
      clearInterval(intervalId);
      intervalId = null;
    }
  }

  setaEsquerda.addEventListener('click', () => {
    stopAutoSlide();
    prevSlide();
    setTimeout(() => {
      if (!intervalId) startAutoSlide();
    }, 15000);
  });

  setaDireita.addEventListener('click', () => {
    stopAutoSlide();
    nextSlide();
    setTimeout(() => {
      if (!intervalId) startAutoSlide();
    }, 15000);
  });

  dots.forEach((dot, idx) => {
    dot.addEventListener('click', () => {
      stopAutoSlide();
      showSlide(idx);
      setTimeout(() => {
        if (!intervalId) startAutoSlide();
      }, 15000);
    });
  });

  showSlide(0);
  startAutoSlide();
});
