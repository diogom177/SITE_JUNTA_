document.addEventListener('DOMContentLoaded', () => {
  const executivoWrapper = document.querySelector('.executivo-dropdown');
  const submenuRight = executivoWrapper.querySelector('.submenu-right');
  let hideTimeout;
  function showMenu() {
    clearTimeout(hideTimeout);
    submenuRight.classList.add('show');
  }
  function hideMenu() {
    hideTimeout = setTimeout(() => {
      submenuRight.classList.remove('show');
    }, 150);
  }
  executivoWrapper.addEventListener('mouseenter', showMenu);
  executivoWrapper.addEventListener('mouseleave', hideMenu);
  submenuRight.addEventListener('mouseenter', showMenu);
  submenuRight.addEventListener('mouseleave', hideMenu);
});
