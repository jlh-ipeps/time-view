

function initMap(maplat, maplng, mapmarker, mapmarkers) {


myCenter = new google.maps.LatLng(maplat, maplng);

myZoom = 13;
if (maplat === 0) {
  myZoom = 2;
} 

// setup mpa options
var mapOptions = {
  zoom: myZoom,
  center: myCenter,
  scrollwheel: false,
  mapTypeId: google.maps.MapTypeId.ROADMAP
};

var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
var geocoder = new google.maps.Geocoder();
var address = {
    LatLng: null,
    street: null,
    postalCode: null,
    locality: null,
    country: null
};
var geoform;
var marker;

    geoform = {
        street: document.getElementById('appbundle_picture_route'),
        postalCode: document.getElementById('appbundle_picture_postalCode'),
        locality: document.getElementById('appbundle_picture_locality'),
        country: document.getElementById('appbundle_picture_country'),
        lat: document.getElementById('appbundle_picture_lat'),
        lng: document.getElementById('appbundle_picture_lng'),
    };
    $("#geoform").submit(function(e){
        e.preventDefault();
        geocodeAddr();
        ajaxSendForm();
    });

// recenter on map resize
google.maps.event.addDomListener(window, "resize", function() {
  myCenter = map.getCenter();
  google.maps.event.trigger(map, "resize");
  map.setCenter(myCenter);
});


// set map size when map tab is clicked 
// maptab = ('tab_' + view name)
var maptab = document.getElementsByClassName("tab_map_form");
google.maps.event.addDomListener(maptab[0], "click", function() {
  setTimeout(function(){
    myCenter = map.getCenter();
    google.maps.event.trigger(map, "resize");
    map.setCenter(myCenter);
  }, 100);
});


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

  google.maps.event.addListener(marker, 'dragend', function(event) {
    placeMarker(event.latLng);
  });


  function placeMarker(latLng) {
//    map.setCenter(latLng);
    if (marker) {
      marker.setPosition(latLng);
//      if (map.getZoom() < 10) {
//        map.setZoom() = 13;
//      }
    } else {
       marker = new google.maps.Marker({
        position: latLng,
        map: map,
        draggable: true
      });
      google.maps.event.addListener(marker, "dragend", function (mEvent) {
          geocodeLoc(mEvent.latLng);
      });
    }
    geocodeLoc(latLng);
    ajaxSendForm();
  }
  
    function geocodeLoc(latLng) {
        address.LatLng = latLng;
        geoform.lat.value = latLng.lat();
        geoform.lng.value = latLng.lng();
        geocoder.geocode({'location': latLng}, function(results, status) {
            if (status === 'OK') {
                var components = results[0].address_components;
                for(i=0; i < components.length; i++) {
                    if (components[i].types[0] === 'route') {
                      address.street = components[i].long_name;
                      geoform.street.value = address.street;
                    }
                    if (components[i].types[0] === 'postal_code') {
                      address.postalCode = components[i].long_name;
                      geoform.postalCode.value = address.postalCode;
                    }
                    if (components[i].types[0] === 'locality') {
                      address.locality = components[i].long_name;
                      geoform.locality.value = address.locality;
                    }
                    if (components[i].types[0] === 'country') {
                      address.country = components[i].short_name;
                      geoform.country.value = address.country;
                    }
                }
                address.bounds = results[0].geometry.viewport;
                map.fitBounds(address.bounds);
//                ajaxSendForm();
            } else {
                console.log(status);
            }
        });
    }
    
    function geocodeAddr() {
                console.log('status');
        var latLng = {};
        var request = {
            address: geoform.street.value,
            componentRestrictions: {
                postalCode: geoform.postalCode.value,
                locality: geoform.locality.value,
                country: geoform.country.value
            }
        };
        geocoder.geocode(request, function(results, status) {
                console.log(status);
            if (status === 'OK') {
                latLng = results[0].geometry.location;
                var bounds = results[0].geometry.bounds;
                if (request.address) {
                    map.fitBounds(bounds);
                    placeMarker(latLng);
                } else {
//                    map.setCenter(latLng);
//                    marker.setPosition(latLng);
//                    if (marker) { marker = null; }
//                    marker.setMap(null);
//                    geoform.lat.value = latLng.lat();
//                    geoform.lng.value = latLng.lng();
                    geoform.lat.value = null;
                    geoform.lng.value = null;
//                    ajaxSendForm();
                }
            } else {
                console.log(status);
            }
        });
    }

    function ajaxSendForm()  {
        var formSerialize = $("#geoform").serialize();
        $.post('', formSerialize, function(response){
            console.log(response);
        },'JSON');
    }
  

    $("#appbundle_picture_clear").click(function(e){
        marker.setMap(null);
        geoform.street.value = null;
        geoform.postalCode.value = null;
        geoform.locality.value = null;
        geocodeAddr();
        ajaxSendForm();
    });

  
}

