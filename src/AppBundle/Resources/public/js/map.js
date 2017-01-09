
function initMap(maplat, maplng, mapmarker, mapmarkers) {

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


    for (var m in mapmarkers) {
        var ratio = 1.3;
        var mIcon = {
          url: '/web/thumb/uploads/img/' + mapmarkers[m].file.id + '.jpeg',
          scaledSize: new google.maps.Size(50*ratio, 50),
          origin: new google.maps.Point(0,0),
          anchor: new google.maps.Point(0, 0)
        };
        var mLatlng = new google.maps.LatLng(mapmarkers[m].lat, mapmarkers[m].lng);
        var marker = new google.maps.Marker({
          position: mLatlng,
          map: map,
          icon: mIcon,
          title: 'image title'
        });
    }


}