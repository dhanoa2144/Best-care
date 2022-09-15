jQuery(document).ready(function($){
    jQuery(document.body).removeClass('no-js').addClass('js');

   /* initPolyfills();

    // Utilities
    initClassToggle();
    initAnchorScroll();

    // Theme
	initSlickSliders();
	initMobileMenu();
	initAccordions();
	initTabs();
	initAjaxLoadMore();
	initAutoSubmitFilters();*/

	initFrameworkTable();
	initSlickSliders();
	
	//initAudienceSearch();
});

var resizeTimer;
jQuery(window).on('resize', function($) {

	clearTimeout(resizeTimer);
	resizeTimer = setTimeout(function() {

		// Run code here, resizing has "stopped"
		initFrameworkTable();

	}, 250);
});



/**
 * Utility function to check viewport size is under 768px
 * @returns {boolean}
 */
function isMobile() {
    return window.matchMedia('(max-width:767px)').matches;
}


/**
 * Utility function for polyfills
 */
function initPolyfills() {
    // CSS object-fit for IE
    objectFitImages();

    // polyfill for IE - startsWith
    if (!String.prototype.startsWith) {
        String.prototype.startsWith = function(searchString, position) {
            position = position || 0;
            return this.indexOf(searchString, position) === position;
        };
    }
}


/**
 * Toggle class on click
 */
function initClassToggle() {
    $(document.body).on('click', '[data-toggle="class"][data-class]', function(event) {
        var $trigger = $(this);
        var $target = $($trigger.data('target') ? $trigger.data('target') : $trigger.attr('href'));

        if($target.length) {
            event.preventDefault();
            $target.toggleClass($trigger.data('class'));
            $trigger.toggleClass('classed');
        }
    });
}


/**
 * Smooth anchor scrolling
 */
function initAnchorScroll() {
    $('a[href*="#"]:not([data-toggle])').click(function(event) {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name="'+this.hash.slice(1)+'"]');
            if (target.length) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 1000);
            }
        }
    });
}


/**
 * Slick sliders
 */
function initSlickSliders() {

	jQuery('.module__carousel__slider').slick({
		infinite: true,
		dots: false,
		autoplay: true,
		autoplaySpeed: 2000
	});
}


/**
 * Mobile menu
 */
function initMobileMenu() {

	$('.site-header__mobile-toggle').click(function() {
		$('.mobile-nav').toggleClass('open');
		$('body').toggleClass('menu-is-open');
	});

	// Submenu handling
	$('.mobile-nav__menu li.menu-item-has-children > a span').click(function(event) {
		event.preventDefault();

		$(this).parent().next('.sub-menu').slideToggle(400);
		$(this).toggleClass('open');
	});

}

/**
 * Accordions
 */
function initAccordions() {

	function toggleIcon(e) {
		$(e.target)
			.prev('.panel-heading')
			.find(".more-less")
			.toggleClass('glyphicon-plus glyphicon-minus');
	}

	$('.panel-group').on('hidden.bs.collapse', toggleIcon);
	$('.panel-group').on('shown.bs.collapse', toggleIcon);
}


/**
 * Tabs
 */
function initTabs() {

	/**
	 * Show first tab by default (all uses of tabs)
	 */
	$('.nav-tabs li:first-of-type a').tab('show');


	/**
	 * If a link outside the tabs is linked to a tab, open it
	 * and scroll down to the tabs module
	 *
	 * @see partials/modules/framework_table.php
	 * @see partials/modules/icons.php
	 */
	var linksToTabs = document.querySelectorAll('a[data-toggle="tab"]');
	for(var i = 0; i < linksToTabs.length; i++) {
		linksToTabs[i].addEventListener('click', function() {

			// Trigger click event on the matching tab
			// Using data attribute instead of href for two reasons:
			// 1. Framework table also uses this attribute for related things
			// 2. It stops the issue where this.href may return the full site URL plus the #tab, causing this not to work
			$('.nav-tabs a[href="#' + this.dataset.linkgroup + '"]').click();

			if($('#tabs').length){
				var taboffset = $('#tabs').offset().top + $('body').scrollTop();

				// Scroll down
				$('html,body').animate({
					scrollTop: taboffset
				}, 1000);
			}
		});
	}

	/**
	 * In the tabs module, if a tab panel has a background colour set,
	 * update the background colour of the whole module
	 *
	 * @type {Element}
	 */
	var theWholeModule = document.querySelector('.module__tabs');

	if(theWholeModule != null) {

		// When the page first loads, find the first tab and use its background colour to set the module's
		var activePanel = document.querySelector('.module__tabs .tab-pane.active');
		var activePanelClasses = activePanel.classList;
		for (var i = 0; i < activePanelClasses.length; i++) {
			if (activePanelClasses[i].indexOf('bg-') >= 0) { // indexOf not includes because includes doesn't work in IE11
				theWholeModule.classList.add(activePanelClasses[i]);
			}
		}

		// Update when tabs are clicked
		$('.module__tabs .nav-link').on('show.bs.tab', function (event) {
			var previousPanelID = event.relatedTarget.getAttribute('href');
			var panelID = event.target.getAttribute('href');

			// Remove the old panel's background class
			var oldPanel = document.querySelector(previousPanelID);
			var oldPanelClasses = oldPanel.classList;
			for (var i = 0; i < oldPanelClasses.length; i++) {
				if (oldPanelClasses[i].indexOf('bg-') >= 0) {
					theWholeModule.classList.remove(oldPanelClasses[i]);
				}
			}

			// Add the current panel's background class
			var currentPanel = document.querySelector(panelID);
			var panelClasses = currentPanel.classList;
			for (var i = 0; i < panelClasses.length; i++) {
				if (panelClasses[i].indexOf('bg-') >= 0) {
					theWholeModule.classList.add(panelClasses[i]);
				}
			}
		});
	}
}


