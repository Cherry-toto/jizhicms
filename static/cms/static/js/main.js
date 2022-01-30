(function(a) {
	var m = a(window);
	var j = m.width();
	var l = a(".header-sticky");
	var c = a("html");
	var b = a("body");
	var m = a(window);
	var j = m.width();
	var l = a(".header-sticky");
	var c = a("html");
	var b = a("body");
	m.on("scroll", function() {
		var o = m.scrollTop();
		var n = l.height();
		if (j >= 320) {
			if (o < n) {
				l.removeClass("is-sticky")
			} else {
				l.addClass("is-sticky")
			}
		}
	});

	function k() {
		var o = a("#scroll-top"),
			n = 0,
			p = a(window);
		p.on("scroll", function() {
			var q = a(this).scrollTop();
			if (q > n) {
				o.removeClass("show")
			} else {
				if (p.scrollTop() > 200) {
					o.addClass("show")
				} else {
					o.removeClass("show")
				}
			}
			n = q
		});
		o.on("click", function(q) {
			a("html, body").animate({
				scrollTop: 0
			}, 600);
			q.preventDefault()
		})
	}
	k();
	if (a(".has-children--multilevel-submenu").find(".submenu").length) {
		var h = a(".has-children--multilevel-submenu").find(".submenu");
		h.each(function() {
			var r = a(this).offset();
			var q = r.left;
			var s = a(this).width();
			var n = m.height();
			var o = m.width() - 10;
			var p = (q + s <= o);
			if (!p) {
				a(this).addClass("left")
			}
		})
	}
	a("#mobile-menu-trigger").on("click", function() {
		a("#mobile-menu-overlay").addClass("active");
		b.addClass("no-overflow")
	});
	a("#mobile-menu-close-trigger").on("click", function() {
		a("#mobile-menu-overlay").removeClass("active");
		b.removeClass("no-overflow")
	});
	a(".offcanvas-navigation--onepage ul li a").on("click", function() {
		a("#mobile-menu-overlay").removeClass("active");
		b.removeClass("no-overflow")
	});
	b.on("click", function(o) {
		var n = o.target;
		if (!a(n).is(".mobile-menu-overlay__inner") && !a(n).parents().is(".mobile-menu-overlay__inner") && !a(n).is("#mobile-menu-trigger") && !a(n).is("#mobile-menu-trigger i")) {
			a("#mobile-menu-overlay").removeClass("active");
			b.removeClass("no-overflow")
		}
	});
	var g = window.location.pathname;
	var f = g.substr(g.lastIndexOf("/") + 1);
	var i = window.location.hash.substr(1);
	if ((f == "" || f == "/" || f == "admin") && i == "") {} else {
		a(".navigation-menu li").each(function() {
			a(this).removeClass("active")
		});
		if (i != "") {
			a(".navigation-menu li a[href*='" + i + "']").parents("li").addClass("active")
		} else {
			a(".navigation-menu li a[href*='" + f + "']").parents("li").addClass("active")
		}
	}
	a(".openmenu-trigger").on("click", function(n) {
		n.preventDefault();
		a(".open-menuberger-wrapper").addClass("is-visiable")
	});
	a(".page-close").on("click", function(n) {
		n.preventDefault();
		a(".open-menuberger-wrapper").removeClass("is-visiable")
	});
	a("#open-off-sidebar-trigger").on("click", function() {
		a("#page-oppen-off-sidebar-overlay").addClass("active");
		b.addClass("no-overflow")
	});
	a("#menu-close-trigger").on("click", function() {
		a("#page-oppen-off-sidebar-overlay").removeClass("active");
		b.removeClass("no-overflow")
	});
	a("#search-overlay-trigger").on("click", function() {
		a("#search-overlay").addClass("active");
		b.addClass("no-overflow")
	});
	a("#search-close-trigger").on("click", function() {
		a("#search-overlay").removeClass("active");
		b.removeClass("no-overflow")
	});
	a("#hidden-icon-trigger").on("click", function() {
		a("#hidden-icon-wrapper").toggleClass("active")
	});
	a(".share-icon").on("click", function(n) {
		n.preventDefault();
		a(".entry-post-share").toggleClass("opened")
	});
	b.on("click", function() {
		a(".entry-post-share").removeClass("opened")
	});
	a(".entry-post-share").on("click", function(n) {
		n.stopPropagation()
	});
	var d = a(".offcanvas-navigation"),
		e = d.find(".sub-menu");
	e.parent().prepend('<span class="menu-expand"><i></i></span>');
	e.slideUp();
	d.on("click", "li a, li .menu-expand", function(o) {
		var n = a(this);
		if ((n.parent().attr("class").match(/\b(menu-item-has-children|has-children|has-sub-menu)\b/)) && (n.attr("href") === "#" || n.hasClass("menu-expand"))) {
			o.preventDefault();
			if (n.siblings("ul:visible").length) {
				n.parent("li").removeClass("active");
				n.siblings("ul").slideUp()
			} else {
				n.parent("li").addClass("active");
				n.closest("li").siblings("li").removeClass("active").find("li").removeClass("active");
				n.closest("li").siblings("li").find("ul:visible").slideUp();
				n.siblings("ul").slideDown()
			}
		}
	});
	a(document).ready(function() {
		var n = new Swiper(".most-popular-slider-active", {
			slidesPerView: 3,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 30,
			navigation: {
				nextEl: ".popular-swiper-button-next",
				prevEl: ".popular-swiper-button-prev",
			},
			pagination: {
				el: ".swiper-pagination-t0",
				type: "bullets",
				clickable: true
			},
			breakpoints: {
				1499: {
					slidesPerView: 3
				},
				991: {
					slidesPerView: 3
				},
				767: {
					slidesPerView: 2
				},
				575: {
					slidesPerView: 2
				},
				0: {
					slidesPerView: 1
				}
			}
		});
		var n = new Swiper(".hero-slider-three-active", {
			slidesPerView: 1,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 0,
			navigation: {
				nextEl: ".slider-three-swiper-button-next",
				prevEl: ".slider-three-swiper-button-prev",
			},
			pagination: {
				el: ".hero-swiper-pagination",
				type: "bullets",
				clickable: true
			}
		});
		var n = new Swiper(".hero-slider-four-active", {
			slidesPerView: 1,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 0,
			navigation: {
				nextEl: ".slider-four-button-next",
				prevEl: ".slider-four-button-prev",
			},
			pagination: {
				el: ".hero-swiper-pagination",
				type: "bullets",
				clickable: true
			}
		});
		var n = new Swiper(".trending-slider-active", {
			slidesPerView: 1,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 0,
			navigation: {
				nextEl: ".trending-button-next",
				prevEl: ".trending-button-prev",
			},
			pagination: {
				el: ".trending-swiper-pagination",
				type: "bullets",
				clickable: true
			}
		});
		var n = new Swiper(".following-slider-active", {
			slidesPerView: 1,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 0,
			navigation: {
				nextEl: ".following-button-next",
				prevEl: ".following-button-prev",
			},
			pagination: {
				el: ".following-swiper-pagination",
				type: "bullets",
				clickable: true
			}
		});
		var n = new Swiper(".trending-topic-slider-active", {
			slidesPerView: 5,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 25,
			navigation: {
				nextEl: ".trending-topic-button-next",
				prevEl: ".trending-topic-button-prev",
			},
			pagination: {
				el: ".hero-swiper-pagination",
				type: "bullets",
				clickable: true
			},
			breakpoints: {
				1499: {
					slidesPerView: 5
				},
				991: {
					slidesPerView: 4
				},
				767: {
					slidesPerView: 4
				},
				575: {
					slidesPerView: 3,
					spaceBetween: 10
				},
				0: {
					slidesPerView: 2,
					spaceBetween: 10,
				}
			}
		});
		var n = new Swiper(".latest-post-slider-active", {
			slidesPerView: 1,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 0,
			navigation: {
				nextEl: ".latest-post-button-next",
				prevEl: ".latest-post-button-prev",
			},
			pagination: {
				el: ".swiper-pagination",
				type: "bullets",
				clickable: true
			}
		});
		var n = new Swiper(".recent-reading-slider-active", {
			slidesPerView: 3,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 30,
			navigation: {
				nextEl: ".recent-reading-button-next",
				prevEl: ".recent-reading-button-prev",
			},
			pagination: {
				el: ".swiper-pagination",
				type: "bullets",
				clickable: true
			},
			breakpoints: {
				1499: {
					slidesPerView: 3
				},
				991: {
					slidesPerView: 2
				},
				767: {
					slidesPerView: 1
				},
				575: {
					slidesPerView: 1
				},
				0: {
					slidesPerView: 1
				}
			}
		});
		var n = new Swiper(".trusted-partners-slider-active", {
			slidesPerView: 4,
			slidesPerGroup: 2,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 30,
			navigation: {
				nextEl: ".partners-swiper-button-next",
				prevEl: ".partners-swiper-button-prev",
			},
			pagination: {
				el: ".partners-swiper-pagination",
				type: "bullets",
				clickable: true
			},
			breakpoints: {
				1499: {
					slidesPerView: 4
				},
				991: {
					slidesPerView: 4
				},
				767: {
					slidesPerView: 2
				},
				575: {
					slidesPerView: 2
				},
				0: {
					slidesPerView: 2
				}
			}
		});
		var n = new Swiper(".testimonial-slider-active", {
			slidesPerView: 3,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 30,
			navigation: {
				nextEl: ".testimonial-button-next",
				prevEl: ".testimonial-button-prev",
			},
			pagination: {
				el: ".partners-swiper-pagination",
				type: "bullets",
				clickable: true
			},
			breakpoints: {
				1499: {
					slidesPerView: 3
				},
				991: {
					slidesPerView: 3
				},
				767: {
					slidesPerView: 2
				},
				575: {
					slidesPerView: 1
				},
				0: {
					slidesPerView: 1
				}
			}
		});
		var n = new Swiper(".trending-tody-slider-active", {
			slidesPerView: 1,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 30,
			navigation: {
				nextEl: ".testimonial-button-next",
				prevEl: ".testimonial-button-prev",
			},
			pagination: {
				el: ".trending-tody-swiper-pagination",
				type: "bullets",
				clickable: true
			}
		});
		var n = new Swiper(".hero-six-slider-active", {
			slidesPerView: 1,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 30,
			navigation: {
				nextEl: ".slider-six-button-next",
				prevEl: ".slider-six-button-prev",
			},
			pagination: {
				el: ".hero-six-swiper-pagination",
				type: "bullets",
				clickable: true
			}
		});
		var n = new Swiper(".trending-tody-slider-two-active", {
			slidesPerView: 3,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 30,
			navigation: {
				nextEl: ".trending-tody-button-next",
				prevEl: ".trending-tody-button-prev",
			},
			pagination: {
				el: ".trending-tody-swiper-pagination",
				type: "bullets",
				clickable: true
			},
			breakpoints: {
				1499: {
					slidesPerView: 3
				},
				991: {
					slidesPerView: 3
				},
				767: {
					slidesPerView: 2
				},
				575: {
					slidesPerView: 1
				},
				0: {
					slidesPerView: 1
				}
			}
		});
		var n = new Swiper(".related-post-slider-active", {
			slidesPerView: 2,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 30,
			navigation: {
				nextEl: ".related-post-button-next",
				prevEl: ".related-post-button-prev",
			},
			pagination: {
				el: ".related-post-swiper-pagination",
				type: "bullets",
				clickable: true
			},
			breakpoints: {
				1499: {
					slidesPerView: 2
				},
				991: {
					slidesPerView: 2
				},
				767: {
					slidesPerView: 2
				},
				575: {
					slidesPerView: 1
				},
				0: {
					slidesPerView: 1
				}
			}
		});
		var n = new Swiper(".related-post-two-slider-active", {
			slidesPerView: 3,
			slidesPerGroup: 1,
			centeredSlides: false,
			loop: true,
			speed: 1000,
			spaceBetween: 30,
			navigation: {
				nextEl: ".related-post-button-next",
				prevEl: ".related-post-button-prev",
			},
			pagination: {
				el: ".related-post-swiper-pagination",
				type: "bullets",
				clickable: true
			},
			breakpoints: {
				1499: {
					slidesPerView: 3
				},
				991: {
					slidesPerView: 3
				},
				767: {
					slidesPerView: 2
				},
				575: {
					slidesPerView: 1
				},
				0: {
					slidesPerView: 1
				}
			}
		})
	});
	a(".popup-images").lightGallery();
	a(".video-popup").lightGallery();
// 	AOS.init({
// 		offset: 80,
// 		duration: 1000,
// 		once: true,
// 		easing: "ease",
// 	});
wow = new WOW({
        animateClass: 'animated',
        offset: 200
       });
       wow.init();
	a(".projects-masonary-wrapper,.masonry-activation").imagesLoaded(function() {
		a(".messonry-button").on("click", "button", function() {
			var o = a(this).attr("data-filter");
			a(this).siblings(".is-checked").removeClass("is-checked");
			a(this).addClass("is-checked");
			n.isotope({
				filter: o
			})
		});
		var n = a(".masonry-wrap").masonry({
			itemSelector: ".masonary-item",
			percentPosition: true,
			transitionDuration: "0.7s",
			columnWidth: ".masonary-sizer"
		});
		var n = a(".mesonry-list").isotope({
			percentPosition: true,
			transitionDuration: "0.7s",
			layoutMode: "masonry",
		})
	})
})(jQuery);