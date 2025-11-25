const btnTopo = document.getElementById("btnTopo");

    window.onscroll = function() {
      btnTopo.style.display = (document.documentElement.scrollTop > 300) ? "block" : "none";
    };

    function topFunction() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    }