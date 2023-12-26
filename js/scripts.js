document.addEventListener("DOMContentLoaded", function () {
  var navbar = document.getElementById("myTopnav");

  window.onscroll = function () {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      navbar.classList.add("scroll-bg");

    } else {
      navbar.classList.remove("scroll-bg");
    }
  };
});