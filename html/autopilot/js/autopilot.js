
var Navbar = function() {
	this.navbar = $('.navbar-fixed-top');
}
Navbar.prototype = {
	toggleDetails: function() {
		$(this.navbar).toggleClass('details');
		$('button[data-toggle="button"]', this.navbar).toggleClass('dropup');
	}
};

var iFrame = function() {
	this.iframe = {}
}
iFrame.prototype = {
	toggleMinimize: function(id) {
		this.iframe = this.getObj(id);
		this.iframe.toggleClass('minimized');
	},
	toggleFullScreen: function(id) {
		this.iframe = this.getObj(id);
		this.iframe.toggleClass('fullscreen');
	},
	close: function(id) {
		this.iframe = this.getObj(id);
		this.iframe.detach();
	},
	getObj: function(id) {
		return $('.content[iframe-id='+id+']');
	}
};

var iframe = new iFrame;
var navbar = new Navbar;