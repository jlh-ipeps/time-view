

function initMap(maplat, maplng, mapmarker, mapmarkers) {

var myCenter = new google.maps.LatLng(maplat, maplng);

// setup mpa options
var mapOptions = {
  zoom: 13,
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
$(document).ready(function() {
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
    });
});
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
    map.setCenter(latLng);
    if (marker) {
      marker.setPosition(latLng);
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
                ajaxSendForm();
//    ajaxSend(latLng);

            } else {
                console.log(status);
            }
        });
    }
    
    function geocodeAddr() {
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
            if (status === 'OK') {
                latLng = results[0].geometry.location;
                if (request.address) {
                    placeMarker(latLng);
                } else {
                    map.setCenter(latLng);
                    marker.setPosition(latLng);
//                    marker.setMap(null);
                    geoform.lat.value = latLng.lat();
                    geoform.lng.value = latLng.lng();
                    ajaxSendForm();
                }
            } else {
                console.log(status);
            }
        });
    }



//    function ajaxSend(latLng)  {
//        $.ajax({
//            dataType : 'json',
//            method: 'POST',
//            data : {
//                mlat : latLng.lat(),
//                mlng : latLng.lng(),
//            },
//            success: function(success) {
//                console.log(success);
//            },
//            error : function(err){
//                console.error(err);
//                console.log(err);
//            }
//        });
//    }
    
    function ajaxSendForm()  {
        var formSerialize = $("#geoform").serialize();
        $.post('', formSerialize, function(response){
            console.log(response);
        },'JSON');
    }
  
}

