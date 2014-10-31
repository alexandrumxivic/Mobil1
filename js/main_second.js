$(document).ready(function(){
	
// Slide Gallery functionality
	$('.sg-next').bind('click', function(){
		var target = $(this).siblings('.sg-content').find('.active');
		var targetSibling;
		var that = $(this);
		
		if(target.attr('data-index') < $(this).siblings('.sg-content').find('.sg-item').length){
			targetSibling = target.next();
		} else {
			targetSibling = target.siblings(':first-child');
		}

		target.addClass('transition exit-left');
		targetSibling.addClass('no-transition');

		target.on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(e){

			if(that.parents('.linked-container').length)
			{
				var linkedSibling = that.parents('.linked-container').siblings('.linked-container');
				var targetIndex = parseInt(that.parent().find('.active').attr('data-index'));

				
				if(targetIndex === linkedSibling.find('li').length){
					targetIndex = 1;
				} else {
					targetIndex += 1;
				}

				linkedSibling.find('.active').removeClass('active');
				linkedSibling.find('*[data-index="'+ targetIndex +'"]').addClass('active');
				linkedSibling.find('.active').trigger('activeElementChangedNext')
			}

			target.removeClass('active transition exit-left');
			targetSibling.addClass('active').removeClass('no-transition');
			target.unbind('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend');
		})
	});

	$('.sg-prev').bind('click', function(){
		var that = $(this);
		var target = $(this).siblings('.sg-content').find('.active');
		var targetSibling;
		if(target.attr('data-index') > 1){
			targetSibling = target.prev();
		} else {
			targetSibling = target.siblings(':last-child');
		}

		target.addClass('transition exit-right');
		targetSibling.addClass('no-transition');

		
		target.on('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(e){
			if(that.parents('.linked-container').length)
			{
				var linkedSibling = that.parents('.linked-container').siblings('.linked-container');
				var targetIndex = parseInt(that.parent().find('.active').attr('data-index'));

				
				if(targetIndex === 1){
					targetIndex = linkedSibling.find('li').length;
				} else {
					targetIndex -= 1;
				}

				linkedSibling.find('.active').removeClass('active');
				linkedSibling.find('*[data-index="'+ targetIndex +'"]').addClass('active');
				linkedSibling.find('.active').trigger('activeElementChangedPrev');
			}

			target.removeClass('active transition exit-right');
			targetSibling.addClass('active').removeClass('no-transition');
			target.unbind('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend');
		})
	});




// Top area toggling sections
	$('.js-show-submit').bind('click', function(){
		$(this).parents('.top-module').hide(300);
		$('.submit-story-wrap').show(300);
	})

	$('.js-show-stories').bind('click', function(){
		$(this).parents('.top-module').hide(300);
		$('.view-stories-wrap').show(300);
	})

	$('.js-top-default').bind('click', function(){
		$(this).parents('.top-module').hide(300);
		$('.landing-intro').show(300);
	})



// Expanding & collapsing the performance pictures/videos sections
	$('.js-expand-section').bind('click', function(){
		$(this).parents('.media-section').addClass('expanded').siblings('.media-section').addClass('collapsed');
	})

	$('.js-restore-default').bind('click', function(){
		$(this).parents('.media-section').removeClass('expanded')
										 .siblings('.media-section').removeClass('collapsed');

		$('.overlayed-image-gallery').removeClass('active')
	})

	$('.js-switch-section').bind('click', function(){
		$(this).parents('.media-section').removeClass('expanded').addClass('collapsed')
										 .siblings('.media-section').removeClass('collapsed').addClass('expanded')
		
		$('.overlayed-image-gallery').removeClass('active')
	})



