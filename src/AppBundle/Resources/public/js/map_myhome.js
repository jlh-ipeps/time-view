
// set map center
if (localStorage.loc) {
  var myCenter = new google.maps.LatLng(localStorage.lat, localStorage.lng);
} else {
  var myCenter = new google.maps.LatLng(50.63, 5.58);
}

// empty the map
var delmarkers = [];

// setup mpa options
var mapOptions = {
  zoom: 13,
  center: myCenter,
  scrollwheel: false,
  mapTypeId: google.maps.MapTypeId.ROADMAP
};

// map instantiate
var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

//Add Home to map
if (localStorage.loc) {
  var marker = new google.maps.Marker({
    position: myCenter,
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

// recenter on map resize
google.maps.event.addDomListener(window, "resize", function() {
  myCenter = map.getCenter();
  google.maps.event.trigger(map, "resize");
  map.setCenter(myCenter);
});

// set map size when map tab is clicked
var maptab = document.getElementsByClassName("tab_map");
google.maps.event.addDomListener(maptab[0], "click", function() {
  setTimeout(function(){
    myCenter = map.getCenter();
    google.maps.event.trigger(map, "resize");
    map.setCenter(myCenter);
  }, 100);
});

// fill the map by ajax
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
          icon: BaseDir + 'img/Pin-location.png',
          title: 'image title'
        });
        delmarkers.push(marker);
      }
    },
    error : function(err){
        // do error checking
//          alert("something went wrong");
        console.error(err);
    }
  });
});
