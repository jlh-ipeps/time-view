
$( document ).ready( function() {
    
    function initialize() {

      
//        if (localStorage.loc) {
//          console.log(localStorage.loc);
//          console.log(localStorage.lat);
//          console.log(localStorage.lng);
//        }
        
        // window.location is a very bad solution
        if (localStorage.loc && window.location.href.indexOf("home/here") > -1) {
          var myLatlng = new google.maps.LatLng(localStorage.lat, localStorage.lng);
        } else {
          var myLatlng = new google.maps.LatLng(50.63, 5.58);
        }
        
        
        var delmarkers = [];
        var imagePath = imgBaseDir + 'Pin-location.png';
        var mapOptions = {
                zoom: 13,
                center: myLatlng,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        //Callout Content
        
        // fetch the existing places (ajax) 
        // and put them on the map
//        fetchPlaces();

        var contentString = 'Some address here..';
        //Set window width + content
        var infowindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 500
        });

        //Add Marker
        var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                icon: imagePath,
                title: 'image title'
        });
        

        google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
        });

        //Resize Function
        google.maps.event.addDomListener(window, "resize", function() {
            var center = map.getCenter();
            google.maps.event.trigger(map, "resize");
            map.setCenter(center);
//          console.log(map.getBounds());
        });

        $('#tab-2').on('mouseup',function(e) {
            setTimeout(function (){
                var center = map.getCenter();
                google.maps.event.trigger(map, "resize");
                map.setCenter(center);
            }, 100);
        });
        
        // http://stackoverflow.com/questions/20555582/google-maps-getbounds-returns-undefined
        google.maps.event.addListener(map, 'bounds_changed', function() {
//          console.log(map.getBounds());
          $bounds = map.getBounds();
          $neLat = $bounds.getNorthEast().lat;
          $neLng = $bounds.getNorthEast().lng;
          $swLat = $bounds.getSouthWest().lat;        
          $swLng = $bounds.getSouthWest().lng;        

          if (localStorage.loc && window.location.href.indexOf("home/here") > -1) {
//          console.log($bounds);
        jQuery.ajax({
//        	url : '/home/here/',
        	dataType : 'json',
        	type : 'POST',
        	data : {
                nelat : $neLat,
                nelng : $neLng,
        		swlat : $swLat,
        		swlng : $swLng,
        	},
        	success : function(response){
              var markers = response.markers;
              //Remove old markers from the Map
              for (var i=0; i < delmarkers.length; i++) {
                delmarkers[i].setMap(null);
              }
              delmarkers.length = 0;
              // add mmarkers
              for (var m in markers) {
                var mLatlng = new google.maps.LatLng(markers[m][0], markers[m][1]);
                var marker = new google.maps.Marker({
                  position: mLatlng,
                  map: map,
                  icon: imagePath,
                  title: 'image title'
                });
                delmarkers.push(marker);

              }
        	},
        	error : function(err){
        		// do error checking
        		alert("something went wrong");
        		console.error(err);
        	}
        });
        }
        });
        
    }

    google.maps.event.addDomListener(window, 'load', initialize);
    

});