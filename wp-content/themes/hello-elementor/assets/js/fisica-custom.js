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
