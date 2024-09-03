/* ---------------------------------------------
     mobile-menu
--------------------------------------------- */
const navSlide = () => {
  const burger = document.querySelector(".mobile-menu-btn");
  const burger2 = document.querySelector(".mobile-menu-btn2");
  const nav = document.querySelector(".main-nav2");
  const navLinks = document.querySelectorAll(".main-nav2 .menu-list .menu-item");
  const menuClose = document.querySelector(".menu-close-btn");
  const toggleMenu = () => {
    console.log("menu click");
    nav.classList.toggle("show-menu");
    navLinks.forEach((link, index) => {
      if (link.style.animation) {
        link.style.animation = "";
      } else {
        link.style.animation = `navLinkFade 0.4s ease forwards ${index / 10 + 0.5}s `;
      }
    });
  };
  burger.addEventListener("click", toggleMenu);
  burger2.addEventListener("click", toggleMenu);
  menuClose.addEventListener("click", toggleMenu);
};
navSlide();

  /* ---------------------------------------------
     mobile-drop-down
--------------------------------------------- */

$(".main-nav2 .bi").on('click', function (event) {
  var $fl = $(this);
  $(this).parent().siblings().find('.sub-menu').slideUp();
  $(this).parent().siblings().find('.bi').addClass('bi-plus-lg');
  if ($fl.hasClass('bi-plus-lg')) {
      $fl.removeClass('bi-plus-lg').addClass('bi-dash-lg');
  } else {
      $fl.removeClass('bi-dash-lg').addClass('bi-plus-lg');
  }
  $fl.next(".sub-menu").slideToggle();
});