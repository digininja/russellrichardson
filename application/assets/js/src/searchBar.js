function searchBar() {

  var btn   = document.querySelector('.site-header .icon--small-search');
  var bar   = document.querySelector('.search-bar');
  var input = document.querySelector('.search-bar__input');

  btn.addEventListener('click', function(e){
    e.preventDefault();
    bar.classList.toggle('show');
  });



}
searchBar();
