/**
 * Galleria Classic Theme 2012-04-04
 * http://galleria.io
 *
 * Licensed under the MIT license
 * https://raw.github.com/aino/galleria/master/LICENSE
 *
 */

(function($) {

/*global jQuery, Galleria */

Galleria.addTheme({
    name: 'classic',
    author: 'Galleria',
    defaults: {
        transition: 'fade',
		imageCrop : 'true',
        thumbCrop:  'height',
		idleMode : 'hover',
		responsive : true,
        // set this to false if you want to show the caption all the time:
        _toggleInfo: false
    },
    init: function(options) {

        Galleria.requires(1.25, 'This version of Classic theme requires Galleria 1.2.5 or later');

        // cache some stuff
        var info = this.$('info-text'),
            touch = Galleria.TOUCH,
            click = touch ? 'touchstart' : 'click';

        // show loader with opacity
        this.$('loader').show().css('opacity', 0.4);

        // some stuff for non-touch browsers
        if (! touch ) {
            this.addIdleState( this.get('image-nav-left'), { left:-50 });
            this.addIdleState( this.get('image-nav-right'), { right:-50 });
        }

        // toggle info
        if ( options._toggleInfo === true ) {
            info.bind( click, function() {
                info.toggle();
            });
        } else {
            info.show();
        }

        // fade in/out thumbnail when hover
        this.bind('thumbnail', function(e) {
            if (! touch ) {
                // fade thumbnails
                $(e.thumbTarget).css('opacity', 0.6).parent().hover(function() {
                    $(this).not('.active').children().stop().fadeTo(100, 1);
                }, function() {
                    $(this).not('.active').children().stop().fadeTo(400, 0.6);
                });

                if ( e.index === this.getIndex() ) {
                    $(e.thumbTarget).css('opacity',1);
                }
            } else {
                $(e.thumbTarget).css('opacity', this.getIndex() ? 1 : 0.6);
            }
        });

		// ajax loader start
        this.bind('loadstart', function(e) {
            if (!e.cached) {
                this.$('loader').show().fadeTo(200, 0.4);
            }

            this.$('info').toggle( this.hasInfo() );
            $(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity', 0.6);
        });

		// ajax loader finish
        this.bind('loadfinish', function(e) {
            this.$('loader').fadeOut(200);
        });

		// create control panel
		this.addElement('control');
		text_append = '';
		text_append += '<div class="galleria-play icon-galleria icon-large icon-play" title="Play slideshow"></div>';
		text_append += '<div class="galleria-pause icon-galleria icon-large icon-pause" title="Pause slideshow"></div>';
		text_append += '<div class="galleria-albumlist icon-galleria icon-large icon-th-list" title="Show album list"></div>';
		text_append += '<div class="galleria-popup icon-galleria icon-large icon-picture" title="Popout photo"></div>';
		text_append += '<div class="galleria-comment icon-galleria icon-large icon-comment" title="Leave comment"></div>';
		text_append += '<div class="galleria-fullscreen icon-galleria icon-large icon-fullscreen" title="Enter fullscreen"></div>';
		text_append += '<div class="galleria-exitfullscreen icon-galleria icon-large icon-display" title="Exit fullscreen"></div>';
		this.$('control').append(text_append);
		this.appendChild('control','counter');
		this.appendChild('control','info');
		this.appendChild('container','control');
		// create comment panel
		this.addElement('comment-panel');
		this.prependChild('container', 'comment-panel');
		text_append = '';
		text_append += '<div class="comment-head" data-name=""></div>';
		text_append += '<div class="comment-body"></div>';
		text_append += '<div class="comment-input"><form id="post_comment_form" title=""><table><tr><td>Leave your commnet...</td><td class="comment-byte-count">150</td></tr><tr><td><textarea class="comment-textarea" name="album_desc" size="30" title="Leave your comment" ></textarea></td><td><input type="submit" name="submit" value="Post" id="btn_post_comment" title="Post" data-name="" /></td></tr></table></form></div>';
		this.$('comment-panel').append(text_append);
		// create progress bar
		this.addElement('progress-bar');
		this.prependChild('container', 'progress-bar');

		// register control panel buttons
		$('.galleria-play').click(this.proxy(function(e) {
			e.preventDefault();
			$('.galleria-play').toggle();
			$('.galleria-pause').toggle();
			this.play(3000);
		}));
		$('.galleria-pause').click(this.proxy(function(e) {
			e.preventDefault();
			$('.galleria-play').toggle();
			$('.galleria-pause').toggle();
			this.pause();
		}));
		// listen slideshow progress event
		this.bind('progress', function(e) {
			$('.galleria-progress-bar').progressbar({
				value : e.percent
			}).show();
		});
		// listen pause event
		this.bind('pause', function(e) {
			e.preventDefault();
			$('.galleria-play').show();
			$('.galleria-pause').hide();
			$('.galleria-progress-bar').hide();
    	});
		// clearbox event
		$('.galleria-popup').click(this.proxy(function(e) {
			e.preventDefault();
            this.openLightbox(this.getIndex());
		}));
		// comment panel
		$('.galleria-comment').click(this.proxy(function(e) {
			e.preventDefault();
			var elem = $('.galleria-comment-panel');
			if (!elem.is(':visible')) {
				var stage = $('.galleria-stage');
				var stage_offset = stage.offset();
				elem.css({
					position : 'fixed',
					top : stage_offset.top + 'px',
					left : stage_offset.left + stage.outerWidth() + 'px',
					height : stage.outerHeight()
				});
			}
            elem.toggle();
			var imgObj = this.getData(this.getIndex());
			if (elem.is(':visible') && $('.comment-head').attr('data-name') != imgObj.title) {
				this.trigger('loadfinish');
			}
		}));
		$('.galleria-fullscreen').click(this.proxy(function(e) {
			e.preventDefault();
			$('.galleria-fullscreen').toggle();
			$('.galleria-exitfullscreen').toggle();
			//$('.galleria-albumlist').toggle();
			$('.galleria-comment-panel').hide();
			$('.galleria-comment').hide();
			$('.galleria-popup').hide();
			this.enterFullscreen();
		}));
		$('.galleria-exitfullscreen').click(this.proxy(function(e) {
			e.preventDefault();
			$('.galleria-fullscreen').show();
			$('.galleria-exitfullscreen').hide();
			$('.galleria-albumlist').hide();
			$('.galleria-select').hide();
			$('.galleria-comment').show();
			$('.galleria-popup').show();
			this.exitFullscreen();
		}));
		// listen exit fullscreen event
		this.bind("fullscreen_exit", function(e) {
			e.preventDefault();
			$('.galleria-fullscreen').show();
			$('.galleria-exitfullscreen').hide();
			$('.galleria-albumlist').hide();
			$('.galleria-select').hide();
			$('.galleria-comment').show();
			$('.galleria-popup').show();
    	});
		// listen image ready event
		this.bind('image', function(e) {
			// click main image will go to next image
            $(e.imageTarget).click(this.proxy(function() {
               this.next();
            }));
        });
    }
});

}(jQuery));
