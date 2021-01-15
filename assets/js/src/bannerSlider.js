function bannerSlider() {
  $(document).ready(function() {
			$('.banner-slider__slides').unslider({
        autoplay:true,
        animation:'fade',
        delay:5000,
        arrows:false
      });
		});
}
/*
  var slide = document.querySelector('.banner-slider__slide');
  var currentHighlight = 0;

  setInterval(function() {

    currentHighlight = (currentHighlight + 1) % slide.length;
    //slide.classList.remove('active')[currentHighlight].classList.add('active');

    console.log(slide);
    // console.log(currentHighlight);

  }, 1000);

}
if ( document.querySelector('.banner-slider') ) {
  // bannerSlider();
}*/

bannerSlider();
