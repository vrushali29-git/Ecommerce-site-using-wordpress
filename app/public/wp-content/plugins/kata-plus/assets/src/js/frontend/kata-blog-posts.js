(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var WidgetBlogPostsHandler = function ($scope, $) {
        $scope.find('.kata-blog-post').each(function (index, element) {
            var $this = $(this),
                $thumb = $this.find('.kata-post-thumbnail'),
                video = $thumb.attr('data-video'),
                videotype = $thumb.attr('data-videotype');
                if (typeof video !== 'undefined' && video !== false) {
                    if ( videotype == 'youtube' ) {
                        var iframe = '<div class="kata-blog-post-video-player-modal"><iframe width="962" height="541" src="'+video+'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                    }
                    if ( videotype == 'vimeo' ) {
                        var iframe = '<div class="kata-blog-post-video-player-modal"><iframe src="'+video+'" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div>';
                    }
                    if ( videotype == 'hosted' ) {
                        var iframe = '<div class="kata-blog-post-video-player-modal"><video controls><source src="'+video+'"></video></div>';
                    }
                }
            $this.find('.kata-post-format .kata-icon').on('click', function(e) {
                if ( iframe ) {
                    $('body').append(iframe);
                }
            });

            $(document).on('click', '.kata-blog-post-video-player-modal', function() {
                $('.kata-blog-post-video-player-modal').fadeOut( 400, function() {
                    $('.kata-blog-post-video-player-modal').remove();
                });
            });
            $(document).keydown(function (e) {
                var code = e.keyCode || e.which;
                if (code == 27) { 
                    $('.kata-blog-post-video-player-modal').fadeOut( 400, function() {
                        $('.kata-blog-post-video-player-modal').remove();
                    });
                }
            });
        });
        
        /**
         * Load More Button
         */
        $scope.find('.kata-blog-posts[data-loadmore]').each(function (index, element) {
            const $This = $(this);
            if ( $This.attr('data-loadmore') ) {
                const PostToShow = parseInt( $This.attr('data-loadmore') );
                console.log(PostToShow);
                $This.find('.kata-blog-post').hide();
                for (let index = 1; index < PostToShow  + 1; index++) {
                    $This.find('.kata-blog-post:nth-child('+index+')').show();
                }
            }
        });
        $scope.find('.kata-blog-posts[data-loadmore]').each(function (index, element) {
            let $This = $(this),
            $Btn = $This.find('.ktbl-load-more');
            $Btn.on('click', function(e) {
                e.preventDefault();
                let $This = $(this),
                    $Wrapper = $This.closest('.kata-blog-posts[data-loadmore]'),
                    AllPostLength = $Wrapper.data('showedposts'),
                    PostsPerPage = $Wrapper.data('loadmore'),
                    $Posts = $Wrapper.find('.kata-blog-post');
                $Posts.fadeIn();
                $Btn.fadeOut();
            });
        });

        /**
         * Bookmark
         */
        $(document).on('click', '.kata-bookmark .kata-icon', function() {
            let $This = $(this),
                $Post = $This.closest('.kata-blog-post'),
                $Status = $Post.find('.kata-bookmark').data('status');
            $.ajax({
                url: kata_plus_localize.ajax.url,
                type: 'POST',
                dataType: 'json',
                data: {
                    nonce: kata_plus_localize.ajax.nonce,
                    action: 'posts_bookmark', //calls wp_ajax_nopriv_posts_bookmark
                    post_id : $Post.data('postid'),
                    status : $Status,
                },
                success: function (response,data) {
                    if ( response.data.status == 'add' ) {
                        $Post.find('.kata-bookmark').attr('data-status', 'remove');
                    } else if( response.data.status == 'remove' ) {
                        $Post.find('.kata-bookmark').attr('data-status', 'add');
                    }
                    alert( response.data.message );
                },
                error: function(response,data) {
                    alert( response.responseJSON.data );
                }
            });
        });
        
        /**
         * Sort By 
         */
        $('.ktbl-sortoptions').bind('change', function () {
            var url = $(this).val(); // get selected value
            if (url) { // require a URL
                window.location = url; // redirect
            }
            return false;
        });
    };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/kata-plus-blog-posts-new.default', WidgetBlogPostsHandler);
    });
})(jQuery);