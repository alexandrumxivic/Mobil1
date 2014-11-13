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
                
                $('.submit').on('click', function(e){
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

            FB.Canvas.setSize({height: 1400, width: 810});

        });
               

});

})(jQuery);