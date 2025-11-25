document.addEventListener("DOMContentLoaded", function () {
  const link = document.getElementById("formulario");

  if (link) {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      document.body.style.transition = "opacity 0.6s ease";
      document.body.style.opacity = "0";
      setTimeout(() => {
        window.location.href = this.href;
      }, 600);
    });
  }
});