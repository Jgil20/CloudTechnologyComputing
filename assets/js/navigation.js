(function () {
  'use strict';

  function initMobileNavigation() {
    var header = document.querySelector('.header-area2');
    if (!header) return;

    var menu = header.querySelector('.main-menu');
    var openButton = header.querySelector('.sidebar-button, .mobile-menu-btn');
    var closeButton = header.querySelector('.ctc-menu-close-btn');
    if (!menu || !closeButton) return;

    menu.id = menu.id || 'ctc-mobile-navigation';
    menu.setAttribute('aria-hidden', 'true');

    function closeMenu() {
      menu.classList.remove('show-menu');
      menu.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('ctc-mobile-menu-open');

      if (openButton) {
        openButton.classList.remove('active');
        openButton.setAttribute('aria-expanded', 'false');
      }

      menu.querySelectorAll('.dropdown-icon').forEach(function (icon) {
        icon.classList.remove('active', 'bi-dash-lg');
        icon.classList.add('bi-plus');
      });

      menu.querySelectorAll('ul.sub-menu').forEach(function (submenu) {
        submenu.style.display = 'none';
      });
    }

    function syncMenuState() {
      if (window.innerWidth > 991) {
        menu.removeAttribute('aria-hidden');
        document.body.classList.remove('ctc-mobile-menu-open');
        return;
      }

      var isOpen = menu.classList.contains('show-menu');
      document.body.classList.toggle('ctc-mobile-menu-open', isOpen);
      menu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');

      if (openButton) {
        openButton.classList.toggle('active', isOpen);
        openButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      }
    }

    closeButton.addEventListener('click', function (event) {
      event.preventDefault();
      event.stopPropagation();
      closeMenu();
    });

    if (openButton) {
      openButton.setAttribute('aria-controls', menu.id);
      openButton.setAttribute('aria-expanded', 'false');
      openButton.addEventListener('click', function () {
        window.setTimeout(syncMenuState, 0);
      });
    }

    menu.addEventListener('click', function (event) {
      if (window.innerWidth > 991) return;
      var link = event.target.closest('a');
      if (link && !link.classList.contains('drop-down')) closeMenu();
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && menu.classList.contains('show-menu')) closeMenu();
    });

    window.addEventListener('resize', syncMenuState, { passive: true });
    new MutationObserver(syncMenuState).observe(menu, { attributes: true, attributeFilter: ['class'] });
    syncMenuState();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMobileNavigation);
  } else {
    initMobileNavigation();
  }
})();
