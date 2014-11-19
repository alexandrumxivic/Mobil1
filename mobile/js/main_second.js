$(document).ready(function () {

// Slide Gallery functionality
    $('.sg-next').bind('click', function () {
        var target = $(this).siblings('.sg-content').find('.active');
        var targetSibling;
        var that = $(this);

        if (target.attr('data-index') < $(this).siblings('.sg-content').find('.sg-item').length) {
            targetSibling = target.next();
        } else {
            targetSibling = target.siblings(':first-child');
        }

        target.addClass('transition exit-left');
        targetSibling.addClass('no-transition');

        target.on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function (e) {

            if (that.parents('.linked-container').length)
            {
                var linkedSibling = that.parents('.linked-container').siblings('.linked-container');
                var targetIndex = parseInt(that.parent().find('.active').attr('data-index'));


                if (targetIndex === linkedSibling.find('li').length) {
                    targetIndex = 1;
                } else {
                    targetIndex += 1;
                }

                linkedSibling.find('.active').removeClass('active');
                linkedSibling.find('*[data-index="' + targetIndex + '"]').addClass('active');
                linkedSibling.find('.active').trigger('activeElementChangedNext');
            }

            target.removeClass('active transition exit-left');
            targetSibling.addClass('active').removeClass('no-transition');
            target.unbind('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend');
        });
    });

    $('.sg-prev').bind('click', function () {
        var that = $(this);
        var target = $(this).siblings('.sg-content').find('.active');
        var targetSibling;
        if (target.attr('data-index') > 1) {
            targetSibling = target.prev();
        } else {
            targetSibling = target.siblings(':last-child');
        }

        target.addClass('transition exit-right');
        targetSibling.addClass('no-transition');


        target.on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function (e) {
            if (that.parents('.linked-container').length)
            {
                var linkedSibling = that.parents('.linked-container').siblings('.linked-container');
                var targetIndex = parseInt(that.parent().find('.active').attr('data-index'));


                if (targetIndex === 1) {
                    targetIndex = linkedSibling.find('li').length;
                } else {
                    targetIndex -= 1;
                }

                linkedSibling.find('.active').removeClass('active');
                linkedSibling.find('*[data-index="' + targetIndex + '"]').addClass('active');
                linkedSibling.find('.active').trigger('activeElementChangedPrev');
            }

            target.removeClass('active transition exit-right');
            targetSibling.addClass('active').removeClass('no-transition');
            target.unbind('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend');
        });
    });




// Top area toggling sections
   
    $('.js-show-thank').bind('click', function () {

        $(this).parents('.top-module').hide(300);
        $('.submit-story-wrap').hide();
        $('.thank-msg-wrap').show(300);
    });

    $('.js-show-submit').bind('click', function () {
        $(this).parents('.top-module').hide(300);
        $('.submit-story-wrap').show(300);
    });

    $('.js-show-stories').bind('click', function () {

        $(this).parents('.top-module').hide(300);
        $('.view-stories-wrap').show(300);
    });

    $('.js-top-default').bind('click', function () {
        $(this).parents('.top-module').hide(300);
        $('.landing-intro').show(300);
    });

    $('.js-default-submit').bind('click', function () {
        $(this).parents('.preview-submission').fadeOut(300);
    })

    $('.js-show-preview').bind('click', function () {
        if ($(this).hasClass('active') && $('.submit-story').valid()) {
            
            $('.preview-submission').fadeIn(300);
        }
    });



// Expanding & collapsing the performance pictures/videos sections
    $('.js-expand-section').bind('click', function () {
        $(this).parents('.media-section').addClass('expanded').siblings('.media-section').removeClass('expanded');

        $('.images').attr('style', '');
        $('.scroll-visible-area').attr('style', '');
        $('.scrollable').attr('style', '');
    });

    $('.js-restore-default').bind('click', function () {
        $(this).parents('.media-section').removeClass('expanded')
                .siblings('.media-section').removeClass('collapsed');

        $('.overlayed-image-gallery').removeClass('active');

        if ($(this).parents('.images').length) {
            $('.images').attr('style', '');
            $('.scroll-visible-area').attr('style', '');
            $('.scrollable').attr('style', '');
        }
    });

    $('.js-switch-section').bind('click', function () {
        $(this).parents('.media-section').removeClass('expanded').addClass('collapsed')
                .siblings('.media-section').removeClass('collapsed').addClass('expanded');

        $('.overlayed-image-gallery').removeClass('active');
    });



