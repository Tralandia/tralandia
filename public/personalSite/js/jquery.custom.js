jQuery(document).ready(function($){
    /*------------------------------------------------------------------------------*/
    /* Set cookie for retina displays; refresh if not set
    /*------------------------------------------------------------------------------*/

    (function(){
        "use strict";
        if( document.cookie.indexOf('retina') === -1 && 'devicePixelRatio' in window && window.devicePixelRatio === 2 ){
            document.cookie = 'retina=' + window.devicePixelRatio + ';';
            window.location.reload();
        }
    })();

    /*------------------------------------------------------------------------------*/
    /* Mobile Navigation Setup
    /*------------------------------------------------------------------------------*/
    var mobileNav = $('#primary-nav').clone().attr('id', 'mobile-primary-nav');

    $('#primary-nav ul').supersubs({
        minWidth: 10,
        maxWidth: 27,
        extraWidth: 1
    }).superfish({
        delay: 100,
        animation: {opacity:'show', height:'show'},
        speed: 'fast',
        autoArrows: false,
        dropShadows: false
    });

    function stag_mobilemenu(){
        "use strict";
        var windowWidth = $(window).width();
        if( windowWidth <= 992 ) {
            if( !$('#mobile-nav').length ) {
                $('<a id="mobile-nav" href="#mobile-primary-menu"><i class="stag-icon icon-bars"></i></a>').prependTo('#navigation');
                mobileNav.insertAfter('#mobile-nav').wrap('<div id="mobile-primary-nav-wrap" />');
                mobile_responder();
            }
        }else{
            mobileNav.css('display', 'none');
        }
    }
    stag_mobilemenu();

    function mobile_responder(){
        $('#mobile-nav').click(function(e) {
            if( $('body').hasClass('ie8') ) {
                var mobileMenu = $('#mobile-primary-nav');
                if( mobileMenu.css('display') === 'block' ) {
                    mobileMenu.css({
                        'display' : 'none'
                    });
                } else {
                    mobileMenu.css({
                        'display' : 'block',
                        'height' : 'auto',
                        'z-index' : 999,
                        'position' : 'absolute'
                    });
                }
            } else {
                $('#mobile-primary-nav').stop().slideToggle(500);
            }
            e.preventDefault();
        });
    }

    $(window).resize(function() {
        stag_mobilemenu();
    });

    /*------------------------------------------------------------------------------*/
    /* Better fallback for input[placeholder]
    /*------------------------------------------------------------------------------*/
    if (! ("placeholder" in document.createElement("input"))) {
        $('*[placeholder]').each(function() {
            var that = $(this);
            var placeholder = $(this).attr('placeholder');
            if ($(this).val() === '') {
                that.val(placeholder);
            }
            that.bind('focus',
            function() {
                if ($(this).val() === placeholder) {
                    this.plchldr = placeholder;
                    $(this).val('');
                }
            });
            that.bind('blur',
            function() {
                if ($(this).val() === '' && $(this).val() !== this.plchldr) {
                    $(this).val(this.plchldr);
                }
            });
        });
        $('form').bind('submit',
        function() {
            $(this).find('*[placeholder]').each(function() {
                if ($(this).val() === $(this).attr('placeholder')) {
                    $(this).val('');
                }
            });
        });
    }


    /*------------------------------------------------------------------------------*/
    /* Animated back to top navigation
    /*------------------------------------------------------------------------------*/

    $("#backToTop, #logo").click(function(e){
        $('body, html').animate({ scrollTop: "0" }, 800);
        e.preventDefault();
    });

    $(".primary-menu").find('a:not(#mobile-nav)').on('click', function(e){
        var re=/^#/g;
        var _this = $(this).attr('href') 
        if(re.test(_this) === true){
            e.preventDefault();
            var h = _this.replace('#', '');
            window.location.hash = "section="+h;
        }
    });

    var goToSection = function(location) {
        var destination = $(location).offset().top;
        if(window.innerWidth > 1024){
            $("html:not(:animated),body:not(:animated)").animate({ scrollTop: destination - $("#header").outerHeight() }, 500 );
        }else{
            $("html:not(:animated),body:not(:animated)").animate({ scrollTop: destination }, 500 );
        }
        return false;
    };

    $(window).on('hashchange', function(){
        if(location.hash.search(/section/i) === 1){
            var h = location.hash.split("=").pop();
            goToSection("#"+h);
        }

        if(location.hash.search(/post/i) === 1){
            var hash = location.hash.split("=").pop();
            $("body, html").addClass('gateway-open');
            loadPost(hash);
            gatewayWrap.show();
        }
        return false;
    });


    /*------------------------------------------------------------------------------*/
    /* Change menu active when scroll through sections
    /*------------------------------------------------------------------------------*/

    $(window).scroll(function () {
        var inview = $('section:in-viewport:first').attr('id');
        var menu_item = $('#navigation li a');
        var link = menu_item.filter('[href=#' + inview + ']');
        
        if (link.length && !link.is('.active')) {
            menu_item.parent().removeClass('active');
            menu_item.parent().removeClass('sfHover');
            link.parent().addClass('active');
        }
    });

    /*------------------------------------------------------------------------------*/
    /* Modal (Gateway) boxes setup
    /*------------------------------------------------------------------------------*/

    var gateway = $("#gateway"),
        gatewayWrap = $("#gateway-wrapper"),
        url = gateway.data('gateway-path'),
        scrollPos;

    function loadPost( postid ){
        scrollPos = $(window).scrollTop();
        gateway.load( url, {
            id: postid
        }, function(){
            closeGateway();
            nextPrevNav();
            wp_comment();
        });
    }

    gatewayWrap.hide();

    if(location.hash.search(/post/i) === 1){
        var hash = location.hash.split("=").pop();
        $("body, html").addClass('gateway-open');
        gateway.html('<h1 class="incoming-gateway">Loading...</h1>');
        loadPost(hash);
        gatewayWrap.show();
    }

    var bc;

    /* OPEN GATEWAY */
    $("a[data-through=gateway]").on( 'click', function(e){
        e.preventDefault();
        var thus = $(this);
        $("body, html").addClass('gateway-open');
        bc = $('body').scrollTop();
        var postid = $(this).data('postid');
        loadPost(postid);
        $(".stag-tabs").hide();
        location.hash = "post="+thus.data('postid');
        gatewayWrap.fadeIn(200);
    });

    function nextPrevNav(){
        $("#gateway .next, #gateway .prev").on('click', function(e){
            e.preventDefault();
            var pid = $(this).data('postid');
            location.hash= '#post='+$(this).data('postid');
            gateway.html('<h1 class="incoming-gateway">Loading...</h1>');
            $("#gateway .hfeed").fadeOut(200);
            
            loadPost(pid);
        });

        // To prevent any linked post from keeping the old scroll position.
        goToSection("#gateway-wrapper");

        /* Include Shortcode stuffs here... */
        $(".stag-tabs").tabs();
        $(".stag-toggle").each( function () {
          if($(this).attr('data-id') === 'closed') {
            $(this).accordion({ header: '.stag-toggle-title', collapsible: true, heightStyle: "content", active: false  });
          } else {
            $(this).accordion({ header: '.stag-toggle-title', collapsible: true, heightStyle: "content"});
          }
        });

        prettyPrint();

        $("#gateway-wrapper").fitVids();

        jQuery('#portfolio-single-slider').fitVids().flexslider({
            directionNav: false,
            controlNav: true,
            multipleKeyboard: true,
            video: true
        });
    }

    function closeGateway(){
        $(".close-gateway").on('click', function(e){
            e.preventDefault();
            $("body, html").removeClass("gateway-open");
            $("body").animate({scrollTop: bc}, 100);
            location.hash = '';
            // Remove content to avoid conflicts
            gateway.html('');
            gatewayWrap.fadeOut(200);
            $(window).scrollTop(scrollPos);
        });
    }


    /*------------------------------------------------------------------------------*/
    /* Modal (Gateway) boxes comment setup
    /*------------------------------------------------------------------------------*/
    function wp_comment(){
        var commentform = $('#commentform'); // find the comment form
        commentform.prepend('<div id="comment-status" ></div>'); // add info panel before the form to provide feedback or errors
        var statusdiv = $('#comment-status'); // define the infopanel

        commentform.submit(function(){
        //serialize and store form data in a variable
        var formdata = commentform.serialize();
        //Add a status message
        statusdiv.html('<p>Processing...</p>');
        //Extract action URL from commentform
        var formurl = commentform.attr('action');
        //Post Form with data
        $.ajax({
            type: 'post',
            url: formurl,
            data: formdata,
            error: function(){
            statusdiv.html('<p class="wdpajax-error">You might have left one of the fields blank, or be posting too quickly</p>');
            },
            success: function(data){
                if(data==="success"){
                    statusdiv.html('<p class="ajax-success" >Thanks for your comment. We appreciate your response.</p>');
                    window.location.reload();
                }else{
                    statusdiv.html('<p class="ajax-error" >Please wait a while before posting your next comment</p>');
                    commentform.find('textarea[name=comment]').val('');
                }
            }
        });
        return false;
        });

        // Do it, so it doesn't mess with other stuffs.
        $("a[href='#']").on('click', function(e){
            e.preventDefault();
        });

        try{
            var rofst = $("#reply-title").offset().top;    
            $("a[href='#respond']").on('click', function(e){
                e.preventDefault();
                $("#gateway-wrapper").animate({scrollTop: rofst});
            });
        }catch(e){

        }

        var commentText;
        var commentList;
        var respondBox;

        $('.comment-reply-link').removeAttr("onclick");

        $('.comment-reply-link').each(function(){
            var href = $(this).attr('href');
            href = href.split("?").pop().replace('#respond', '')+location.hash;
            href = location.pathname+"?"+href;
            $(this).attr('href', href);
        });


        $('.comment-reply-link').click(function() {

            commentText     = $(this).next().next().next('.comment-text');
            commentList     = $(this).closest('.commentlist');
            respondBox      = commentList.parent().parent().next();

            commentText.after( respondBox );

            var comment_href = $(this).attr('href');
            var comment_parent_id = getURLParameter(comment_href, "replytocom").split("#")[0];

            $('#comment_parent').val( comment_parent_id );

            return false;
        });

        function getURLParameter(url, name) {
            return decodeURIComponent(
                (url.match(RegExp("[?&]"+name+"=([^&]*)"))||[null])[1]
            );
        }

    }

    // $("#container").fitVids();

    photoSetGrid();

});

function photoSetGrid() {
    $('.photoset-grid-custom').photosetGrid({
      gutter: '5px',
      layout: '2',
      rel: 'print-gallery',

      onInit: function(){},
      onComplete: function(){
        // Show the grid after it renders
        $('.photoset-grid-custom').attr('style', '');
        setTimeout(function() {
            $('.photoset-grid-custom img.photo-hide').parents('.photoset-grid-custom').addClass('hidden');
        }, 1);
      }
    });
}


/*------------------------------------------------------------------------------*/
/* The Awesome FlexSlider
/*------------------------------------------------------------------------------*/
// jQuery(window).load(function(){

//     jQuery("#container").css('padding-top', jQuery("#header").outerHeight());

//     jQuery('#blog-post-slider').flexslider({
//         directionNav: true,
//         controlNav: false,
//         multipleKeyboard: false,
//         animation: "slide",
//         animationLoop: false,
//         slideshow: false
//     });

// });
