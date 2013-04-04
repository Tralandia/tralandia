
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
		base.$prevLink	= base.$el.find('a.leftArrow');
		base.$nextLink	= base.$el.find('a.rightArrow');
		base.$contents 	= base.$el.find('#navbarTabContent');

		// vars
		base.activeTabName 	= null;
		base.currentRental	= null;

		/**
		 * Instalation
		 */
		base.init = function()
		{
			base.currentRental = base.$el.attr('current-rental');
			base.bindTabs();
			base.bindFavorites();
			base.$tabs.find('li.active').trigger('click');
		}

		/**
		 * navBar actions
		 */
		base.setActive = function(obj)
		{
			if (typeof obj == 'object') {
				base.activeTabName = $(obj.target).attr('for');
			} else if (typeof obj == 'string') {
				base.activeTabName = obj;
			} else {
				return false;
			}

			base.setActiveTab();
			base.setActiveContent();
			base.setLastActive();
			base.updateNav();
		}

		base.setFirstActive = function()
		{
			base.$tabs.find('li').not(':hidden').first().trigger('click');
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
			base.$tabs.find('li[for='+tabName+']').addClass('hide').removeClass('active');
			base.$contents.find('div#'+tabName).addClass('hide').removeClass('active');
			if (!base.hasActive()) {
				base.setFirstActive();
			}
		}

		base.showTab = function(tabName)
		{
			base.$tabs.find('li[for='+tabName+']').removeClass('hide');
			base.$contents.find('div#'+tabName).removeClass('hide');
			if (!Favorites._getFavoritesLength()) {
				base.hideTab('navBarFavorites');
			}
			base.setActive(tabName);
		}

		base.hasActive = function(tabName)
		{
			active = this.$tabs.find('li.active').attr('for');
			return (tabName ? (active==tabName ? true : false) : (active ? true : false));
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
			base.setActiveRental();
		}

		base.toggleAddFavorite = function(e)
		{
			Favorites.toggleAdd(e, this);
			base.showTab('navBarFavorites');
			base.setActiveRental();
		}

		base.setActiveRental = function()
		{
			base.$contents.find('li').removeClass('active');
			base.$contents.find('li[rel='+base.currentRental+']').addClass('active');
		}

		base.updateNav = function()
		{
			$active = base.$contents.find('div#'+base.activeTabName+' ul li.active');
			if (!$active[0]) {
				$active = base.$contents.find('div#'+base.activeTabName+' ul li:first-child');
			}

			prevLink = $active.prev('li').find('a.link').attr('href');
			nextLink = $active.next('li').find('a.link').attr('href');
			base.$prevLink.attr('href', prevLink);
			base.$nextLink.attr('href', nextLink);
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
			$('.addToFavorites').on('click', base.toggleAddFavorite);
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
		cookieExpiration: 7 // in days
	});
});
