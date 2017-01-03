
$( document ).ready( function() {
    
  function initialize() {
  
    function map_gallery() {
      console.log(document.getElementById('map'));
      if (localStorage.loc) {
        var homeIcon = BaseDir + 'img/home.png';
        var myLatlng = new google.maps.LatLng(localStorage.lat, localStorage.lng);
        //Add Home
        var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                icon: homeIcon,
                title: 'image title'
        });

        google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
        });
        
      } else {
        var myLatlng = new google.maps.LatLng(50.63, 5.58);
      }

      var delmarkers = [];
      var imagePath = BaseDir + 'img/Pin-location.png';
      var mapOptions = {
              zoom: 13,
              center: myLatlng,
              scrollwheel: false,
              mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      var map = new google.maps.Map(document.getElementById('map'), mapOptions);

      //Add Home
      if (localStorage.loc) {
        var marker = new google.maps.Marker({
          position: myLatlng,
          map: map,
          icon: BaseDir + 'img/home.png',
          title: 'image title'
        });
        google.maps.event.addListener(marker, 'click', function() {
          infowindow.open(map,marker);
        });
        var contentString = 'Some address here..';
        //Set window width + content
        var infowindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 500
        });
      }




      //Resize Function
      google.maps.event.addDomListener(window, "resize", function() {
        var center = map.getCenter();
        google.maps.event.trigger(map, "resize");
        map.setCenter(center);
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
        $bounds = map.getBounds();
        $neLat = $bounds.getNorthEast().lat;
        $neLng = $bounds.getNorthEast().lng;
        $swLat = $bounds.getSouthWest().lat;        
        $swLng = $bounds.getSouthWest().lng;        

        jQuery.ajax({
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
      });
    }

    function map_gallery() {
      alert('input');
    }

    
    if (document.getElementById('map') !== null) {
      map_gallery();
    } else if (document.getElementById('map_input') !== null) {
      map_gallery();
    }
  }
  
  google.maps.event.addDomListener(window, 'load', initialize);

});