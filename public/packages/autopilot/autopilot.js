
var Navbar = function() {
	this.navbar = {}
}
Navbar.prototype = {
	toggleDetails: function() {
		$(this.navbar).toggleClass('details');
		$('button[data-toggle="button"]', this.navbar).toggleClass('dropup');
	}
};

var Tab = function() {
	this.options = {
		selectedClass: 'active',
		topSpace: 145
	};
	this.el = { 
		id: null,
		tabContent: null,
		tabNav: null,
		wrapper: null,
		closed: false
	};
}
Tab.prototype = {
	open: function(name, src) {
		this.blur();
		id = this.getNewID();
		tabHTML =
			'<li onclick="tab.focus(' + id + ');" tab="' + id + '" class="">' + 
				name + 
				' <button class="btn btn-mini btn-danger" onclick="tab.close(' + id + ');"><i class="icon-remove icon-white"></i></button>'+
				' <button class="btn btn-mini btn-success" onclick="tab.refresh(' + id + ');"><i class="icon-refresh icon-white"></i></button>'+
			'</li>';

		contentHTML = '<div class="tab-content" tab="' + id + '"><iframe src="' + src + '"></iframe></div>';
		this.el.wrapper.append(contentHTML);
		$('ul.nav', this.el.wrapper).append(tabHTML);
		this.setHeight();
		return this.focus(id);
	},
	close: function(id) {
		this.el = this.getObj(id);
		this.el.tabContent.detach();
		this.el.tabNav.detach();
		this.el.closed = true;
		this.blur();
	},
	focus: function(id) {
		if (this.el.closed) {
			this.el.closed = false;
			return this.focusFirst();
		}
		if (this.el.id) this.blur();
		this.el = this.getObj(id);
		this.el.tabContent.addClass(this.options.selectedClass);
		this.el.tabNav.addClass(this.options.selectedClass);
		return this;
	},
	blur: function() {
		this.el.tabContent.removeClass(this.options.selectedClass);
		this.el.tabNav.removeClass(this.options.selectedClass);
		return this;
	},
	focusFirst: function() {
		first = $('ul.nav li', this.el.wrapper).attr('tab');
		return this.focus(first);
	},
	setHeight: function(h) {
		h = $(window).height() - this.options.topSpace;
		$('.tab-content iframe', this.el.wrapper).height(h);
	},
	refresh: function(id) {
		iframe = $('iframe', this.el.tabContent);
		iframe.attr('src', iframe.attr('src'));
		return this;
	},
	getNewID: function() {
		i = 1;
		$('.tab-content', this.el.wrapper).each(function() {
			id = parseInt($(this).attr('tab'));
			if (id > i) i = id;
			i++;
		});
		return i;
	},
	getObj: function(id) {
		if (id == this.el.id || id === undefined) return this.el;
		this.el.tabContent = $('.tab-content[tab='+id+']', this.el.wrapper);
		this.el.tabNav = $('li[tab='+id+']', this.el.wrapper);
		this.el.id = id;
		return this.el;
	}
};

var tab = new Tab;
var navbar = new Navbar;

$(function() {

	navbar.navbar = $('.navbar-fixed-top');

	// TABS
	tab.el.wrapper = $('.tabs');
	tab.focusFirst();
	tab.setHeight();

	$(window).resize(function(a,b) {
		tab.setHeight();
	});

	$('a').live('click', function(event) {
		event.preventDefault();
		name = $(this).text();
		src = $(this).attr('href');
		tab.open(name, src);
	});

	$('.calendar').livequery(function() {
		$(".calendar").calendar();
	});

});