( function() {
	function buildBolsasSubmenu() {
		var config = window.fisicaMenuData || {};
		var links = Array.isArray( config.bolsas ) ? config.bolsas : [];
		var menuItem = document.querySelector( '#menu-item-1014' );

		if ( ! menuItem || ! links.length || menuItem.querySelector( '.fisica-sub-menu--nested' ) ) {
			return;
		}

		// If the menu already has a native WordPress/HFE submenu, keep it as-is
		// and avoid injecting a second toggle arrow.
		if ( menuItem.querySelector( ':scope > .sub-menu, :scope > ul.sub-menu' ) ) {
			return;
		}

		var anchor = menuItem.querySelector( 'a' );
		if ( ! anchor ) {
			return;
		}

		menuItem.classList.add( 'menu-item-has-children', 'parent', 'hfe-has-submenu', 'fisica-menu-item-bolsas' );

		var container = document.createElement( 'div' );
		container.className = 'hfe-has-submenu-container fisica-bolsas-toggle';
		container.setAttribute( 'tabindex', '0' );
		container.setAttribute( 'role', 'button' );
		container.setAttribute( 'aria-haspopup', 'true' );
		container.setAttribute( 'aria-expanded', 'false' );

		var icon = document.createElement( 'span' );
		icon.className = 'hfe-menu-toggle sub-arrow hfe-menu-child-1 fisica-bolsas-icon';
		icon.innerHTML = '<i class="fa"></i>';

		anchor.parentNode.insertBefore( container, anchor );
		container.appendChild( anchor );
		anchor.appendChild( icon );

		var submenu = document.createElement( 'ul' );
		submenu.className = 'sub-menu fisica-sub-menu fisica-sub-menu--nested';

		links.forEach( function( item ) {
			var li = document.createElement( 'li' );
			li.className = 'menu-item menu-item-type-post_type menu-item-object-page hfe-creative-menu';

			var link = document.createElement( 'a' );
			link.className = 'hfe-sub-menu-item';
			link.href = item.url || '#';
			link.textContent = item.label || '';

			li.appendChild( link );
			submenu.appendChild( li );
		} );

		menuItem.appendChild( submenu );

		function toggleMenu( forceOpen ) {
			var nextState = typeof forceOpen === 'boolean' ? forceOpen : ! menuItem.classList.contains( 'fisica-menu-open' );
			menuItem.classList.toggle( 'fisica-menu-open', nextState );
			container.setAttribute( 'aria-expanded', nextState ? 'true' : 'false' );
		}

		container.addEventListener( 'click', function( event ) {
			event.preventDefault();
			event.stopPropagation();
			toggleMenu();
		} );

		container.addEventListener( 'keydown', function( event ) {
			if ( event.key === 'Enter' || event.key === ' ' ) {
				event.preventDefault();
				toggleMenu();
			}

			if ( event.key === 'Escape' ) {
				toggleMenu( false );
			}
		} );

		menuItem.addEventListener( 'mouseleave', function() {
			if ( window.innerWidth > 1024 ) {
				toggleMenu( false );
			}
		} );

		document.addEventListener( 'click', function( event ) {
			if ( ! menuItem.contains( event.target ) ) {
				toggleMenu( false );
			}
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', buildBolsasSubmenu );
	} else {
		buildBolsasSubmenu();
	}
}() );

( function() {
	function normalizeLocalUrl( url ) {
		if ( typeof url !== 'string' || ! url ) {
			return url;
		}

		return url.replace( /^https:\/\/localhost/i, 'http://localhost' );
	}

	function ensureHeaderAndFooterBranding() {
		var branding = window.fisicaBrandingData || {};
		var leftHeaderContainer = document.querySelector( '.elementor-element-fisheaderlogo2' );
		var rightHeaderContainer = document.querySelector( '.elementor-element-fisheaderuerj2' );
		var existingUerjWidget = document.querySelector( '.elementor-element-fislogoimguerj' );
		var footerLogoImage = document.querySelector( '.elementor-element-fisfooterlogo2 img' );
		var uerjLogoData = branding.uerjLogo || {};
		var footerLogoData = branding.footerLogo || {};
		var normalizedUerjUrl = normalizeLocalUrl( uerjLogoData.url || '' );
		var headerContainer = rightHeaderContainer || leftHeaderContainer;
		var existingUerjImage = headerContainer ? headerContainer.querySelector( 'img[src="' + normalizedUerjUrl + '"]' ) : null;

		if (
			headerContainer &&
			uerjLogoData.url &&
			! existingUerjWidget &&
			! existingUerjImage &&
			! headerContainer.querySelector( '.fisica-header-uerj-logo' )
		) {
			var uerjLink = document.createElement( 'a' );
			var uerjImage = document.createElement( 'img' );

			uerjLink.className = 'fisica-header-uerj-logo';
			uerjLink.href = '/';
			uerjLink.setAttribute( 'aria-label', uerjLogoData.alt || 'UERJ' );

			uerjImage.className = 'fisica-header-uerj-logo__image';
			uerjImage.src = normalizedUerjUrl;
			uerjImage.alt = uerjLogoData.alt || 'UERJ';
			uerjImage.decoding = 'async';
			uerjImage.loading = 'eager';

			uerjLink.appendChild( uerjImage );
			headerContainer.appendChild( uerjLink );
		}

		if ( footerLogoImage && footerLogoData.url ) {
			footerLogoImage.src = normalizeLocalUrl( footerLogoData.url );
			footerLogoImage.style.display = 'block';
			footerLogoImage.style.visibility = 'visible';
			footerLogoImage.style.opacity = '1';
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', ensureHeaderAndFooterBranding );
	} else {
		ensureHeaderAndFooterBranding();
	}
}() );

( function() {
	function initDocentesTabs() {
		var wrappers = document.querySelectorAll( '[data-docentes-tabs]' );

		if ( ! wrappers.length ) {
			return;
		}

		wrappers.forEach( function( wrapper ) {
			var tabs = Array.prototype.slice.call( wrapper.querySelectorAll( '[data-docentes-tab]' ) );
			var panels = Array.prototype.slice.call( wrapper.querySelectorAll( '.fisica-docentes-panel' ) );

			if ( ! tabs.length || ! panels.length ) {
				return;
			}

			function activateTab( tabToActivate ) {
				var targetId = tabToActivate.getAttribute( 'data-docentes-tab' );

				tabs.forEach( function( tab ) {
					var isActive = tab === tabToActivate;
					tab.classList.toggle( 'is-active', isActive );
					tab.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
					tab.setAttribute( 'tabindex', isActive ? '0' : '-1' );
				} );

				panels.forEach( function( panel ) {
					var isActive = panel.getAttribute( 'data-docentes-panel' ) === targetId;
					panel.hidden = ! isActive;
					panel.classList.toggle( 'is-active', isActive );
				} );
			}

			tabs.forEach( function( tab, index ) {
				tab.addEventListener( 'click', function() {
					activateTab( tab );
				} );

				tab.addEventListener( 'keydown', function( event ) {
					if ( event.key !== 'ArrowRight' && event.key !== 'ArrowLeft' ) {
						return;
					}

					event.preventDefault();
					var direction = event.key === 'ArrowRight' ? 1 : -1;
					var nextIndex = ( index + direction + tabs.length ) % tabs.length;
					tabs[ nextIndex ].focus();
					activateTab( tabs[ nextIndex ] );
				} );
			} );

			var activeTab = wrapper.querySelector( '[data-docentes-tab].is-active' ) || tabs[0];
			activateTab( activeTab );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initDocentesTabs );
	} else {
		initDocentesTabs();
	}
}() );

( function() {
	function initInstituteTimeline() {
		var timelines = document.querySelectorAll( '[data-fisica-timeline]' );

		if ( ! timelines.length ) {
			return;
		}

		timelines.forEach( function( timeline ) {
			if ( timeline.dataset.fisicaTimelineReady === 'true' ) {
				return;
			}

			var tabs = Array.prototype.slice.call( timeline.querySelectorAll( '[data-fisica-timeline-tab]' ) );
			var panels = Array.prototype.slice.call( timeline.querySelectorAll( '.timeline-content' ) );

			if ( ! tabs.length || ! panels.length ) {
				return;
			}

			function activateTab( tabToActivate ) {
				if ( ! tabToActivate ) {
					return;
				}

				var targetKey = tabToActivate.getAttribute( 'data-timeline-key' );

				tabs.forEach( function( tab ) {
					var isActive = tab === tabToActivate;
					tab.classList.toggle( 'is-active', isActive );
					tab.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
					tab.setAttribute( 'tabindex', isActive ? '0' : '-1' );
				} );

				panels.forEach( function( panel ) {
					var isActive = panel.getAttribute( 'data-timeline-key' ) === targetKey;
					panel.hidden = ! isActive;
					panel.classList.toggle( 'is-active', isActive );
				} );
			}

			timeline.addEventListener( 'click', function( event ) {
				var tab = event.target.closest( '[data-fisica-timeline-tab]' );

				if ( ! tab || ! timeline.contains( tab ) ) {
					return;
				}

				event.preventDefault();
				activateTab( tab );
			} );

			tabs.forEach( function( tab, index ) {
				tab.addEventListener( 'keydown', function( event ) {
					var nextIndex = null;

					if ( event.key === 'ArrowRight' || event.key === 'ArrowDown' ) {
						nextIndex = ( index + 1 ) % tabs.length;
					}

					if ( event.key === 'ArrowLeft' || event.key === 'ArrowUp' ) {
						nextIndex = ( index - 1 + tabs.length ) % tabs.length;
					}

					if ( event.key === 'Home' ) {
						nextIndex = 0;
					}

					if ( event.key === 'End' ) {
						nextIndex = tabs.length - 1;
					}

					if ( nextIndex === null ) {
						return;
					}

					event.preventDefault();
					tabs[ nextIndex ].focus();
					activateTab( tabs[ nextIndex ] );
				} );
			} );

			var initialTab = timeline.querySelector( '[data-fisica-timeline-tab].is-active' ) ||
				timeline.querySelector( '[data-fisica-timeline-tab][aria-selected="true"]' ) ||
				tabs[0];

			activateTab( initialTab );
			timeline.dataset.fisicaTimelineReady = 'true';
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initInstituteTimeline );
	} else {
		initInstituteTimeline();
	}
}() );
