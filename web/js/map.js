/* 
 * https://codepen.io/hubpork/pen/xriIz
 */


$( document ).ready( function() {
    
    
    //Google Maps JS
    //Set Map
    function initialize() {
        var myLatlng = new google.maps.LatLng(50.63, 5.58);
        var imagePath = 'http://m.schuepfen.ch/icons/helveticons/black/60/Pin-location.png'
        var mapOptions = {
                zoom: 13,
                center: myLatlng,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        //Callout Content
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
        });

        $('#tab-2').on('mouseup',function(e) {
            setTimeout(function (){
                var center = map.getCenter();
                google.maps.event.trigger(map, "resize");
                map.setCenter(center);
            }, 100);
        });
        
    }

    google.maps.event.addDomListener(window, 'load', initialize);

});