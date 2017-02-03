

    var picturePath = baseUrl + '/picture/';

    var bounds = new google.maps.LatLngBounds();

  // set map default center
  if (localStorage.loc) {
      var myCenter = new google.maps.LatLng(localStorage.lat, localStorage.lng);
  } else {
      var myCenter = new google.maps.LatLng(50.63, 5.58);
  }

    // empty the map
    var delmarkers = [];

    // setup map options
    var mapOptions = {
        zoom: 13,
        center: myCenter,
        scrollwheel: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    // map instantiate
    var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

    //Add Home to map
    if (localStorage.loc) {
        var marker = new google.maps.Marker({
            position: myCenter,
            map: map,
            icon: homeIcon,
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
    var maptab = document.getElementsByClassName("tab_map_book");
    google.maps.event.addDomListener(maptab[0], "click", function() {
        setTimeout(function(){
            myCenter = map.getCenter();
            google.maps.event.trigger(map, "resize");
            map.setCenter(myCenter);
            if (mapmarkers.length > 0) {map.fitBounds(bounds);}
        }, 100);
    });

    // fill the map and fit the bounds accordingly
    if (mapmarkers.length > 0) {
        for (m = 0; m < mapmarkers.length; m++) { 
            var sIcon = {
                url: thumbUrlDir + mapmarkers[m].file_id + '.' + mapmarkers[m].file_ext,
                scaledSize: new google.maps.Size(20 * mapmarkers[m].file_ratio, 20),
                origin: new google.maps.Point(0,0),
                anchor: new google.maps.Point(20, 10)
            };
            var bIcon = {
                url: thumbUrlDir + mapmarkers[m].file_id + '.' + mapmarkers[m].file_ext,
                scaledSize: new google.maps.Size(100 * mapmarkers[m].file_ratio, 100),
                origin: new google.maps.Point(0,0),
                anchor: new google.maps.Point(100, 50)
            };
            var mLatlng = new google.maps.LatLng(mapmarkers[m].lat, mapmarkers[m].lng);
            var url = mapmarkers[m].file_id;
            bounds.extend(mLatlng);
            marker = new google.maps.Marker({
                position: mLatlng,
                icon: sIcon,
                bigicon: bIcon,
                smlicon: sIcon,
                map: map,
                url: picturePath + book_id + '-' + mapmarkers[m].file_id + '/'
            });
            new google.maps.event.addListener(marker, 'click', function(event) {
                window.location.href = this.url;
            });
            new google.maps.event.addListener(marker, 'mouseover', function(event) {
               this.setIcon(this.bigicon);
            });
            new google.maps.event.addListener(marker, 'mouseout', function(event) {
               this.setIcon(this.smlicon);
            });
            delmarkers.push(marker);
        }
        map.fitBounds(bounds);
    }
