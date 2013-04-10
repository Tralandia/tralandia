
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
		// base.$prevLink	= base.$el.find('a.leftArrow');
		// base.$nextLink	= base.$el.find('a.rightArrow');
		base.$contents 	= base.$el.find('#navbarTabContent');
		base.$shareContent = base.$el.find('#shareContent')

		// vars
		base.activeTabName 		= null;
		base.currentRental		= null;
		base.navBarShareShown 	= false;

		/**
		 * Instalation
		 */
		base.init = function()
		{
			base.currentRental = base.$el.attr('current-rental');
			base.bindTabs();
			base.bindFavorites();

			base.$tabs.find('li.active').trigger('click');

			base.checkTabs();
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
				base.$tabs.find('li').not(':hidden').first().trigger('click');
				return false;
			}

			base.setActiveTab();
			base.setActiveContent();
			base.setLastActive();
			// base.updateNav();
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
			if (!base.hasActiveTab()) {
				base.setActive();
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

		base.hasActiveTab = function(tabName)
		{
			active = this.$tabs.find('li.active').attr('for');
			return (tabName ? (active==tabName ? true : false) : (active ? true : false));
		}

		base.checkTabs = function()
		{
			hasRentals = false;
			base.$contents.find('.tab-pane ul').each(function() {
				$this = $(this);
				tabName = $this.parents('.tab-pane').attr('id');
				if (!base.hasRental(tabName)) {
					base.hideTab(tabName);
				} else {
					hasRentals = true;
				}
			});

			if (!hasRentals) {
				base.hide();
			} else {
				base.show();
			}
			base.setActiveRental();
		}

		base.hide = function()
		{
			base.$el.parent().slideUp(200, function() {
				$(this).addClass('hide');
			});
		}

		base.show = function()
		{
			base.$el.parent().slideDown(200, function() {
				$(this).removeClass('hide');
			});
		}

		base.hasRental = function(tabName)
		{
			return base.$contents.find('#'+tabName+' ul li').not('.template').length;
		}

		base.share = function()
		{
			removejscssfile('http://platform.twitter.com/widgets.js','js');
			removejscssfile('https://apis.google.com/js/plusone.js','js');

			$this = $(this);
			tabName = $this.attr('for');
			
			if (tabName=='navBarFavorites') {
				var shareUrl = $('#favoritesStaticContainer').attr('data-favoritesLink');
				importShareLink(shareUrl , function(d){
					initNavBarShare(d.link);
				});
			} else if (tabName=='navBarSerchResults') {
				initNavBarShare($this.attr('data-href'));
			}

			if($this.hasClass('open')){
				base.hideShare();
			} else {
				base.showShare();
			}

			$this.toggleClass('open');
		}

		base.showShare = function()
		{
			base.$shareContent.show();
			base.navBarShareShown = true;
		}

		base.hideShare = function()
		{
			base.$shareContent.hide();
			base.navBarShareShown = false;
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
			base.checkTabs();
		}

		base.toggleAddFavorite = function(e)
		{
			Favorites.toggleAdd(e, this);
			base.showTab('navBarFavorites');
			base.checkTabs();
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
			base.$tabs.on('click', 'a.share', base.share);
			// $('body').on('click', base.bodyActions);
		}

		// base.bodyActions = function()
		// {
		// 	if (base.navBarShareShown) {
		// 		base.hideShare();
		// 	}
		// }

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