// Scroller functionality
	$('.js-scroller-next').bind('click', function(){
		if(!$(this).hasClass('disabled')){
			var target= $(this);
			var targetContent = target.siblings('.scroller--content');
			var height= target.parent().height();

			if($('.scroller.vertical').length){ 
				var lastScrollHeight = targetContent.height() % height;

				if(Math.abs(parseInt(targetContent.css('top'))) < targetContent.height() - height){
					target.siblings('.js-scroller-prev.disabled').removeClass('disabled');

					if(Math.abs(parseInt(targetContent.css('top'))) < targetContent.height() - height - lastScrollHeight){
						targetContent.css('top', parseInt(targetContent.css('top')) - height)

					} else{
						targetContent.css('top', parseInt(targetContent.css('top')) - lastScrollHeight);
						target.addClass('disabled');
					}
				}
			}
		}
	})

	$('.js-scroller-prev').bind('click', function(){
		if(!$(this).hasClass('disabled')){
			var target= $(this);
			var targetContent = target.siblings('.scroller--content');
			var height= target.parent().height();

			if($('.scroller.vertical').length){ 
				if(Math.abs(parseInt(targetContent.css('top'))) > 0){
					var lastScrollHeight = targetContent.height() % height;
					
					target.siblings('.js-scroller-next.disabled').removeClass('disabled');

					if(Math.abs(parseInt(targetContent.css('top'))) > lastScrollHeight){
						targetContent.css('top', parseInt(targetContent.css('top')) + height);


					} else{
						targetContent.css('top', 0);
						target.addClass('disabled');
					}
				}
			}
		}		
	})
// Masonry
var $container = $('.image-performance-wrap');
	$container.masonry({
		columnWidth: ".image-performance",
		itemSelecter: ('.image-performance')
	})



// Linked galleries controls
	$('.linked-control li:not(".sg-item")').bind('click', function(){
		var targetIndex=$(this).attr('data-index');
		var targetSibling= $(this).parents('.linked-container').siblings('.linked-container')

		$(this).addClass('active').siblings('.active').removeClass('active');

		targetSibling.find('.linked-control .active').removeClass('active');
		targetSibling.find('.linked-control li[data-index="'+ targetIndex +'"]').addClass('active')
	})



// Overlay image gallery
	$('.image-performance').bind('click', function(){
		
			$('.overlayed-image-gallery').addClass('active')
			var targetIndex = $(this).attr('data-index');
			$('.overlayed-image-gallery li[data-index="'+ targetIndex +'"]').addClass('active')
		
	})

	$('.js-close-overlay').bind('click', function(){
		$(this).parents('.overlay').removeClass('active')
	})


// Listening to see if the scroller needs to update its position to display currently active element
	$(window).bind('activeElementChangedPrev', function(e){
		var target = $(e.target);
		var targetParent = target.parents('.scroller--content');

		if(target.is(':last-child')){
			targetParent.css('top', -(targetParent.height() - target.height()) + targetParent.parent().height() - target.height())
			target.parents('.scroller').find('.js-scroller-prev').removeClass('disabled').siblings('.js-scroller-next').addClass('disabled');
		} else {
			var customHeight;
			if(parseInt(targetParent.css('top')) + target.height() < targetParent.parent().height()){
				customHeight = 0;
			} else {
				customHeight = parseInt(targetParent.css('top')) + target.height();
				if(parseInt(targetParent.css('top')) + target.height() == targetParent.parent().height()){
					target.parents('.scroller').find('.js-scroller-next').addClass('disabled');
				}
			}
			targetParent.css('top', customHeight)
		}
	})

	$(window).bind('activeElementChangedNext', function(e){
		var target = $(e.target);
		var targetParent = target.parents('.scroller--content');

		if(target.is(':first-child')){
			targetParent.css('top', 0);
			target.parents('.scroller').find('.js-scroller-next').removeClass('disabled').siblings('.js-scroller-prev').addClass('disabled');
		} else {
			var customHeight;
			
			if(parseInt(target.attr('data-index'))*target.height() <= targetParent.parent().height()){
				customHeight = 0;
			} else {
				customHeight = parseInt(targetParent.css('top')) - target.height();
				if(target.is(':last-child')){
					target.parents('.scroller').find('.js-scroller-next').addClass('disabled').siblings('.js-scroller-prev').removeClass('disabled');
				}
			}
				targetParent.css('top', customHeight)
		}
	})


// Code for the toggling of the category filtering for the image gallery

	$('.image-categories .toggle').bind('click', function(){
		$(this).parent().toggleClass('active');
	})



// Validating the submit story form
	$('.submit-story input[type="submit"]').bind('click', function(){
		$('.submit-story').validate({
			rules: {
				first_name:"required",
				last_name:"required",
				email:"required",
				phone:"required",
				story:"required",
				agree_age:"required",
				agree_rules:"required"
			},
			messages: {
				story: " Tell us your story in a few lines",
				agree_age: " ",
				agree_rules: " "
			}
		})
	})
})
