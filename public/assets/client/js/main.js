(function () {
	"use strict";

	function toggleScrolled() {
		const selectBody = document.querySelector('body');
		const selectHeader = document.querySelector('#header');
		if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
		window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
	}

	document.addEventListener('scroll', toggleScrolled);
	window.addEventListener('load', toggleScrolled);

	/**
	 * Mobile nav toggle
	 */
	const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

	function mobileNavToogle() {
		document.querySelector('body').classList.toggle('mobile-nav-active');
		mobileNavToggleBtn.classList.toggle('fa-bars');
		mobileNavToggleBtn.classList.toggle('fa-xmark');
	}
	mobileNavToggleBtn.addEventListener('click', mobileNavToogle);

	/**
	 * Toggle mobile nav dropdowns
	 */
	document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
		navmenu.addEventListener('click', function (e) {
			e.preventDefault();
			this.parentNode.classList.toggle('active');
			this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
			e.stopImmediatePropagation();
		});
	});
	
	/**
	 * Scroll top button
	 */
	let scrollTop = document.querySelector('.scroll-top');

	function toggleScrollTop() {
		if (scrollTop) {
			window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
		}
	}

	const scrollToTop = (duration = 1000) => {
		const start = window.scrollY;
		const startTime = performance.now();

		const scroll = () => {
			const currentTime = performance.now();
			const time = Math.min(1, (currentTime - startTime) / duration);

			const eased = time < 0.5
				? 2 * time * time
				: 1 - Math.pow(-2 * time + 2, 2) / 2;

			window.scrollTo(0, start * (1 - eased));

			if (time < 1) {
				requestAnimationFrame(scroll);
			}
		}

		requestAnimationFrame(scroll);
	}

	scrollTop.addEventListener('click', (e) => {
		e.preventDefault();
		scrollToTop();
	});

	window.addEventListener('load', toggleScrollTop);
	document.addEventListener('scroll', toggleScrollTop);
})();