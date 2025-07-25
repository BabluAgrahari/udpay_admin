/// Sticky Header
    var header = $(".can-sticky");
    var footer = $(".ft-sticky");
    var headerHeight = header.innerHeight();
    var FooterHeight = footer.innerHeight();

    function doSticky() {
        if (window.pageYOffset > headerHeight) {
            header.addClass("sticky");
        } else {
            header.removeClass("sticky");
        }
        if (window.pageYOffset > FooterHeight) {
            footer.addClass("d-flex");
        } else {
            footer.removeClass("d-flex");
        }
    }
    doSticky();
    //On scroll events
    $(window).on('scroll', function() {
        doSticky();
    });



    // ------------------------------
   $(".hamburger>.hamburger-btn").on('click', function() {
        $(".header .navigation").toggleClass('open');
        $(this).toggleClass('active');
    });


       // Navigation
    $(document).ready(function () {
        $('.navigation .main-menu li.menu-item-has-children>a, .navigation .main-menu li.menu-item-has>a').on('click', function () {
          $(this).removeAttr('href');
          var element = $(this).parent('li');
          if (element.hasClass('open')) {
            element.removeClass('open');
            element.find('li').removeClass('open');
            element.find('ul.sub-menu').slideUp();
          } else {
            element.addClass('open');
            element.children('ul.sub-menu').slideDown();
            element.siblings('li').children('ul.sub-menu').slideUp();
            element.siblings('li').removeClass('open');
            element.siblings('li').find('li').removeClass('open');
            element.siblings('li').find('ul.sub-menu').slideUp();
          }
        });
        $('.menu-item-has-children>a').append('<span class="arrow"></span>');
        $('.menu-item-has>a').append('<span class="arrow"></span>');
    });
    //


   // back to top
    var offset = 220;
    var duration = 500;
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > offset) {
            $('.back-top').fadeIn(duration);
        } else {
            $('.back-top').fadeOut(duration);
        }
    });
    $('.back-top').on('click', function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, "slow");
        return false;
    });
    if($(window).scrollTop() > offset) {
        $('.back-top').fadeOut(0);
    }
    $('a[href="#"]').click(function(e) {
        e.preventDefault ? e.preventDefault() : e.returnValue = false;
    });


    // Main Slider
    $('.banner-slider').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows:false,
        dots:true,
        autoplay: true,
        autoplaySpeed: 2000,
        speed:500,
        cssEase: 'linear',
        responsive: [{
          breakpoint: 768,
          settings: {
            arrows: false,
            dots: true,
            slidesToShow: 1
          }
        }]
    });

// ==-product-slider
    $('.product-slider').slick({
  dots: false,
  arrows: true,
  infinite: true,
  autoplay: true,
  autoplaySpeed: 2000,
  speed:500,
  slidesToShow: 4,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 767,
      settings: {
        arrows: false,
        dots: true,
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 576,
      settings: {
        arrows: false,
        dots: true,
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
   
  ]
});
// ==-client-slider
  $('.client-slider').slick({
  dots: true,
  arrows: true,
   centerPadding: '20px',
   centerMode: true,
  infinite: true,
  autoplay: true,
  autoplaySpeed: 2000,
  speed:500,
  slidesToShow: 3,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 767,
      settings: {
        arrows: false,
        dots: true,
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 576,
      settings: {
        arrows: false,
        dots: true,
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
   
  ]
});


// counter
function handlePreloader() {
		if($('.loader-wrap').length){
			$('.loader-wrap').delay(200).fadeOut(200);
		}
	}
    $(window).on('load', function() {
		handlePreloader();
	});
// faq open --------


// Update icons
const searchToggle = document.getElementById('searchToggle');
const searchInput = document.getElementById('searchInput');

searchToggle.addEventListener('click', () => {
  searchInput.classList.toggle('open');
  if (searchInput.classList.contains('open')) {
    searchInput.focus();
  } else {
    searchInput.value = ''; // Optional: clear input when closing
  }
});

// ---------active------------------
 document.addEventListener('DOMContentLoaded', function () {
    const allOptionGroups = document.querySelectorAll('.options');

    allOptionGroups.forEach(group => {
      group.addEventListener('click', function (e) {
        if (e.target.classList.contains('option')) {
          // Remove 'active' from all buttons in this group
          group.querySelectorAll('.option').forEach(btn => btn.classList.remove('active'));
          // Add 'active' to the clicked button
          e.target.classList.add('active');
        }
      });
    });
  });
  // ------------------------------

