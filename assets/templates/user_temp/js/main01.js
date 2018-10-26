
/* --------------------------------------------------------	
	 Fixed Menu
   --------------------------------------------------------	*/	

  $('.navbar').sticky({topSpacing:0});

/* --------------------------------------------------------	
	 TinyNav
   --------------------------------------------------------	*/	
 
  $(function () {
    $("#nav").tinyNav();
  });

  $('html').addClass('js');
 
/*-------------------------------------------------*/
  /* =  flexslider
  /*-------------------------------------------------*/
  try {

    var SliderPost = $('.flexslider');

    SliderPost.flexslider({
      slideshowSpeed: 6000,
    });
  } catch(err) {

  }
  /*-------------------------------------------------*/
  /* =  Full-window section
  /*-------------------------------------------------*/

  var windowHeight = $(window).height(),
    topSection = $('#home');
  topSection.css('height', windowHeight);

  $(window).resize(function(){
    var windowHeight = $(window).height();
    topSection.css('height', windowHeight);
  });

   /*-------------------------------------------------*/
  /* =  owl Carousel  slider
  /*-------------------------------------------------*/
$(document).ready(function() {
  $(".owl-carousel").owlCarousel({
    autoplay:true,
	loop:true,
	dots:false,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
 
  });
});

/*-------------------------------------------------*/
  /* =  smooth scroll
  /*-------------------------------------------------*/
  try {
    //$.browserSelector();
    // Adds window smooth scroll on chrome.
    //if($("html").hasClass("chrome")) {
      $.smoothScroll();
    //}
  } catch(err) {

  }

//  Slider fotorama
if($('.fotorama').length > 0) {
  $('.fotorama').fotorama({
    width: '100%',
    maxwidth: '100%',
    allowfullscreen: true,
    transition: 'dissolve',
    transitionduration: 500,
    stopautoplayontouch: true,
    shadows: false,
    keyboard: true,
    autoplay: true,
    arrows: true,
    fit: 'cover',
    swipe: true,
    loop: true,
    hash: false,
    nav: false,
  });
}


/*-------------------------------------------------*/
  /* =  Isotop-Filter
  /*-------------------------------------------------*/
$(window).load(function(){
  var $container = $('.portfolio-container');
  if($container.length > 0) {
    $container.isotope({
      itemSelector: '.portfolio-item',
      filter: '*',
      animationOptions: {
        duration: 750,
        easing: 'linear',
        queue: false
      }
    });

    $('.portfolio-filter a').click(function(){
      $('.portfolio-filter .current').removeClass('current');
      $(this).addClass('current');

      var selector = $(this).attr('data-filter');
      $container.isotope({
        filter: selector,
        animationOptions: {
          duration: 750,
          easing: 'linear',
          queue: false
        }
      });
      return false;
    }); 
  }
});

/*-------------------------------------------------*/
  /* =  Isotop-Filter-3-Column
  /*-------------------------------------------------*/
$(window).load(function(){
  var $container = $('.portfolio-container-3column');
  if($container.length > 0) {
    $container.isotope({
      itemSelector: '.portfolio-item',
      filter: '*',
      animationOptions: {
        duration: 750,
        easing: 'linear',
        queue: false
      }
    });

    $('.portfolio-filter-3column a').click(function(){
      $('.portfolio-filter-3column .active').removeClass('active');
      $(this).addClass('active');

      var selector = $(this).attr('data-filter');
      $container.isotope({
        filter: selector,
        animationOptions: {
          duration: 750,
          easing: 'linear',
          queue: false
        }
      });
      return false;
    }); 
  }
});




/* -----------------------------------------------------------------------
Countdown / Coming soon
----------------------------------------------------------------------- */

$(function(){
  // Create a countdown instance. Change the launch day according to your needs.
  // The month ranges from 0 to 11. I specify the month from 1 to 12 and manually subtract the 1.
  if($('#countdown').length > 0) {
    var ts = new Date(2016, 12-1, 30); 
    $('#countdown').countdown({
      until: ts
    });
  }
});

/*-----------------------------------------------------------------------
Fixed 3rd level menu bootstrap by reinhard
------------------------------------------------------------------------*/
$('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
  // Avoid following the href location when clicking
  event.preventDefault();
  // Avoid having the menu to close when clicking
  event.stopPropagation();
  // If a menu is already open we close it
  $('ul.dropdown-menu [data-toggle=dropdown]').parent().removeClass('open');
  // opening the one you clicked on
  $(this).parent().addClass('open');
});
