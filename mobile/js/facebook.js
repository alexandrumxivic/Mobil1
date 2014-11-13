( function($){
	var totalAlbums = 0;
	photosList = [];

	getImagesFromFacebook = function(){
            // already initialized and granted permissions from Facebook.php    
            //FB.init({ appId: '491415134290936' });
            //FB.login(function(response) {
                //if (response.authResponse) {
                    //$(window).trigger($.CONNECTED_TO_FACEBOOK);
                    FB.api('me/albums', function(response) {
                        photosList = [];
                        var albums = response.data;
                        totalAlbums = albums.length;
                        for (var i=0; i<albums.length; i++) {
                                    getPhotosFromAlbum(albums[i].id, photosListDone);
                        }
                    });
                //}
            //}, {scope: 'user_photos'});
	}


	function getPhotosFromAlbum(id, callback){
		FB.api(id + '/photos', function(response) {
			totalAlbums -- ;
            var photos = response.data;
            for (var i=0; i<photos.length; i++) {
            	photosList.push(photos[i]);
            }
            if (totalAlbums == 0) {
            	callback();
            }
        });
	}

	function photosListDone() {
		var html = '';
		var num = 0
		for (var i=0; i<photosList.length; i++) {
			if (num==0) {
				html += '<p>';
			} else if (num==4){
				num = 0
				html += '</p>';
			}
			html += '<img class="static-bordered preview-item" src="' + photosList[i].picture + '" obj="' + photosList[i].source + '"/>'
			num++
		}

		if (num<4) {
			html += '</p>';
		}

		$('#facebookPhotos').append(html);
	}


})(jQuery)