/**
 * Utility function to turn pagination into a Load More button
 */
function transformPaginationControl($pagination, label, loadedCallback) {
	var $currentLink = $pagination.find('.current');
	var $nextLink = $pagination.find('a.next');

	if ($currentLink.text().trim() === '1') {
		if ($nextLink) {
			$pagination.html('<a href="' + $nextLink.attr('href') + '" class="btn btn-primary btn-lg btn--load-more">' + label + '</a>');

			var loadMoreAJAX = undefined;

			$pagination.find('.btn--load-more').click(function (event) {
				event.preventDefault();

				var $this = $(this);

				if (loadMoreAJAX !== undefined) {
					return;
				}

				$this.addClass('loading');

				loadMoreAJAX = $.ajax({
					url: $this.attr('href'),
					method: 'GET',
					dataType: 'html',
					context: this,
					success: function (response) {
						loadMoreAJAX = undefined;

						var $response = $(response);

						var $nextLink = loadedCallback($response);

						var $loadMore = $(this);

						if ($nextLink.length) {
							$loadMore.attr('href', $nextLink.attr('href'));
							$loadMore.removeClass('loading');
						} else {
							$loadMore.remove();
						}
					},
					error: function () {
						$(this).remove();
					},
				});
			});
		}
	}
}


/**
 * AJAX Load More buttons
 */
function initAjaxLoadMore() {

	var $pagination = $('.archive__pagination');

	if ($pagination.length) {
		transformPaginationControl($pagination, 'Load more', function ($response) {
			var $posts = $response.find('.archive__ajaxed').children();
			$('.archive__ajaxed').append($posts);
			return $response.find('.archive__pagination a.next');
		});
	}
}


/**
 * Auto-submit archive filters
 */
function initAutoSubmitFilters() {

	var filterBar = document.querySelector('.filters');
	var filters = document.querySelectorAll('.filters__select__selectbox');
	var form = document.getElementById('filter-form');

	if((filters != null) && (form != null)) {
		for (var i = 0; i < filters.length; i++) {

			filters[i].addEventListener('change', function () {
				filterBar.classList.add('loading');
				form.submit();
			});
		}
	}
}


/**
 * Framework table module interactivity
 */
