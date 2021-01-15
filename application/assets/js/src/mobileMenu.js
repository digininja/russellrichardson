function mobileMenu() {

  var openBTN  = document.getElementById('open-menu-btn');
  var closeBTN = document.getElementById('close-menu-btn');
  var menu     = document.querySelector('.site-header__main-nav');
  var search   = document.querySelector('.search-bar--mobile');

  openBTN.addEventListener('click', function(e){
    e.preventDefault();
    menu.classList.add('open');
    search.classList.add('open');
  });

  closeBTN.addEventListener('click', function(e){
    e.preventDefault();
    menu.classList.remove('open');
    search.classList.remove('open');
  });

}
mobileMenu();
