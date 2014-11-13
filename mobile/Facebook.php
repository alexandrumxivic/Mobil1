<script type="text/javascript">


var Facebook = {};
Facebook.DEBUG				= false; // console messages useful for development stages

Facebook.status				= {};
Facebook.permissions		= {};
Facebook.config				= {};
Facebook.flash				= {};
Facebook.api				= {};
Facebook.ui					= {};

Facebook.ui.selector		= {};
Facebook.ui.selector.friend	= {};
Facebook.ui.selector.image	= {};

/***********************
*	Configure your app details here.
************************/
Facebook.config.appID		= "597677730337203"; // application ID.
Facebook.config.channel		= "//mobile1.projects-directory.com/channel.html"; // full URL to channel file on your application's deployment location. Do not shorten.
Facebook.config.appURL		= "https://www.facebook.com/pages/Fanscape-Development/353585258094472?sk=app_597677730337203"; // full URL to your application's deployment tab. Do not shorten.
Facebook.config.size		= {height: 792, width: 810}; // set page tab canvas size.


// User & friends permissions
Facebook.permissions.user = {
	ABOUT_ME:				"user_about_me",
	ACTIVITIES:				"user_activities",
	BIRTHDAY:				"user_birthday",
	CHECKINS:				"user_checkins",
	EDUCATION_HISTORY:		"user_education_history",
	EVENTS:					"user_events",
	GROUPS:					"user_groups",
	HOMETOWN:				"user_hometown",
	INTERESTS:				"user_interests",
	LIKES:					"user_likes",
	LOCATION:				"user_location",
	NOTES:					"user_notes",
	PHOTOS:					"user_photos",
	QUESTIONS:				"user_questions",
	RELATIONSHIPS:			"user_relationships",
	RELATIONSHIP_DETAILS:	"user_relationship_details",
	RELIGION_POLITICS:		"user_religion_politics",
	STATUS:					"user_status",
	SUBSCRIPTIONS:			"user_subscriptions",
	VIDEOS:					"user_videos",
	WEBSITE:				"user_website",
	WORK_HISTORY:			"user_work_history",
	EMAIL:					"email"
};
Facebook.permissions.friends = {
	ABOUT_ME:				"friends_about_me",
	ACTIVITIES:				"friends_activities",
	BIRTHDAY:				"friends_birthday",
	CHECKINS:				"friends_checkins",
	EDUCATION_HISTORY:		"friends_education_history",
	EVENTS:					"friends_events",
	GROUPS:					"friends_groups",
	HOMETOWN:				"friends_hometown",
	INTERESTS:				"friends_interests",
	LIKES:					"friends_likes",
	LOCATION:				"friends_location",
	NOTES:					"friends_notes",
	PHOTOS:					"friends_photos",
	QUESTIONS:				"friends_questions",
	RELATIONSHIPS:			"friends_relationships",
	RELATIONSHIP_DETAILS:	"friends_relationship_details",
	RELIGION_POLITICS:		"friends_religion_politics",
	STATUS:					"friends_status",
	SUBSCRIPTIONS:			"friends_subscriptions",
	VIDEOS:					"friends_videos",
	WEBSITE:				"friends_website",
	WORK_HISTORY:			"friends_work_history"
};

// Extended permissions - Use these only if necessary.
Facebook.permissions.extended = {
	READ_FRIENDLISTS:			"read_friendlists",
	READ_INSIGHTS:				"read_insights",
	READ_MAILBOX:				"read_mailbox",
	READ_REQUESTS:				"read_requests",
	READ_STREAM:				"read_stream",
	XMPP_LOGIN:					"xmpp_login",
	ADS_MANAGEMENT:				"ads_management",
	CREATE_EVENT:				"create_event",
	MANAGE_FRIENDLISTS:			"manage_friendlists",
	MANAGE_NOTIFICATIONS:		"manage_notifications",
	USER_ONLINE_PRESENCE:		"user_online_presence",
	FRIENDS_ONLINE_PRESENCE:	"friends_online_presence",
	PUBLISH_CHECKINS:			"publish_checkins",
	PUBLISH_STREAM:				"publish_stream",
	RSVP_EVENT:					"rsvp_event"
};

/***********************
*	Set the permissions your app needs here.
************************/
Facebook.config.permissions = [
        Facebook.permissions.user.EMAIL,
	Facebook.permissions.user.PHOTOS
];

Facebook.status.CONNECTED	= 'connected';
Facebook.status.NOT_AUTH	= 'not_authorized';


/*
	Call this ahead of anything related to Facebook.
	Aaanything at all. Use the callback parameter if
	you need something done right after setting up the
	application.
*/
Facebook.setup = function(callback){
	window.fbAsyncInit = function(){
		FB.init({
			appId      : Facebook.config.appID,
			channelUrl : Facebook.config.channel,
			status     : true,
			cookie     : true,
			xfbml      : true,
                        version    : 'v2.1'
		});
		
		// Check status and set the user id if available
		Facebook.api.status(function(response){
			if(typeof callback=='function') {callback(response);}
		});
	};
	// Load the SDK Asynchronously
	(function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
          }(document, 'script', 'facebook-jssdk'));
}