// Scroller functionality
    var scrollerWidth = 0;
    $('.scroller--item img').each(function () {
        $(this).load(function () {
            scrollerWidth += $(this).width();
            $(this).parents('.scroller--content').width(scrollerWidth);
        });
    });

    $('.js-scroller-next').bind('click', function () {
        if (!$(this).hasClass('disabled')) {
            var target = $(this);
            var targetContent = target.siblings('.scroller--content-mask').find('.scroller--content');
            var width = target.siblings('.scroller--content-mask').find('.scroller--item').width();
            var parentWidth = target.siblings('.scroller--content-mask').width();

            if ($('.scroller.horizontal').length) {
                var lastScrollWidth = targetContent.width() % parentWidth;
                if (Math.abs(parseInt(targetContent.css('left'))) < targetContent.width() - parentWidth - width) {
                    target.siblings('.js-scroller-prev.disabled').removeClass('disabled');
                    if (Math.abs(parseInt(targetContent.css('left'))) < targetContent.width() - width - lastScrollWidth) {
                        targetContent.css('left', parseInt(targetContent.css('left')) - width);

                    } else {
                        targetContent.css('left', parseInt(targetContent.css('left')) - lastScrollWidth);
                        target.addClass('disabled');
                    }
                }
            }
        }
    });

    $('.js-scroller-prev').bind('click', function () {
        if (!$(this).hasClass('disabled')) {
            var target = $(this);
            var targetContent = target.siblings('.scroller--content-mask').find('.scroller--content');
            var width = target.siblings('.scroller--content-mask').find('.scroller--item').width();
            var parentWidth = target.siblings('.scroller--content-mask').width();


            if ($('.scroller.horizontal').length) {
                if (Math.abs(parseInt(targetContent.css('left'))) > 0) {
                    var lastScrollWidth = targetContent.width() % parentWidth;

                    target.siblings('.js-scroller-next.disabled').removeClass('disabled');

                    if (Math.abs(parseInt(targetContent.css('left'))) > lastScrollWidth) {
                        targetContent.css('left', parseInt(targetContent.css('left')) + width);


                    } else {
                        targetContent.css('left', 0);
                        target.addClass('disabled');
                    }
                }
            }
        }
    });
// Masonry
    var $masonryCont = $('.image-performance-wrap');
    $masonryCont.imagesLoaded(function(){
        $masonryCont.masonry({
            columnWidth: ".image-performance",
            itemSelector: '.image-performance'
        });
    })

    $('.image-categories-list li').bind('click', function(){
        $('.image-performance').hide();
        var filtered = false;
        var customSelector = '';
        var isFirst = true;

        if($(this).find('input').hasClass('default-categs')){
            $('.image-categories-list li input:checked').each(function(){
                $(this).prop('checked', false);
            })
            $(this).find('input').prop('checked', true);

            $('.image-performance').show();
            
            $masonryCont.masonry('destroy');
            $masonryCont.masonry({
                columnWidth: ".image-performance",
                itemSelector: ".image-performance"
            })
        } else {
            $('.image-categories .default-categs').prop('checked', false)

            $('.image-categories-list li input:checked').each(function(){
                var categID = $(this).attr('data-category');
                $('.image-performance[data-category="'+ categID +'"]').show();

                if(isFirst){
                    customSelector += '.image-performance[data-category="'+ categID +'"]';
                } else {
                    customSelector += ',.image-performance[data-category="'+ categID +'"]';
                }

                filtered = true;
                isFirst = false;
            })
            
            if(filtered){
                $masonryCont.masonry('destroy');
                $masonryCont.masonry({
                    columnWidth: ".image-performance",
                    itemSelector: customSelector
                })
            } else {
                $('.image-performance').show();
                
                $masonryCont.masonry('destroy');
                $masonryCont.masonry({
                    columnWidth: ".image-performance",
                    itemSelector: ".image-performance"
                })
            }
        }
    });

