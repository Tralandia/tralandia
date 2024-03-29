
/**
 * component: NavBar
 */

(function($) {

	$.navigationBar = function(el, options) {

		/**
		 * navigationBar Elements
		 */
		var base 		= this;
		base.el 		= el;
		base.$el 		= $(el);
		base.$tabs 		= base.$el.find('#navigationBarTabs');
		base.$contents 	= base.$el.find('#navigationBarTabContent');
		base.$shareContent = base.$el.find('#shareContent')

		// vars
		base.activeTabName 		= null;
		base.currentRental		= null;
		base.navigationBarShareShown 	= false;
		base.favoritesLinks 	= null;

		base.searchListUrl = $('a#shareSearchListLink').attr('data-href');
		base.$shareTextInput = $('#clipboardLinkShare');
		/**
		 * Instalation
		 */
		base.init = function()
		{
			base.currentRental = base.$el.attr('current-rental');
			base.bindings();

			base.$tabs.find('li.active').trigger('click');

			base.checkTabs();
			base.favoritesSync();
		}

		/**
		 * navigationBar actions
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
				base.hideTab('navigationBarFavorites');
			}
			base.setActive(tabName);
		}

		base.hasActiveTab = function(tabName)
		{
			active = this.$tabs.find('li.active').attr('for');
			return (tabName ? (active==tabName ? true : false) : (active ? true : false));
		}

		base.isVisible = function(tabName)
		{
			return this.$tabs.find('li[for='+tabName+']').is(':visible');
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
					if (!base.isVisible(tabName)) base.showTab(tabName);
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



			if (base.navigationBarShareShown){
				base.hideShare();
				return false;
			}
			
			removejscssfile('http://platform.twitter.com/widgets.js','js');
			removejscssfile('https://apis.google.com/js/plusone.js','js');

			$this = $(this);
			tabName = $this.attr('for');
			
			if (tabName=='navigationBarFavorites') {
				if (base.favoritesLinks) {
					initNavBarShare(base.favoritesLinks);
				} else {

					var shareUrl = $('#favoritesStaticContainer').attr('data-favorites-link');
					base.importShareLink(shareUrl, function(data){
						initNavBarShare(data);
						base.favoritesLinks = data;

					});
				}
			} else if (tabName=='navigationBarSearchResults') {


				base.$shareTextInput.val(base.searchListUrl);

				html = base.$tabs.find('li.share[for="navigationBarSearchResults"] ul')[0].outerHTML;
				$socialLinks = base.$shareContent.find('#dynamicShareContainer').html(html);
				Socialite.load($socialLinks);
				$('body').removeAttr('data-socialPluginsInit');
			}

			base.showShare();
		}

		base.showShare = function()
		{
			base.$shareContent.show();
			base.navigationBarShareShown = true;
			base.innerClick = true;
		}

		base.hideShare = function()
		{
			base.$shareContent.hide();
			base.navigationBarShareShown = false;
			base.innerClick = false;
		}

		base.importShareLink = function(fromUrl, callback)
		{
			jQuery.getJSON(fromUrl, callback);
		}

		/**
		 * navigationBar favorites
		 */
		base.removeFavorite = function(e)
		{
			base.favoritesLinks = null;
			Favorites.removeLink(e, this);
			if (!Favorites._getFavoritesLength()) {
				base.hideTab('navigationBarFavorites');
			}
			base.checkTabs();
		}

		base.toggleAddFavorite = function(e)
		{
			base.favoritesLinks = null;
			Favorites.toggleAdd(e, this);
			base.showTab('navigationBarFavorites');
			base.checkTabs();
			$(document).trigger('scroll');
		}

		base.setActiveRental = function()
		{
			base.$contents.find('li').removeClass('active');
			base.$contents.find('li[rel='+base.currentRental+']').addClass('active');
		}

		base.bodyActions = function(event)
		{
			if (base.innerClick) {
				base.innerClick = false;
				return false;
			}
			if (base.navigationBarShareShown) {
				if (!$(event.target).parents('#shareContent').length) base.hideShare();
			}
		}

		base.favoritesSync = function()
		{

			if(!Favorites.checkChanges()){
				Favorites.updateList();
				base.checkTabs();
			}		
			setTimeout(base.favoritesSync,Favorites.autoupdateTime);
		}

		/**
		 * Bindings
		 */
		base.bindings = function()
		{
			// tabs
			base.$tabs.find('li').bind('click', base.setActive);

			// favorites
			base.$contents.on('click', 'a.removeLink', base.removeFavorite);
			base.$contents.find('a.removeLink').click(base.removeFavorite);
			$('.addToFavorites').on('click', base.toggleAddFavorite);

			// share
			$('body').on('click', base.bodyActions);
			base.$tabs.on('click', 'a.share', base.share);
			base.$el.on('click', 'a.close', base.hideShare);
		}

		/**
		 * cookies
		 */
		base.setLastActive = function()
		{
			$.cookie("navigationBarActive", base.activeTabName, {
				expires: options.cookieExpiration,
				path: '/'
			});
		}

		base.getLastActive = function()
		{
			return $.cookie("navigationBarActive");
		}

		/**
		 * Run...
		 */
		base.init();
	}

	$.fn.navigationBar = function(options, params) {
		return (new $.navigationBar(this, options));
	};

})(jQuery);

$(function() {

	// init favorites
	favorites = Favorites;
	favorites.init();

	// init navigationBar
	$('#navigationBar').navigationBar({
		cookieExpiration: 7 // in days
	});
});
