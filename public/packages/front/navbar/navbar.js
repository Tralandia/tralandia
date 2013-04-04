
/**
 * component: NavBar
 */

(function($) {

	$.navBar = function(el, options) {

		/**
		 * navBar Elements
		 */
		var base 		= this;
		base.el 		= el;
		base.$el 		= $(el);
		base.$tabs 		= base.$el.find('#navBarTabs');
		base.$contents 	= base.$el.find('#navbarTabContent');

		// vars
		base.activeTabName 	= null;

		/**
		 * Instalation
		 */
		base.init = function()
		{
			base.bindTabs();
			base.bindFavorites();
			base.setActive();	
		}

		/**
		 * navBar actions
		 */
		base.setActive = function(obj)
		{
			if (typeof obj == 'object') {
				base.activeTabName = $(obj.target).attr('for');
			} else {
				if (!options.autoSetTabs) return false;
				base.activeTabName = base.getLastActive();
			}

			base.setActiveTab();
			base.setActiveContent();
			base.setLastActive();
		}

		base.setFirstActive = function()
		{
			base.setActive(base.$tabs.find('li').not(':hidden').first().attr('for'));
		}

		base.setActiveTab = function()
		{
			if (!base.activeTabName) return false;
			base.$tabs.find('li').removeClass('active');
			base.$tabs.find('li[for='+base.activeTabName+']:not(.share)').addClass('active');
			base.$tabs.find('li.share').addClass('hide');
			base.$tabs.find('li[for='+base.activeTabName+'].share').removeClass('hide');
		}

		base.setActiveContent = function()
		{
			if (!base.activeTabName) return false;
			base.$contents.find('.tab-pane').removeClass('active');
			base.$contents.find('.tab-pane[id='+base.activeTabName+']').addClass('active');
		}

		base.hideTab = function(tabName)
		{
			base.$tabs.find('li[for='+tabName+']').addClass('hide');
			base.$contents.find('div#'+tabName).addClass('hide');
			base.setFirstActive();
		}

		base.showTab= function(tabName)
		{
			base.$tabs.find('li[for='+tabName+']').removeClass('hide');
			base.$contents.find('div#'+tabName).removeClass('hide');
			base.setFirstActive();
		}

		/**
		 * navBar favorites
		 */
		base.removeFavorite = function(e)
		{
			Favorites.removeLink(e, this);
			if (!Favorites._getFavoritesLength()) {
				base.hideTab('navBarFavorites');
			}
		}

		base.addFavorite = function(e)
		{
			Favorites.toggleAdd(e, this);
			if (Favorites._getFavoritesLength()) {
				base.showTab('navBarFavorites');
			}
		}

		/**
		 * navBar bindings
		 */
		base.bindTabs = function()
		{
			base.$tabs.find('li').bind('click', base.setActive);
			return base;
		}

		base.bindFavorites = function()
		{
			$('a.removeLink').live('click', base.removeFavorite);
			$('a.removeLink').on('click', base.removeFavorite);
			$('.addToFavorites').on('click', base.addFavorite);
		}

		/**
		 * cookies
		 */
		base.setLastActive = function()
		{
			$.cookie("navBarActive", base.activeTabName, {
				expires: options.cookieExpiration,
				path: '/'
			});
		}

		base.getLastActive = function()
		{
			return $.cookie("navBarActive");
		}

		/**
		 * Run...
		 */
		base.init();
	}

	$.fn.navBar = function(options, params) {
		return (new $.navBar(this, options));
	};

})(jQuery);

$(function() {

	// init favorites
	favorites = Favorites;
	favorites.init();

	// init navbar
	$('#navBar').navBar({
		cookieExpiration: 7, // in days
		autoSetTabs: false,
		debug: true
	});
});