// SCroll for the image gallery
    var vertScrollOffset = 0; // Difference between the first expanded view of the images height & the scrolling height
    var firstAttempt = true;

    $('.js-scroll-images-next').bind('click', function () {
        var btn = $(this);
        var scrollH = 300;

        if (firstAttempt) {
            vertScrollOffset = $('.scrollable').height() - scrollH;
        }

        if (!btn.hasClass('.disabled')) {
            firstAttempt = false;
            var target = $('.images.expanded');
            var targetAux = $('.scroll-visible-area');
            var targetAuxMask = $('.scrollable');
            var scrolledCont = $('.image-performance-wrap');
            var targetH = target.height();
            var targetAuxH = targetAux.height();
            var remainingScroll = (scrolledCont.height() - vertScrollOffset) % scrollH;

            if (targetH < scrolledCont.height()) {
                if (scrolledCont.height() - targetAuxMask.height() > remainingScroll) {

                    target.height(target.height() + scrollH);
                    targetAux.height(targetAux.height() + scrollH);
                    targetAuxMask.height(targetAuxMask.height() + scrollH);
                } else {

                    target.height(target.height() + vertScrollOffset);
                    targetAux.height(targetAux.height() + vertScrollOffset);
                    targetAuxMask.height(targetAuxMask.height() + vertScrollOffset);
                    btn.addClass('disabled');
                }
            }
        }

    });



// Linked galleries controls
    $('.linked-control li:not(".sg-item")').bind('click', function () {
        var targetIndex = $(this).attr('data-index');
        var targetSibling = $(this).parents('.linked-container').siblings('.linked-container');

        $(this).addClass('active').siblings('.active').removeClass('active');

        targetSibling.find('.linked-control .active').removeClass('active');
        targetSibling.find('.linked-control li[data-index="' + targetIndex + '"]').addClass('active');
    });

    $(window).bind('activeElementChangedPrev', function (e) {
        var target = $(e.target);
        var targetParent = target.parents('.scroller--content');
        if (target.is(':last-child')) {
            targetParent.css('left', -(targetParent.width()) + targetParent.parent().width());
            target.parents('.scroller').find('.js-scroller-next').addClass('disabled').siblings('.js-scroller-prev').removeClass('disabled');
        } else {
            if (parseInt(target.attr('data-index')) > target.siblings().length - 1) {
                target.parents('.scroller').find('.js-scroller-next').addClass('disabled').siblings('.js-scroller-prev').removeClass('disabled');
            } else {
                targetParent.css('left', -target.position().left);
                if (target.is(':first-child')) {
                    target.parents('.scroller').find('.js-scroller-prev').addClass('disabled');
                }
                target.parents('.scroller').find('.js-scroller-next').removeClass('disabled');
            }
        }
    });

    $(window).bind('activeElementChangedNext', function (e) {
        var target = $(e.target);
        var targetParent = target.parents('.scroller--content');
        if (target.is(':first-child')) {
            targetParent.css('left', 0);
            target.parents('.scroller').find('.js-scroller-prev').addClass('disabled').siblings('.js-scroller-next').removeClass('disabled');
        } else {
            if (parseInt(target.attr('data-index')) > target.siblings().length - 1) {
                target.parents('.scroller').find('.js-scroller-next').addClass('disabled').siblings('.js-scroller-prev').removeClass('disabled');
            } else {
                if (parseInt(target.attr('data-index')) > 1) {
                    target.parents('.scroller').find('.js-scroller-prev').removeClass('disabled');
                }
                targetParent.css('left', -target.position().left);
            }
        }
    });


// Overlay image gallery
// $('.image-performance').bind('click', function(){

// 		$('.overlayed-image-gallery').addClass('active')
// 		var targetIndex = $(this).attr('data-index');
// 		$('.overlayed-image-gallery li[data-index="'+ targetIndex +'"]').addClass('active')

// })

// $('.js-close-overlay').bind('click', function(){
// 	$(this).parents('.overlay').removeClass('active')
// })


// Prepping the palceholder for the user's submitted story
    $('.story-preview .pic').height($(window).width() - 60);

    $('.image-categories .toggle').bind('click', function () {
        $(this).parent().toggleClass('active');
    });



// Checking to see if the form is valid to enable the preview for the story
    $('.submit-story input').bind('change', function () {
        if ($('.submit-story').valid()) {
            $('.submit-story .js-show-preview').addClass('active');
        } else {
            $('.submit-story .js-show-preview').removeClass('active');
        }
    })


$(window).resize(function(){
     $('.story-preview .pic').height($(window).width() - 60);

})
});
