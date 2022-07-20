jQuery(document).ready(function ($) {
  "use strict";
  //Your Js Code Here
  $(".carousel-prof").slick({
    // normal options...
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    nextArrow: '<i class="carousel-control next-carousel fa fa-angle-right"></i>',
    prevArrow: '<i class="carousel-control prev-carousel fa fa-angle-left"></i>',
    // the magic
    // responsive: [
    //   {
    //     breakpoint: 1024,
    //     settings: {
    //       slidesToShow: 3,
    //       infinite: true,
    //     },
    //   },
    //   {
    //     breakpoint: 600,
    //     settings: {
    //       slidesToShow: 2,
    //       dots: true,
    //     },
    //   },
    //   {
    //     breakpoint: 300,
    //     settings: "unslick", // destroys slick
    //   },
    // ],
  });
});
