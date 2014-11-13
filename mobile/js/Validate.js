/**
	VALIDATOR
*/
Validate = {};

Validate.feedback = function(message, element){
    
        if($('.validate-feedback.'+element.attr('name')).length>0 || $('.validate-feedback.'+element.closest('.icr').attr('id')).length>0)
            return;
    
	var feedbackElement = $("<div></div>").addClass('validate-feedback');
	feedbackElement.html(message);

        var inputClass = element.attr('name');
        if(inputClass)
            feedbackElement.addClass(inputClass);
        else    //use case for custom radio
            feedbackElement.addClass(element.closest('.icr').attr('id'));   //use case for custom radio
	
	// Set size
	feedbackElement.height(element.outerHeight()+3);
	feedbackElement.width(element.outerWidth()+4);
	feedbackElement.css('line-height', feedbackElement.height()-1+"px");
	
	// Set position
	feedbackElement.css('top', element.offset().top-2+"px");
	feedbackElement.css('left', element.offset().left-2+"px");
	
	// Hide on rollover
	feedbackElement.on('mouseover', function(event){
		// Hide, remove and select element's text for easy update
		TweenMax.to(feedbackElement, 0.5, {	css: {	autoAlpha: 0	},
			onComplete: function(){
				feedbackElement.remove();
				//element.select();
				document.getSelection().removeAllRanges();
			}
		});
	});
	
	$('body').append(feedbackElement);
	element.blur();
	
	// Start hidden
	TweenMax.to(feedbackElement, 0, {	css: {autoAlpha: 0	}	});
	// Show
	TweenMax.to(feedbackElement, 1, {	css: {autoAlpha: 1	}	});
}

Validate.reset = function(){
	if($('.validate-feedback').length==0) return;
	$('.validate-feedback').remove();
};


Validate.email = function(element){
	var pattern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	
	if(pattern.test(element.val())==false)
	{
		Validate.feedback("Invalid email", element);
		return false;
	}
	return true;
}

Validate.telephone = function(element){
	var pattern = /^\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/;
	
	if(pattern.test(element.val())==false)
	{
		Validate.feedback("Invalid", element);
		return false;
	}
	return true;
}

Validate.name = function(element){	
	if(element.val().length<2)
	{
		Validate.feedback("Invalid", element);
		return false;
	}
	return true;
}

Validate.address = function(element){	
	if(element.val().length<5)
	{
		Validate.feedback("Invalid address", element);
		return false;
	}
	return true;
}

Validate.city = function(element){	
	if(element.val().length<3)
	{
		Validate.feedback("Invalid city", element);
		return false;
	}
	return true;
}

Validate.birthday = function(date, limit, element){
	var d = date.split('/');
	if(d[0].length!=2 || d[1].length!=2 || d[2].length!=4)
	{
		Validate.feedback("Invalid (MM/DD/YYYY)", element);
		return false;
	}

	limit = limit * 60 * 60 * 24 * 30 * 12 * 1000;
	
	var birthday = new Date(Date.parse(date));
	var today = new Date();

	if(_.isNaN(birthday.getTime()))
	{
		Validate.feedback("Invalid (MM/DD/YYYY)", element);
		return false;
	}

	//console.log(birthday.getTime(), today.getTime(), limit);
	
	if(today.getTime()-birthday.getTime()>=limit)
	{
		return true;
	}
	else
	{
		Validate.feedback("You must be at least 18", element);
		return false;
	}
	return false;
}

Validate.state = function(element){
	var pattern1 = /(Alabama|Alaska|Arizona|Arkansas|California|Colorado|Connecticut|Delaware|Florida|Georgia|Hawaii|Idaho|Illinois|Indiana|Iowa|Kansas|Kentucky|Louisiana|Maine|Maryland|Massachusetts|Michigan|Minnesota|Mississippi|Missouri|Montana|Nebraska|Nevada|New\sHampshire|New\sJersey|New\sMexico|New\sYork|North\sCarolina|North\sDakota|Ohio|Oklahoma|Oregon|Pennsylvania|Rhode\sIsland|South\sCarolina|South\sDakota|Tennessee|Texas|Utah|Vermont|Virginia|Washington|West\sVirginia|Wisconsin|Wyoming)/i;
	var pattern2 = /^([Aa][LKSZRAEPlkszraep]|[Cc][AOTaot]|[Dd][ECec]|[Ff][LMlm]|[Gg][AUau]|[Hh][Ii]|[Ii][ADLNadln]|[Kk][SYsy]|[Ll][Aa]|[Mm][ADEHINOPSTadehinopst]|[Nn][CDEHJMVYcdehjmvy]|[Oo][HKRhkr]|[Pp][ARWarw]|[Rr][Ii]|[Ss][CDcd]|[Tt][NXnx]|[Uu][Tt]|[Vv][AITait]|[Ww][AIVYaivy])$/;
	if(pattern2.test(element.val())==false /*&& pattern1.test(element.val())==false*/)
	{
		Validate.feedback("Invalid", element);
		return false;
	}
	return true;
}

Validate.zip = function(element){
	var pattern = /^\d{5}(-\d{4})?$/;
	
	if(pattern.test(element.val())==false)
	{
		Validate.feedback("Invalid zip", element);
		return false;
	}
	return true;
}