Facebook.flash.app = {};
Facebook.flash.setApp = function(appID){
	Facebook.flash.app = $('#'+appID)[0];
}

/***********************
*	API Functions

API Functions require logging into the application
before using any of them (except the login function, obviously).
************************/

/*
	Returns the currently logged user's facebook ID.
*/
Facebook.api._userID = '0';


/*
	Sets and returns the current user ID, if available.
*/
Facebook.api.userID = function(id){
	if(id)
	{
		Facebook.api._userID = id;
	}
	return Facebook.api._userID;
}

/*
	Returns the current page ID.
*/
Facebook.api.pageID = function(){
	return <?php echo (isset($pageID))? $pageID : ''; ?>;
}

/*
	Retrieves appData for the current tab - useful for deep linking
	Returns empty string if nothing was found.
*/
Facebook.api.appData = function(){
	
	return "";
}

/*
	Returns an URL for direct/deep linking.
	Use Facebook.api.appData to retrieve the "value"
	parameter. Encode a JSON for multiple properties.
*/
Facebook.api.queryString = function(value){
	var param = "?";
	if(Facebook.config.appURL.indexOf('?')>-1)
	{
		param = "&";
	}

	return Facebook.config.appURL + param+'app_data='+value;
}

/*
	Retrieves user login status - See Facebook.status object.
*/
Facebook.api.status = function(callback){
	FB.getLoginStatus(function(response){
		
		if(response.status == Facebook.status.CONNECTED)
		{
			Facebook.api.userID(response.authResponse.userID);
		}
		else if(response.status == Facebook.status.NOT_AUTH)
		{
			// Denied application access
		}
		else
		{
			// Not logged in on Facebook
		}
		if(typeof callback=='function'){callback(response);}
	});
}

/*
	Logs the user in, if necessary
*/
Facebook.api.login = function(callback){
	Facebook.api.status(function(r){
		if(r.status!=Facebook.status.CONNECTED)
		{	
			FB.login(function(response){
				if(response.status == Facebook.status.CONNECTED)
				{
					Facebook.api.userID(response.authResponse.userID);
				}
				else if(response.status == Facebook.status.NOT_AUTH)
				{
					if(Facebook.DEBUG) console.log("Login failed, denied access:", response)
				}
				else
				{
					if(Facebook.DEBUG) console.log("Login failed, not signed in:", response)
				}
				if(typeof callback=='function') callback(response);
			}, {scope: Facebook.config.permissions.join(',')});
		}
		else
		{
			Facebook.api.userID(r.authResponse.userID);
			if(typeof callback=='function') callback(r);
		}
	});
}


/*
	Posts a link on currently logged user's wall.
*/
Facebook.api.postLink = function(link,callback){
	var post = {};
	post.target			= Facebook.api.userID();
	post.link			= link.link;
	post.name			= link.name;
	post.message		= link.message;
	post.description	= link.description;
	post.caption		= link.caption;
	post.picture		= link.picture;

	Facebook.api.status(function(response){
		if(response.status===Facebook.status.CONNECTED)
		{
			FB.api('/'+post.target+'/feed', 'post', post, function(response){
				if(typeof callback=='function') callback(response);
			});
		}
	});
}

/*
	Retrieve a list of friends. Contains user IDs and names
*/
Facebook.api.getFriends = function(callback){
	FB.api('me?fields=friends.fields(name)', function(response){
		if(typeof callback=='function'){callback(response);}
	});
}

Facebook.api.getUser = function(callback){
	FB.api('me', function(response){
		if(typeof callback=='function'){callback(response);}
	});
}


/***********************
*	UI Functions

UI Functions don't require logging in to the application
in order to post content to a user's wall.
************************/

/*
	Open an overlay for posting a link to a user's timeline.
*/
Facebook.ui.postLink = function(link, callback){
	var post = {};
	
	post.method = 'feed';
	
	if(!link.target)
	{
		link.target = Facebook.api.userID();
	}
	if(!link.from)
	{
		link.from = Facebook.api.userID();
	}
	
	post.to				= link.target;
	post.from			= link.from;
	post.link			= link.link;
	post.name			= link.name;
	post.picture		= link.picture;
	post.caption		= link.caption;
	post.description	= link.description;

	FB.ui(post, function(response){
		if(typeof callback=='function'){callback(response);}
	});
}

/*
	Open an overlay for selecting friends for sending
	application requests. Facebook's default method for sharing apps.
*/
Facebook.ui.sendRequest = function(request, callback){
	var post		= {};
	post.method		= 'apprequests';
	post.message	= request.message;
	post.title		= request.title;

	FB.ui(post, function(response){
		if(typeof callback=='function'){callback(response);}
	});
}

</script>

<script type='text/javascript'>
//	var fbInfo;
//	
//	Facebook.setup(function() {
//		Facebook.api.login(function() {
//			FB.api('/me', function (response) {
//				fbInfo = response;
//
//				//facebookReady();
//			});
//		});
//	});
	
</script>