function initFrameworkTable() {

	var module = document.querySelector('.module__framework-table');

	if(module != null) {

		/**
		 * Find the highest image and copy divs and make the rest match
		 * @type {NodeListOf<Element>}
		 */
		var images = document.querySelectorAll('.framework-table__column__header__image');
		var copy = document.querySelectorAll('.framework-table__column__header__copy');
		var imageHeight = 0;
		var copyHeight = 0;
		var copyFullHeight = 0;

		for (var i = 0; i < images.length; i++) {
			if (images[i].offsetHeight > imageHeight) {
				imageHeight = images[i].offsetHeight;
			}
		}

		for (var j = 0; j < copy.length; j++) {
			var thisHeight = Math.round(jQuery(copy[j]).height());
			if (thisHeight > copyHeight) {
				copyHeight = thisHeight // excluding padding
				copyFullHeight = copy[j].offsetHeight; // includes padding etc
			}
		}

		var totalHeight = (imageHeight + copyFullHeight);

		jQuery('.framework-table__column__header__image').height(imageHeight);
		jQuery('.framework-table__column__header__copy').height(copyHeight);
		jQuery('.framework-table__column__header').css({
			'height': totalHeight + 'px',
			'min-height': totalHeight + 'px',
			'flex-basis': totalHeight + 'px',
		});

		//jQuery('a.framework-table__column__header').click(function() { return false; }); //disable click on image


		/**
		 * Row hovers on desktop
		 */
		if(!isMobile()) {

			var rowLinks = document.querySelectorAll('.framework-table__column__rows a');
			var fullHeightItems = document.querySelectorAll('.framework-table__column__rows a.full-height');

			// Loop through links, find others with the same class
			// and add hover styles to the matching links when this link is hovered on
			for(var i = 0; i < rowLinks.length; i++) {

				rowLinks[i].addEventListener('mouseover', function() {

					var currentClasses = this.className;

					// Find matching links and labels (anything with matching data-linkgroup attribute) and add hover styles to them
					var linkGroup = this.dataset.linkgroup;
					var matchingRows = document.querySelectorAll('[data-linkgroup="'+linkGroup+'"]');
					for(var j = 0; j < matchingRows.length; j++) {
						matchingRows[j].classList.add('hovered');
					}

					if(currentClasses){
						
						currentClasses = currentClasses.replace('active','').replace(' ','');

						// Also add this hover style to any full-height ones
						for (var l = 0; l < fullHeightItems.length; l++) {
							fullHeightItems[l].classList.add(currentClasses);
							fullHeightItems[l].classList.add('hovered');
						}
					}
				});

				// Remove the additional hover styles when we stop hovering
				rowLinks[i].addEventListener('mouseout', function() {
					var linkGroup = this.dataset.linkgroup;

					var matchingRows = document.querySelectorAll('[data-linkgroup="'+linkGroup+'"]');
					for(var k = 0; k < matchingRows.length; k++) {
						matchingRows[k].classList.remove('hovered');
					}

					for (var l = 0; l < fullHeightItems.length; l++) {
						fullHeightItems[l].className = '';
					}
				});
			}
		}


		/**
		 * Carousel on mobile
		 */
         jQuery('.module__framework-table > .container > .row').slick({
			infinite: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			mobileFirst: true,
			responsive: [
				{
					breakpoint: 0,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						infinite: true,
						dots: false,
						arrows: true,
						appendArrows: jQuery('.framework-table__arrows')
					}
				},
				{
					breakpoint: 960,
					settings: 'unslick'
				}
			]
		});
	}
}

function initAudienceSearch(){
	var isLoading = false;
	var selectOne = ''; // value of first select field
	var selectTwo = ''; // value of second select field
	var searchParam = ''; // value of the search field
	var postType = ''; // the post type
	var paged = 0;
	var currentAudience = '';

	$('.archive__audience .audience-search').submit(function(e){
		e.preventDefault();
		doSearchAjax($(this).closest('.tab-pane'), true, false);
	});

	$('.archive__audience .filters__select__selectbox-wrapper select').change(function(e){
		e.preventDefault();
		doSearchAjax($(this).closest('.tab-pane'), true, false);
	});

	$('.archive__audience [data-paged]').click(function(e){
		e.preventDefault();
		doSearchAjax($(this).closest('.tab-pane'), false, true);
	});

	$('.reset-search').click(function(e) {
		e.preventDefault();
		$('.audience-search input[type=search]').val(''); // empty the search field
		doSearchAjax($(this).closest('.tab-pane'), true, false); // run the search
	});


	function doSearchAjax(parent, replace, page){
		if(isLoading){
			return;
		}
		isLoading = true;

		$('.archive__ajaxed, .filters').addClass('loading');

		selectOne = $('.filters__select__selectbox-wrapper:nth-of-type(1) select', parent).val();
		selectTwo = $('.filters__select__selectbox-wrapper:nth-of-type(2) select', parent).val();
		searchParam = $('input[name="s"]', parent).val();
		postType = $('input[name="ptype"]', parent).val();
		paged = $('[data-paged]', parent).data('paged');
		currentAudience = $('input[name="audience"]', parent).val();

		 // update current posttypes pagination value
		if(page){
			paged++;
		}else{
			// filters changed, reset pagination
			paged = 1;
		}
		$('[data-paged]', parent).data('paged', paged);

		$.ajax({
			url: _ajaxurl,
			method: 'post',
			data:{
				action: 'search_audience',
				search: searchParam,
				posttype: postType,
				selectone: selectOne,
				selecttwo: selectTwo,
				currentaudience: currentAudience,
				paged: paged
			}
		}).done(function(data){
			var response = JSON.parse(data);
			isLoading = false;
			$('.archive__ajaxed, .filters').removeClass('loading');


			if(response.html){
				if(replace){
					$('.archive__ajaxed .row', parent).html(response.html);
				}else{
					$('.archive__ajaxed .row', parent).append(response.html);
				}
			}else{
				$('.archive__ajaxed .row', parent).html('<p>No Results Found</p>');
			}

			if(response.has_next_page){
				$('.archive__btn-wrap', parent).removeClass('archive__btn-wrap--hide');
			}else{
				$('.archive__btn-wrap', parent).addClass('archive__btn-wrap--hide');
			}
		});
	}
}

