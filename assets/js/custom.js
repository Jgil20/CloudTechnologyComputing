(function ($) {
    "use strict";

    // Preloader 
    jQuery(window).on('load', function () {
        $(".preloader").delay(1600).fadeOut("slow");
    });
    // sidebar
    $('.sidebar-btn2').on("click", function () {
        $('.header-sidebar').addClass('slide');
    });
    $('.close-btn').on("click", function () {
        $('.header-sidebar').removeClass('slide');
    });

    jQuery('.dropdown-icon').on('click', function () {
        // alert()
        // $(this).next('.mob-submenu').slideToggle();
        jQuery(this).toggleClass('active').next('ul').slideToggle();
        jQuery(this).parent().siblings().children('ul').slideUp();
        jQuery(this).parent().siblings().children('.active').removeClass('active');
    });

    // sticky header

    window.addEventListener('scroll', function () {
        const header = document.querySelector('header.header-area2, .header5');
        header.classList.toggle("sticky", window.scrollY > 0);
    });
    //Counter up
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });

    $('.sidebar-button').click(function () {
        $(this).toggleClass('active');
    });
    //     // Mouse cursor design
    //     var cursor = document.querySelector('.cursor');
    //     var cursorinner = document.querySelector('.cursor2');
    //     var a = document.querySelectorAll('a');

    //     document.addEventListener('mousemove', function (e) {
    //         var x = e.clientX;
    //         var y = e.clientY;
    //         cursor.style.transform = `translate3d(calc(${e.clientX}px - 50%), calc(${e.clientY}px - 50%), 0)`
    //     });

    //     $('a').hover(
    //         function () {
    //             $('.cursor').css({
    // //                "background-color": "transparent"
    //             });
    //         },
    //         function () {
    //             $('.cursor2').css({
    //                 "background-color": "#fff"
    //             });
    //         }
    //     );



    //Active menu
    const currentLocation = location.href;
    const menuItem = document.querySelectorAll('ul li a');
    const menuLength = menuItem.length;
    for (let i = 0; i < menuLength; i++) {
        if (menuItem[i].href === currentLocation) {
            menuItem[i].className = "active";
        }
    }

    //sticky menu
    $(window).on('scroll', function () {

        if ($(this).scrollTop() > 200) {
            $('.position_top').addClass('sticky');
        } else {
            $('.position_top').removeClass('sticky');
        }
    });








    //Isotope with image load
    $(document).on('click', 'ul.isotope-menu li', function () {

        $("ul.isotope-menu li").removeClass("active");
        $(this).addClass("active");

        var selector = $(this).attr('data-filter');
        var $grid = $(".project-items").isotope({
            filter: selector,
            itemSelector: '.single-item',
            layoutMode: 'fitRows',
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false,
            }
        });
        $grid.imagesLoaded().progress( function() {
            $grid.isotope('layout');
          });  
        return false;
    });
    

    //Video popup
    $('[data-fancybox="gallery"]').fancybox({
        buttons: [
        //   "slideShow",
        //   "thumbs",
        //   "zoom",
        //   "fullScreen",
        //   "share",
          "close"
        ],
        loop: false,
        protect: true
      });



    //Home# Banner slider
    var swiper = new Swiper(".banner3-slider", {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 30,
        speed: 1500,
        effect: 'fade',
        autoplay: {
            delay: 4000
        },
        pagination: {
            el: ".swiper-pagination1",
            clickable: true,
        },
        fadeEffect: {
            crossFade: true,
        },
    });

    //Home5 Banner slider
    var swiper = new Swiper(".banner5-slider", {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 30,
        speed: 3000,
        effect: 'fade',
        autoplay: {
            delay: 2500
        },
        pagination: {
            el: ".swiper-pagination1",
            clickable: true,
        },
        fadeEffect: {
            crossFade: true,
        },
    });


    //Trusted client slider
    var swiper = new Swiper(".home4-trusted-client", {
        loop: true,
        slidesPerView: 5,
        spaceBetween: 20,

        navigation: {
            nextEl: '.swiper-button-next-c',
            prevEl: '.swiper-button-prev-c',
        },
        breakpoints: {
            320: {
                slidesPerView: 2,
            },
            576: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 4,
            },
            1200: {
                slidesPerView: 5,
            }
        }
    });

    //Insights slider
    var swiper = new Swiper(".home4-insight-slider", {
        loop: true,
        slidesPerView: 3,
        spaceBetween: 30,
        speed: 1000,
        autoplay: {
            delay: 5000
        },
        pagination: {
            el: ".swiper-pagination5",
            clickable: true,
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 2,
            },
            992: {
                slidesPerView: 3,
            }
        }
    });

    //Insights slider
    var swiper = new Swiper(".home3-solution-slider", {
        loop: true,
        slidesPerView: 3,
        spaceBetween: 30,
        speed: 1000,
        autoplay: {
            delay: 5000
        },
        navigation: {
            nextEl: ".nextbtn1",
            prevEl: ".prevbtn1",
        },
        breakpoints: {
            280: {
                slidesPerView: 1,
            },
            386: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            992: {
                slidesPerView: 2
            },
            1200: {
                slidesPerView: 3
            },
            1400: {
                slidesPerView: 3
            },
        }
    });

    //Success slider
    var swiper = new Swiper(".home3-success-stories-slider", {
        loop: true,
        slidesPerView: 3,
        spaceBetween: 30,
        speed: 1000,
        pagination: {
            el: ".swiper-pagination11",
            clickable: true,
        },
        autoplay: {
            delay: 5000
        },
        breakpoints: {
            280: {
                slidesPerView: 1,
            },
            386: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            992: {
                slidesPerView: 2
            },
            1200: {
                slidesPerView: 3,
                spaceBetween: 20,

            },
            1400: {
                slidesPerView: 3
            },
        }
    });
    //HOme 3 Testimonial slider
    var swiper = new Swiper(".home3-testimonial-slider", {
        loop: true,
        spaceBetween: 30,
        speed: 2000,
        centeredSlides: true,
        navigation: {
            nextEl: ".nextbtn2",
            prevEl: ".prevbtn2",
        },
        autoplay: {
            delay: 5000
        },
        breakpoints: {
            280: {
                slidesPerView: 1,
            },
            386: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 1.5,
            },
            992: {
                slidesPerView: 2
            },
            1200: {
                slidesPerView: 2,

            },
            1400: {
                slidesPerView: 2
            },
        }
    });


    //HOme 3 Team slider
    var swiper = new Swiper(".home3-team-slider", {
        loop: true,
        spaceBetween: 30,
        speed: 2000,
        pagination: {
            el: ".swiper-pagination22",
            clickable: true,
        },
        autoplay: {
            delay: 5000
        },
        breakpoints: {
            280: {
                slidesPerView: 1,
            },
            386: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 2,
            },
            992: {
                slidesPerView: 3
            },
            1200: {
                slidesPerView: 4,

            },
            1400: {
                slidesPerView: 4
            },
        }
    });

    //HOme5 Testimonial slider
    var swiper = new Swiper(".home5-testimonial-slider", {
        loop: true,
        spaceBetween: 0,
        speed: 2000,
        centeredSlides: true,
        // direction: "vertical",
        navigation: {
            nextEl: ".nextbtn3",
            prevEl: ".prevbtn3",
        },
        autoplay: {
            delay: 5000
        },
    });
    //HOme4 Blog slider
    var swiper = new Swiper(".home5-blog-slider", {
        loop: true,
        spaceBetween: 50,
        speed: 2000,
        // centeredSlides: true,
        navigation: {
            nextEl: ".nextbtn4",
            prevEl: ".prevbtn4",
        },
        autoplay: {
            delay: 5000
        },
        breakpoints: {
            280: {
                slidesPerView: 1,
            },
            386: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            992: {
                slidesPerView: 2,
            },
            1200: {
                slidesPerView: 3,

            },
            1400: {
                slidesPerView: 3
            },
        }
    });

    //home6 solution slider
    var swiper = new Swiper(".home6-solution-slider", {
        loop: true,
        slidesPerView: 3,
        spaceBetween: 30,
        speed: 1000,
        autoplay: {
            delay: 5000
        },
        pagination: {
            el: ".swiper-pagination61",
            clickable: true,
        },
        breakpoints: {
            280: {
                slidesPerView: 1,
            },
            386: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            992: {
                slidesPerView: 2
            },
            1200: {
                slidesPerView: 3
            },
            1400: {
                slidesPerView: 3
            },
        }
    });

    //home6 Testimonial slider
    var swiper = new Swiper(".home6-testimonial-slider", {
        loop: true,
        speed: 1000,
        slidesPerView: 1,
        autoplay: {
            delay: 5000
        },
    });
    //home6 solution slider
    var swiper = new Swiper(".home6-partner-slider", {
        loop: true,
        speed: 1000,
        autoplay: {
            delay: 5000
        },
        breakpoints: {
            280: {
                slidesPerView: 1,
            },
            386: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 4
            },
            1200: {
                slidesPerView: 5
            },
            1400: {
                slidesPerView: 7
            },
        }
    });

    jQuery(window).on('load', function () {
        new WOW().init();
        window.wow = new WOW({
            boxClass: 'wow',
            animateClass: 'animated',
            offset: 0,
            mobile: true,
            live: true,
            offset: 100
        })
        window.wow.init();
    });


    // =======================================================================================
    // Magic cursor (no effect on small screens!)
    // =======================================================================================

    if ($("body").not(".is-mobile").hasClass("tt-magic-cursor")) {
        if ($(window).width() > 1024) {
            gsap.config({
                nullTargetWarn: false,
                trialWarn: false,
            });

            $(".magnetic-item").wrap('<div class="magnetic-wrap"></div>');

            if ($("a.magnetic-item").length) {
                $("a.magnetic-item").addClass("not-hide-cursor");
            }

            var $mouse = { x: 0, y: 0 }; // Cursor position
            var $pos = { x: 0, y: 0 }; // Cursor position
            var $ratio = 0.15; // delay follow cursor
            var $active = false;
            var $ball = $("#ball");

            var $ballWidth = 36; // Ball default width
            var $ballHeight = 36; // Ball default height
            var $ballOpacity = 0.5; // Ball default opacity
            var $ballBorderWidth = 2; // Ball default border width

            gsap.set($ball, {  // scale from middle and style ball
                xPercent: -50,
                yPercent: -50,
                width: $ballWidth,
                height: $ballHeight,
                borderWidth: $ballBorderWidth,
                opacity: $ballOpacity
            });

            document.addEventListener("mousemove", mouseMove);

            function mouseMove(e) {
                $mouse.x = e.clientX;
                $mouse.y = e.clientY;
            }

            gsap.ticker.add(updatePosition);

            function updatePosition() {
                if (!$active) {
                    $pos.x += ($mouse.x - $pos.x) * $ratio;
                    $pos.y += ($mouse.y - $pos.y) * $ratio;

                    gsap.set($ball, { x: $pos.x, y: $pos.y });
                }
            }

            $(".magnetic-wrap").mousemove(function (e) {
                parallaxCursor(e, this, 2); // magnetic ball = low number is more attractive
                callParallax(e, this);
            });

            function callParallax(e, parent) {
                parallaxIt(e, parent, parent.querySelector(".magnetic-item"), 25); // magnetic area = higher number is more attractive
            }

            function parallaxIt(e, parent, target, movement) {
                var boundingRect = parent.getBoundingClientRect();
                var relX = e.clientX - boundingRect.left;
                var relY = e.clientY - boundingRect.top;

                gsap.to(target, {
                    duration: 0.3,
                    x: ((relX - boundingRect.width / 2) / boundingRect.width) * movement,
                    y: ((relY - boundingRect.height / 2) / boundingRect.height) * movement,
                    ease: Power2.easeOut
                });
            }

            function parallaxCursor(e, parent, movement) {
                var rect = parent.getBoundingClientRect();
                var relX = e.clientX - rect.left;
                var relY = e.clientY - rect.top;
                $pos.x = rect.left + rect.width / 2 + (relX - rect.width / 2) / movement;
                $pos.y = rect.top + rect.height / 2 + (relY - rect.height / 2) / movement;
                gsap.to($ball, { duration: 0.3, x: $pos.x, y: $pos.y });
            }


            // Magic cursor behavior
            // ======================

            // Magnetic item hover.
            $(".magnetic-wrap").on("mouseenter mouseover", function (e) {
                $ball.addClass("magnetic-active");
                gsap.to($ball, { duration: 0.3, width: 70, height: 70, opacity: 1 });
                $active = true;
            }).on("mouseleave", function (e) {
                $ball.removeClass("magnetic-active");
                gsap.to($ball, { duration: 0.3, width: $ballWidth, height: $ballHeight, opacity: $ballOpacity });
                gsap.to(this.querySelector(".magnetic-item"), { duration: 0.3, x: 0, y: 0, clearProps: "all" });
                $active = false;
            });

            // Alternative cursor style on hover.
            $(".cursor-alter, .tt-main-menu-list > li > a, .tt-main-menu-list > li > .tt-submenu-trigger > a")
                .not(".magnetic-item") // omit from selection.
                .on("mouseenter", function () {
                    gsap.to($ball, {
                        duration: 0.3,
                        borderWidth: 0,
                        opacity: 0.2,
                        backgroundColor: "#CCC",
                        width: "100px",
                        height: "100px",
                    });
                }).on("mouseleave", function () {
                    gsap.to($ball, {
                        duration: 0.3,
                        borderWidth: $ballBorderWidth,
                        opacity: $ballOpacity,
                        backgroundColor: "transparent",
                        width: $ballWidth,
                        height: $ballHeight,
                        clearProps: "backgroundColor"
                    });
                });

            // Overlay menu caret hover.
            $(".tt-ol-submenu-caret-wrap .magnetic-wrap").on("mouseenter", function () {
                gsap.to($ball, { duration: 0.3, scale: 0.6, borderWidth: 3 });
            }).on("mouseleave", function () {
                gsap.to($ball, { duration: 0.3, scale: 1, borderWidth: $ballBorderWidth });
            });

            // Cursor view on hover (data attribute "data-cursor="...").
            $("[data-cursor]").each(function () {
                $(this).on("mouseenter", function () {
                    $ball.addClass("ball-view").append('<div class="ball-view-inner"></div>');
                    $(".ball-view-inner").append($(this).attr("data-cursor"));
                    gsap.to($ball, { duration: 0.3, yPercent: -75, width: 95, height: 95, opacity: 1, borderWidth: 0 });
                    gsap.to(".ball-view-inner", { duration: 0.3, scale: 1, autoAlpha: 1 });
                }).on("mouseleave", function () {
                    gsap.to($ball, { duration: 0.3, yPercent: -50, width: $ballWidth, height: $ballHeight, opacity: $ballOpacity, borderWidth: $ballBorderWidth });
                    $ball.removeClass("ball-view").find(".ball-view-inner").remove();
                });
                $(this).addClass("not-hide-cursor");
            });

            // Cursor drag on hover (class "cursor-drag"). For Swiper sliders.
            $(".swiper").each(function () {
                if ($(this).parent().attr("data-simulate-touch") === "true") {
                    if ($(this).parent().hasClass("cursor-drag")) {
                        $(this).on("mouseenter", function () {
                            $ball.append('<div class="ball-drag"></div>');
                            gsap.to($ball, { duration: 0.3, width: 60, height: 60, opacity: 1 });
                        }).on("mouseleave", function () {
                            $ball.find(".ball-drag").remove();
                            gsap.to($ball, { duration: 0.3, width: $ballWidth, height: $ballHeight, opacity: $ballOpacity });
                        });
                        $(this).addClass("not-hide-cursor");

                        // Ignore "data-cursor" on hover.
                        $(this).find("[data-cursor]").on("mouseenter mouseover", function () {
                            $ball.find(".ball-drag").remove();
                            return false;
                        }).on("mouseleave", function () {
                            $ball.append('<div class="ball-drag"></div>');
                            gsap.to($ball, { duration: 0.3, width: 60, height: 60, opacity: 1 });
                        });
                    }
                }
            });

            // Cursor drag on mouse down / click and hold effect (class "cursor-drag-mouse-down"). For Swiper sliders.
            $(".swiper").each(function () {
                if ($(this).parent().attr("data-simulate-touch") === "true") {
                    if ($(this).parent().hasClass("cursor-drag-mouse-down")) {
                        $(this).on("mousedown pointerdown", function (e) {
                            if (e.which === 1) { // Affects the left mouse button only!
                                gsap.to($ball, { duration: 0.2, width: 60, height: 60, opacity: 1 });
                                $ball.append('<div class="ball-drag"></div>');
                            }
                        }).on("mouseup pointerup", function () {
                            $ball.find(".ball-drag").remove();
                            if ($(this).find("[data-cursor]:hover").length) {
                            } else {
                                gsap.to($ball, { duration: 0.2, width: $ballWidth, height: $ballHeight, opacity: $ballOpacity });
                            }
                        }).on("mouseleave", function () {
                            $ball.find(".ball-drag").remove();
                            gsap.to($ball, { duration: 0.2, width: $ballWidth, height: $ballHeight, opacity: $ballOpacity });
                        });

                        // Ignore "data-cursor" on mousedown.
                        $(this).find("[data-cursor]").on("mousedown pointerdown", function () {
                            return false;
                        });

                        // Ignore "data-cursor" on hover.
                        $(this).find("[data-cursor]").on("mouseenter mouseover", function () {
                            $ball.find(".ball-drag").remove();
                            return false;
                        });
                    }
                }
            });

            // Cursor close on hover.
            $(".cursor-close").each(function () {
                $(this).addClass("ball-close-enabled");
                $(this).on("mouseenter", function () {
                    $ball.addClass("ball-close-enabled");
                    $ball.append('<div class="ball-close">Close</div>');
                    gsap.to($ball, { duration: 0.3, yPercent: -75, width: 80, height: 80, opacity: 1 });
                    gsap.from(".ball-close", { duration: 0.3, scale: 0, autoAlpha: 0 });
                }).on("mouseleave click", function () {
                    $ball.removeClass("ball-close-enabled");
                    gsap.to($ball, { duration: 0.3, yPercent: -50, width: $ballWidth, height: $ballHeight, opacity: $ballOpacity });
                    $ball.find(".ball-close").remove();
                });

                // Hover on "cursor-close" inner elements.
                $(".cursor-close a, .cursor-close button, .cursor-close .tt-btn, .cursor-close .hide-cursor")
                    .not(".not-hide-cursor") // omit from selection (class "not-hide-cursor" is for global use).
                    .on("mouseenter", function () {
                        $ball.removeClass("ball-close-enabled");
                    }).on("mouseleave", function () {
                        $ball.addClass("ball-close-enabled");
                    });
            });

            // Blog interactive title link hover.
            $(".blog-interactive-item").each(function () {
                var $biItem = $(this);
                if ($biItem.find(".bi-item-image").length) {
                    $biItem.find(".bi-item-title a").on("mouseenter mouseover", function () {
                        $("#magic-cursor").addClass("blog-interactive-hover-on");
                        $biItem.find(".bi-item-image").appendTo($ball);
                        gsap.to($ball, { duration: 0.3, width: "20vw", height: "20vw", opacity: 1 });
                    }).on("mouseleave", function () {
                        $("#magic-cursor").removeClass("blog-interactive-hover-on");
                        $ball.find(".bi-item-image").appendTo($biItem);
                        gsap.to($ball, { duration: 0.3, width: $ballWidth, height: $ballHeight, opacity: $ballOpacity });
                    });
                    $biItem.find(".bi-item-title a").addClass("not-hide-cursor");
                    $biItem.addClass("bi-item-image-on");
                }
            });

            // ================================================================
            // Scroll between anchors 
            // ================================================================

            $('a[href^="#"]')
                .not('[href$="#"]') // omit from selection
                .not('[href$="#0"]') // omit from selection
                .on("click", function () {

                    var target = this.hash;

                    // If fixed header position enabled.
                    if ($("#tt-header").hasClass("tt-header-fixed")) {
                        var $offset = $("#tt-header").height();
                    } else {
                        var $offset = 0;
                    }

                    // You can use data attribute (for example: data-offset="100") to set top offset in HTML markup if needed. 
                    if ($(this).data("offset") != undefined) $offset = $(this).data("offset");

                    if (!isMobile) { // Not for mobile devices!
                        if ($("body").hasClass("tt-smooth-scroll")) {
                            var topY = $(target).offset().top - $("#scroll-container > .scroll-content").offset().top - $offset;
                            var $scrollbar = Scrollbar.init(document.getElementById("scroll-container"));
                            gsap.to($scrollbar, { duration: 1.5, scrollTo: { y: topY, autoKill: true }, ease: Expo.easeInOut });

                        } else {
                            var topY = $(target).offset().top - $("body").offset().top - $offset;
                            $("html,body").animate({ scrollTop: topY }, 800);
                        }
                    } else {
                        var topY = $(target).offset().top - $("body").offset().top - $offset;
                        $("html,body").animate({ scrollTop: topY }, 800);
                    }
                    return false;
                });



            // ================================================================
            // Scroll to top 
            // ================================================================

            $(".scroll-to-top").on("click", function () {
                if (!isMobile) { // Not for mobile devices!
                    if ($("body").hasClass("tt-smooth-scroll")) {
                        var $scrollbar = Scrollbar.init(document.getElementById("scroll-container"));
                        gsap.to($scrollbar, { duration: 1.5, scrollTo: { y: 0, autoKill: true }, ease: Expo.easeInOut });
                    } else {
                        $("html,body").animate({ scrollTop: 0 }, 800);
                    }
                } else {
                    $("html,body").animate({ scrollTop: 0 }, 800);
                }
                return false;
            });



            // Show/hide magic cursor
            // =======================

            // Hide on hover.
            $("a, button, .tt-btn, .tt-form-control, .tt-form-radio, .tt-form-check, .hide-cursor") // class "hide-cursor" is for global use.
                .not(".not-hide-cursor") // omit from selection (class "not-hide-cursor" is for global use).
                .not(".cursor-alter") // omit from selection
                .not(".tt-main-menu-list > li > a") // omit from selection
                .not(".tt-main-menu-list > li > .tt-submenu-trigger > a") // omit from selection
                .on("mouseenter", function () {
                    gsap.to($ball, { duration: 0.3, scale: 0, opacity: 0 });
                }).on("mouseleave", function () {
                    gsap.to($ball, { duration: 0.3, scale: 1, opacity: $ballOpacity });
                });

            // Hide on click.
            $("a")
                .not('[target="_blank"]') // omit from selection.
                .not('[href^="#"]') // omit from selection.
                .not('[href^="mailto"]') // omit from selection.
                .not('[href^="tel"]') // omit from selection.
                .not(".lg-trigger") // omit from selection.
                .not(".tt-btn-disabled") // omit from selection.
                .on('click', function () {
                    gsap.to($ball, { duration: 0.3, scale: 1.3, autoAlpha: 0 });
                });

            // Show/hide on document leave/enter.
            $(document).on("mouseleave", function () {
                gsap.to("#magic-cursor", { duration: 0.3, autoAlpha: 0 });
            }).on("mouseenter", function () {
                gsap.to("#magic-cursor", { duration: 0.3, autoAlpha: 1 });
            });

            // Show as the mouse moves.
            $(document).mousemove(function () {
                gsap.to("#magic-cursor", { duration: 0.3, autoAlpha: 1 });
            });
        }
    }

    document.querySelector('.sidebar-button').addEventListener('click', () =>
    document.querySelector('.main-menu').classList.toggle('show-menu'));

}(jQuery));