;(function($){
    
if (!Array.prototype.indexOf)
{
  Array.prototype.indexOf = function(elt /*, from*/)
  {
    var len = this.length >>> 0;

    var from = Number(arguments[1]) || 0;
    from = (from < 0)
         ? Math.ceil(from)
         : Math.floor(from);
    if (from < 0)
      from += len;

    for (; from < len; from++)
    {
      if (from in this &&
          this[from] === elt)
        return from;
    }
    return -1;
  };
}

var Application = {};

Application.location = "//mobile1.projects-directory.com/";
Application.share = {};
Application.share.photourl = "http://mobile1.projects-directory.com/";
Application.share.URL = "https://www.facebook.com/pages/Fanscape-Development/353585258094472?sk=app_597677730337203";
Application.share.TITLE = "Let's Celebrate A Better Nation";


Application.navigation = {};
Application.selector = {};
Application.data = {};

Application.selector.init = function(){
                Facebook.api.getFriends(function(response){
                        if(response && response.friends)
                        {
                                Application.selector.friends = response.friends.data;
                                var result = Application.selector.friends;
                                for(var i=0;i<result.length;i++)
                                {
                                        //var preload = new Image();
                                        //preload.src = 'http://graph.facebook.com/'+Application.selector.friends[i].id+"/picture?type=square";

                                        var user = $('<div></div>').addClass('facebook-user');
                                        //var avatar = $('<div></div>').addClass('facebook-avatar').css('background-image','url('+preload.src+')');
                                        var avatar = $('<div></div>').addClass('facebook-avatar').css('background-image','url('+'http://graph.facebook.com/'+Application.selector.friends[i].id+"/picture?type=square"+')');
                                        var name = $('<div>'+result[i].name+'</div>').addClass('facebook-name');
                                        var clear = $('<div></div>').addClass('clear');

                                        user.append(avatar);
                                        user.append(name);
                                        user.append(clear);
                                        user.data('user', result[i]);
                                        user.addClass('');
                                        
                                        user.on('click', function(e){
                                            Application.selector.set($(this).data('user'));
                                            $('.friendResults').hide();
                                            $('.submit').show();
                                            $('.submitInactive').hide();
                                        });
                                        Application.selector.friends[i].element = user;
                                        $('.friendResults').append(user);
                                }
                                $('.friendInputWrap').css('visibility', 'visible');
                                $('.friendResults').mCustomScrollbar();
                                Application.selector.reset();
                        }
                });


                $('.friendInput').on('focusin', function(e){
                        if($('.friendInput').val()==="Type your friend's name here...")
                        {
                                $('.friendInput').val('');
                                $('.submit').hide();
                                $('.submitInactive').show();
                        }
                });
                
                $('.friendInput').on('focusout', function(e){
                    if($('.friendInput').val()==="")
                    {
                        $('.friendInput').val("Type your friend's name here...");
                    }
                });
                $('.friendInput').on('keyup', function(e){
                    $('.submit').hide();
                    $('.submitInactive').show();
                        var value = $('.friendInput').val();
                        if(value!=="")
                        { 
                            $('.friendResults').show();
                            Application.selector.update(Application.selector.search(value));
                        }
                        else
                        {
                            $('.friendResults').hide();
                            Application.selector.reset();
                        }
                });
                
                $('#share').on('click', function(e){
                    if (Application.selector !== null) {
                        ga('send', 'event', 'button', 'click', 'Select a Veteran');
                        showThankYou();
                        FB.ui({
                            method: 'feed',
                            title: "We're Celebrating a Better Nation!",
                            link: Application.share.URL,
                            name: "We're Celebrating a Better Nation!",
                            picture: Application.share.photourl+"images/shareLogo.png",
                            to: Application.data.id,
                            caption: '',
                            description: "You've been honored for serving our country through sacrifice and dedication. Thank you. Now we'd like to help you Find Better&reg; in today's job market and maybe win some great prizes, too!",
                            message: ''
                        });
                    }
                });
        };
        Application.selector.search = function(query){
                var result = [];
                for(var i=0;i<Application.selector.friends.length;i++)
                {
                        var user = Application.selector.friends[i].name;
                        if(Application.selector.test(user, query)===true)
                        {
                                result.push(Application.selector.friends[i]);
                        }
                }
                return result;
        };
        
        Application.selector.set = function(user){
                Application.selector.reset();

                var selected = {};
                selected.id = user.id;
                selected.name = user.name;
                $('.friendInput').val(user.name);
                $('#friendAvatar').attr('src', "images/loader.gif");
                $('#friendAvatar').attr('src', "http://graph.facebook.com/"+user.id+"/picture?width=200&height=200");
                Application.data = selected;
        };

        Application.selector.reset = function(){
                $('.facebook-user').addClass('hidden');
        };
        
        Application.selector.update = function(result){
                Application.selector.reset();

                for(var i=0;i<result.length;i++)
                {

                    result[i].element
                            .removeClass('hidden')
                            .css('opacity',1);
                    if(i===result.length-1) {
                        $('.friendResults').mCustomScrollbar('destroy');
                        $('.friendResults').mCustomScrollbar();
                    }
                }
                if(0===result.length) {
                    $('.friendResults').mCustomScrollbar('destroy');
                    $('.friendResults').mCustomScrollbar();
                }
                $('.friendResults').trigger('click');
        };

        function showThankYou() {
            $(".content ").hide();
            $('.thank_you').show();
        }
        
        Application.selector.test = function(user, query){
            
                var values = query.toLowerCase().split(' ');
                var flags = 0;
                while(values.indexOf('')>-1)
                {
                        values.splice(values.indexOf(''), 1);
                }

                var name = user.toLowerCase().split(' ').join();

                if(values.length===1)
                {
                        //if(name.indexOf(values[0])>-1)
                        if(name.indexOf(values[0])===0)
                        {
                                return true;
                        }
                        else
                        {
                                return false;
                        }
                }
                else
                {
                        for(var j=0;j<values.length;j++)
                        {
                                if(name.indexOf(values[j])>-1)
                                {
                                        flags++;
                                }
                        }
                }
                if(flags===values.length)
                {
                        return true;
                }
                return false;
        };

$(document).ready(function(){
        
        Facebook.setup(function(){

            FB.Canvas.setSize({height: 1300, width: 810});
            
            if(Facebook.api.likedPage()) {
                
                Facebook.api.login(function(response){
                    if(response.status===Facebook.status.CONNECTED) {
                        Application.selector.init();
                        
                        $('.header .monster').on('click',function(){
                            ga('send', 'event', 'outbound', 'click', 'Monster.com - Find Better');
                        });                        
                        $('.header .military').on('click',function(){
                            ga('send', 'event', 'outbound', 'click', 'Military.com');
                        });                        
                        $('.header .pivot').on('click',function(){
                            ga('send', 'event', 'outbound', 'click', 'Pivot');
                        });                        
                        $('.footer .officialRules').on('click',function(){
                            ga('send', 'event', 'outbound', 'click', 'Official Rules');
                        });                        
                        $('.footer .privacyPolicy').on('click',function(){
                            ga('send', 'event', 'outbound', 'click', 'Privacy Policy');
                        });                        
                        $('.footer .thirdParty').on('click',function(){
                            ga('send', 'event', 'outbound', 'click', 'Third Party Rights');
                        });                        
                        
                        var facebook_id = Facebook.api.userID();
                        
                        $('.homepage .red_btn').on('click',function(){
                            ga('send', 'event', 'button', 'click', 'Honor a Service Member');
                            $('.content').hide();
                            $('.honor_veteran').show();
                        });    

                        $('.homepage .blue_btn').on('click',function(){
                            ga('send', 'event', 'button', 'click', 'Enter the Sweepstakes');
                            $.ajax({
                                    type: 'POST',
                                    url: Application.location+'services/checkUnique.php',
                                    data : {
                                        facebook_id     : facebook_id
                                    }
                            }).done(function(data){                                            
                                    var result = $.parseJSON(data);
                                    if(result.success == '2') {
                                        showThankYou();
                                    } else {                                      
                                        $('.content').hide();
                                        $('.im_veteran').show();
                                    }

                            });
                            
                        });    

                       $('.checkbox_d').click(function(){
                                if($(this).hasClass('checked')){
                                        $(this).removeClass('checked');
                                }
                                else{
                                $(this).addClass('checked');
                                }
                        });   
                        
                        $('.header li a').on('click',function(){
                            return false;
                        });
                        
                                                
 
                        $('#uploadFile').fileupload({
                            url: Application.location+'services/uploadFile.php',
                            dataType: 'json',
                            start: function (e, data) {
                                $('.imgCheck').hide();
                                $('.imgError').hide();
                                $('.loader').show();                               
                            },
                            done: function (e, data) {
                                $('#uploadFileValue').attr('value',data.result.url);
                                $('.loader').hide();
                                $('.imgError').hide();
                                $('.imgCheck').show();
                            },
                            fail: function (e, data) {
                                $('.loader').hide();
                                $('.imgCheck').hide();
                                $('.imgError').show();
                            }
                        }).prop('disabled', !$.support.fileInput)
                                .parent().addClass($.support.fileInput ? undefined : 'disabled');
                        
                        Facebook.api.getUser(function(response){
                            $('#first-name').val(response.first_name);
                            $('#last-name').val(response.last_name);
                            $('#emailValue').val(response.email);
                        });
                        
                        $('.im_veteran_form').submit(function(){
                            var v1 = Validate.name($('#first-name')), v2 = Validate.name($('#last-name')), v3 = Validate.telephone($('#phone'));
                            var v4 = 1;
                            if(!$('.im_veteran_form .rules_field .checkbox_d').hasClass('checked')) {
                                v4 = 0;
                                Validate.feedback("Please read and accept the official rules", $('.im_veteran_form .checkbox_p'));
                            }
                            var email = '';
                            if($('.im_veteran_form .email_field .checkbox_d').hasClass('checked')) {
                                email = $('#emailValue').val();
                            }
                            if(v1&&v2&&v3&&v4){
                                ga('send', 'event', 'button', 'click', 'Submit form');
                                Validate.reset();
                                    $.ajax({
                                            type: 'POST',
                                            url: Application.location+'services/registerUser.php',
                                            data : {
                                                facebook_id     : facebook_id,
                                                first_name 	: $('#first-name').val(),
                                                last_name  	: $('#last-name').val(),
                                                phone     	: $('#phone').val(),
                                                photo     	: $('#uploadFileValue').attr('value'),
                                                military_job  	: $('#military-job').val(),
                                                ideal_job  	: $('#ideal-job').val(),
                                                email           : email
                                            }
                                    }).done(function(data){                                            
                                            var result = $.parseJSON(data);
                                            if(result.success == '2') {
                                                Validate.feedback("Already registered today!", $('.im_veteran_form .checkbox_p'));
                                            } else {
                                                showThankYou();
                                                FB.ui({
                                                    method: 'feed',
                                                    title: "We're Celebrating a Better Nation!",
                                                    link: Application.share.URL,
                                                    name: "We're Celebrating a Better Nation!",
                                                    picture: Application.share.photourl+"images/shareLogo.png",
                                                    to: Facebook.api.userID,
                                                    caption: '',
                                                    description: "Honor veterans and those currently serving in the military right here on Facebook and they can enter for their chance to win great prizes and learn how to Find Better&reg; in today's job market!",
                                                    message: ''
                                                });
                                            }

                                    });
                                        
                                    

                                        
                                }
                                
                            return false;
                        });
                        
                        $('.im_veteran_form input').focus(function(){
                            var name = $(this).attr('name');
                            TweenMax.to($('.validate-feedback.'+name), 0.5, {	css: {	autoAlpha: 0	},
                                    onComplete: function(){
                                            $('.validate-feedback.'+name).remove();
                                    }
                            });
                        });
                        
                        $('.uploadBtn').on('click',function(){
                            Validate.reset();
                            $('#overlay').show();
                            $('#uploadWrap').show();
                        });
                        $('#overlay').on('click',function(){
                            Validate.reset();
                            $('#overlay').hide();
                            $('#uploadWrap').hide();
                            $('#facebookPhotos').hide();
                        });
   
//                        TweenMax.to($('.fromMobile #uploadFile'), 0.1, {	css: {	rotation: 180	},
//                                onComplete: function(){
//                                        
//                                }
//                        });
   
                        $('.fromDesktop.disabled').on('click',function(e){
                            alert('Upgrade your OS or access this page on your desktop!')                                      
                        });  
    
                        //$('.fromDesktop').on('click',function(){
                        $('#uploadFile').on('click',function(e){
                            if($('#uploadWrap .checkbox_d').hasClass('checked')) {
                                Validate.reset();
                              //  $('#uploadFile').click();                               
                            } else {
                                Validate.feedback("Please accept these terms", $('#uploadWrap .checkbox_p'));
                                e.preventDefault();
                                return false;
                            }
                            
                        });    
                        
                      
                        
                        $('#uploadFile').change(function(){
                            $('#overlay').hide();
                            $('#uploadWrap').hide();
                        });
                        
                        $('.fromFacebook').on('click',function(){
                            if($('#uploadWrap .checkbox_d').hasClass('checked')) {
                                Validate.reset();
                                $('#facebookPhotos').show().html('');
                                getImagesFromFacebook();                              
                            } else {
                                Validate.feedback("Please accept these terms", $('#uploadWrap .checkbox_p'));
                            }
                            
                        });
                        $('#facebookPhotos').on('mouseover','img',function(){
                            $(this).addClass('over-bordered').removeClass('static-bordered');
                        }).on('mouseleave','img',function(){
                            $(this).removeClass('over-bordered').addClass('static-bordered');
                        }).on('click','img',function(){
                            $('#uploadFileValue').attr('value',$(this).attr('src'));
                            $('#facebookPhotos').hide();
                            $('#overlay').hide();
                            $('#uploadWrap').hide();
                            $('.imgError').hide();
                            $('.imgCheck').show();
                        });

                        
//                        $(document).click(function(e) { 
//                            if($('#uploadWrap').hasClass('opened')) {
//                                if (!$(e.target).parents().andSelf().is('#uploadWrap') && !$(e.target).parents().andSelf().is('#uploadWrap')) {
//                                    $('#overlay').hide();
//                                    $('#uploadWrap').hide()
//                                    $('#uploadWrap').removeClass('opened');                
//                              //      return false;            
//                                }
//                            }
//                            
//                        });

                        $('.thank_you_container .red_btn').on('click',function(){
                            ga('send', 'event', 'outbound', 'click', 'Sign Up - Virtual Career');
                        });
                        
                    }
                });
                
            }

        });
               

});

})(jQuery);