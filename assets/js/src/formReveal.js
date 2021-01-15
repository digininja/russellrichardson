var form = document.getElementById('quote-form');
var button  = document.getElementById('button--quote');

function formReveal() {

  if(button !== null){

    button.addEventListener('click', function(e){
      e.preventDefault();
      form.classList.add('reveal');
      button.classList.add('hide');
    });

  }
}

window.onload=function(){

  if(!form.classList.contains('reveal')){
    formReveal();
  }else{
    if(button !== null){
      button.classList.add('hide');
    }
  }

  if((form !== null) && form.classList.contains('reveal')){
    window.scrollTo(0, (document.querySelector('.sidebar').getBoundingClientRect().top + window.scrollY) - (document.querySelector('.site-header').clientHeight));
  }

}
