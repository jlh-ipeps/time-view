
    // set map size when map tab is clicked
    // class name is generated by tab_ + name of the view
    var maptab = document.getElementsByClassName("tab_map_book");

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
    google.maps.event.addDomListener(maptab[0], "click", function() {
        setTimeout(function(){
            myCenter = map.getCenter();
            google.maps.event.trigger(map, "resize");
            map.setCenter(myCenter);
            if (jsonPictures.length > 0) {map.fitBounds(bounds);}
        }, 100);
    });

    // fill the map and fit the bounds accordingly
    for (m = 0; m < jsonPictures.length; m++) { 
        if (jsonPictures[m].lat) {
            var ratio = jsonPictures[m].file.width / jsonPictures[m].file.height;
            var sIcon = {
                url: thumbUrlDir + jsonPictures[m].file.id + '.' + jsonPictures[m].file.ext,
                scaledSize: new google.maps.Size(20 * ratio, 20),
                origin: new google.maps.Point(0,0),
                anchor: new google.maps.Point(20, 10)
            };
            var bIcon = {
                url: thumbUrlDir + jsonPictures[m].file.id + '.' + jsonPictures[m].file.ext,
                scaledSize: new google.maps.Size(100 * ratio, 100),
                origin: new google.maps.Point(0,0),
                anchor: new google.maps.Point(100, 50)
            };
            var mLatlng = new google.maps.LatLng(jsonPictures[m].lat, jsonPictures[m].lng);
            bounds.extend(mLatlng);
            marker = new google.maps.Marker({
                position: mLatlng,
                icon: sIcon,
                bigicon: bIcon,
                smlicon: sIcon,
                map: map,
                url: picturePath + jsonPictures[m].book.id + '-' + jsonPictures[m].file.id + '/'
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
