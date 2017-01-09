
function initMap(maplat, maplng, mapmarker, mapmarkers) {

var myCenter = new google.maps.LatLng(maplat, maplng);

// setup mpa options
var mapOptions = {
  zoom: 13,
  center: myCenter,
  scrollwheel: false,
  mapTypeId: google.maps.MapTypeId.ROADMAP
};

// map instantiate
var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

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



var marker;

  if (mapmarker) {
     marker = new google.maps.Marker({
      position: myCenter,
      map: map,
      draggable: true
    });
  }


  google.maps.event.addListener(map, 'click', function(event) {
    placeMarker(event.latLng);
  });

  google.maps.event.addListener(marker, 'drag', function(event) {
    placeMarker(event.latLng);
  });

  function placeMarker(location) {
    if (marker) {
      marker.setPosition(location);
    } else {
       marker = new google.maps.Marker({
        position: location,
        map: map,
        draggable: true
      });
      google.maps.event.addListener(marker, "drag", function (mEvent) {
        ajaxSend(mEvent.latLng);
      });
    }
    ajaxSend(location);
  }

//// JQuery Ajax 
//function ajaxSend(pos) {
//  jQuery.ajax({
//    dataType : 'json',
//    type : 'POST',
//    data : {
//      mlat: pos.lat(),
//      mlng: pos.lng()
//    },
//    success : function(response){
//      console.log(response);
//    },
//    error : function(error){
//        console.error(error);
//    }
//  });
//}

function ajaxSend(pos) {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE ) {
      if (xhr.status === 200) {
        console.log(xhr.responseText);
      } else if (xhr.status === 400) {
           alert('There was an error 400');
      } else {
           alert('something else other than 200 was returned');
      }
    }
  };
  var url = '?mlat=' + pos.lat() + '&mlng=' + pos.lng()
  xhr.open("POST", url );
  xhr.send();
}

  
}
